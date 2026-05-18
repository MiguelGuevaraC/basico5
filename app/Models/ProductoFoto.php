<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoFoto extends Model
{
    use SoftDeletes;

    protected $table = 'producto_fotos';

    protected $fillable = [
        'producto_id',
        'ruta',
        'nombre_original',
        'mime_type',
        'tamano_bytes',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}

