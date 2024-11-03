<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Recibo extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function estudiante(): belongsTo
    {
        return $this->belongsTo(Estudiante::class,'no_doc','no_doc');
    }

}
