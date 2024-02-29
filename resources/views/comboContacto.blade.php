<option value="">Seleccionar contacto</option>
    @foreach($consulta as $s)
        <option value='{{$s->nombreCont}}'>{{$s->nombreCont}}</option>
    @endforeach