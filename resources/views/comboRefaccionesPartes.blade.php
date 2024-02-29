<option value="">Seleccione una refacción:</option>                            
 @foreach($comboRefacciones as $cr)
 @if($cr->activo=="Si")
 <option value='{{$cr->idPartesVenta}}'>{{$cr->codigo}}-{{$cr->nombreRefaccion}}</option>
 @endif
 @endforeach