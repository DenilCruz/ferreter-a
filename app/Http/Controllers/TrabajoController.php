<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Rol;
use App\Models\EstadoRol;
use App\Models\Empleado;
use App\Models\Usuario;
use App\Models\Cliente;

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
            // Obtenemos a TODOS los usuarios registrados como Personal (tipoPersona = 'E')
            $empleados = Usuario::where('tipoPersona', 'E')->get();
            $rolesDisponibles = Rol::all();
            
            // Ver asignaciones de todos
            $asignaciones = EstadoRol::with(['rol', 'empleado.usuario'])->orderBy('fechaInicio', 'desc')->get();
        } else {
            // Un empleado mortal entra aquí. Cruzarlo por correo.
            $yo_empleado = Usuario::where('email', Auth::user()->email)->first();
            
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

        // Calcular el siguiente ID de forma manual
        $ultimoId = Rol::max('id') ?? 0;
        $nuevoId = $ultimoId + 1;

        Rol::create([
            'id' => $nuevoId,
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
            'ci_empleado' => 'required|string|exists:usuario,ci'
        ]);

        // 0. Asegurarse de que el usuario existe en la tabla 'empleado' 
        // (Por si fue registrado como Personal pero nunca se le asignó un rol)
        Empleado::firstOrCreate(
            ['ci' => $request->ci_empleado],
            ['salario' => 0.00, 'estado' => 'Activo']
        );

        // 1. Intentar actualizar si ya tiene el rol (aunque esté inactivo)
        $updated = EstadoRol::where('id_rol', $request->id_rol)
            ->where('ci_empleado', $request->ci_empleado)
            ->update(['fechaInicio' => now()->toDateString(), 'fechaFin' => null, 'estado' => 'Activo']);

        if (!$updated) {
            EstadoRol::create([
                'id_rol'      => $request->id_rol,
                'ci_empleado' => $request->ci_empleado,
                'fechaInicio' => now()->toDateString(),
                'fechaFin'    => null,
                'estado'      => 'Activo',
            ]);
        }

        return redirect()->route('trabajos.index')->with('success_asignacion', 'Trabajo asignado correctamente al empleado seleccionado.');
    }

    public function darDeBaja(Request $request)
    {
        Gate::authorize('admin');

        $request->validate([
            'id_rol'      => 'required|integer',
            'ci_empleado' => 'required|string',
        ]);

        // Marcar la asignación como inactiva
        EstadoRol::where('id_rol', $request->id_rol)
            ->where('ci_empleado', $request->ci_empleado)
            ->update([
                'estado'   => 'Inactivo',
                'fechaFin' => now()->toDateString(),
            ]);

        // Si el empleado ya no tiene ningún rol activo, pasa a ser Cliente
        $tieneRolesActivos = EstadoRol::where('ci_empleado', $request->ci_empleado)
            ->where('estado', 'Activo')
            ->exists();

        if (!$tieneRolesActivos) {
            $usuario = Usuario::find($request->ci_empleado);
            if ($usuario) {
                $usuario->tipoPersona = 'C';
                $usuario->save();
            }

            Cliente::firstOrCreate(
                ['ci' => $request->ci_empleado],
                ['puntos' => 0]
            );

            Empleado::where('ci', $request->ci_empleado)
                ->update(['estado' => 'Inactivo']);

            $mensaje = 'Empleado dado de baja. Sin roles activos: pasó a ser Cliente.';
        } else {
            $mensaje = 'Asignación dada de baja correctamente.';
        }

        return redirect()->route('trabajos.index')->with('success_baja', $mensaje);
    }
}
