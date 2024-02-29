<option value="">Seleccionar equipo</option>
 @foreach($consultaEquipos as $cE)
    <option value='{{$cE->idEquipos}}'>{{$cE->GCMid}} - {{$cE->complementoGCMid}}</option>
 @endforeach