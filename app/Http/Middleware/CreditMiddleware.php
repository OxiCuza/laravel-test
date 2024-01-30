<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $role = $user->role->id;
        $creditResult = $user->credit - 5;

        if ($role != Role::OWNER) {
            if ($creditResult < 0) {
                return response()->api(false, "You don't have enough credit", null, 400);
            }
        }

        return $next($request);
    }
}
