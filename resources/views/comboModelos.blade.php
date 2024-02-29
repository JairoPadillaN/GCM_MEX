<option value="">Seleccione un modelo</option>
<option value='Todos'>Todos</option>
 @foreach($consulta as $s)
        <option value='{{$s->idEquipos}}'>{{$s->modelo}}</option>
 @endforeach

