@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2><i class="bi bi-gear-fill"></i> Configuraci&oacuten</h2>
        </div>
    </div>
    <br>
    <div class="col-md-12">
        <x-nav_config :pag="$pagina" />
    </div>
    <br>
    
    <div class="row border shadow rounded-3 pt-2 mb-4">
        <div class="col-md-12 border-bottom border-secondary pb-2">
            <h3>Grupos</h3>
            <label class="text-secondary">Conjunto donde se agrupan los productos.</label>
        </div>
        <div class="accordion accordion-flush" id="accordionGrupos">
            @php
                $count = 0;
            @endphp
            @foreach ($categorias as $categoria)
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne-{{$count}}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-{{$count}}" aria-expanded="false" aria-controls="flush-collapseOne">
                    <i class="{{$categoria->iconCategoria}}"></i> {{$categoria->nombreCategoria}}  
                </button>
              </h2>
              <div id="flush-collapseOne-{{$count}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-{{$count}}" data-bs-parent="#accordionGrupos">
                <div class="accordion-body">
                    <div class="row">
                        @foreach ($categoria->GrupoProducto as $grupo)
                        <div class="col-md-3 pb-2">
                            <div class="row bg-light text-center border rounded-3 ms-2 me-2">
                                <div class="col-md-8 d-flex align-items-center">
                                    <h6>{{$grupo->nombreGrupo}}</h6>
                                </div>
                                <div class="col-md-4 pt-0 pe-0">
                                    <img src="{{asset('storage/'. $grupo->imagenGrupo)}}" alt="" class="border ps-0 pe-0 w-100">
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="col-md-3 pb-2">
                            <div class="row bg-light text-center border rounded-3 ms-2 me-2 h-100">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#grupoModal" onclick="sendGrupoData('{{$categoria->idCategoria}}','{{$categoria->nombreCategoria}}')">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            @php
                $count++;
            @endphp
            @endforeach
        </div>
        
    </div>
    
    <br>
    <div class="row border shadow rounded-3 pt-2 mb-4">
        <div class="col-md-8 border-bottom border-secondary">
            <h3>Marcas</h3>
        </div>
        <div class="col-md-4 border-bottom border-secondary text-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#marcaModal"><i class="bi bi-bookmark-plus-fill"></i></button>
        </div>
        <div class="col-md-12 pt-2 mb-0 bg-list">
            <div class="row">
                @foreach ($marcas->sortBy('nombreMarca') as $marca)
                <div class="col-md-2 pb-2">
                    <div class="row bg-light text-center border rounded-3 ms-2 me-2 pt-2">
                        <h5>{{$marca->nombreMarca}}</h5>
                        <img src="{{asset('storage/'. $marca->imagenMarca)}}" alt="" class="border ps-0 pe-0">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <form action="{{route('insertgrupo')}}" method="post" enctype="multipart/form-data" id="form-insert-grupo">
        @csrf
        <div class="modal fade" id="grupoModal" tabindex="-1" aria-labelledby="grupoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row">
                            <h1 class="modal-title fs-5" id="grupoModalLabel">Nuevo Grupo</h1>
                            <small class="text-secondary" id="title-modal-grupo"></small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="body-modal-grupo">
                        <div class="row">
                            <input type="hidden" name="categoria" value="" id="hidde-modal-grupo">
                            <div class="col-md-4" id="drop-area-grupo" class="drop-area">
                                <input class="d-none" id="file-modal-grupo" name="img" type="file" accept="image/*">
                                <img src="https://placehold.co/300x300"  alt="Click to upload" id="img-modal-grupo" class="w-100 border border-secondary rounded-3" style="cursor: pointer; object-fit: cover;">
                            </div>
                            <div class="col-md-8 mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Nombre del Grupo:</label>
                                        <input type="text" maxlength="50" class="form-control" name="grupo">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Tipo de producto</label>
                                        <select name="tipo" class="form-select">
                                            <option value="" selected>-Elige-</option>
                                            @foreach ($tipos as $tipo)
                                            <option value="{{$tipo->idTipoProducto}}">{{$tipo->tipoProducto}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="validateForm('form-insert-grupo')" class="btn btn-primary" id="btn-modal-grupo"><i class="bi bi-floppy-fill"></i> Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form action="{{route('insertmarca')}}" method="post" enctype="multipart/form-data" id="form-insert-marca">
        @csrf
        <div class="modal fade" id="marcaModal" tabindex="-1" aria-labelledby="marcaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="marcaModalLabel">Nueva Marca</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-modal-marcas">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nombre de la Marca:</label>
                        <input type="text" maxlength="50" class="form-control" name="nombre" id="">
                    </div>
                    <div class="col-md-12" id="drop-area-marca" class="drop-area">
                        <input class="d-none" id="file-modal-marca" name="img" type="file" accept="image/*">
                        <img src="https://placehold.co/1000x400"  alt="Click to upload" id="img-modal-marca" class="w-100 border border-secondary rounded-3" style="cursor: pointer; object-fit: cover;">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" onclick="validateForm('form-insert-marca')" class="btn btn-primary" id="btn-modal-marca"><i class="bi bi-floppy-fill"></i> Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </form>
</div>
<script src="{{asset('js/configproductos.js')}}"></script>
@endsection