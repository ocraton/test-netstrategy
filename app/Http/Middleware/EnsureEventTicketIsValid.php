<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\EventQueue;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEventTicketIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        $user = $request->user();

        $ticket = EventQueue::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$ticket || !$ticket->allowed_at || $ticket->expires_at <= now()) {

            return to_route('dashboard');
        }

        return $next($request);
    }
}
