<select name='idActividad' id="idActividad" class="form-control">
<option value="">Seleccionar actividad</option>
 @foreach($consulta as $act)
    @if($act->activo=="Si")
        <option value='{{$act->idActividad}}'>{{$act->nombreActividad}}</option>
    @endif    
 @endforeach
</select>