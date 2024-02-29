<select name="idRefaccion" id="idRefaccion" class="form-control">
    <option value="">Selecciona SKU</option> 
    @foreach($consultaRefaccion as $consulta)
    <option value="{{$consulta->idRefaccion}}">{{$consulta->codigoRefaccion}}-{{$consulta->nombreRefaccion}} - {{$consulta->numeroFactura}}</option>
    @endforeach
        
</select>