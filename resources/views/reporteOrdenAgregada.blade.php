    <center><h3>Reporte productos</h3></center><br>
    <div class="table-responsive">
    
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:90%" align="center">
            <thead>
                <tr style="background-color: #D6EAF8;">
                    <th class="fila">Servicio</th>
                    <th class="fila">Cantidad</th>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th class="fila">VPN</th>
                    <th>Notas</th>
                    <th class="precios">Precio unitario</th>
                    <th class="precios">Subtotal</th>
                    <th class="fila">IVA(%)</th>
                    <th class="fila">IVA($)</th>
                    <th class="fila">ISR(%)</th>
                    <th class="fila">ISR($)</th>
                    <th class="fila">Retención(%)</th>
                    <th class="fila">Retención($)</th>
                    <th class="precios">Precio Total</th>
                    @if($consulta->estatus!='Pagado' && $consulta->estatus!='Pagado con complementos de pago')
                        <th>Opciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($ordenCompra as $compra)
                <tr>
                    <td>{{$compra->idServicios}}</td>
                    <td>{{$compra->cantidadOrden}}</td>
                    <td>{{$compra->nombreRefaccion}}</td>
                    <td>{{$compra->codigo}}</td>
                    <td>{{$compra->vendorPartNumber}}</td>
                    <td>{{$compra->notasProducto}}</td>
                    <td>{{$compra->moneda}} ${{$compra->precioOrden}}</td>
                    <td>{{$compra->moneda}} ${{$compra->subtotal}}</td>
                    <td>{{$compra->ivaOrden}}%</td>
                    <td>${{$compra->ivaDinero}}</td>
                    <td>{{$compra->isrOrden}}%</td>
                    <td>${{$compra->isrDinero}}</td>
                    <td>{{$compra->retencion}}%</td>
                    <td>${{$compra->retencionDinero}}</td>
                    <td style="text-align: right;">{{$compra->moneda}} ${{$compra->precioProducto}}</td>
                    @if($consulta->estatus!='Pagado' && $consulta->estatus!='Pagado con complementos de pago')
                    <td>
                    <form action="">
                                @csrf
                            <input type="hidden" name="idProducto" value="{{$compra->idProducto}}">
                            <input type="hidden" name="idOrden" value="{{$compra->idOrden}}">
                            <input type="hidden" name="idPartes" value="{{$compra->idPartesVenta}}">  
                            <input type="hidden" name="idFacturaTabla" value="{{$compra->idFactura}}">                          
                                <button type="button" class="btn btn-sm btn-info editarProductoOrden" style='width:40px; height: 35px;'>
                                    <i class="ace-icon fa fa-pencil bigger" aria-hidden="true"></i>
                                </button>
                            </form>
                        <form action="">
                        @csrf
                        <input type="hidden" name="idProducto" value="{{$compra->idProducto}}">
                        <input type="hidden" name="moneda" value="{{$compra->moneda}}">
                        <input type="hidden" name="cambioDolar" value="{{$compra->cambioDolar}}">
                        <input type="hidden" name="precioBorrar" value="{{$compra->precioProducto}}">
                        <input type="hidden" name="precioOrdenBorrar" value="{{$compra->precioOrden}}">
                        <input type="hidden" name="ivaBorrar" value="{{$compra->ivaDinero}}">
                        <input type="hidden" name="isrBorrar" value="{{$compra->isrDinero}}">
                        <input type="hidden" name="retencionBorrar" value="{{$compra->retencionDinero}}">
                        <input type="hidden" name="idFacturaBorrar" value="{{$compra->idFactura}}">
                        <input type="hidden" name="cantidadBorrar" value="{{$compra->cantidadOrden}}">
                        <input type="hidden" name="idPartes" value="{{$compra->idPartesVenta}}">
                        <input type="hidden" name="idOrden" value="{{$compra->idOrden}}">
                        <input type="hidden" name="idRepcotizacion" value="{{$compra->idRepcotizacion}}">
                        <input type="hidden" name="idRefaccionCotizacion" value="{{$compra->idRefaccionCotizacion}}">
                            <button type="button" class="btn btn-sm btn-danger borrarProducto" style='width:40px; height: 35px;'>
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
                <tr>
                    <td colspan="12" style="text-align: right;">Importe</td>
                    <td colspan="2" style="text-align: right;"> <strong>{{$sumaTotal->moneda}} ${{$sumaTotal->importe}}</strong></td>
                </tr>
                <tr>
                    <td colspan="12" style="text-align: right;">+ ISR</td>
                    <td colspan="2" style="text-align: right;"> <strong>${{$sumaTotal->isrDineroTotal}}</strong></td>
                </tr>
                <tr>
                    <td colspan="12" style="text-align: right;">+ IVA</td>
                    <td colspan="2" style="text-align: right;"> <strong>${{$sumaTotal->ivaDineroTotal}}</strong></td>
                </tr>
                <tr>
                    <td colspan="12" style="text-align: right;">- Retención</td>
                    <td colspan="2" style="text-align: right;"> <strong>${{$sumaTotal->retencionDineroTotal}}</strong></td>
                </tr>
                <tr>
                    <td colspan="12" style="text-align: right;">Total</td>
                    <td colspan="2" style="text-align: right;"> <strong>{{$sumaTotal->moneda}} ${{$sumaTotal->total}}</strong></td>
                </tr>
            </tbody>
        </table>
        
    </div>

<script>

    $(".borrarProducto").click(function(){
        $("#reporteProductos").load('{{url('borrarProductoOrden')}}' + '?r=' + Date.now() + $(this).closest('form').serialize()); 
        limpiarDatosSku()
    });

    $(".editarProductoOrden").click(function(e) {
        $('#modalEditar').load('{{ url('editarProductoOrden') }}' + '?' + $(this).closest(
            'form').serialize(),);
            e.preventDefault();
    });
</script>

<style>
.fila{
    width: 80px;
    text-align: center;
}
.precios{
    width: 120px;
    text-align: center;
}
td{
    text-align: center;
}
</style>