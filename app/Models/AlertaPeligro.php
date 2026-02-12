<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertaPeligro extends Model
{
    use HasFactory;

    protected $table = 'alertas_peligro';

    protected $fillable = [
        'patologia_id',
        'medicamento_id',
        'mensaje_error',
    ];

    /**
     * Relación con patología
     */
    public function patologia()
    {
        return $this->belongsTo(Patologia::class);
    }

    /**
     * Relación con medicamento
     */
    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }
}
