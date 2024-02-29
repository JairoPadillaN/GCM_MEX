
    <select name="idRefaccion" id="idRefaccion" class="form-control">
        <option value="">Selecciona SKU</option> 
            @foreach($consultaCodigoParte as $prov)
            @if($prov->activo=="Si")
            <option value='{{$prov->idRefaccion}}'>{{$prov->codigoRefaccion}} - {{$prov->nombreRefaccion}} - {{$prov->numeroFactura}}</option>
            @endif
            @endforeach
    </select>
