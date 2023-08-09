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
    public function store(Request $request): Response
    {

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
        ]);


        $this->authenticateFrontend();
        $message = 'User logged in successfully';

        return response([
            'message' => $message,
        ], 200);



        // $request->session()->regenerate();
    }

    private function authenticateFrontend()
    {
        if (!Auth::guard('web')
            ->attempt(
                request()->only('email', 'password'),
                request()->boolean('remember')
            )) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {

        Auth::guard('web')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();
        return response('Logged out successfully!', 200);
    }
}
