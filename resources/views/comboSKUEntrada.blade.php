<option value="">Seleccione un SKU</option>
@foreach($consultaSKU as $sku)
@if($sku->activo=="Si")
<option value='{{$sku->idPartesVenta}}'>{{$sku->codigo}}-{{$sku->nombreRefaccion}}</option>
@endif
@endforeach
