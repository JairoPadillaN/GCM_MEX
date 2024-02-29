<div id="infoFactura">
    <div class="sub-title">A que servicio se asignan las piezas</div>
    <div>
        {{Form::text('numeroFacturaProv', $consulta->numeroFactura,['class'=>'form-control', 'readonly', 'id'=>'numFactura'])}}
    </div>
    <div class="sub-title">Proveedor</div>
    <div>
        {{Form::text('proveedor',  $consulta->razonSocialProv,['class'=>'form-control', 'readonly', 'id'=>'proveedor'])}}
        {{Form::hidden('idProveedor',  $consulta->idProveedor,['class'=>'form-control', 'readonly'])}}
    </div>
    <div class="sub-title">Fecha compra</div>
    <div>
        {{Form::text('fechaCompra',  $consulta->fechaCompra,['class'=>'form-control', 'readonly', 'id'=>'fecha'])}}
    </div>
    <div class="sub-title">Cantidad disponible</div>
    <div>
        {{Form::text('cantidad', $consulta->existencia,['class'=>'form-control', 'readonly', 'id'=>'cantidad'])}}
        {{Form::hidden('total', $cantidadTotal->total,['class'=>'form-control', 'readonly', 'id'=>'total'])}}
    </div>

    @if($consultaSolicitada->tipoCotizacion!='reparacion')
        <div class="row">
            <div class="form-group col-md-6">
                <div class="sub-title">Cantidad solicitada</div>
                    <div>
                        {{Form::text('piezas', $consultaSolicitada->piezas,['class'=>'form-control', 'readonly', 'id'=>'piezasSolicitada'])}}
                    </div>
            </div>
            <div class="form-group col-md-6" id="idPendiente">
                <div class="sub-title">Cantidad pendiente</div>
                    <div>
                        {{Form::text('pendientes', $consultaSolicitada->pendientes,['class'=>'form-control', 'readonly', 'id'=>'cantidadPendiente'])}}
                    </div>
            </div>
        </div>
    @else
        <div class="sub-title">En cotización</div>
        <div>
            {{Form::text('piezas', $enCotizacion,['class'=>'form-control', 'readonly', 'id'=>'piezasSolicitada'])}}
        </div>
    @endif
    
</div>