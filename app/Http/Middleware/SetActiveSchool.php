<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetActiveSchool
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $host = $request->getHttpHost();
        $host = $request->getHost();
        $school = School::where('domain', $host)->firstOfFail();
        App::instance('school.active', $school);
        $db = $school->database_options['dbname'];

        //switching to db
        Config::set('database.connections.tenant.database', $db);

        // $school = School::where('domain', $host)->firstOfFail();
        // if($school){
        // App::instance('school.active', $school);
    // }
        return $next($request);
    }
}
