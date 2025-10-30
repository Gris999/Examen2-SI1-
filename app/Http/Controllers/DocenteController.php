<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $docentes = Docente::query()
            ->with('usuario')
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('usuario', function ($sub) use ($q) {
                    $sub->where('nombre', 'ILIKE', "%$q%")
                        ->orWhere('apellido', 'ILIKE', "%$q%")
                        ->orWhere('correo', 'ILIKE', "%$q%")
                        ->orWhere('telefono', 'ILIKE', "%$q%")
                        ->orWhereRaw("concat(nombre,' ',apellido) ILIKE ?", ["%$q%"]);
                })->orWhere('codigo_docente', 'ILIKE', "%$q%")
                  ->orWhere('profesion', 'ILIKE', "%$q%")
                  ->orWhere('grado_academico', 'ILIKE', "%$q%");
            })
            ->orderBy('id_docente', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('docentes.index', compact('docentes', 'q'));
    }

    public function create()
    {
        return view('docentes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'correo' => ['required', 'email'],
            'codigo_docente' => ['nullable', 'string', 'max:30'],
            'profesion' => ['nullable', 'string', 'max:100'],
            'grado_academico' => ['nullable', 'string', 'max:50'],
        ]);

        $usuario = Usuario::where('correo', $data['correo'])->first();
        if (!$usuario) {
            return back()->withErrors(['correo' => 'El correo no existe en usuarios.'])->withInput();
        }

        $exists = Docente::where('id_usuario', $usuario->id_usuario)->exists();
        if ($exists) {
            return back()->withErrors(['correo' => 'Ese usuario ya está asignado como docente.'])->withInput();
        }

        Docente::create([
            'id_usuario' => $usuario->id_usuario,
            'codigo_docente' => $data['codigo_docente'] ?? null,
            'profesion' => $data['profesion'] ?? null,
            'grado_academico' => $data['grado_academico'] ?? null,
        ]);

        return redirect()->route('docentes.index')->with('status', 'Docente creado correctamente.');
    }

    public function edit(Docente $docente)
    {
        $docente->load('usuario');
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, Docente $docente)
    {
        $data = $request->validate([
            'correo' => ['required', 'email'],
            'codigo_docente' => ['nullable', 'string', 'max:30'],
            'profesion' => ['nullable', 'string', 'max:100'],
            'grado_academico' => ['nullable', 'string', 'max:50'],
        ]);

        $usuario = Usuario::where('correo', $data['correo'])->first();
        if (!$usuario) {
            return back()->withErrors(['correo' => 'El correo no existe en usuarios.'])->withInput();
        }

        $exists = Docente::where('id_usuario', $usuario->id_usuario)
            ->where('id_docente', '!=', $docente->id_docente)
            ->exists();
        if ($exists) {
            return back()->withErrors(['correo' => 'Ese usuario ya está asignado como docente.'])->withInput();
        }

        $docente->update([
            'id_usuario' => $usuario->id_usuario,
            'codigo_docente' => $data['codigo_docente'] ?? null,
            'profesion' => $data['profesion'] ?? null,
            'grado_academico' => $data['grado_academico'] ?? null,
        ]);

        return redirect()->route('docentes.index')->with('status', 'Docente actualizado correctamente.');
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();
        return redirect()->route('docentes.index')->with('status', 'Docente eliminado.');
    }
}

