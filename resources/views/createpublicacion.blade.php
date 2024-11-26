@extends('layouts.app')

@section('title', 'Nueva Publicación')

@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-8 col-md-10">
            <h2><a href="{{route('publicaciones',[now()->format('Y-m')])}}" class="text-secondary"><i class="bi bi-arrow-left-circle"></i></a> Nueva Publicaci&oacuten</h2>
        </div>
        <div class="col-4 col-md-2 text-end">
            <img src="{{ asset('storage/'.$plataforma->imagenPlataforma) }}" alt="plataforma" style="width:80%" class="rounded-2">
        </div>
    </div>
    <br>
    <form action="{{route('insertpublicacion')}}" method="POST">
        @csrf
    <div class="row border shadow rounded-3 pt-2 pb-2">
        <div class="col-md-9 mb-2">
            <label class="form-label">Titulo:</label>
            <input type="text" name="titulo" value="{{old('titulo')}}" class="form-control" aria-describedby="basic-addon1" id="titulo-public">
            <input type="hidden"  name="idplataforma" value="{{$plataforma->idPlataforma}}">
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">SKU:</label>
            <input type="text" value="{{old('sku')}}" name="sku" class="form-control" aria-describedby="basic-addon1" id="sku-public">
        </div>
        <div class="col-md-4 mb-2">
            <label class="form-label">Producto:</label>
            <input type="text" value="{{old('producto')}}" id="search" name="producto" placeholder="Buscar por modelo..." autocomplete="off" class="form-control" id="producto-public">
            <input type="hidden" value="{{old('idproducto')}}" value="0" name="idproducto" id="hidden-product">
            <ul class="list-group" id="suggestions" style="position:absolute"></ul>
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">Cuenta:</label>
            <select name="cuenta" class="form-select" id="cuenta-public">
                <option value="" {{old('cuenta') == '' ? 'selected' : ''}} >-Elige una cuenta-</option>
                @foreach($plataforma->CuentasPlataforma as $cuenta)
                    <option value="{{$cuenta->idCuentaPlataforma}}" {{old('cuenta') == $cuenta->idCuentaPlataforma ? 'selected' : '' }}>{{$cuenta->nombreCuenta}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <label class="form-label">Fecha de publicaci&oacuten:</label>
            <input type="date" name="fecha" value="{{old('fecha')}}" class="form-control" id="fecha-public">
        </div>
        <div class="col-6 col-md-2 mb-2">
            <label class="form-label">Precio:</label>
            <input type="number" value="{{old('precio')}}" name="precio" class="form-control" step="0.01" aria-describedby="basic-addon1" id="precio-public">
        </div>
    </div>
    <div class="row mt-2 mb-2">
        <div class="col-md-12 text-center">
            <button type="submit" id="btnSave" class="btn btn-success">Guardar <i class="bi bi-floppy"></i></button>
        </div>
    </div>
    </form>
</div>
<script src="{{asset('js/createpublicacion.js')}}"></script>
@endsection