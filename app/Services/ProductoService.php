<?php
namespace App\Services;

use App\Repositories\ProductoRepositoryInterface;
use App\Repositories\MarcaProductoRepositoryInterface;
use App\Repositories\GrupoProductoRepositoryInterface;
use App\Repositories\CategoriaProductoRepositoryInterface;
use App\Repositories\ProveedorRepositoryInterface;
use App\Repositories\AlmacenRepositoryInterface;
use App\Repositories\CaracteristicasProductoRepositoryInterface;
use App\Repositories\InventarioRepositoryInterface;
use App\Repositories\ProveedorInventarioRepositoryInterface;
use App\Repositories\RegistroProductoRepositoryInterface;
use Exception;

class ProductoService implements ProductoServiceInterface
{
    protected $productoRepository;
    protected $marcaRepository;
    protected $grupoRepository;
    protected $categoriaRepository;
    protected $proveedorRepository;
    protected $almacenRepository;
    protected $inventarioRepository;
    protected $proveedorInventarioRepository;
    protected $caracteristicasProductoRepository;
    protected $registroRepository;
    
    private $path = '/home3/unikstor/public_html/images/productos';

    public function __construct(ProductoRepositoryInterface $productoRepository,
                                MarcaProductoRepositoryInterface $marcaRepository,
                                GrupoProductoRepositoryInterface $grupoRepository,
                                CategoriaProductoRepositoryInterface $categoriaRepository,
                                ProveedorRepositoryInterface $proveedorRepository,
                                AlmacenRepositoryInterface $almacenRepository,
                                InventarioRepositoryInterface $inventarioRepository,
                                ProveedorInventarioRepositoryInterface $proveedorInventarioRepository,
                                CaracteristicasProductoRepositoryInterface $caracteristicasProductoRepository,
                                RegistroProductoRepositoryInterface $registroRepository)
    {
        $this->productoRepository = $productoRepository;
        $this->marcaRepository = $marcaRepository;
        $this->grupoRepository = $grupoRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->proveedorRepository = $proveedorRepository;
        $this->almacenRepository = $almacenRepository;
        $this->inventarioRepository = $inventarioRepository;
        $this->proveedorInventarioRepository = $proveedorInventarioRepository;
        $this->caracteristicasProductoRepository = $caracteristicasProductoRepository;
        $this->registroRepository = $registroRepository;
    }
    
    public function getAllAlmacen(){
        $almacenes = $this->almacenRepository->all();
        return $almacenes;
    }
    
    public function getAllLabelGrupo(){
        $grupoModel = $this->grupoRepository->all()->sortBy('nombreGrupo');
        $grupo = $grupoModel->map(function($group){
            return [
                    'idGrupoProducto' => $group->idGrupoProducto,
                    'idCategoria' => $group->idCategoria,
                    'nombreGrupo' => $group->nombreGrupo
                ];
        }); 
        return $grupo; 
    }
    
    public function getAllLabelProveedor(){
        $proveedorModel = $this->proveedorRepository->all();
        $proveedor = $proveedorModel->sortBy('nombreProveedor')->map(function($prov){
            return [
                    'nombreProveedor' => $prov->nombreProveedor,
                    'idProveedor' => $prov->idProveedor
                ];
        });
        return $proveedor;
    }
    
    public function getAllLabelMarca(){
        $marcasModel = $this->marcaRepository->all();
        $marcas = $marcasModel->sortBy('nombreMarca')->map(function($mark){
            return [
                    'nombreMarca' => $mark->nombreMarca,
                    'idMarca' => $mark->idMarca
                ];
        }); 
        return $marcas;
    }
    
    public function getAllLabelCategory(){
        $categoriaModel = $this->categoriaRepository->all();
        $categoria = $categoriaModel->map(function ($cat) {
        return [
                    'idCategoria' => $cat->idCategoria,
                    'nombreCategoria' => $cat->nombreCategoria,
                    'GrupoProducto' => $cat->GrupoProducto
                ];
            });
        return $categoria;
    }
    
    public function getOneLabelCategory($idCategory){
        $categoria = $this->categoriaRepository->getOne('idCategoria',$idCategory)->only(['idCategoria', 'nombreCategoria']);
        return $categoria;
    }
    
    public function getAllLabelGrupoXCategory($idCategory){
        $grupoModel = $this->grupoRepository->getAllByColumn('idCategoria',$idCategory);
        $grupos = $grupoModel->map(function ($group) {
        return [
                    'idGrupoProducto' => $group->idGrupoProducto,
                    'idCategoria' => $group->idCategoria,
                    'nombreGrupo' => $group->nombreGrupo
                ];
            });
        return $grupos;
    }
    
    public function getOneLabelGrupo($idGrupo){
        $grupo = $this->grupoRepository->getOne('idGrupoProducto',$idGrupo)->only(['nombreGrupo', 'idGrupoProducto', 'idCategoria']);
        return $grupo;
    }
    
    public function getAllProductsByColumn($column,$data,$cant,$querys){
        $productos = $this->productoRepository->paginateAllByColumn($column,$data,$cant,$querys);
        return $productos;
    }
    
    public function getOneProductByColumn($column,$data){
        $producto = $this->productoRepository->getOne($column,$data);
        return $producto;
    }
    
    public function getLastCodesProducts(){
        $codes = $this->productoRepository->getCodes();
        return $codes;
    }
    
    public function searchAjaxProducts($column,$query){
        $productos = $this->productoRepository->searchTakeList($column,$query,5);
        return $productos;
    }
    
    public function searchProducts($input,$cont,$filtros){
        $productos = $this->productoRepository->getPaginationNull();    
        $marca = $this->marcaRepository->searchOne('nombreMarca',$input);
        
        if ($marca) {
            $productos = $this->productoRepository->paginateAllByColumn('idMarca',$marca->idMarca,$cont,$filtros);
        }
        
        if($productos->isEmpty()){
            $productos = $this->productoRepository->searchIntensiveProducts($input,$cont,$filtros);
        }
        if($productos->isEmpty()){
            $productos = $this->productoRepository->searchPaginateList('nombreProducto',$cont,$input);
        }
        
        return $productos;
    }
    
    public function insertProduct($array,$proveedor,$img1,$img2,$img3,$img4){
        $imgService = new ImageService();
        $array['idProducto'] = $this->generarId();
        $array['imagenProducto1'] = 'productos/IMGPRO'.$array['idProducto'].'_1.webp';
        $array['imagenProducto2'] = 'productos/IMGPRO'.$array['idProducto'].'_2.webp';
        $array['imagenProducto3'] = 'productos/IMGPRO'.$array['idProducto'].'_3.webp';
        $array['imagenProducto4'] = 'productos/IMGPRO'.$array['idProducto'].'_4.webp';
        $array['codigoProducto'] = $this->generarCodigo($array['codigoProducto']);
        
        
        if($proveedor['stock'] > 0){
            $array['estadoProductoWeb'] = $array['estadoProductoWeb'];
        }else{
            if($array['estadoProductoWeb'] == 'DESCONTINUADO'){
                $array['estadoProductoWeb'] = $array['estadoProductoWeb'];
            }else{
                $array['estadoProductoWeb'] = 'AGOTADO';
            }
        }
        try{
            $newProducto = $this->productoRepository->create($array);
            
            if($newProducto){
                $this->createSeguimiento($array['idProducto'],$proveedor);
                $this->insertInventory($array['idProducto']);
                $imgService->createImage($img1,$array['idProducto'].'_1',$this->path);
                $imgService->createImage($img2,$array['idProducto'].'_2',$this->path);
                $imgService->createImage($img3,$array['idProducto'].'_3',$this->path);
                $imgService->createImage($img4,$array['idProducto'].'_4',$this->path);
                
            }
            
            return $array['idProducto'];
            
        } catch (Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }
    
    public function updateProduct($id,$array,$img1,$img2,$img3,$img4){
        $imgService = new ImageService();
        
        if(!$id){
            return null;
        }
        
        try{
            $producto = $this->productoRepository->update($id,$array);
            
            if($producto){
                if(!is_null($img1) || !is_null($img2) || !is_null($img3) || !is_null($img4)){
                    $this->imageVersions($id);
                }
                is_null($img1) ? '' : $imgService->createImage($img1,$producto->idProducto.'_1',$this->path);
                is_null($img2) ? '' : $imgService->createImage($img2,$producto->idProducto.'_2',$this->path);
                is_null($img3) ? '' : $imgService->createImage($img3,$producto->idProducto.'_3',$this->path);
                is_null($img4) ? '' : $imgService->createImage($img4,$producto->idProducto.'_4',$this->path);
            }
            
            return $producto;
        } catch (Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }
    
    public function insertOrUpdateCaracteristicas($id,$arrayCreate,$arrayUpdate){
        if(!empty($arrayCreate)){
            foreach($arrayCreate as $spec =>  $desc){
                if(!empty($spec) && !empty($desc)){
                    $this->caracteristicasProductoRepository->insertCaracteristica($spec,$id,$desc);
                }
            }
        }
        if(!empty($arrayUpdate)){
            foreach($arrayUpdate as $spec =>  $desc){
                if(!empty($spec) && !empty($desc)){
                    $this->caracteristicasProductoRepository->updateCaracteristica($spec,$id,$desc);
                }
            }
        }
    }

    public function deleteCaracteristicaXProduct($idProducto,$idCaracteristica){
        $this->caracteristicasProductoRepository->deleteSpect($idProducto,$idCaracteristica);
    }
    
    public function validateState($id){
        $producto = $this->productoRepository->getOne('idProducto',$id);
        if($producto){
            $agotado = true;
            $proveedor = $producto->Inventario_Proveedor->stock;
            foreach($producto->Inventario as $inventario){
                if($inventario->stock > 0){
                    $agotado = false;
                }
            }
            if($producto->estadoProductoWeb != 'DESCONTINUADO'){
                if($agotado && $proveedor < 1){
                    $array = array();
                    $array['estadoProductoWeb'] = 'AGOTADO';
                    
                    $this->productoRepository->update($producto->idProducto,$array);
                }
            }
        }
    }
    
    public function validateDuplicySerial($id, $serial) {
        return $this->productoRepository->validateSerial($id, $serial);
    }
    
    public function updateInventory($idProduct,$array){
        if (!$idProduct) {
            return null;
        }
    
        try {
            $this->inventarioRepository->update($idProduct,$array);
    
            return true;
        } catch (Exception $e) {
            // Manejo de excepciones
            throw new \InvalidArgumentException($e->getMessage());
        }
        
    }
    
    public function updateSeguimiento($idProducto,$array){
        if(!is_null($idProducto) && !is_null($array)){
            $this->proveedorInventarioRepository->update($idProducto,$array);
        }
    }
    
    public function filtroMarcas($column,$data){
        return $this->productoRepository->getMarcasByColumn($column,$data);
    }

    public function filtroEstados($column,$data){
        return $this->productoRepository->getEstadosByColumn($column,$data);
    }

    private function insertInventory($idProducto){
        if (!$idProducto) {
            return null;
        }
    
        try {
            $almacenes = $this->almacenRepository->all();
            foreach($almacenes as $almacen){
                $data = [
                    'idProducto' => $idProducto,
                    'idAlmacen' => $almacen->idAlmacen,
                    'stock' => 0
                ];

                $this->inventarioRepository->create($data);
            }
    
            return true;
        } catch (Exception $e) {
            // Manejo de excepciones
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    private function imageVersions($id){
        if($id){
            $producto = $this->productoRepository->getOne('idProducto',$id);
            $data = ['imagenProducto1' => $this->generateImageVersion($producto->imagenProducto1),
                    'imagenProducto2' => $this->generateImageVersion($producto->imagenProducto2),
                    'imagenProducto3' => $this->generateImageVersion($producto->imagenProducto3),
                    'imagenProducto4' => $this->generateImageVersion($producto->imagenProducto4)
                    ];
            $this->productoRepository->update($id,$data);
        }
    }

    private function generateImageVersion($path){
        if($path){
            $pos = strpos($path, '?v=');
            if(!$pos){
                return $path . '?v=1';
            }else{
                $version =  (int) substr($path, $pos + 3);
                $namepath = substr($path,0, -1);
                return $namepath . ($version + 1);
            }
        }
        return $path;
    }
    
    private function createSeguimiento($idProducto,$array){
        if (!$idProducto) {
            return null;
        }
        
        try {
            $data = array();
            $data['idInventarioProveedor'] = (int)$this->generateIdSeguimiento();
            $data['idProducto'] = (int)$idProducto;
            $data['idProveedor'] = (int)$array['idProveedor'];
            $data['stock'] = (int)$array['stock'];
            $data['estado'] = 'VIGENTE';
            
            $this->proveedorInventarioRepository->create($data);
            return true;
        } catch (Exception $e) {
            // Manejo de excepciones
            throw new \InvalidArgumentException($e->getMessage());
        }
    }
    
    private function generateIdSeguimiento(){
        $lastId = $this->proveedorInventarioRepository->getLast();
        $id = $lastId ? $lastId->idInventarioProveedor : 0;
        return $id + 1;
    }
    
    private function generarId(){
        $lastId = $this->productoRepository->getLast();
        $id = $lastId ? $lastId->idProducto : 0;
        return $id + 1;
    }
    
    private function generarCodigo($string)
    {
        $category = substr($string, 0, 6);
        $number = substr($string,6,10);
        $newnumber = str_pad((int)$number + 1, strlen($number), "0", STR_PAD_LEFT);
        return $category.$newnumber;
    }
}