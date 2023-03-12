<?php

namespace App\Listeners;

use App\Events\SchoolCreated;
use DirectoryIterator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CreateSchoolDatabase
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
    public function handle(SchoolCreated $event): void
    {
        $school =  $event->school;

        $db = "school_". $school->id;

        $school->database_options = [
            'dbname'=> $db
        ];
        $school->save();

        //create db for new school
        DB::statement("CREATE DATABASE `{$db}`");

        //switching to the new db
        Config::set('database.connections.tenant.database', $db);

        $dir = new DirectoryIterator(database_path('migrations/tenants'));
        foreach($dir as $file){
            if($file->isFile()){
                Artisan::call('migrate', [
                    '--database' =>'tenant',
                    '--path' => 'database/migrations/tenants/' . $file->getFilename(),
                    '--force' => true,
                ]);
            }
        }
    }
}
