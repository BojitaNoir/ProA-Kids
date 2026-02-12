<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'nombre'];

    /**
     * Get the category that owns the subcategory.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'category_id');
    }

    /**
     * Get the documents for the subcategory.
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'subcategory_id');
    }
}
