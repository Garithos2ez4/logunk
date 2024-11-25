<div class="lista_producto" >
    <div class="text-end" style="position:fixed;top:0;width:200px;z-index:1000">
            <div class="alert alert-info alert-dismissible fade show" role="alert" id="myAlert" style="display: none;">
              ¡Texto copiado!
            </div>
        </div>
    <div class="row">
        <div class="col-6 col-md-3 pt-1">
            <select onchange="viewProductsList(this.value)" class="form-select form-select-sm" id="select-state-product">
              <option value="TODOS" selected>Todos</option>
              <option value="DISPONIBLE">DISPONIBLE(S)</option>
              <option value="AGOTADO">AGOTADO(S)</option>
              <option value="EXCLUSIVO">EXCLUSIVO(S)</option>
              <option value="OFERTA">OFERTA(S)</option>
              <option value="DESCONTINUADO">DESCONTINUADO(S)</option>
            </select>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12" style="overflow-x: hidden;overflow-y:auto;height: 50vh">
                <ul class="list-group ">
                        <li class="list-group-item d-flex bg-sistema-uno text-light" style="position:sticky; top: 0;z-index:800">
                            <div class="row w-100 h-100 align-items-center" >
                                <div class="col-6 col-md-6 d-none d-md-block text-center">
                                    <h6>Producto</h6>
                                </div>
                                <div class="col-3 col-md-1 text-center">
                                    <h6><a style="cursor:pointer" onclick="changePriceList()"><i class="bi bi-caret-down-fill d-none d-md-inline"></i>Precio</a></h6>
                                </div>
                                <div class="col-6 col-md-4 text-center">
                                    <h6>Stock</h6>
                                </div>
                                <div class="col-3 col-md-1 text-center">
                                    <h6>Proveedor</h6>
                                </div>
                            </div>
                        </li>
                    @foreach($productos as $pro)
                        <li class="list-group-item justify-content-between align-items-center li-item-product-{{$pro->estadoProductoWeb}} li-item-product-all">
                            <div class="row w-100 ">
                                <div class="col-2 col-md-1 text-center" style="position:relative;cursor:pointer">
                                    <img onmouseover="mostrarImg({{ $pro->idProducto }})" onmouseout="ocultarImg({{ $pro->idProducto }})" src="{{ asset('storage/'.$pro->imagenProducto1) }}" alt="Tooltip Imagen" style="width:100%" class="rounded-3">
                                    <div class="border border-secondary rounded-3 justify-content-top" style="width: 200px;position: absolute;z-index: 900;top:0;left:100%;display:none" id="img-{{$pro->idProducto}}">
                                        <img src="{{ asset('storage/'.$pro->imagenProducto1) }}" alt="Tooltip Imagen" style="width:100%" class="rounded-3">
                                    </div>
                                </div>
                                <div class="col-10 col-md-5">
                                    <div class="row h-100">
                                        <div class="col-12" data-bs-toggle="tooltip" data-bs-placement="top" title="Cod: {{$pro->codigoProducto}}">
                                            <a class="link-sistema fw-bold"  href="{{route('producto',[encrypt($pro->idProducto)])}}">
                                                <small>{{$pro->nombreProducto}}</small>
                                            </a>
                                        </div>
                                        <div class="col-9 d-flex flex-column justify-content-end text-start pb-2">
                                            <small class="text-secondary">{{$pro->modelo}}</small>
                                        </div>
                                        <div class="col-3 d-flex flex-column justify-content-end text-end pb-2">
                                            <small class="text-secondary">{{$pro->MarcaProducto->nombreMarca}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 col-md-1 text-center">
                                    <small data-value="{{$pro->precioDolar}}" class="price-list-product">${{$pro->precioDolar}}</small>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="row text-center">
                                            @foreach($pro->Inventario as $inventario)
                                                <div class="col-6 {{$inventario->stock < $pro->stockMin ? 'text-danger' : ''}}">
                                                    <small>{{ $inventario->Almacen->descripcion }}</small>
                                                    <br>
                                                    <small>{{ $inventario->stock }}</small>
                                                </div>
                                            @endforeach
                                        
                                    </div>
                                </div>
                                <div class="col-3 col-md-1 text-center">
                                    <small>{{$pro->Inventario_Proveedor->Preveedor->nombreProveedor}}</small>
                                    <br>
                                    <small>{{$pro->Inventario_Proveedor->stock}}</small>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
    </div>
    <script src="{{ route('js.list-product-scripts',[$tc]) }}"></script>
    <script>
        
    </script>
</div>