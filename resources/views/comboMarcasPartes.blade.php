<option value="">Seleccione una marca:</option>                            
 @foreach($comboMarcas as $cr)
 @if($cr->activo=="Si")
 <option value='{{$cr->idMarcaRefa}}'>{{$cr->marcaRefaccion}}</option>
 @endif
 @endforeach