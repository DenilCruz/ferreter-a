<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\User;
use App\Models\Bitacora;

class UsuarioController extends Controller
{
    /**
     * Mostrar la lista completa de usuarios.
     * Solo administradores (se define en vista o en middleware, pero aquí ponemos Gate)
     */
    public function index()
    {
        Gate::authorize('admin');
        
        $usuarios = Usuario::all();
        $isAdmin = true; // Por la linea anterior siempre será true pero lo mandamos por consistencia
        
        return view('usuarios.index', compact('usuarios', 'isAdmin'));
    }

    /**
     * El método "Estudios": devuelve un JSON completo para consultar en el modal.
     */
    public function getUsuario($ci)
    {
        Gate::authorize('admin');

        $usuario = Usuario::where('ci', $ci)->first();
        if ($usuario) {
            // Ejemplo extra para tu "posible estudio", buscar roles asignados (Opcional, pero te lo dejo de extra).
            $asignaciones = \App\Models\EstadoRol::with('rol')->where('ci_empleado', $ci)->get();
            
            return response()->json([
                'success' => true, 
                'usuario' => $usuario,
                'detalles_estudio_asignaciones' => $asignaciones
            ]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Sincroniza y Modifica datos.
     */
    public function update(Request $request, $ci)
    {
        Gate::authorize('admin');

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'sexo' => 'nullable|string|max:1',
            'correo' => 'required|email|max:150',
            'domicilio' => 'nullable|string|max:255',
            'tipoPersona' => 'required|string|max:50',
        ]);

        $usuario = Usuario::findOrFail($ci);
        $correoAntiguo = $usuario->correo;
        $nombreAntiguo = $usuario->nombre;

        // Iniciar transacción doble motor
        DB::beginTransaction();

        try {
            // 1. Modificar tabla de negocios
            $usuario->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'sexo' => $request->sexo,
                'correo' => $request->correo,
                'domicilio' => $request->domicilio,
                'tipoPersona' => $request->tipoPersona,
            ]);

            // 2. Modificación mágica dual a la base BREEZE `users` (si existía)
            $userBreeze = User::where('email', $correoAntiguo)->first();
            if ($userBreeze) {
                // Actualizamos credenciales Breeze
                $userBreeze->update([
                    'email' => $request->correo,
                    'name' => $request->nombre . ' ' . $request->apellido
                ]);
            }

            // 3. Registrar Accion en Bitacora
            Bitacora::registrar('MODIFICAR', 'usuario');

            DB::commit();

            return redirect()->route('usuarios.index')->with('success', 'Usuario modificado y accesos de seguridad sincronizados con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['form_modificar' => 'No se pudo actualizar conectadamente. Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar usuario irrevocablemente en ambos sistemas
     */
    public function destroy($ci)
    {
        Gate::authorize('admin');

        $usuario = Usuario::findOrFail($ci);
        $correoBorrar = $usuario->correo;

        DB::beginTransaction();

        try {
            // 1. Matar Breeze Auth (deshabilitar login irrevocablemente)
            User::where('email', $correoBorrar)->delete();

            // 2. Extirpar de tabla negocio (Cascará en DB a estadoRol, ventas si está en CASCADE, sino saltará excepción PDO sana)
            $usuario->delete();

            // 3. Apuntar limpieza en libro de registro
            Bitacora::registrar('ELIMINAR', 'usuario');

            DB::commit();

            return redirect()->route('usuarios.index')->with('success_eliminar', 'Usuario y registro de acceso borrados permanentemente del sistema.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['form_eliminar' => 'Falló eliminación. Revisa que este usuario no esté atado a facturas existentes (Llaves foráneas). Error: ' . $e->getMessage()]);
        }
    }
}
