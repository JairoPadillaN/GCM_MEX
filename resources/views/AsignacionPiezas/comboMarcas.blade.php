
<select name="idMarcaRefa" id="idMarca" class="form-control">
    <option value="">Seleccione marca</option>
    @foreach($consultaMarcas as $marcas)
    <option value="{{$marcas->idMarcaRefa}}">{{$marcas->marcaRefaccion}}</option>
    @endforeach
</select>