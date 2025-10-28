<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    // Tabla y clave primaria personalizadas
    protected $table = 'docentes';
    protected $primaryKey = 'id_docente';
    public $timestamps = false; // La tabla no tiene created_at/updated_at

    protected $fillable = [
        // Ajusta según tus columnas reales
        'id_usuario',
        'codigo',
        'departamento',
    ];

    // Relación con el usuario propietario (tabla `usuarios`)
    public function usuario()
    {
        // clave foránea en `docentes`: id_usuario -> usuarios.id_usuario
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    // Roles asociados al usuario del docente
    public function roles()
    {
        // Usa el id_usuario del docente para resolver roles vía la tabla pivote usuario_rol
        // belongsToMany con claves personalizadas para mapear docentes.id_usuario -> usuario_rol.id_usuario
        return $this->belongsToMany(
            Rol::class,
            'usuario_rol',      // tabla pivote
            'id_usuario',       // columna pivote que referencia al usuario
            'id_rol',           // columna pivote que referencia al rol
            'id_usuario',       // clave del modelo Docente a usar (columna en docentes)
            'id_rol'            // clave del modelo Rol (PK en roles)
        );
    }
}

