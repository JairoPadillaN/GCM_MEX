<option value="">Seleccionar un subtipo</option>
 @foreach($consulta as $sub)
    @if($sub->activo=="si")
       <option value='{{$sub->idSubtipoEquipo}}'>{{$sub->subtipoEquipo}}</option>
    @endif
 @endforeach
