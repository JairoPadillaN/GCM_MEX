<select name="idOrden" id="idOrden" class="form-control"> 
    <option value="">Selecciona número de factura</option>
    @foreach($consultaCodigo as $consulta)
    <option value="{{$consulta->idOrden}}">{{$consulta->codigoOrden}}</option>
    @endforeach
</select>