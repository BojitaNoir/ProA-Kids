<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $usuarios = User::where('id', '!=', Auth::id())->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('usuarios.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor',
            'especialidad' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'especialidad' => $request->especialidad,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado correctamente en el sistema HNM.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor',
            'especialidad' => 'nullable|string|max:255',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;
        $usuario->especialidad = $request->especialidad;

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);

        // No permitir borrar al admin actual
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
