<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Foto extends Model
{
    protected $table = 'foto';
    public $timestamps = false;

    protected $fillable = [
        'establecimiento_id', 'url', 'es_portada',
    ];

    protected $casts = [
        'es_portada' => 'boolean',
    ];

    // Accessor: $foto->url_completa te da la URL pública
    public function getUrlCompletaAttribute(): string
    {
        return asset('storage/' . $this->url);
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}