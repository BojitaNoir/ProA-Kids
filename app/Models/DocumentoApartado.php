<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoApartado extends Model
{
    use HasFactory;

    protected $table = 'documento_apartados';

    protected $fillable = [
        'documento_id',
        'titulo',
        'fragmento_texto',
        'archivo_path',
        'orden',
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }
}
