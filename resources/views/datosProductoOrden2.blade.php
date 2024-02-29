
    <div class="row" id="">
        <div class="col-sm-6">
            <div class="sub-title">* Precio unitario compra(MXN):</div>
            <div>
                {{Form::text('precioLista',$consultaProductosOrden->precioCompraPesos,['class' => 'form-control', 'readonly'])}}
            </div>
            @if($consultaProductosOrden->moneda=='USD')
            <div class="sub-title">* Precio unitario compra(USD):</div>
            <div>
                {{Form::text('precioCompraDol',$consultaProductosOrden->precioCompraDolares,['class' => 'form-control', 'readonly'])}}
            </div>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="sub-title">Precio unitario venta(MXN):</div>
            <div>
                {{Form::text('precioUltimo',$consultaProductosOrden->precioVentaPe,['class' => 'form-control', 'readonly'])}}
            </div>
            @if($consultaProductosOrden->moneda=='USD')
            <div class="sub-title">Precio unitario venta(USD):</div>
            <div>

                {{Form::text('precioVentaDol',$consultaProductosOrden->precioVentaDol,['class' => 'form-control', 'readonly'])}}
            </div>
            @endif
        </div>
    </div>
    <div class="sub-title">* Cantidad:</div>
    <div>
        {{Form::text('cantidad',$consultaProductosOrden->cantidadOrden,['class' => 'form-control', 'readonly'])}}
    </div>


