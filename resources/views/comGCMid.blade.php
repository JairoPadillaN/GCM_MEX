<option value="">Seleccionar sucursal</option>
 @foreach($consulta as $gcm)
    @if($gcm->activo=="Si")
       <option value='{{$gcm->idSucursal}}'>{{$gcm->idSucursal</option>
    @endif
 @endforeach
