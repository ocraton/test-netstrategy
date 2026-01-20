<?php

namespace App\Console\Commands;

use App\Models\EventQueue;
use Illuminate\Console\Command;

class CleanExpiredQueues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rimuove gli utenti dalla coda se il loro tempo è scaduto';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $deleted = EventQueue::where('expires_at', '<', now())->delete();

        if ($deleted > 0) {
            $this->info("Pulizia completata. Rimossi {$deleted} utenti scaduti.");
        } else {
            $this->info('Nessun utente scaduto trovato.');
        }
    }
}
