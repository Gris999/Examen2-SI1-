<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Horario;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    private array $tipos = ['TEORIA','LABORATORIO','AUDITORIO','VIRTUAL'];

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $tipo = $request->get('tipo');

        $aulas = Aula::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('codigo', 'ILIKE', "%$q%")
                      ->orWhere('nombre', 'ILIKE', "%$q%")
                      ->orWhere('tipo', 'ILIKE', "%$q%")
                      ->orWhere('ubicacion', 'ILIKE', "%$q%");
            })
            ->when($tipo, fn($query)=>$query->where('tipo', $tipo))
            ->orderBy('id_aula','desc')
            ->paginate(10)
            ->withQueryString();

        $usoMap = Horario::selectRaw('id_aula, COUNT(*) as c')
            ->whereNotNull('id_aula')
            ->groupBy('id_aula')
            ->pluck('c','id_aula');

        $tipos = $this->tipos;
        return view('aulas.index', compact('aulas','q','tipo','tipos','usoMap'));
    }

    public function create()
    {
        $tipos = $this->tipos;
        return view('aulas.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => ['nullable','string','max:50'],
            'nombre' => ['required','string','max:120'],
            'tipo' => ['nullable','string','max:50'],
            // capacidad requerida salvo aulas virtuales
            'capacidad' => ['nullable','integer','min:1','required_unless:tipo,VIRTUAL'],
            'ubicacion' => ['nullable','string'],
        ]);

        Aula::create($data);
        return redirect()->route('aulas.index')->with('status','Aula creada correctamente.');
    }

    public function edit(Aula $aula)
    {
        $tipos = $this->tipos;
        return view('aulas.edit', compact('aula','tipos'));
    }

    public function update(Request $request, Aula $aula)
    {
        $data = $request->validate([
            'codigo' => ['nullable','string','max:50'],
            'nombre' => ['required','string','max:120'],
            'tipo' => ['nullable','string','max:50'],
            'capacidad' => ['nullable','integer','min:1','required_unless:tipo,VIRTUAL'],
            'ubicacion' => ['nullable','string'],
        ]);

        $aula->update($data);
        return redirect()->route('aulas.index')->with('status','Aula actualizada correctamente.');
    }

    public function destroy(Aula $aula)
    {
        if (Horario::where('id_aula', $aula->id_aula)->exists()) {
            return back()->withErrors(['general' => 'No se puede eliminar: el aula está en uso en horarios.']);
        }
        $aula->delete();
        return redirect()->route('aulas.index')->with('status','Aula eliminada.');
    }
}

