<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'archivo_path', // Kept for legacy compatibility, stores the path of the first/current version
        'categoria_id',
        'current_version_id',
        'visibilidad',
        'uploaded_by',
    ];

    /**
     * Get the category of the document.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Get all versions of the document.
     */
    public function versiones(): HasMany
    {
        return $this->hasMany(DocumentoVersion::class)->orderBy('version_number', 'desc');
    }

    /**
     * Get the latest version of the document.
     */
    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentoVersion::class, 'current_version_id');
    }

    /**
     * Get all sections/apartados of the document.
     */
    public function apartados(): HasMany
    {
        return $this->hasMany(DocumentoApartado::class)->orderBy('orden', 'asc');
    }

    /**
     * Get the user that uploaded the document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Accessor to get the file path from the current version.
     * This ensures compatibility with existing code calling $documento->archivo_path.
     */
    public function getArchivoPathAttribute($value)
    {
        return $this->currentVersion ? $this->currentVersion->archivo_path : $value;
    }
}
