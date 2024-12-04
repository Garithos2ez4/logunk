@if(!$egresos->isEmpty())
    <div class="row">
        <div class="col-12">
            <ul class="list-group" style="position: relative;overflow-x:hidden;overflow-y:auto;height:70vh">
                <li class="list-group-item bg-sistema-uno text-light" style="position:sticky;top:0;z-index:800">
                    <div class="row text-center">
                        <div class="col-3 col-md-2 text-start">
                            <small>Producto</small>
                        </div>
                        <div class="col-md-2 d-none d-lg-block">
                            <small>Nro Orden</small>
                        </div>
                        <div class="col-md-1 d-none d-lg-block">
                            <small>Usuario</small>
                        </div>
                        <div class="col-md-2 d-none d-md-block">
                            <small>SKU</small>
                        </div>
                        <div class="col-5 col-md-4 col-lg-2">
                            <small>Serial Number</small>
                        </div>
                        <div class="col-md-1 d-none d-lg-block">
                            <small>Estado</small>
                        </div>
                        <div class="col-2 col-lg-1">
                            <small>Pedido</small>
                        </div>
                        <div class="col-2 col-lg-1">
                            <small>Despacho</small>
                        </div>
                    </div>
                </li>
                @foreach ($egresos as $egreso)
                    <li class="list-group-item">
                        <div class="row text-center">
                            <div class="col-3 col-md-2 text-start">
                                <small>
                                    <a href="{{ route('producto', [encrypt($egreso->RegistroProducto->DetalleComprobante->Producto->idProducto)]) }}" class="decoration-link">
                                        {{ $egreso->RegistroProducto->DetalleComprobante->Producto->codigoProducto }}
                                    </a>
                                </small>
                            </div>
                            <div class="col-md-2 d-none d-lg-block">
                                <small>{{ is_null($egreso->numeroOrden) ? 'No aplica' : $egreso->numeroOrden }}</small>
                            </div>
                            <div class="col-md-1 d-none d-lg-block">
                                <small>{{ $egreso->Usuario->user }}</small>
                            </div>
                            <div class="col-md-2 d-none d-md-block">
                                <small>{{ is_null($egreso->Publicacion) || is_null($egreso->Publicacion->sku) ? 'No aplica' : $egreso->Publicacion->sku }}</small>
                            </div>
                            <div class="col-5 col-md-4 col-lg-2">
                                @php
                                    $egresoJson = [
                                        'idEgreso' => $egreso->idEgreso,
                                        'nombreProducto' => $egreso->RegistroProducto->DetalleComprobante->Producto->nombreProducto,
                                        'numeroSerie' => $egreso->RegistroProducto->numeroSerie,
                                        'estado' => $egreso->RegistroProducto->estado,
                                        'fechaCompra' => $egreso->fechaCompra,
                                        'fechaDespacho' => $egreso->fechaDespacho,
                                        'fechaMovimiento' => $egreso->RegistroProducto->fechaMovimiento,
                                        'usuario' => $egreso->Usuario->user,
                                        'observacion' => $egreso->RegistroProducto->observacion,
                                        'cuenta' => $egreso->Publicacion ? $egreso->Publicacion->CuentasPlataforma->nombreCuenta : null,
                                        'sku' => $egreso->Publicacion ? $egreso->Publicacion->sku : null,
                                        'numeroOrden' => $egreso->numeroOrden,
                                        'imagenPublicacion' => $egreso->Publicacion ? asset('storage/'.$egreso->Publicacion->CuentasPlataforma->Plataforma->imagenPlataforma) : null
                                    ];
                                @endphp
                                <a href="javascript:void(0)" 
                                onclick='viewModalEgreso(@json($egresoJson))' 
                                class="decoration-link">
                                    <small>{{ $egreso->RegistroProducto->numeroSerie }}</small>
                                </a>
                            </div>
                            @php
                                $state = $egreso->RegistroProducto->estado
                            @endphp
                            <div class="col-md-1 d-none d-lg-block {{$state == 'NUEVO' ? 'text-sistema-uno' : (
                                                                    $state == 'ENTREGADO' ? 'text-green' : (
                                                                    $state == 'DEVOLUCION' ? 'text-warning' : (
                                                                    $state == 'GARANTIA' ? 'text-marron' : (
                                                                    $state == 'ABIERTO' ? 'text-purple' : (
                                                                    $state == 'DEFECTUOSO' ? 'text-red' : '')))))}}">
                            <small>
                                {{ $state }}
                            </small>
                            </div>
                            <div class="col-2 col-lg-1">
                                <small>{{ $egreso->fechaCompra->format('d/m/y') }}</small>
                            </div>
                            <div class="col-2 col-lg-1">
                                <small>{{ $egreso->fechaDespacho->format('d/m/y') }}</small>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
    </div>
@else
    <div class="row align-items-center" style="height:65vh">
        <x-aviso_no_encontrado :mensaje="''" />
    </div>
@endif
