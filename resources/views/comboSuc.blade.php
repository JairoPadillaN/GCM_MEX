<option value="">Seleccione sucursal</option>
 @foreach($sucursales as $s)
        <option value='{{$s->idSucursal}}'>{{$s->sucursal}}</option> 
 @endforeach
