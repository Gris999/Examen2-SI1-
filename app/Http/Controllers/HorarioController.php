<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Docente;
use App\Models\DocenteMateriaGestion as DMG;
use App\Models\Gestion;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    private array $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    private array $modalidades = ['PRESENCIAL','VIRTUAL','HIBRIDA'];

    public function index(Request $request)
    {
        $docenteId = $request->integer('docente_id');
        $gestionId = $request->integer('gestion_id');
        $materiaId = $request->integer('materia_id');
        $grupoId = $request->integer('grupo_id');
        $aulaId = $request->integer('aula_id');
        $dia = $request->get('dia');

        $query = Horario::with(['grupo.materia','grupo.gestion','docenteMateriaGestion.docente.usuario','aula']);

        if ($docenteId) {
            $dmgIds = DMG::where('id_docente', $docenteId)->pluck('id_docente_materia_gestion');
            $query->whereIn('id_docente_materia_gestion', $dmgIds);
        }
        if ($gestionId) {
            $grupoIds = Grupo::where('id_gestion', $gestionId)->pluck('id_grupo');
            $query->whereIn('id_grupo', $grupoIds);
        }
        if ($materiaId) {
            $grupoIds = Grupo::where('id_materia', $materiaId)->pluck('id_grupo');
            $query->whereIn('id_grupo', $grupoIds);
        }
        if ($grupoId) {
            $query->where('id_grupo', $grupoId);
        }
        if ($aulaId) {
            $query->where('id_aula', $aulaId);
        }
        if ($dia) {
            $query->where('dia', $dia);
        }

        $horarios = $query->orderBy('id_horario','desc')->paginate(10)->withQueryString();

        $docentes = Docente::with('usuario')->orderBy('id_docente','desc')->get();
        $gestiones = Gestion::orderBy('fecha_inicio','desc')->get();
        $materias = Materia::orderBy('nombre')->get();
        $grupos = Grupo::orderBy('id_grupo','desc')->get();
        $aulas = Aula::orderBy('nombre')->get();
        $dias = $this->dias;

        return view('horarios.index', compact('horarios','docentes','gestiones','materias','grupos','aulas','dias','docenteId','gestionId','materiaId','grupoId','aulaId','dia'));
    }

    public function create()
    {
        $docentes = Docente::with('usuario')->orderBy('id_docente','desc')->get();
        $grupos = Grupo::with(['materia','gestion'])->orderBy('id_grupo','desc')->get();
        $aulas = Aula::orderBy('nombre')->get();
        $dias = $this->dias;
        $modalidades = $this->modalidades;
        return view('horarios.create', compact('docentes','grupos','aulas','dias','modalidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_docente' => ['required','integer','exists:docentes,id_docente'],
            'id_grupo' => ['required','integer','exists:grupos,id_grupo'],
            'id_aula' => ['nullable','integer','exists:aulas,id_aula'],
            'dia' => ['required','string'],
            'hora_inicio' => ['required','date_format:H:i'],
            'hora_fin' => ['required','date_format:H:i','after:hora_inicio'],
            'modalidad' => ['required','in:PRESENCIAL,VIRTUAL,HIBRIDA'],
            'virtual_plataforma' => ['nullable','string','max:50'],
            'virtual_enlace' => ['nullable','string'],
            'observacion' => ['nullable','string'],
        ]);

        $grupo = Grupo::with(['materia','gestion'])->findOrFail($data['id_grupo']);

        $dmg = DMG::where('id_docente', $data['id_docente'])
            ->where('id_materia', $grupo->id_materia)
            ->where('id_gestion', $grupo->id_gestion)
            ->first();
        if (!$dmg || $dmg->estado !== 'APROBADA') {
            return back()->withErrors(['id_docente' => 'La asignación Docente–Materia–Gestión no está aprobada. Revise CU6/CU7.'])->withInput();
        }

        $this->validateOverlap($data, $dmg->id_docente_materia_gestion);

        Horario::create([
            'id_docente_materia_gestion' => $dmg->id_docente_materia_gestion,
            'id_grupo' => $grupo->id_grupo,
            'id_aula' => $data['id_aula'] ?? null,
            'dia' => $data['dia'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin' => $data['hora_fin'],
            'modalidad' => $data['modalidad'],
            'virtual_plataforma' => $data['virtual_plataforma'] ?? null,
            'virtual_enlace' => $data['virtual_enlace'] ?? null,
            'observacion' => $data['observacion'] ?? null,
        ]);

        return redirect()->route('horarios.index')->with('status','Horario creado.');
    }

    public function edit(Horario $horario)
    {
        $horario->load(['grupo.materia','grupo.gestion','docenteMateriaGestion.docente.usuario','aula']);
        $docentes = Docente::with('usuario')->orderBy('id_docente','desc')->get();
        $grupos = Grupo::with(['materia','gestion'])->orderBy('id_grupo','desc')->get();
        $aulas = Aula::orderBy('nombre')->get();
        $dias = $this->dias;
        $modalidades = $this->modalidades;
        return view('horarios.edit', compact('horario','docentes','grupos','aulas','dias','modalidades'));
    }

    public function update(Request $request, Horario $horario)
    {
        $data = $request->validate([
            'id_docente' => ['required','integer','exists:docentes,id_docente'],
            'id_grupo' => ['required','integer','exists:grupos,id_grupo'],
            'id_aula' => ['nullable','integer','exists:aulas,id_aula'],
            'dia' => ['required','string'],
            'hora_inicio' => ['required','date_format:H:i'],
            'hora_fin' => ['required','date_format:H:i','after:hora_inicio'],
            'modalidad' => ['required','in:PRESENCIAL,VIRTUAL,HIBRIDA'],
            'virtual_plataforma' => ['nullable','string','max:50'],
            'virtual_enlace' => ['nullable','string'],
            'observacion' => ['nullable','string'],
        ]);

        $grupo = Grupo::with(['materia','gestion'])->findOrFail($data['id_grupo']);

        $dmg = DMG::where('id_docente', $data['id_docente'])
            ->where('id_materia', $grupo->id_materia)
            ->where('id_gestion', $grupo->id_gestion)
            ->first();
        if (!$dmg || $dmg->estado !== 'APROBADA') {
            return back()->withErrors(['id_docente' => 'La asignación Docente–Materia–Gestión no está aprobada. Revise CU6/CU7.'])->withInput();
        }

        $this->validateOverlap($data, $dmg->id_docente_materia_gestion, $horario->id_horario);

        $horario->update([
            'id_docente_materia_gestion' => $dmg->id_docente_materia_gestion,
            'id_grupo' => $grupo->id_grupo,
            'id_aula' => $data['id_aula'] ?? null,
            'dia' => $data['dia'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin' => $data['hora_fin'],
            'modalidad' => $data['modalidad'],
            'virtual_plataforma' => $data['virtual_plataforma'] ?? null,
            'virtual_enlace' => $data['virtual_enlace'] ?? null,
            'observacion' => $data['observacion'] ?? null,
        ]);

        return redirect()->route('horarios.index')->with('status','Horario actualizado.');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('horarios.index')->with('status','Horario eliminado.');
    }

    private function validateOverlap(array $data, int $dmgId, ?int $ignoreHorarioId = null): void
    {
        $docDmgIds = DMG::where('id_docente', DMG::find($dmgId)->id_docente)
            ->pluck('id_docente_materia_gestion');

        $conflictDoc = Horario::whereIn('id_docente_materia_gestion', $docDmgIds)
            ->where('dia', $data['dia'])
            ->when($ignoreHorarioId, fn($q)=>$q->where('id_horario','!=',$ignoreHorarioId))
            ->where(function($q) use ($data) {
                $q->where('hora_inicio','<',$data['hora_fin'])
                  ->where('hora_fin','>',$data['hora_inicio']);
            })
            ->exists();
        if ($conflictDoc) {
            abort(back()->withErrors(['hora_inicio' => 'El docente ya tiene un horario que se solapa.'])->getTargetUrl());
        }

        if (!empty($data['id_aula'])) {
            $conflictAula = Horario::where('id_aula', $data['id_aula'])
                ->where('dia', $data['dia'])
                ->when($ignoreHorarioId, fn($q)=>$q->where('id_horario','!=',$ignoreHorarioId))
                ->where(function($q) use ($data) {
                    $q->where('hora_inicio','<',$data['hora_fin'])
                      ->where('hora_fin','>',$data['hora_inicio']);
                })
                ->exists();
            if ($conflictAula) {
                abort(back()->withErrors(['id_aula' => 'El aula ya está ocupada en ese rango.'])->getTargetUrl());
            }
        }

        $conflictGrupo = Horario::where('id_grupo', $data['id_grupo'])
            ->where('dia', $data['dia'])
            ->when($ignoreHorarioId, fn($q)=>$q->where('id_horario','!=',$ignoreHorarioId))
            ->where(function($q) use ($data) {
                $q->where('hora_inicio','<',$data['hora_fin'])
                  ->where('hora_fin','>',$data['hora_inicio']);
            })
            ->exists();
        if ($conflictGrupo) {
            abort(back()->withErrors(['id_grupo' => 'El grupo ya tiene un horario que se solapa.'])->getTargetUrl());
        }
    }
}
