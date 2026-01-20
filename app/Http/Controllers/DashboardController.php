<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {

        $events = Event::orderBy('created_at', 'desc')->paginate(9);

        return Inertia::render('Dashboard', [
            'events' => $events,
        ]);
    }
}
