<?php

namespace App\Http\Controllers;

use App\Models\RegistroProducto;
use App\Services\HeaderServiceInterface;
use App\Services\ProductoServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistroProductoController extends Controller
{
    protected $productoService;
    protected $headerService;

    public function __construct(
        HeaderServiceInterface $headerService,
        ProductoServiceInterface $productoService
    ) {
        $this->headerService = $headerService;
        $this->productoService = $productoService;
    }

    public function observaciones(Request $request)
    {
        $query = RegistroProducto::conObservaciones();

        if ($request->has('date')) {
            $fecha = Carbon::createFromFormat('Y-m', $request->input('date'));
            $query->whereMonth('fechaMovimiento', $fecha->month)
                ->whereYear('fechaMovimiento', $fecha->year);
        }

        $observaciones = $query->paginate(10);
        $user = $this->headerService->getModelUser();

        return view('observaciones', compact('observaciones', 'user'));
    }
}
