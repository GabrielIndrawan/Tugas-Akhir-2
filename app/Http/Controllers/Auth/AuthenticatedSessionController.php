<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\UserProfile;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if(Auth::user()->id === "admin"){
            UserProfile::where('username', Auth::user()->id)->update(['is_active' => true]);
            return redirect()->intended(route('dashboard-admin'));
        }

        UserProfile::where('username', Auth::user()->id)->update(['is_active' => true]);
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        UserProfile::where('username', Auth::user()->id)->update(['is_active' => false]);
        Cache::forget("all_users_data");

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
