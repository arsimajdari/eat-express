<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $token = $user->createToken("API TOKEN")->plainTextToken;
        $message = 'User logged in successfully';

        return response([
            'message' => $message,
            'token' => $token,
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out successfully!',
            'status_code' => 200
        ], 200);
    }
}
