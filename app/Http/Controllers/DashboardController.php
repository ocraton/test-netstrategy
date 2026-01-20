<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $events = Event::orderBy('created_at', 'desc')->paginate(9);

        return Inertia::render('Dashboard', [
            'events' => $events
        ]);
    }
}
