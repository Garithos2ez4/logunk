<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories;
use App\Services\ProductoServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\HeaderServiceInterface;
use App\Models\RegistroProducto;

class ObservacionesController extends Controller{
    protected $productoService;
    protected $headerService;

    public function __construct(
        HeaderServiceInterface $headerService,
        ProductoServiceInterface $productoService,
        
    )
    {
     $this->headerService = $headerService; 
     $this->productoService = $productoService;
       
    }

    public function index(Request $Request)
    {
       $observacionesQuery = RegistroProducto::whereNotNull('observacion');

       if($Request->has('date')){
        $fecha = Carbon::createFromFormat('Y-m', $Request->input('date'));
        $observacionesQuery->whereMonth('fecha', $fecha->month)
                           ->whereYear('fecha',$fecha->year);

                           $observaciones=$observacionesQuery->paginate(10);
                           dd($observaciones);
                           return view('observaciones', compact('observaciones'));
       }

      
    }
}


