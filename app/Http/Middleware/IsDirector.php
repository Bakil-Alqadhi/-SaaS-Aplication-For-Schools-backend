<?php

namespace App\Http\Middleware;

use App\Events\DbSchoolConnected;
use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IsDirector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // if ($request->header('X-Sanctum-Guard') == 'teacher' || $request->header('X-Sanctum-Guard') == 'student') {
        //     return response()->json([
        //         "You don't have an access to this data!"
        //     ]);
        // } else if ($request->header('X-Sanctum-Guard') == 'director') {
        //     DB::setDefaultConnection('mysql');
        //     return 'hi bakil';
        // }

        
        return $next($request);
    }
}
