<option value="">Seleccionar sucursal</option>
 @foreach($sucursales as $pr)
 @if($pr->activo=="Si")
 
 <option value='{{$pr->idSucursal}}'>{{$pr->sucursal}}</option>

 @endif
 @endforeach