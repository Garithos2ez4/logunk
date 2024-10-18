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
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DocumentoController;

//scripts
use App\Http\Controllers\ScriptController;


Route::withoutMiddleware(['validate.session'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/', [LoginController::class, 'validation'])->name('validation');
    
    //scripts
    Route::get('/js/header-scripts.js', [ScriptController::class, 'headerScript'])->name('js.header-scripts');
    Route::get('/js/calculator-scripts.js', [ScriptController::class, 'calculatorScript'])->name('js.calculator-scripts');
    Route::get('/js/documento-scripts.js', [ScriptController::class, 'documentoScript'])->name('js.documento-scripts');
    Route::get('/js/product-create-scripts.js/{tc}', [ScriptController::class, 'createProductScript'])->name('js.create-product-scripts');
    Route::get('/js/product-update-scripts.js/{tc}', [ScriptController::class, 'updateProductScript'])->name('js.update-product-scripts');
    Route::get('/js/product-list-scripts.js/{tc}', [ScriptController::class, 'listProductScript'])->name('js.list-product-scripts');
});

Route::middleware(['validate.session'])->group(function () {
    
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    Route::get('/calculadora', [CalculadoraController::class, 'index'])->name('calculadora');
    Route::get('/calculadora/calculate', [CalculadoraController::class, 'calculate'])->name('calculadora-calculate');
    
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/usuario/nuevo', [UsuarioController::class, 'create'])->name('nuevousuario');
    Route::post('/usuario/createuser', [UsuarioController::class, 'createUser'])->name('createuser');
    Route::post('/usuario/updatepass', [UsuarioController::class, 'updatePass'])->name('updatepass');
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
    
    Route::get('/ingresos/searchingresos', [IngresoController::class, 'searchIngreso'])->name('searchingresos');
    Route::get('/ingresos/{month}', [IngresoController::class, 'index'])->name('ingresos');
    Route::post('/ingreso/insertingreso/{comprobante}', [IngresoController::class, 'insertIngreso'])->name('insertingreso');
    Route::post('/ingreso/deleteingreso', [IngresoController::class, 'deleteIngreso'])->name('deleteingreso');
    Route::post('/ingreso/insertcomprobante', [IngresoController::class, 'insertComprobante'])->name('insertcomprobante');
    
    Route::get('/documento/searchdocument', [DocumentoController::class, 'searchDocument'])->name('searchdocument');
    Route::get('/documento/{id}/{bool}', [DocumentoController::class, 'index'])->name('documento');
    Route::get('/documentos/{date}', [DocumentoController::class, 'list'])->name('documentos');
    
    
    Route::get('/egresos/searchregistro', [EgresoController::class, 'searchRegistro'])->name('searchregistro');
    Route::get('/egresos/{month}', [EgresoController::class, 'index'])->name('egresos');
    Route::post('/egresos/insertegreso', [EgresoController::class, 'insertEgreso'])->name('insertegreso');
    
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
    
    Route::get('/configuracion/web', [ConfiguracionController::class, 'web'])->name('configweb');
    Route::get('/configuracion/calculos', [ConfiguracionController::class, 'calculos'])->name('configcalculos');
    Route::get('/configuracion/productos', [ConfiguracionController::class, 'productos'])->name('configproductos');
    Route::get('/configuracion/especificaciones', [ConfiguracionController::class, 'especificaciones'])->name('configespecificaciones');
    Route::get('/configuracion/inventario', [ConfiguracionController::class, 'inventario'])->name('configinventario');
    Route::post('/configuracion/createcaracteristica', [ConfiguracionController::class, 'createCaracteristica'])->name('createcaracteristica');
    Route::post('/configuracion/removecaracteristica', [ConfiguracionController::class, 'removeCaracteristica'])->name('removecaracteristica');
    Route::post('/configuracion/updatecomision', [ConfiguracionController::class, 'updateComision'])->name('updatecomision');
    Route::post('/configuracion/updatecalculos', [ConfiguracionController::class, 'updateCalculos'])->name('updatecalculos');
    Route::post('/configuracion/updatecorreos', [ConfiguracionController::class, 'updateCorreos'])->name('updatecorreos');
    Route::post('/configuracion/insertcaracteristicaxgrupo', [ConfiguracionController::class, 'insertCaracteristicaXGrupo'])->name('insertcaracteristicaxgrupo');
    Route::post('/configuracion/deletecaracteristicaxgrupo', [ConfiguracionController::class, 'deleteCaracteristicaXGrupo'])->name('deletecaracteristicaxgrupo');
  
});



