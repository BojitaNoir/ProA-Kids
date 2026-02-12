<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'icono'];

    /**
     * Get the categories for the module.
     */
    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class, 'module_id');
    }
}
