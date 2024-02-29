<select name='idProducto' class="form-control mi-selector" id="idProducto">
    <option value="">Seleccione un SKU</option>
    @foreach($consultaProductosOrden as $consulta)
    <option value="{{$consulta->idProducto}}">{{$consulta->codigo}} - {{$consulta->nombreRefaccion}}</option>
    @endforeach
</select>        