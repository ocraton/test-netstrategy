<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            // --- CONCERTI  ---
            [
                'title' => 'Taylor Swift - The Eras Tour',
                'type' => 'concert',
                'details' => ['artist' => 'Taylor Swift', 'genre' => 'Pop', 'tour_leg' => 'European Leg'],
                'max_concurrent_users' => 1,
                'queue_timeout_seconds' => 120, // 2 minuti
            ],
            [
                'title' => 'Coldplay - Music of the Spheres',
                'type' => 'concert',
                'details' => ['artist' => 'Coldplay', 'genre' => 'Alternative Rock', 'stage_setup' => 'Eco-Friendly'],
                'max_concurrent_users' => 5,
                'queue_timeout_seconds' => 300,
            ],
            [
                'title' => 'Eminem - Live in Milan',
                'type' => 'concert',
                'details' => ['artist' => 'Eminem', 'genre' => 'Hip Hop', 'opening_act' => '50 Cent'],
                'max_concurrent_users' => 10,
                'queue_timeout_seconds' => 300,
            ],
            [
                'title' => 'Metallica World Tour',
                'type' => 'concert',
                'details' => ['artist' => 'Metallica', 'genre' => 'Heavy Metal'],
                'max_concurrent_users' => null,
                'queue_timeout_seconds' => 600,
            ],
            [
                'title' => 'Dua Lipa - Radical Optimism',
                'type' => 'concert',
                'details' => ['artist' => 'Dua Lipa', 'genre' => 'Pop/Disco'],
                'max_concurrent_users' => 2,
                'queue_timeout_seconds' => 180,
            ],

            // --- TEATRO  ---
            [
                'title' => 'Il Fantasma dell\'Opera',
                'type' => 'theater',
                'details' => ['director' => 'Andrew Lloyd Webber', 'cast_size' => 40, 'language' => 'English'],
                'max_concurrent_users' => 50,
                'queue_timeout_seconds' => 600,
            ],
            [
                'title' => 'Amleto - Royal Shakespeare Company',
                'type' => 'theater',
                'details' => ['playwright' => 'William Shakespeare', 'lead_actor' => 'Benedict Cumberbatch'],
                'max_concurrent_users' => null,
                'queue_timeout_seconds' => 600,
            ],

            // --- SPORT ---
            [
                'title' => 'Finale Champions League',
                'type' => 'sport',
                'details' => ['teams' => ['Manchester City', 'Real Madrid'], 'competition' => 'UEFA Champions League'],
                'max_concurrent_users' => 1,
                'queue_timeout_seconds' => 60,
            ],
            [
                'title' => 'NBA Global Games: Lakers vs Celtics',
                'type' => 'sport',
                'details' => ['teams' => ['LA Lakers', 'Boston Celtics'], 'league' => 'NBA'],
                'max_concurrent_users' => 20,
                'queue_timeout_seconds' => 300,
            ],

            // --- FESTIVAL (Altro tipo) ---
            [
                'title' => 'Tomorrowland Winter',
                'type' => 'festival',
                'details' => ['location' => 'Alpe d\'Huez', 'days' => 7, 'lineup_size' => '100+ DJs'],
                'max_concurrent_users' => 3,
                'queue_timeout_seconds' => 200,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
