<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
 use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login submission
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Generate API token for dashboard
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            // Store token in session (persistent, not flash)
            $request->session()->put('api_token', $token);

            return redirect()
                ->intended(route('dashboard'))
                ->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle register submission
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:employee,admin',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'email_verified_at' => now(),
            ]);

            // Create leave balance for the new user
            $user->leaveBalances()->create([
                'year' => now()->year,
                'total_quota' => 12,
                'used_quota' => 0,
                'remaining_quota' => 12,
            ]);

            // Auto login
            Auth::login($user);
            $request->session()->regenerate();

            // Generate API token for dashboard
            $token = $user->createToken('auth_token')->plainTextToken;
            
            // Store token in session (persistent, not flash)
            $request->session()->put('api_token', $token);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Akun berhasil dibuat! Selamat datang di Leave Management System.');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('name', 'email', 'role'))
                ->withErrors(['error' => 'Terjadi kesalahan saat membuat akun. Silakan coba lagi.']);
        }
    }

    /**
     * Redirect user to Google OAuth
     */
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists, if not create them
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                Auth::login($user);
            } else {
                // Create new user from Google account
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()), // Generate random password since OAuth doesn't use password
                    'role' => 'employee', // Default role for Google OAuth signup
                    'email_verified_at' => now(),
                ]);

                // Create leave balance for the new user
                $user->leaveBalances()->create([
                    'year' => now()->year,
                    'total_quota' => 12,
                    'used_quota' => 0,
                    'remaining_quota' => 12,
                ]);

                Auth::login($user);
            }

            // Regenerate session
            request()->session()->regenerate();

            // Generate API token for dashboard
            $token = $user->createToken('auth_token')->plainTextToken;
            
            // Store token in session
            request()->session()->put('api_token', $token);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Login dengan Google berhasil! Selamat datang.');
        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return redirect()
                ->route('login')
                ->withErrors(['error' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.']);
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Remove token from session
        $request->session()->forget('api_token');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Logout berhasil.')
            ->with('clear_token', true);
    }
}
