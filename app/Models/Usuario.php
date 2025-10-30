<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'contrasena',
        'telefono',
        'activo',
        'fecha_creacion',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function setContrasenaAttribute($value)
    {
        if (! empty($value) && ! is_string($value)) {
            $value = (string) $value;
        }
        if (! empty($value) && ! str_starts_with($value, '$2y$')) {
            $this->attributes['contrasena'] = Hash::make($value);
        } else {
            $this->attributes['contrasena'] = $value;
        }
    }

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function docente()
    {
        return $this->hasOne(Docente::class, 'id_usuario', 'id_usuario');
    }
}
