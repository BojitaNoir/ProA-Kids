<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'icono', 'module_id'];

    /**
     * Get the module that owns the category.
     */
    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'module_id');
    }

    /**
     * Get the subcategories for the category.
     */
    public function subcategorias(): HasMany
    {
        return $this->hasMany(Subcategoria::class, 'category_id');
    }

    /**
     * Get the documents for the category (legacy/redundant but kept for compatibility).
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }
}
