<?php
namespace App\Services;

use App\Repositories\AlmacenRepositoryInterface;
use App\Repositories\CalculadoraRepositoryInterface;
use App\Repositories\CaracteristicasGrupoRepositoryInterface;
use App\Repositories\CaracteristicasRepositoryInterface;
use App\Repositories\CaracteristicasSugerenciasRepositoryInterface;
use App\Repositories\CategoriaProductoRepositoryInterface;
use App\Repositories\ComisionPlataformaRepositoryInterface;
use App\Repositories\ComisionRepositoryInterface;
use App\Repositories\CuentasTransferenciaRepositoryInterface;
use App\Repositories\EmpresaRepositoryInterface;
use App\Repositories\GrupoProductoRepositoryInterface;
use App\Repositories\MarcaProductoRepositoryInterface;
use App\Repositories\PlataformaRepositoryInterface;
use App\Repositories\ProveedorRepositoryInterface;
use App\Repositories\RangoPrecioRepositoryInterface;
use App\Repositories\TipoProductoRepositoryInterface;
use Exception;

class ConfiguracionService implements ConfiguracionServiceInterface
{
    protected $categoriaRepository;
    protected $rangoRepository;
    protected $empresaRepository;
    protected $calculadoraRepository;
    protected $comisionRepository;
    protected $caracteristicasRepository;
    protected $caracteristicasGrupoRepository;
    protected $almacenRepository;
    protected $proveedorRepository;
    protected $marcaRepository;
    protected $headerService;
    protected $cuentasTransferenciaRepository;
    protected $plataformasRepository;
    protected $comisionPlataformaRepository;
    protected $grupoRepository;
    protected $tipoProductoRepository;
    protected $sugerenciaRepository;

    private $pathMarca = '/home3/unikstor/public_html/images/marcas';
    private $pathGrupo = '/home3/unikstor/public_html/images/grupos';

    public function __construct(CategoriaProductoRepositoryInterface $categoriaRepository,
                                RangoPrecioRepositoryInterface $rangoRepository,
                                EmpresaRepositoryInterface $empresaRepository,
                                CalculadoraRepositoryInterface $calculadoraRepository,
                                ComisionRepositoryInterface $comisionRepository,
                                CaracteristicasRepositoryInterface $caracteristicasRepository,
                                CaracteristicasGrupoRepositoryInterface $caracteristicasGrupoRepository,
                                AlmacenRepositoryInterface $almacenRepository,
                                ProveedorRepositoryInterface $proveedorRepository,
                                MarcaProductoRepositoryInterface $marcaRepository,
                                HeaderServiceInterface $headerService,
                                CuentasTransferenciaRepositoryInterface $cuentasTransferenciaRepository,
                                PlataformaRepositoryInterface $plataformasRepository,
                                ComisionPlataformaRepositoryInterface $comisionPlataformaRepository,
                                GrupoProductoRepositoryInterface $grupoRepository,
                                TipoProductoRepositoryInterface $tipoProductoRepository,
                                CaracteristicasSugerenciasRepositoryInterface $sugerenciaRepository)
    {
        $this->categoriaRepository = $categoriaRepository;
        $this->rangoRepository = $rangoRepository;
        $this->empresaRepository = $empresaRepository;
        $this->calculadoraRepository = $calculadoraRepository;
        $this->comisionRepository = $comisionRepository;
        $this->caracteristicasRepository = $caracteristicasRepository;
        $this->caracteristicasGrupoRepository = $caracteristicasGrupoRepository;
        $this->almacenRepository = $almacenRepository;
        $this->proveedorRepository = $proveedorRepository;
        $this->marcaRepository = $marcaRepository;
        $this->headerService = $headerService;
        $this->cuentasTransferenciaRepository = $cuentasTransferenciaRepository;
        $this->plataformasRepository = $plataformasRepository;
        $this->comisionPlataformaRepository = $comisionPlataformaRepository;
        $this->grupoRepository = $grupoRepository;
        $this->tipoProductoRepository = $tipoProductoRepository;
        $this->sugerenciaRepository = $sugerenciaRepository;
    }

    public function getOneCaracteristica($idCaracteristica){
        return $this->caracteristicasRepository->getOne('idCaracteristica',$idCaracteristica);
    }

    public function getOneCategoria($idCategoria){
        return $this->categoriaRepository->getOne('idCategoria',$idCategoria);
    }

    public function getAllPlataformas(){
        return $this->plataformasRepository->all()->reject(function($plataforma){
            return $plataforma->tipoPlataforma == 'RED SOCIAL';
        });
    }

    public function getAllAlmacenes(){
        return $this->almacenRepository->all();
    }

    public function getAllProveedores(){
        return $this->proveedorRepository->all();
    }

    public function getAllCategorias(){
        return $this->categoriaRepository->all();
    }

    public function getAllMarcas(){
        return $this->marcaRepository->all();
    }

    public function getAllRangos(){
        return $this->rangoRepository->all();
    }

    public function getAllEmpresas(){
        return $this->empresaRepository->all();
    }

    public function getAllTipoProductos(){
        return $this->tipoProductoRepository->all();
    }

    public function updateCorreoEmpresa($id,$correo){
        if($id && $correo){
            $data = ['correoEmpresa' => $correo];
            $this->empresaRepository->update($id,$data);
        }
    }

    public function updateCuentaBancaria($id,$titular,$cuenta){
        if($id && $titular && $cuenta){
            $data = ['titular' => $titular,
                    'numeroCuenta' => $cuenta];
            $this->cuentasTransferenciaRepository->update($id,$data);
        }else{
            $this->headerService->sendFlashAlerts('Faltan Datos','Faltan datos para completar las transaccion','warning','btn-warning');
        }
    }

    public function updateComisionEmpresa($id,$comision){
        if($id && !is_null($comision)){
            $data = ['comision' => $comision];
            $this->empresaRepository->update($id,$data);
        }
    }

    public function updateCalculadora($igv,$fact){
        if($igv && $fact){
            $data = ['igv' => $igv,
                    'facturacion' => $fact];
            $this->calculadoraRepository->update($data);
        }
    }

    public function updateComisionValue($idGrupo,$idRango,$comision){
        if(!is_null($idGrupo) && !is_null($idRango) && !is_null($comision)){
            $data = ['comision' => $comision];
            $this->comisionRepository->update($idRango,$idGrupo,$data);
        }
    }

    public function getAllEspecificaciones(){
        return $this->caracteristicasRepository->all()->sortBy('especificacion');
    }

    public function insertCaracteristicaXGrupo($idGrupo,$idCaracteristica){
        if($idGrupo && $idCaracteristica){
            $modelo = $this->caracteristicasGrupoRepository->getOne($idGrupo,$idCaracteristica);
            if(!$modelo){
                $data = ['idGrupoProducto' => $idGrupo,
                'idCaracteristica' => $idCaracteristica];
                return $this->caracteristicasGrupoRepository->create($data);
            }
            
        }

        return null;
    }

    public function deleteCaracteristicaXGrupo($idGrupo,$idCaracteristica){
        if($idGrupo && $idCaracteristica){
            $this->caracteristicasGrupoRepository->remove($idGrupo,$idCaracteristica);
        }
    }

    public function createCaracteristica($descripcion){
        if($descripcion){
            $modelo = $this->caracteristicasRepository->getOne('especificacion',$descripcion);
            if(!$modelo){
                $data = ['idCaracteristica' => $this->getNewIdCaracteristica(),
                        'especificacion' => $descripcion,
                        'tipo' => 'DETALLE'];
                $this->caracteristicasRepository->create($data);
            }
        }
    }

    public function removeCaracteristica($idCaracteristica){
        if($idCaracteristica){
            $caracteristicaModel = $this->caracteristicasRepository->getOne('idCaracteristica',$idCaracteristica)->Caracteristicas_Grupo;
            if(count($caracteristicaModel) < 1){
                $this->caracteristicasRepository->remove($idCaracteristica);
            }
        }
    }

    public function updateOrCreateCaracteristica($id,$tipo,$updates,$creates){
        if(isset($tipo)){
            $arrayCaracteristica = ['tipo' => $tipo];
            $this->caracteristicasRepository->update($id,$arrayCaracteristica);
        }

        if(isset($updates)){
            foreach($updates as $idSugerencia => $sugerencia){
                $arraySugerencia = ['sugerencia' => $sugerencia];
                $this->sugerenciaRepository->update($idSugerencia,$arraySugerencia);
            }
        }

        if(isset($creates)){
            foreach($creates as $create){
                $arrayNewSugerencia = ['idSugerencia' => $this->getNewIdSugerencia(),
                                        'idCaracteristica' => $id,
                                        'sugerencia' => $create];
                $this->sugerenciaRepository->create($arrayNewSugerencia);
            }
        }
    }

    public function createAlmacen($desc){
        if($desc){
            $almacenValidate = $this->almacenRepository->getOne('descripcion',$desc);
            if(!$almacenValidate){
                $data = ['idAlmacen' => $this->getNewIdAlmacen(),
                        'descripcion' => $desc];
                $this->almacenRepository->create($data);
            }else{
                $this->headerService->sendFlashAlerts('Error','Almacen repetido','error','btn-danger');
            }
        }
    }

    public function createProveedor($razonSocial,$nombreComercial,$ruc){
        if($razonSocial && $nombreComercial && $ruc){
            $validateProveedor = $this->proveedorRepository->getOne('rucProveedor',$ruc);
            if(!$validateProveedor){
                $data = ['idProveedor' => $this->getNewIdProveedor(),
                        'nombreProveedor' => $nombreComercial,
                        'razSocialProveedor' => $razonSocial,
                        'rucProveedor' => $ruc];
                $this->proveedorRepository->create($data);
            }else{
                $this->headerService->sendFlashAlerts('Error','Proveedor repetido','error','btn-danger');
            }
        }
    }

    public function createComisionPlataforma($idPlataforma,$comision,$flete){
        $data = ['idComisionPlataforma' => $this->getNewIdComisionPlataforma(),
                'idPlataforma' => $idPlataforma,
                'comision' => $comision,
                'flete' => $flete];
        $this->comisionPlataformaRepository->create($data);
    }

    public function deleteComisionPlataforma($idComisionPlataforma){
        $this->comisionPlataformaRepository->delete($idComisionPlataforma);
    }

    public function createMarcaProducto($nombre,$img){
        $imgService = new ImageService();
        $data = ['idMarca' => $this->getNewIdMarca(),
                'nombreMarca' => $nombre,
                'imagenMarca' => ''
                ];
        $this->marcaRepository->create($data);
        $newMarca = $this->marcaRepository->getOne('idMarca',$data['idMarca']);
        $updateData = ['imagenMarca' => 'marcas/IMGPRO'.$newMarca->slugMarca.'.webp' ];
        try{
            $imgService->createImage($img,$newMarca->slugMarca,$this->pathMarca);
        }catch(Exception $e){
            throw new \InvalidArgumentException("Error al crear Imagen de marca");
        }
        $this->marcaRepository->update($newMarca->idMarca,$updateData);
    }

    public function createGrupoProducto($categoria,$grupo,$tipo,$img){
        $imgService = new ImageService();
        $rangos = $this->rangoRepository->all();
        $data = ['idGrupoProducto' => $this->getNewIdGrupo(),
                'nombreGrupo' => $grupo,
                'idCategoria' => $categoria,
                'idTipoProducto' => $tipo,
                'imagenGrupo' => ''
                ];
        $this->grupoRepository->create($data);
        foreach($rangos as $rango){
            $comisionData = ['idGrupoProducto' => $data['idGrupoProducto'],
                            'idRango' => $rango->idRango,
                            'comision' => 0];
            $this->comisionRepository->create($comisionData);
        }
        $newGrupo = $this->grupoRepository->getOne('idGrupoProducto',$data['idGrupoProducto']);
        $updateData = ['imagenGrupo' => 'grupos/IMGPRO'.$newGrupo->slugGrupo.'.webp' ];
        try{
            $imgService->createImage($img,$newGrupo->slugGrupo,$this->pathGrupo);
        }catch(Exception $e){
            throw new \InvalidArgumentException("Error al crear Imagen de grupo");
        }
        $this->grupoRepository->update($newGrupo->idGrupoProducto,$updateData);
    }

    public function removeSugerencia($idSugerencia){
        $this->sugerenciaRepository->remove($idSugerencia);
    }

    private function getNewIdMarca(){
        $marca = $this->marcaRepository->getLast();
        $id = $marca ? $marca->idMarca : 0;
        return $id + 1;
    }

    private function getNewIdGrupo(){
        $grupo = $this->grupoRepository->getLast();
        $id = $grupo ? $grupo->idGrupoProducto : 0;
        return $id + 1;
    }

    private function getNewIdComisionPlataforma(){
        $comision = $this->comisionPlataformaRepository->getLast();
        $id = $comision ? $comision->idComisionPlataforma : 0;
        return $id + 1;
    }

    private function getNewIdProveedor(){
        $proveedor = $this->proveedorRepository->getLast();
        $id = $proveedor ? $proveedor->idProveedor : 0;
        return $id + 1;
    }

    private function getNewIdCaracteristica(){
        $caracteristica = $this->caracteristicasRepository->getLast();
        $id = $caracteristica ? $caracteristica->idCaracteristica : 0;
        return $id + 1;
    }

    private function getNewIdAlmacen(){
        $almacen = $this->almacenRepository->getLast();
        $id = $almacen ? $almacen->idAlmacen : 0;
        return $id + 1;
    }

    private function getNewIdSugerencia(){
        $sugerencia = $this->sugerenciaRepository->getLast();
        $id = $sugerencia ? $sugerencia->idSugerencia : 0;
        return $id + 1;
    }
}