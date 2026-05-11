# Reporte de Errores: Gestión de Trabajos y Roles

Se ha analizado el código correspondiente al archivo `TrabajoController.php` y su respectiva vista, encontrando y corrigiendo errores de lógica estructural y comportamiento no deseado. A continuación, te detallo los fallos que se pillaron y cómo fueron solucionados:

## 1. El error de "Empleados Fantasma" al reasignar roles (El más grave)
**El problema:** 
En la función `asignar()`, cuando le dabas un trabajo a alguien, el código verificaba si el empleado existía en la base de datos usando `Empleado::firstOrCreate()`. El problema es que si a un empleado se le había dado de "baja" antes, su registro ya existía en la tabla `empleado`, pero con el estado `'Inactivo'`. El método `firstOrCreate` no actualizaba ese estado, lo que causaba que el usuario recibiera el nuevo rol, pero en el sistema siguiera apareciendo como dado de baja o inactivo a nivel de empleado, y además seguía figurando con `tipoPersona = 'C'` (Cliente).

**La solución:**
Se modificó la lógica para que, después de buscar o crear al empleado, el sistema verifique explícitamente si está en estado `'Inactivo'`. Si es así, se le cambia el estado a `'Activo'`. Adicionalmente, se actualiza forzosamente su atributo `tipoPersona` en la tabla `usuario` a `'E'` para garantizar que el sistema lo reconozca como trabajador.

## 2. Imposibilidad de contratar Clientes o Usuarios Nuevos
**El problema:**
En el método `index()`, para mostrar la lista de personas a las que el administrador podía asignarles un rol, se usaba la siguiente línea:
`$empleados = Usuario::where('tipoPersona', 'E')->get();`
Esto provocaba que en el menú desplegable solo aparecieran las personas que *ya eran* empleados. Si se registraba un nuevo usuario (quien por defecto es Cliente) y querías asignarle un trabajo en la ferretería, simplemente no salía en la lista.

**La solución:**
Se cambió esa consulta por `$empleados = Usuario::all();`. Ahora el administrador puede ver a todos los usuarios registrados en el sistema y asignarles un rol. Al momento de asignarlo, la nueva lógica implementada en el punto anterior se encargará automáticamente de convertir al usuario en Empleado.

## Resumen de Integridad
*   **Base de datos intacta:** No se alteraron las migraciones, ni la estructura de las tablas, ni los modelos subyacentes. Todo el trabajo se limitó a corregir la lógica del controlador.
*   **Manejo seguro de Bajas:** La función `darDeBaja()` funcionaba bien para desactivar usuarios, pero las correcciones ahora permiten que un ciclo de vida completo funcione sin errores: `Contratar -> Dar de Baja -> Volver a Contratar`.
