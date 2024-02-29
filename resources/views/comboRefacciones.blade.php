<option value="">Seleccione un tipo de refacción:</option>                            
 @foreach($comboTipoRefaccionesParte as $cr)
 @if($cr->activo=="Si")
 <option value='{{$cr->idTipoRefacciones}}'>{{$cr->nombreTipoRefaccion}}</option>
 @endif
 @endforeach