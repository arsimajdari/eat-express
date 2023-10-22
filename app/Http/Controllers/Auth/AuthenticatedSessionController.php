<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): array
    {
        $request->authenticate();

        $token = $request->user()->createToken('auth')->plainTextToken;

        return [
            'token' => $token,
            'data' => $request->user(),
        ];
    }




    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {



        $request->user()->tokens()->delete();

        return response('Logged out successfully!', 200);
    }
}
