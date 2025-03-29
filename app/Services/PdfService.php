<?php
namespace App\Services;
use App\Models\Producto;
use App\Repositories\AlmacenRepositoryInterface;
use App\Repositories\ComprobanteRepositoryInterface;
use App\Repositories\GarantiaRepositoryInterface;
use App\Repositories\ProductoRepositoryInterface;
use App\Repositories\RegistroProductoRepositoryInterface;
use Picqer\Barcode\BarcodeGeneratorPNG;


class PdfService implements PdfServiceInterface
{
    protected $comprobanteRepository;
    protected $productoRepository;
    protected $almacenRepository;
    protected $generadorSeries;
    protected $registroRepository;
    protected $garantiaRepository;

    public function __construct(ComprobanteRepositoryInterface $comprobanteRepository,
                                ProductoRepositoryInterface $productoRepository,
                                AlmacenRepositoryInterface $almacenRepository,
                                BarcodeGeneratorPNG $generadorSeries,
                                RegistroProductoRepositoryInterface $registroRepository,
                                GarantiaRepositoryInterface $garantiaRepository)
    {
        $this->comprobanteRepository = $comprobanteRepository;
        $this->productoRepository =  $productoRepository;
        $this->almacenRepository = $almacenRepository;
        $this->generadorSeries = $generadorSeries;
        $this->registroRepository = $registroRepository;
        $this->garantiaRepository = $garantiaRepository;
    }
    public function getAlmacenById($idAlmacen) {
        return $this->almacenRepository->getOne('idAlmacen', $idAlmacen);
       }
       public function getSerialsPrint($idComprobante) {
        $registros = $this->comprobanteRepository->getAllRegistrosByComprobanteId($idComprobante);
        
       //COMENTAR ESTA PARTE DEL CODIGO SI EN ALGÚN MOMENTO QUIERES USAR LOS NUMEROS DE SERIES QUE DIGITAS EN EL CAMPO SERIAL NUMBER
        $registrosFiltrados = $registros->filter(function($register) {
            return strpos($register->numeroSerie, 'UNK-') !== false;
        });
        $series = array();
        //Aqui usar $registros , para usar los numeros de serie comentados
        foreach($registrosFiltrados as $reg) {
            
            if (strpos($reg->numeroSerie, 'UNK-') === 0) {
               
                $partes = explode('-', $reg->numeroSerie);
                $modeloSinGuiones = str_replace('-', '', $reg->modelo);
                $partes[1] = $modeloSinGuiones; 
                $numeroSerieFormateado = implode('-', $partes);
            } else {
              
                $numeroSerieFormateado = $reg->numeroSerie;
            }
    
          
            $barcode = $this->generadorSeries->getBarcode(
                $numeroSerieFormateado,
                BarcodeGeneratorPNG::TYPE_CODE_128,
                1,
                50
            );
    
            // Agregar el número de serie formateado y el código de barras al array
            $series[] = [
                'serie' => $numeroSerieFormateado, 
                'barcode' => base64_encode($barcode)
            ];
        }
    
        return $series;
    }
    public function getReportsAlmacen(){
        return $this->productoRepository->getProductsWithStock()->sortBy('codigoProducto');
    }
 

    public function getAlmacenes(){
        return $this->almacenRepository->all()->sortBy('idAlmacen');
    }
    
    public function getSerialsByProduct($idProducto, $idAlmacen = null)
    {
        $registros = $this->registroRepository->getSerialsByProduct($idProducto, $idAlmacen);
        $producto = $this->productoRepository->getOne('idProducto', $idProducto);
    
        return $registros->map(function ($registro) use ($producto) {
            if (strpos($registro->numeroSerie, 'UNK-') === 0) {
                $partes = explode('-', $registro->numeroSerie);
                $partes[1] = str_replace('-', '', $producto->codigoProducto);
                $registro->numeroSerie = implode('-', $partes);
            }
            return $registro;
        });
    }
    public function getOneProduct($idProducto){
        return $this->productoRepository->getOne('idProducto',$idProducto);
    }
    
    public function getOneGarantia($idGarantia){
        return $this->garantiaRepository->getOne($idGarantia);
    }
    public function getProductsWithStock($idAlmacen = null) {
        $query = Producto::query()
            ->whereHas('Inventario', function ($q) use ($idAlmacen) {
                $q->where('stock', '>', 0);
                if ($idAlmacen) {
                    $q->where('idAlmacen', $idAlmacen);
                }
            })
            ->with(['Inventario' => function ($q) use ($idAlmacen) {
                if ($idAlmacen) {
                    $q->where('idAlmacen', $idAlmacen);
                }
            }])
            ->orderBy('codigoProducto')
            ->get();

        return $query;
    }

}