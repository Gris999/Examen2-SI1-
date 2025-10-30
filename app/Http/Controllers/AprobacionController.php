<?php

namespace App\Http\Controllers;

use App\Models\DocenteMateriaGestion as DMG;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AprobacionController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->get('estado', 'PENDIENTE');
        $gestion = $request->integer('gestion_id');

        $asignaciones = DMG::with(['docente.usuario','materia','gestion'])
            ->when($estado, fn($q)=>$q->where('estado', strtoupper($estado)))
            ->when($gestion, fn($q)=>$q->where('id_gestion', $gestion))
            ->withCount('horarios')
            ->orderBy('id_docente_materia_gestion','desc')
            ->paginate(10)
            ->withQueryString();

        return view('aprobaciones.index', [
            'asignaciones' => $asignaciones,
            'estado' => $estado,
            'gestion' => $gestion,
        ]);
    }

    public function approve(DMG $dmg)
    {
        $dmg->estado = 'APROBADA';
        $dmg->aprobado_por = auth()->id();
        $dmg->aprobado_en = now();
        $dmg->save();
        return back()->with('status', 'Asignación aprobada.');
    }

    public function reject(DMG $dmg)
    {
        $dmg->estado = 'RECHAZADA';
        $dmg->aprobado_por = auth()->id();
        $dmg->aprobado_en = now();
        $dmg->save();
        return back()->with('status', 'Asignación rechazada.');
    }
}
