<?php

namespace App\Http\Middleware;

use App\Events\DbSchoolConnected;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetConnection
{
    protected $school_id;
    protected $school_name;

    protected $authUser;
    protected $guard = 'web';

    protected $bearer;
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
        ///////////////////
        // $this->guard = $request->header('X-Sanctum-Guard');
        // event(new DbSchoolConnected(School::findOrFail($request->header('X-School'))));

        // $this->token = $request->bearerToken();
        // if ($this->guard == 'teacher' || $this->guard == 'student') {
        //     DB::setDefaultConnection('tenant');
        // } else {
        //     DB::setDefaultConnection('mysql');
        // }

        $this->bearer = $request->bearerToken();

        if ($request->header('X-Sanctum-Guard') == 'teacher' || $request->header('X-Sanctum-Guard') == 'student') {
            $this->school_id = $request->header('X-School');
            ///
            event(new DbSchoolConnected(School::findOrFail($this->school_id)));
            DB::setDefaultConnection('tenant');
            return response(DB::table('personal_access_tokens')->where('token', hash('sha256', $this->bearer))->first());


            if ($this->token = DB::table('personal_access_tokens')->where('token', hash('sha256', $this->bearer))->first()) {
                // if (Teacher::findOrFail($this->token->tokenable_id) || Student::findOrFail($this->token->tokenable_id)) {
                //     return $next($request);
                // }
                // return response($this->token);
            }
        } else if ($request->header('X-Sanctum-Guard') == 'director') {
            DB::setDefaultConnection('mysql');
            if ($this->token = DB::table('personal_access_tokens')->where('token', hash('sha256', $this->bearer))->first()) {
                if (User::findOrFail($this->token->tokenable_id)) {
                    // Auth::login($user);
                    return $next($request);
                }
            }
        }


        // return response($this->school_id);

        // $this->school_id = $request->header('X-School');
        // event(new DbSchoolConnected(School::findOrFail($this->school_id)));
        //     DB::setDefaultConnection('tenant');

        return response()->json([
            'success' => false,
            'error' => 'Access denied.',
        ]);
        // return $next($request);
    }
}
