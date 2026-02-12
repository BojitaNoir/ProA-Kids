<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Bitacora;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            Bitacora::registrar('Inicio de Sesión', "El usuario '{$user->name}' ({$user->role}) accedió al sistema.");

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/portal-medico');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            Bitacora::registrar('Cierre de Sesión', "El usuario '{$user->name}' salió del sistema.");
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Handle Quick Login for Medical Staff
     */
    public function quickLogin(Request $request)
    {
        $doctor = User::where('role', 'doctor')->first();

        if ($doctor) {
            Auth::login($doctor);
            $request->session()->regenerate();

            Bitacora::registrar('Inicio de Sesión (Rápido)', "El usuario '{$doctor->name}' accedió mediante Acceso de Staff.");

            return redirect()->route('doctor.dashboard');
        }

        return back()->withErrors(['email' => 'No hay cuentas de médico disponibles para acceso rápido.']);
    }

    /**
     * Handle API login request (for Flutter)
     */
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas.'
            ], 401);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * Get authenticated user profile data
     */
    public function getProfile(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
                'created_at' => $request->user()->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
