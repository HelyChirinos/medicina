<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasProfilePhoto;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasProfilePhoto;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'email',
        'password',
        'direccion',
        'telefono',
        'fecha_nac',        
        'is_propietario',        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_propietario' => 'boolean',        
        'password' => 'hashed',
        'fecha_nac' => 'date:d/m/Y',        
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */

    
    public function getNameAttribute()
    {
        $nombre = strpos(trim($this->nombre)," ") ? substr($this->nombre,0,strpos(trim($this->nombre)," ")) : trim($this->nombre);
        $apellido = strpos(trim($this->apellido)," ") ? substr($this->apellido,0,strpos(trim($this->apellido)," ")) : trim($this->apellido);
        return $nombre . ' ' . $apellido;
    }


    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucwords(trim(strtolower($value)));
    }

    public function setApellidoAttribute($value)
    {
       $this->attributes['apellido'] = ucwords(trim(strtolower($value)));
    }

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }


    /**
     * Appends .
     */
 
     protected $appends = [
        'profile_photo_url', 'name',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Relationships
    /* -------------------------------------------------------------------------------------------- */
    public function userlogs(): HasMany
    {
        return $this->hasMany(Userlog::class);
    }
}
