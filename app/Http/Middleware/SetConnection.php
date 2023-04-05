<?php

namespace App\Http\Middleware;

use App\Events\DbSchoolConnected;
use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetConnection
{
    protected $school_id;
    protected $school_name;

    protected $authUser;
    protected $guard = 'web';

    protected $token;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if ($request->header('X-Sanctum-Guard') != 'director') {
        //     $this->school_id = $request->header('X-School');
        //     $this->school_name = School::where('id', $this->school_id)->first()->school_name;
        //     $this->guard = $request->header('X-Sanctum-Guard');
        // }
        // $this->school_name = School::where('id', $this->school_id)->first()->school_name;

        // $this->school_id = $request->header('X-School');
        $this->guard = $request->header('X-Sanctum-Guard');
        event(new DbSchoolConnected(School::findOrFail($request->header('X-School'))));

        $this->token = $request->bearerToken();
        if ($this->guard == 'teacher' || $this->guard == 'student') {
            DB::setDefaultConnection('tenant');
        } else {
            DB::setDefaultConnection('mysql');
        }


        // $this->school_id = $request->header('X-School');
        // event(new DbSchoolConnected(School::findOrFail($this->school_id)));
        //     DB::setDefaultConnection('tenant');
        return $next($request);
    }
}