<?php

namespace App\Http\Controllers;

use App\Models\RegistroProducto;
use Illuminate\Http\Request;

class RegistroProductoController extends Controller
{
    public function index()
    {
        // Obtener los registros de la tabla `registroproducto` donde `observacion` no es NULL
        $registroProductos = RegistroProducto::whereNotNull('observacion')->get();

        // Pasar los datos a la vista
        return view('registroproductos.index', compact('registroProductos'));
    }
}
