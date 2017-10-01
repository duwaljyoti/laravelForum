<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class isConfirmed
 * @package App\Http\Middleware
 */
class IsConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->confirmed) {
            return redirect('/threads')->with('flash', 'You must first confirm your email address.');
        }
        return $next($request);
    }
}
