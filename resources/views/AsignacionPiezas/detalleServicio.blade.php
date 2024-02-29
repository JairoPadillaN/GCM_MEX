    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h2>Reporte</h2>
        </div>
        <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr style="background-color: #D5F5E3;">                                
                    <th><font SIZE=2>SKU</font></th>
                    <th><font SIZE=2>Nombre</font></th>
                    <th><font SIZE=2>Serial/Modelo</font></th>
                    <th><font SIZE=2>Cantidad</font></th>
                    <th><font SIZE=2>Unidad de medida</font></th>
                    <th><font SIZE=2>Factura Proveedor</font></th>
                    <th><font SIZE=2>Proveedor</font></th>
                    <th><font SIZE=2>Fecha Compra</font></th> 
                    <th><font SIZE=2>Facturado a Servicio</font></th> 
            
                </tr>
                </thead>
                <tbody>
                @foreach($consultaPiezas as $piezas)
                <tr>
                    <td>{{$piezas->codigoRefaccion}}</td>
                    <td>{{$piezas->nombreRefaccion}}</td>
                    <td>{{$piezas->serie}}</td>
                    <td>{{$piezas->cantidadOcupada}}</td>
                    <td>{{$piezas->presentacion}}</td>
                    <td>{{$piezas->numeroFacturaProv}}</td>
                    <td>{{$piezas->razonSocialProv}}</td>
                    <td>{{$piezas->fechaCompra}}</td>
                    <td>{{$piezas->numeroFactura}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </div>

