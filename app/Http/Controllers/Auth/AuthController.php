<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display the login page.
     *
     * Responsibilities:
     * - Render the login form view
     */
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle an authentication (login) request.
     *
     * Responsibilities:
     * - Validate login credentials
     * - Attempt user authentication
     * - Regenerate the session to prevent session fixation
     * - Redirect users based on their role
     *   (admin > admin dashboard, user > homepage)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        /**
         * Attempt to authenticate the user using the provided credentials.
         */
        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid email or password',
            ]);
        }

        /**
         * Regenerate the session after successful login
         * to protect against session fixation attacks.
         */
        $request->session()->regenerate();

        $user = Auth::user();

        /**
         * Redirect users based on their role.
         */
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    /**
     * Handle the logout process.
     *
     * Responsibilities:
     * - Log out the authenticated user
     * - Invalidate the current session
     * - Regenerate the CSRF token
     * - Redirect the user to the login page
     */
    public function logout(Request $request)
    {
        Auth::logout();

        /**
         * Invalidate and regenerate session data
         * to ensure a clean logout state.
         */
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
