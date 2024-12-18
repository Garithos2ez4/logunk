@extends('layouts.app')

@section('title', 'Especificaciones | '.$producto->codigoProducto)

@section('content')
<div class="container">
    <br>
    <br>
    <div class="row ">
        <div class="col-8">
            <h2><a href="{{route('producto',[encrypt($producto->idProducto)])}}" class="text-secondary"><i class="bi bi-arrow-left-circle"></i></a> ESPECIFICACIONES <em class="text-secondary fw-normal">({{$producto->codigoProducto}})</em></h2>
        </div>
         <div class="col-4 text-end">
            <a class="btn bg-sistema-uno text-light" href="{{route('productos',[encrypt($producto->GrupoProducto->idCategoria),encrypt($producto->idGrupo)])}}">Productos <i class="bi bi-box-fill"></i></a>
        </div>
    </div>
    <br>
    <div class="row border shadow rounded-3 pt-2">
        <div class="col-12 col-md-10 text-start d-flex justify-content-between align-items-center">
            <h3>{{$producto->nombreProducto}}</h3>
        </div>
        <div class="col-3 col-md-2">
            <img src="{{ asset('storage/'.$producto->MarcaProducto->imagenMarca) }}" alt="imagen marca" style="width:100%" class="rounded-3">
        </div>
        <div class="col-9 text-md-start text-end col-md-8">
            <h5>{{$producto->estadoProductoWeb}}</h5>
        </div>
        <div class="col-12 col-md-4 text-md-end">
                <h5 data-bs-toggle="tooltip" data-bs-placement="bottom" title="Modelo">{{$grupo['nombreGrupo']}}: {{$producto->modelo}}</h4>
        </div>
        
    </div>
    <br>
    
    <br>
    <form action="{{route('insertorupdatedetails')}}"  method="POST">
        @csrf
    <input type="hidden" name="idproducto" value="{{$producto->idProducto}}">
    <div class="row" id="containerDivs">
        @foreach($producto->Caracteristicas_Producto as $car)
            @if($car->Caracteristicas->tipo != 'INVALIDO')
                <div class="input-group mb-3">
                    <span class="input-group-text update-details label-car bg-sistema-light text-light">{{$car->Caracteristicas->especificacion}}:</span>
                    @if($car->Caracteristicas->tipo == 'FILTRO')
                        <select class="form-select" name="updatecaracteristicas[{{ $car->idCaracteristica }}]" value="{{$car->caracteristicaProducto}}">
                            <option value="">-Elige un valor-</option>
                            @foreach ($car->Caracteristicas->Caracteristicas_Sugerencias->sortBy('sugerencia') as $sugerencia)
                                <option value="{{$sugerencia->sugerencia}}" {{$sugerencia->sugerencia == $car->caracteristicaProducto ? 'selected' : ''}}>{{$sugerencia->sugerencia}}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" name="updatecaracteristicas[{{ $car->idCaracteristica }}]" class="form-control" placeholder="Caracteristica" value="{{$car->caracteristicaProducto}}" aria-label="" aria-describedby="" maxlength="100">
                    @endif
                    <button class="btn btn-outline-danger" onclick="sendDataToDeleteSpect({{$car->idCaracteristica}},'{{$car->Caracteristicas->especificacion}}')" type="button" data-bs-toggle="modal" data-bs-target="#modalDeleteSpect">
                        <i class="bi bi-trash"></i>
                    </button>
            </div>
            @endif
        @endforeach
    </div>
    <div class="row" >
        <div class="col-12">
            <div class="input-group">
              <select class="form-select" id="caracteristicaSelect" aria-label="">
                <option value="none" selected>Caracteristica</option>
                @foreach($options as $car)
                    <option value="{{$car->idCaracteristica}}" data-tipo="{{$car->Caracteristicas->tipo}}" data-filtros='@json($car->Caracteristicas->Caracteristicas_Sugerencias->sortBy('sugerencia'))'>{{$car->Caracteristicas->especificacion}}</option>
                @endforeach
              </select>
              <button class="btn btn-outline-secondary" onclick="addDiv()" id="btnAddDetail" type="button">Agregar</button>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-12 text-center">
            <button class="btn btn-success" >Guardar <i class="bi bi-floppy"></i></button>
        </div>
    </div>
    </form>
    <br>
    <br>
    <form action="{{route('deletedetail',[encrypt($producto->idProducto)])}}" method="post">
        @csrf
        <div class="modal fade" id="modalDeleteSpect" tabindex="-1" aria-labelledby="deleteSpectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title " id="deleteSpectModalLabel">Borrar <span id="title-delete-spect"></span> ?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="input-delete-spect" name="idcaracteristica" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill"></i> Borrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{asset('js/createdetails.js')}}"></script>
@endsection