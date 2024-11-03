<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estudiante extends Model
{
    use HasFactory;
    protected $guarded = [];




    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucwords(trim(strtolower($value)));
    }

     /* -------------------------------------------------------------------------------------------- */
    // Relationships
    /* -------------------------------------------------------------------------------------------- */
    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class);
    }

    public function mencion(): belongsTo
    {
        return $this->belongsTo(Mencion::class);
    }

    public function recibos(): HasMany
    {
        return $this->hasMany(Recibo::class, 'estudiante_id');
    }

}
