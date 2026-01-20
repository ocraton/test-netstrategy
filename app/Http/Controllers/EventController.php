<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventQueue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->paginate(9);

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }

    public function joinQueue(Request $request, Event $event)
    {
        $user = $request->user();

        // Recupera o Crea il posto in fila
        $queueEntry = EventQueue::firstOrCreate([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        // L'utente è già attivo e non è scaduto
        if ($queueEntry->allowed_at && $queueEntry->expires_at > now()) {
            return to_route('events.checkout', $event);
        }

        // L'utente è scaduto? (Il problema delle 5 ore)
        // Se expires_at esiste ed è nel passato, resettiamo la sua posizione.
        // In una logica severa lo cancelleremmo, qui lo rimettiamo in coda come "nuovo".
        if ($queueEntry->expires_at && $queueEntry->expires_at <= now()) {
            $queueEntry->update([
                'allowed_at' => null,
                'expires_at' => null,
                'created_at' => now(), // Torna in fondo alla fila
            ]);
        }

        // Controllo capienza (Se l'utente è ancora in waiting)
        if (! $queueEntry->allowed_at) {

            // Se non c'è limite, entra subito
            if (is_null($event->max_concurrent_users)) {
                $this->allowUser($queueEntry, $event);

                return to_route('events.checkout', $event);
            }

            // Contiamo quanti utenti sono attivi ora
            $activeUsers = EventQueue::where('event_id', $event->id)
                ->whereNotNull('allowed_at')
                ->where('expires_at', '>', now())
                ->count();

            // se c'è spazio entra
            if ($activeUsers < $event->max_concurrent_users) {
                $this->allowUser($queueEntry, $event);

                return to_route('events.checkout', $event);
            }
        }

        // se non c'è spazio vai in wainting room
        return to_route('events.waiting-room', $event);
    }

    private function allowUser(EventQueue $entry, Event $event)
    {
        $entry->update([
            'allowed_at' => now(),
            'expires_at' => now()->addSeconds($event->queue_timeout_seconds),
        ]);
    }

    public function waitingRoom(Event $event)
    {
        // Qui passeremo poi la posizione in coda
        return Inertia::render('Events/WaitingRoom', ['event' => $event]);
    }

    public function checkout(Event $event)
    {
        // Qui poi faremo un middleware per impedire l'accesso diretto via URL
        return Inertia::render('Events/Checkout', ['event' => $event]);
    }

    public function checkQueueStatus(Request $request, Event $event)
    {
        $user = $request->user();

        $queueEntry = EventQueue::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (! $queueEntry) { // se non c'è in coda lo caccio
            return response()->json(['status' => 'kicked', 'redirect' => route('events.join', $event)]);
        }

        if ($queueEntry->allowed_at) { // se è autorizzato

            // Controlla se è scaduto
            if ($queueEntry->expires_at <= now()) { // se è scaduto lo cancello
                $queueEntry->delete();

                return response()->json(['status' => 'expired', 'redirect' => route('dashboard')]);
            }

            return response()->json([
                'status' => 'active',
                'seconds_remaining' => now()->diffInSeconds($queueEntry->expires_at, false),
            ]);
        }

        // se è in attesa
        // controllo quanti sono dentro attivi adesso
        $activeUsers = EventQueue::where('event_id', $event->id)
            ->whereNotNull('allowed_at')
            ->where('expires_at', '>', now())
            ->count();

        // se c'è spazio in coda
        if (is_null($event->max_concurrent_users) || $activeUsers < $event->max_concurrent_users) {
            // Ricalcolo la posizione in fila
            $peopleAhead = EventQueue::where('event_id', $event->id)
                ->whereNull('allowed_at')
                ->where('created_at', '<', $queueEntry->created_at) // Chi è arrivato prima
                ->count();

            if ($peopleAhead === 0) {
                // prima posizione
                $this->allowUser($queueEntry, $event);

                return response()->json([
                    'status' => 'promoted',
                    'redirect' => route('events.checkout', $event),
                ]);
            }
        }

        // calcola la posizione aggiornata
        $peopleAhead = EventQueue::where('event_id', $event->id)
            ->whereNull('allowed_at')
            ->where('created_at', '<', $queueEntry->created_at)
            ->count();

        return response()->json([
            'status' => 'waiting',
            'position' => $peopleAhead + 1,
        ]);
    }

    public function purchase(Request $request, Event $event)
    {
        $user = $request->user();

        EventQueue::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->delete();

        return to_route('dashboard')->with('message', 'Acquisto completato con successo! Grazie.');
    }
}
