<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accion',
        'descripcion',
        'ip_address',
        'fecha',
        'hora',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to log an action.
     */
    public static function registrar($accion, $descripcion)
    {
        return self::create([
            'user_id' => auth()->id(),
            'accion' => $accion,
            'descripcion' => $descripcion,
            'ip_address' => request()->ip(),
            'fecha' => now()->toDateString(),
            'hora' => now()->toTimeString(),
        ]);
    }
}
