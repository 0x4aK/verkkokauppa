<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class RoleMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * 
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|null  $roleNeeded
     * 
     * @return mixed
     */
    public function handle($request, Closure $next, $roleNeeded = 1)
    {
        // error_log('User rolelvl: ' . $this->auth->user()->roleLevel . ' < ' . $roleNeeded . ' :rolelvl needed');

        if ($this->auth->user()->roleLevel < $roleNeeded) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
