<select name="idTipoRefacciones" id="idTipoRefacciones" class="form-control">
    <option value="">Seleccione tipo de refacción</option>
    @foreach($consultaTipoRef as $consulta)
    <option value="{{$consulta->idTipoRefacciones}}">{{$consulta->nombreTipoRefaccion}}</option>
    @endforeach
</select>