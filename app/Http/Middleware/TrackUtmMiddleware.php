<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Auth;

class TrackUtmMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $utmParameters = $request->only([
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ]);
        if (! empty($utmParameters)) {
            if(Auth::check()){
                $user = User::find(Auth::user()->id);
                $user->update($utmParameters);
            } else {
                session(['utm' => $utmParameters]);
            }
        }
        return $next($request);
    }
}
