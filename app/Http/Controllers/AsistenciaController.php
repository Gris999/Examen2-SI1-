<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Horario;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->date('desde');
        $hasta = $request->date('hasta');
        $docenteId = $request->integer('docente_id');
        $estado = $request->get('estado');

        $q = Asistencia::with(['docente.usuario','horario.grupo.materia','horario.grupo.gestion','horario.aula'])
            ->when($desde, fn($x)=>$x->whereDate('fecha', '>=', $desde))
            ->when($hasta, fn($x)=>$x->whereDate('fecha', '<=', $hasta))
            ->when($docenteId, fn($x)=>$x->where('id_docente', $docenteId))
            ->when($estado, fn($x)=>$x->where('estado', $estado))
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc');

        $asistencias = $q->paginate(12)->withQueryString();
        $docentes = Docente::with('usuario')->orderBy('id_docente','desc')->get();
        return view('asistencias.index', compact('asistencias','docentes','desde','hasta','docenteId','estado'));
    }

    public function create(Request $request)
    {
        $fecha = $request->date('fecha') ?: now()->toDateString();
        $dow = $this->dowName(\Carbon\Carbon::parse($fecha)->dayOfWeekIso);

        $horarios = Horario::with(['grupo.materia','grupo.gestion','docenteMateriaGestion.docente.usuario','aula'])
            ->where('dia', $dow)
            ->orderBy('id_horario','desc')
            ->get();

        return view('asistencias.create', compact('horarios','fecha'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_horario' => ['required','integer','exists:horarios,id_horario'],
            'fecha' => ['required','date'],
            'metodo' => ['nullable','in:FORM,MANUAL,QR'],
            'justificacion' => ['nullable','string'],
        ]);

        $horario = Horario::with('docenteMateriaGestion')->findOrFail($data['id_horario']);
        $docenteId = $horario->docenteMateriaGestion->id_docente ?? null;
        if (!$docenteId) {
            return back()->withErrors(['id_horario' => 'El horario no tiene docente asignado.'])->withInput();
        }

        $now = now();
        $estado = $now->format('H:i') > $horario->hora_inicio ? 'RETRASO' : 'PRESENTE';

        Asistencia::create([
            'id_horario' => $horario->id_horario,
            'id_docente' => $docenteId,
            'fecha' => $data['fecha'],
            'hora_entrada' => $now->format('H:i:s'),
            'metodo' => $data['metodo'] ?? 'FORM',
            'estado' => $estado,
            'justificacion' => $data['justificacion'] ?? null,
            'registrado_por' => auth()->user()->id_usuario ?? null,
            'fecha_registro' => $now,
        ]);

        return redirect()->route('asistencias.index')->with('status','Asistencia registrada.');
    }

    public function edit(Asistencia $asistencia)
    {
        return view('asistencias.edit', compact('asistencia'));
    }

    public function update(Request $request, Asistencia $asistencia)
    {
        $data = $request->validate([
            'estado' => ['required','in:PRESENTE,AUSENTE,RETRASO,JUSTIFICADO'],
            'justificacion' => ['nullable','string'],
        ]);
        $asistencia->update($data);
        return redirect()->route('asistencias.index')->with('status','Asistencia actualizada.');
    }

    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();
        return redirect()->route('asistencias.index')->with('status','Asistencia eliminada.');
    }

    // QR: muestra un QR que codifica una URL firmada para registrar asistencia
    public function qr(Horario $horario)
    {
        $fecha = now()->toDateString();
        $signed = URL::temporarySignedRoute('asistencias.qr.register', now()->addMinutes(15), [
            'horario' => $horario->id_horario,
            'fecha' => $fecha,
        ]);
        return view('asistencias.qr', [
            'horario' => $horario->load(['grupo.materia','grupo.gestion','docenteMateriaGestion.docente.usuario','aula']),
            'fecha' => $fecha,
            'signed' => $signed,
        ]);
    }

    public function qrRegister(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Link de QR inválido o expirado');
        }
        $horario = Horario::with('docenteMateriaGestion')->findOrFail($request->query('horario'));
        $fecha = $request->query('fecha');
        $docenteId = $horario->docenteMateriaGestion->id_docente ?? null;
        if (!$docenteId) abort(400, 'Horario sin docente');

        $now = now();
        $estado = $now->format('H:i') > $horario->hora_inicio ? 'RETRASO' : 'PRESENTE';

        Asistencia::create([
            'id_horario' => $horario->id_horario,
            'id_docente' => $docenteId,
            'fecha' => $fecha,
            'hora_entrada' => $now->format('H:i:s'),
            'metodo' => 'QR',
            'estado' => $estado,
            'registrado_por' => auth()->user()->id_usuario ?? null,
            'fecha_registro' => $now,
        ]);

        return redirect()->route('asistencias.index')->with('status','Asistencia por QR registrada.');
    }

    private function dowName(int $iso): string
    {
        // 1=Lunes ... 7=Domingo; nuestra BD usa Lunes..Sábado
        return match($iso) {
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            default => 'Lunes',
        };
    }
}
