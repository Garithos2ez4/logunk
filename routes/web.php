<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\PlataformaController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\CalculadoraController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\ObservacionesController;
use App\Http\Controllers\PdfController;

//scripts
use App\Http\Controllers\ScriptController;
use App\Http\Controllers\TrasladoController;

Route::withoutMiddleware(['validate.session'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'validation'])->name('validation');
    
    //scripts
    Route::get('/js/header-scripts.js', [ScriptController::class, 'headerScript'])->name('js.header-scripts');
    Route::get('/js/calculator-scripts.js', [ScriptController::class, 'calculatorScript'])->name('js.calculator-scripts');
    Route::get('/js/documento-scripts.js/{idDocumento}', [ScriptController::class, 'documentoScript'])->name('js.documento-scripts');
    Route::get('/js/product-create-scripts.js/{tc}', [ScriptController::class, 'createProductScript'])->name('js.create-product-scripts');
    Route::get('/js/product-update-scripts.js/{tc}', [ScriptController::class, 'updateProductScript'])->name('js.update-product-scripts');
    Route::get('/js/product-list-scripts.js/{tc}', [ScriptController::class, 'listProductScript'])->name('js.list-product-scripts');
    Route::get('/js/config-calculos.js', [ScriptController::class, 'configCalculosScript'])->name('js.config-calculos.js');
});

Route::middleware(['validate.session'])->group(function () {
    
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stockmin', [HomeController::class, 'stockMinDashboard'])->name('stockmindashboard');
    Route::get('/dashboard/inventario/{estado}',[HomeController::class,'dashboardInventario'])->name('dashboardinventario');
    
    Route::get('/calculadora', [CalculadoraController::class, 'index'])->name('calculadora');
    Route::get('/calculadora/calculate', [CalculadoraController::class, 'calculate'])->name('calculadora-calculate');

    Route::get('/ingresos/searchingresos', [IngresoController::class, 'searchIngreso'])->name('searchingresos');
    Route::get('/ingresos/getoneingreso', [IngresoController::class, 'getOneIngreso'])->name('getoneingreso');
    Route::get('/ingresos/{month}', [IngresoController::class, 'index'])->name('ingresos');
    Route::post('/ingreso/deleteingreso', [IngresoController::class, 'deleteIngreso'])->name('deleteingreso');
    Route::post('/ingreso/updateregistro', [IngresoController::class, 'updateRegistro'])->name('updateregistro');
    Route::post('/ingreso/insertcomprobante', [IngresoController::class, 'insertComprobante'])->name('insertcomprobante');
    Route::post('/ingreso/insertingreso/{comprobante}', [IngresoController::class, 'insertIngreso'])->name('insertingreso');
    
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/usuario/nuevo', [UsuarioController::class, 'create'])->name('nuevousuario');
    Route::post('/usuario/createuser', [UsuarioController::class, 'createUser'])->name('createuser');
    Route::post('/usuario/updatepass', [UsuarioController::class, 'updatePass'])->name('updatepass');
    Route::post('/usuario/updatebandeja', [UsuarioController::class, 'updateBandeja'])->name('updatebandeja');
    Route::post('/usuario/updateuser', [UsuarioController::class, 'updateUser'])->name('updateuser');
    
    Route::get('/productos/buscarproducto', [ProductoController::class, 'searchProduct'])->name('buscarproducto');
    Route::get('/productos/searchmodelproduct', [ProductoController::class, 'searchModelProduct'])->name('searchmodelproduct');
    Route::get('/producto/calculate', [ProductoController::class, 'calculate'])->name('calculateproducto');
    Route::get('/productos/{cat}/{grup}', [ProductoController::class, 'index'])->name('productos');
    Route::get('/producto/nuevoproducto', [ProductoController::class, 'create'])->name('createproducto');
    Route::get('/producto/especificaciones/{idProducto}', [ProductoController::class, 'details'])->name('details');
    Route::get('/producto/{idproducto}', [ProductoController::class, 'update'])->name('producto');
    Route::post('/producto/createdetails', [ProductoController::class, 'createDetails'])->name('createdetails');
    Route::post('/producto/updateproduct/{id}', [ProductoController::class, 'updateProduct'])->name('updateproduct');
    Route::post('/producto/insertorupdatedetails', [ProductoController::class, 'insertOrUpdateDetails'])->name('insertorupdatedetails');
    Route::post('/producto/deletedetail/{idProducto}', [ProductoController::class, 'deleteDetail'])->name('deletedetail');

    Route::get('/traslado', [TrasladoController::class, 'index'])->name('traslados');
    Route::post('/traslado/updateregistroalmacen', [TrasladoController::class, 'updateRegistroAlmacen'])->name('updateregistroalmacen');
    
    Route::get('/documento/searchdocument', [DocumentoController::class, 'searchDocument'])->name('searchdocument');
    Route::get('/documento/validateseries', [DocumentoController::class, 'validateSeries'])->name('validate.series');
    Route::get('/documento/{id}/{bool}', [DocumentoController::class, 'index'])->name('documento');
    Route::get('/documentos/{date}', [DocumentoController::class, 'list'])->name('documentos');
    Route::post('/documento/deletecomprobante', [DocumentoController::class, 'deleteComprobante'])->name('deletecomprobante');
    
    Route::get('/egresos/searchregistro', [EgresoController::class, 'searchRegistro'])->name('searchregistro');
    Route::get('/egresos/searchegreso', [EgresoController::class, 'searchEgreso'])->name('searchegreso');
    Route::get('/egresos/getoneegreso', [EgresoController::class, 'getOneRegistro'])->name('getoneegreso');
    Route::get('/egresos/nuevosegresos', [EgresoController::class, 'create'])->name('createegreso');
    Route::get('/egresos/total', [EgresoController::class, 'getTotalEgresos'])->name('egresos.total');
    Route::get('/egresos/{month}', [EgresoController::class, 'index'])->name('egresos');
    Route::post('/egresos/insertegreso', [EgresoController::class, 'insertEgreso'])->name('insertegreso');
    Route::post('/egresos/devolucionegreso', [EgresoController::class, 'devolucionEgreso'])->name('devolucionegreso');

    Route::get('/garantia/creategarantia',[GarantiaController::class,'create'])->name('creategarantia');
    Route::get('/garantias/{date}',[GarantiaController::class,'index'])->name('garantias');
    Route::post('/garantia/insertgarantia',[GarantiaController::class,'insertGarantia'])->name('insertgarantia');
    
    Route::get('/plataformas', [PlataformaController::class, 'index'])->name('plataformas');
    Route::post('/plataforma/updatecuenta', [PlataformaController::class, 'updateCuentas'])->name('updatecuenta');
    Route::post('/plataforma/createcuenta', [PlataformaController::class, 'createCuenta'])->name('createcuenta');
    
    Route::get('/web', [PublicidadController::class, 'index'])->name('publicidad');
    Route::get('/web/empresa/{idEmpresa}', [PublicidadController::class, 'empresa'])->name('empresa-publicidad');
    Route::post('/web/updatepublicacion', [PublicidadController::class, 'updatePublicaion'])->name('updatepublicacion');
    
    Route::get('/registro-publicaciones/{date}', [PublicacionController::class, 'index'])->name('publicaciones');
    Route::get('/crear-publicacion/{idPlataforma}', [PublicacionController::class, 'create'])->name('createpublicacion');
    Route::post('/insert-publicacion', [PublicacionController::class, 'insertPublicacion'])->name('insertpublicacion');
    Route::post('/update-estado-publicacion', [PublicacionController::class, 'updateEstado'])->name('update-estado-publicacion');
    Route::get('/searchpublicacion', [PublicacionController::class, 'searchPublicacion'])->name('searchpublicacion');

    Route::get('/observaciones', [ObservacionesController::class, 'index'])->name('observaciones');

    Route::get('/clientes',[ClienteController::class,'index'])->name('clientes');
    Route::get('/cliente/searchcliente',[ClienteController::class,'searchCliente'])->name('searchcliente');
    Route::post('/cliente/create',[ClienteController::class,'createCliente'])->name('createcliente');
    
    //Configuracion-WEB
    Route::get('/configuracion/web', [ConfiguracionController::class, 'web'])->name('configweb');
    Route::post('/configuracion/updatecorreos', [ConfiguracionController::class, 'updateCorreos'])->name('updatecorreos');
    Route::post('/configuracion/updatecuentasbancarias', [ConfiguracionController::class, 'updateCuentasBancarias'])->name('updatecuentasbancarias');

    //Configuracion-CALCULOS
    Route::get('/configuracion/calculos', [ConfiguracionController::class, 'calculos'])->name('configcalculos');
    Route::post('/configuracion/updatecalculos', [ConfiguracionController::class, 'updateCalculos'])->name('updatecalculos');
    Route::post('/configuracion/updatecomision', [ConfiguracionController::class, 'updateComision'])->name('updatecomision');
    Route::post('/configuracion/createcomisionplataforma', [ConfiguracionController::class, 'createComisionPlataforma'])->name('createcomisionplataforma');
    Route::post('/configuracion/deletecomisionplataforma', [ConfiguracionController::class, 'deleteComisionPlataforma'])->name('deletecomisionplataforma');

    //Configuracion-PRODUCTOS
    Route::get('/configuracion/productos', [ConfiguracionController::class, 'productos'])->name('configproductos');
    Route::post('/configuracion/insertmarca', [ConfiguracionController::class, 'createMarcaProducto'])->name('insertmarca');
    Route::post('/configuracion/insertgrupo', [ConfiguracionController::class, 'createGrupoProducto'])->name('insertgrupo');

    //Configuracion-ESPECIFICACIONES
    Route::get('/configuracion/especificacionesxgeneral', [ConfiguracionController::class, 'especificacionesGeneral'])->name('configespecificacionesgeneral');
    Route::get('/configuracion/especificaciones/{idCategoria}', [ConfiguracionController::class, 'especificaciones'])->name('configespecificaciones');
    Route::get('/configuracion/especificacionesxgrupo/{idCategoria}', [ConfiguracionController::class, 'especificacionesGrupo'])->name('configespecificacionesxgrupo');
    Route::post('/configuracion/insertcaracteristicaxgrupo', [ConfiguracionController::class, 'insertCaracteristicaXGrupo'])->name('insertcaracteristicaxgrupo');
    Route::post('/configuracion/deletecaracteristicaxgrupo', [ConfiguracionController::class, 'deleteCaracteristicaXGrupo'])->name('deletecaracteristicaxgrupo');
    Route::post('/configuracion/createcaracteristica', [ConfiguracionController::class, 'createCaracteristica'])->name('createcaracteristica');
    Route::post('/configuracion/updatecaracteristica', [ConfiguracionController::class, 'updateCaracteristica'])->name('updatecaracteristica');
    Route::post('/configuracion/removesugerencia', [ConfiguracionController::class, 'removeSugerencia'])->name('removesugerencia');

    //Configuracion-INVENTARIO
    Route::get('/configuracion/inventario', [ConfiguracionController::class, 'inventario'])->name('configinventario');
    Route::post('/configuracion/createalamcen', [ConfiguracionController::class, 'createAlmacen'])->name('createalmacen');
    Route::post('/configuracion/createproveedor', [ConfiguracionController::class, 'createProveedor'])->name('createproveedor');

    Route::get('/generateSerialPdf/{idDocumento}', [PdfController::class, 'generateSerialPdf'])->name('generarSeriesPdf');
    Route::get('/pdf/serialbyproduct/{idProducto}/{idAlmacen?}', [PdfController::class, 'seriesByProductPdf'])->name('seriesXProducto');    Route::get('/reporte/stock/{idAlmacen}', [PdfController::class, 'reportStockPdf'])->name('reportealmacen');
    Route::get('/pdf/garantia/{idGarantia}', [PdfController::class, 'garantiaPdf'])->name('garantiaPdf');
});


