<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitacoraController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado a la bitácora del sistema.');
        }

        $registros = Bitacora::with('user')->latest()->paginate(20);
        return view('bitacora.index', compact('registros'));
    }
}
