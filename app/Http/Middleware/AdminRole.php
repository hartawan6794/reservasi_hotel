<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn ($role) => explode('|', (string) $role))
            ->map(fn ($role) => trim($role))
            ->filter()
            ->values()
            ->all();

        if (empty($allowedRoles)) {
            return $next($request);
        }

        $columnMatch = $user->role && in_array($user->role, $allowedRoles, true);
        $spatieMatch = method_exists($user, 'hasAnyRole') ? $user->hasAnyRole($allowedRoles) : false;

        if (! $columnMatch && ! $spatieMatch) {
            if ($request->expectsJson()) {
                abort(Response::HTTP_FORBIDDEN, 'This action is unauthorized.');
            }

            return redirect()->route('dashboard')->with('error', 'You are not authorized to access that page.');
        }

        return $next($request);
    }
}
 