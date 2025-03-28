@extends('layouts.app')

@section('title', 'Productos | ' . $categoria['nombreCategoria'])

@section('content')

    <div class="container">
        <br>
        <div class="row">
            <div class="col-9 col-lg-4">
                <x-buscador_producto />
            </div>
            <div class="col-3 col-lg-8 text-end">
                <a href="{{ route('createproducto') }}" target="_blank" class="btn btn-success"><i class="bi bi-plus-square"></i> <span class="d-none d-md-inline"> Nuevo Producto</span></a>
            </div>
            <div class="col-6 col-md-8 pt-2">
                <h3><i class="bi bi-box-fill"></i> Productos</h3>
            </div>
            <div class="col-6 col-md-4 text-end">
                <div class="btn-group dropstart">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                        data-bs-display="static" aria-expanded="false">
                        Categorías
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        @foreach ($categorias as $cat)
                            <li><a class="dropdown-item text-dark" type="button"
                                    href="{{ route('productos', [encrypt($cat['idCategoria']), encrypt($cat['GrupoProducto'][0]->idGrupoProducto)]) }}">{{ $cat['nombreCategoria'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-7 col-md-8">
                <h3 class="">{{ $categoria['nombreCategoria'] }} <em
                        class="fw-light text-secondary">({{ $grupo['nombreGrupo'] }})</em></h3>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs d-none d-sm-none d-md-flex">
                    @foreach ($grupos as $gp)
                        @if ($gp['idGrupoProducto'] == $grupo['idGrupoProducto'])
                            <li class="nav-item">
                                <a class="nav-link active bg-sistema-dos text-light"
                                    href="#">{{ $gp['nombreGrupo'] }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link text-dark"
                                    href="{{ route('productos', [encrypt($categoria['idCategoria']), encrypt($gp['idGrupoProducto'])]) }}">{{ $gp['nombreGrupo'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <ul class="nav nav-tabs  flex-nowrap d-flex d-sm-none" style="overflow-y: hidden;overflow-x: auto;">
                    @foreach ($grupos as $gp)
                        @if ($gp['idGrupoProducto'] == $grupo['idGrupoProducto'])
                            <li class="nav-item">
                                <a class="nav-link active bg-sistema-dos text-light"
                                    href="#">{{ $gp['nombreGrupo'] }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link text-dark"
                                    href="{{ route('productos', [encrypt($categoria['idCategoria']), encrypt($gp['idGrupoProducto'])]) }}">{{ $gp['nombreGrupo'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <form action="{{ url()->current() }}" method="get" id="form-filtro-componente">
            <div class="row mb-2 mt-3">
                <div class="col-4 col-lg-2">
                    <small>Marca</small>
                    <select class="form-select form-select-sm filtro-componente" name="filtro[marca]">
                        <option value="">Todos</option>
                        @foreach ($filtros['marcas'] as $marca)
                        <option value="{{$marca->idMarca}}">{{$marca->MarcaProducto->nombreMarca}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 col-lg-2">
                    <small>Estado</small>
                    <select class="form-select form-select-sm filtro-componente" name="filtro[estado]">
                        <option value="">Todos</option>
                        @foreach ($filtros['estados'] as $estado)
                        <option value="{{$estado->estadoProductoWeb}}">{{$estado->estadoProductoWeb}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
        <div id="container-list-products">
            <x-lista_producto :productos="$productos" :tc="$tc" :container="'container-list-products'" />
        </div>
    </div>
    <script src="{{asset('js/filtro_componente.js')}}"></script>
@endsection
