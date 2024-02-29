<select name="idCuentaProv" id="idCuentaProv" class="form-control">
    <option value="">Selecciona cuenta de proveedor</option>
    @foreach($consulta as $consulta)
        <option value="{{$consulta->idCuentaProv}}">{{$consulta->numeroCuentaProv}} - {{$consulta->bancoProv}} - {{$consulta->tipoMonedaProv}}</option>
    @endforeach
</select>