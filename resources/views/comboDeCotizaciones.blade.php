<select name='idCotizacion' id="idCotizacion" class="form-control">
<option value="">Selecciona una cotizacion</option>
 @foreach($consultaCotizaciones as $conCot)
        <option value='{{$conCot->idCotizacion}}'>{{$conCot->numeroCotizacion}}</option>
 @endforeach
 </select>