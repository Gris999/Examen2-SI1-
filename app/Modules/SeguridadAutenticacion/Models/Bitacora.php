<?php

namespace App\Modules\SeguridadAutenticacion\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacora';
    protected $primaryKey = 'id_bitacora';
    public $timestamps = false; // usa columna `fecha` propia

    protected $fillable = [
        'id_usuario', 'accion', 'tabla_afectada', 'id_afectado', 'ip_origen', 'descripcion', 'fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}

