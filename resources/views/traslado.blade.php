@extends('layouts.app')

@section('title', 'Traslados')

@section('content')

<style>
    .modal {
    display: none;
    position: fixed;
    z-index: 9999; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); 
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px;
    text-align: center;
    z-index: 10000; 
}

button {
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    font-size: 16px;
}

.btn-confirm {
    background-color: green;
    color: white;
}

.btn-cancel {
    background-color: red;
    color: white;
}
</style>

<div id="hidden-body" style="position:fixed;left:0;width:100vw;height:100vh;z-index:998;opacity:0.5;display:none"></div>

<div id="modalConfirmacion" class="modal">
    <div class="modal-content">
        <p>¿Estás seguro de que deseas proceder con la reubicación?</p>
        <button id="btn-confirmar" class="btn-confirm">Confirmar</button>
        <button id="btn-cancelar" class="btn-cancel">Cancelar</button>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-5 col-md-7">
            <h2><a href="{{route('documentos', [now()->format('Y-m')])}}" class="text-secondary"><i class="bi bi-arrow-left-circle"></i></a> Traslados</h2>
        </div>
        
        <div class="col-7 col-md-5">
            <div class="input-group" style="z-index:1000">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Serial Number..." id="search">
                <div class="input-group-text">
                    <x-scan_check :clases="'form-check-input scan-check mt-0'" :idInput="'search'"/>
                </div>
                <ul class="list-group w-100" style="position:absolute;top:100%;z-index:1000" id="suggestions">
                </ul>
            </div>
            <div class="w-100 text-end text-secondary">
                <small>scanner</small>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12 text-start">
            <div id="contador-productos" class="font-weight-bold" style="font-size:18px; color: #007bff;">Productos Agregados: 0</div>
        </div>
    </div>
    <div class="row">
        <form action="{{route('updateregistroalmacen')}}" method="post">
            @csrf
            <div class="col-12">
                <ul class="list-group" id="lista-traslado" style="visibility: hidden; overflow: auto;">
                    <li class="list-group-item bg-sistema-uno text-light">
                        <div class="row pt-1 text-center">
                            <div class="col-md-4">
                                <h6>Producto</h6>
                            </div>
                            <div class="col-md-2 d-none d-md-block">
                                <h6>Serie</h6>
                            </div>
                            <div class="col-md-1 d-none d-md-block">
                                <h6>Estado</h6>
                            </div>
                            <div class="col-md-2 d-none d-md-block">
                                <h6>Origen</h6>
                            </div>
                            <div class="col-md-2 d-none d-md-block">
                                <h6>Destino</h6>
                            </div>
                        </div>
                    </li>
                    <!-- Aquí se agregarán los productos dinámicamente -->
                </ul>
            </div>
            <br>
            
            <div class="col-md-12 text-center" id="btn-reubicar" style="display: none">
                <button type="submit" class="btn btn-success" id="btn-reubicar-submit" disabled><i class="bi bi-arrow-left-right"></i> Reubicar</button>
            </div>
        </form>
    </div>
    

    <div class="row" id="aviso-vacio">
        <div class="col-12 d-flex justify-content-center align-items-center text-secondary text-decoration-underline" style="height:60vh">
            <h4>Añade una serie para su traslado</h4>
        </div>
    </div>
</div>

<script>
    var almacenes = @json($almacenes);
</script>
<script src="{{ asset('js/traslado.js') }}"></script>
@endsection
