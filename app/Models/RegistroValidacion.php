<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroValidacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patologia',
        'medicamento',
        'clcr',
        'resultado',
        'mensaje_resultado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
