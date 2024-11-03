<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposito extends Model
{
    use HasFactory;
    protected $guarded = [];

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */


    protected function numero(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) =>substr($value, -5),
        );
    }

     /* -------------------------------------------------------------------------------------------- */
    // Relationships
    /* -------------------------------------------------------------------------------------------- */

    public function Estudiante(): belongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }


}
