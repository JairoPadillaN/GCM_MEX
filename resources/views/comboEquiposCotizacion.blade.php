<option value="">Seleccione un equipo</option>
 @foreach($comboEquipos as $cE)
     @if($cE->activo=="Si")
        <option value='{{$cE->idEquipos}}'>{{$cE->GCMid}} - {{$cE->complementoGCMid}}</option>
    @endif
 @endforeach