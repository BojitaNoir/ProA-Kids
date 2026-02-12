<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'icono'];

    /**
     * Get the documents for the category.
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }
}
