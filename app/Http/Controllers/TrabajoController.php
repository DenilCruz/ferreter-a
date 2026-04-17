<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Rol;
use App\Models\EstadoRol;
use App\Models\Empleado;
use App\Models\Usuario;

class TrabajoController extends Controller
{
    public function index()
    {
        $isAdmin = Gate::allows('admin');

        $empleados = [];
        $rolesDisponibles = [];

        if ($isAdmin) {
            // El admin ve TODOS los roles y puede asignar
            // Extraer empleados con sus datos de usuario
            $empleados = Empleado::with('usuario')->get();
            $rolesDisponibles = Rol::all();
            
            // Ver asignaciones de todos
            $asignaciones = EstadoRol::with(['rol', 'empleado.usuario'])->orderBy('fechaInicio', 'desc')->get();
        } else {
            // Un empleado mortal entra aquí. Cruzarlo por correo.
            $yo_empleado = Usuario::where('correo', Auth::user()->email)->first();
            
            if ($yo_empleado) {
                $asignaciones = EstadoRol::with(['rol', 'empleado.usuario'])
                    ->where('ci_empleado', $yo_empleado->ci)
                    ->orderBy('fechaInicio', 'desc')
                    ->get();
            } else {
                $asignaciones = collect(); // vacío si no existe
            }
        }

        return view('trabajos.index', compact('asignaciones', 'empleados', 'rolesDisponibles', 'isAdmin'));
    }

    public function store(Request $request)
    {
        Gate::authorize('admin');

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        Rol::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('trabajos.index')->with('success_rol', 'El trabajo general fue creado en el sistema con éxito.');
    }

    public function asignar(Request $request)
    {
        Gate::authorize('admin');

        $request->validate([
            'id_rol' => 'required|integer|exists:rol,id',
            'ci_empleado' => 'required|string|exists:empleado,ci'
        ]);

        EstadoRol::create([
            'id_rol' => $request->id_rol,
            'ci_empleado' => $request->ci_empleado,
            'fechaInicio' => now()->toDateString(),
            'fechaFinal' => null,
            'estado' => 'Activo'
        ]);

        return redirect()->route('trabajos.index')->with('success_asignacion', 'Trabajo asignado correctamente al empleado seleccionado.');
    }
}
