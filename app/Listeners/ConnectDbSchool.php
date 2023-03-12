<?php

namespace App\Listeners;

use App\Events\DbSchoolConnected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Config;

class ConnectDbSchool
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DbSchoolConnected $event): void
    {
        Config::set('database.connections.tenant.database', $event->db);

    }
}