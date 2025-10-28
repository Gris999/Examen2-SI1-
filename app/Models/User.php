<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The model does not use the default created_at/updated_at timestamps.
     * The database table `usuarios` doesn't include those columns.
     */
    public $timestamps = false;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $fillable = ['nombre', 'apellido', 'correo', 'contrasena', 'telefono', 'activo'];
    protected $hidden = ['contrasena', 'remember_token'];

    /**
     * Override the password accessor for Laravel auth compatibility.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'id_usuario', 'id_rol');
    }
}

