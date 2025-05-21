<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        //for api redirect 
        if ($request->is('api/*')) {
            abort(
                response()->json(
                    [
                        'status' => false,
                        'message' => 'Token must have been valid, and the session expiration has been extended. [UnAuthenticated]',
                    ],
                    403
                )
            );
        }

        if (!$request->expectsJson()) {
            if (Route::current()->uri() == "office") {
                return route('login');
            } else {
                return route('login');
            }
        }
    }
}