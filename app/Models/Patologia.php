<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patologia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'alerta_especifica',
    ];

    /**
     * Relación con alertas de peligro
     */
    public function alertasPeligro()
    {
        return $this->hasMany(AlertaPeligro::class);
    }

    /**
     * Obtener medicamentos contraindicados para esta patología
     */
    public function medicamentosContraindicados()
    {
        return $this->belongsToMany(Medicamento::class, 'alertas_peligro')
            ->withPivot('mensaje_error')
            ->withTimestamps();
    }
}
