<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->paginate(9);

        return Inertia::render('Events/Index', [
            'events' => $events
        ]);
    }

    public function joinQueue(Request $request, Event $event)
    {
        dd("Sto provando ad acquistare il biglietto per: " . $event->title);
    }
}
