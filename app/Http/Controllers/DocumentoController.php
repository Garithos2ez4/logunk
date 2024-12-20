<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\HeaderServiceInterface;
use App\Services\ComprobanteServiceInterface;

class DocumentoController extends Controller
{
    protected $headerService;
    protected $comprobanteService;

    public function __construct(HeaderServiceInterface $headerService,
                                ComprobanteServiceInterface $comprobanteService)
    {
        $this->headerService = $headerService;
        $this->comprobanteService = $comprobanteService;
    }
    
    public function index($id,$bool){
        //variables de la cabecera
        $userModel = $this->headerService->getModelUser();
        //variables propias del controlador
        $documento = $this->comprobanteService->getOneById(decrypt($id));
        $ubicaciones = $this->comprobanteService->getAllAlmacen();
        $registros = $this->comprobanteService->getAllRegistrosByComprobanteId(decrypt($id));
        $registrosFiltrados = $registros->filter(function($register) {
            return strpos($register->numeroSerie, 'UNK-') !== false;
        });
        
        $estados = [['value' => 'NUEVO', 'name' => 'Nuevo'],
                    ['value' => 'ABIERTO', 'name' => 'Abierto'],
                    ['value' => 'DEFECTUOSO', 'name' => 'Defectuoso']
                    ];
                    
        $medidas = [['value' => 'CAJA', 'name' => 'Caja'],
                    ['value' => 'UNIDAD', 'name' => 'Unidad']];
                    
        $adquisiciones = [['value' => 'NORMAL', 'name' => 'Normal'],
                        ['value' => 'OFERTA', 'name' => 'Oferta']];
        
        if($documento->estado == "PENDIENTE"){
            $bool = true;
        }

        foreach($userModel->Accesos as $acceso){
            if($acceso->idVista == 8 && $bool){
                return view('documento',['user' => $userModel,
                'documento' => $documento,
                'ubicaciones' => $ubicaciones,
                'estados' => $estados,
                'medidas' => $medidas,
                'adquisiciones' => $adquisiciones,
                'validate' => $bool
                ]);
            }

            if($acceso->idVista == 2 && !$bool){
                return view('documento',['user' => $userModel,
                'documento' => $documento,
                'ubicaciones' => $ubicaciones,
                'estados' => $estados,
                'medidas' => $medidas,
                'adquisiciones' => $adquisiciones,
                'validate' => $bool,
                'pdf' => $registrosFiltrados
                ]);
            }
        }
        $this->headerService->sendFlashAlerts('Acceso denegado','No tienes permiso para ingresar a esta pestaña','warning','btn-danger');
        return redirect()->route('dashboard',['user' => $userModel]);
    }
    
    public function list($date,Request $request){
        //variables de la cabecera
        $userModel = $this->headerService->getModelUser();
        
        foreach($userModel->Accesos as $acceso){
            if($acceso->idVista == 2){
                $carbonMonth = Carbon::createFromFormat('Y-m-d', $date . '-01');
                $documentos = $this->comprobanteService->getByMonth($date,15,$request->query('filtro'))->appends($request->all());

                if($request->query('page') || $request->query('filtro')){
                    $view = view('components.lista_documentos', ['documentos' => $documentos,'container' => $request->query('container')])->render();
                    return response()->json(['html' => $view]);
                }

                $filtros = ['users' => $this->comprobanteService->filtroUsuario($date),
                            'proveedores' => $this->comprobanteService->filtroProveedor($date),
                            'documentos' => $this->comprobanteService->filtroDocumento($date),
                            'estados' => $this->comprobanteService->filtroEstado($date)];
                
                return view('documentos',['user' => $userModel,
                                            'documentos' => $documentos,
                                            'fecha' => $carbonMonth,
                                            'filtros' => $filtros
                                        ]);
            }
        }
        $this->headerService->sendFlashAlerts('Acceso denegado','No tienes permiso para ingresar a esta pestaña','warning','btn-danger');
        return redirect()->route('dashboard',['user' => $userModel]);
    }

    public function deleteComprobante(Request $request){
        $userModel = $this->headerService->getModelUser();
        $idComprobante = $request->input('id');
        foreach($userModel->Accesos as $acceso){
            if($acceso->idVista == 8){
                $documento = $this->comprobanteService->getOneById($idComprobante);
                $this->comprobanteService->deleteComprobante($idComprobante);
                return redirect()->route('documento', [ 'id' => encrypt($documento->idComprobante), 'bool' => 0 ]);

            }
        }
        $this->headerService->sendFlashAlerts('Acceso denegado','No tienes permiso para ingresar a esta pestaña','warning','btn-danger');
        return redirect()->route('dashboard',['user' => $userModel]);
    }
    
    public function searchDocument(Request $request){
        $query = $request->input('query');
    
        $results = $this->comprobanteService->searchAjaxComprobante('numeroComprobante',$query);
    
        return response()->json($results);
    }
    
    public function validateSeries(Request $request){
    $series = $request->query('serial', []);
    $proveedor = $request->query('proveedor');

    if (empty($series) || empty($proveedor)) {
        return response()->json(['error' => 'Faltan parámetros'], 400); 
    }

    $validSeries = $this->comprobanteService->validateSeriesAjax($proveedor, $series);

    if (count($validSeries) > 0) {
        return response()->json(['valid' => true, 'series' => $validSeries]);
    } else {
        return response()->json(['valid' => false]);
    }
    }
}