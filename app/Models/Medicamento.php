<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'familia',
        'ficha_tecnica',
        'edades_recomendadas',
        'dosis_recomendada',
        'indicaciones_especiales',
    ];

    /**
     * Clinical guides or documents related to this medicine.
     */
    public function documentos(): BelongsToMany
    {
        return $this->belongsToMany(Documento::class, 'medicamento_documento');
    }

    /**
     * Other related medicines.
     */
    public function relacionados(): BelongsToMany
    {
        return $this->belongsToMany(Medicamento::class, 'medicamento_relacionado', 'medicamento_id', 'relacionado_id');
    }

    /**
     * Relación con alertas de peligro
     */
    public function alertasPeligro()
    {
        return $this->hasMany(AlertaPeligro::class);
    }
}
