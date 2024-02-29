<!-- <div class="sub-title">SKU</div> -->
<div>
    <input type="hidden" name="idPartesVenta" class="form-control" id="idPartesVentaDes" readonly value="{{$consulta->idPartesVenta}}">
    <input type="hidden" name="codigo" class="form-control" id="codigoDes" readonly value="{{$consulta->codigo}}">
</div>
<div class="sub-title">Descripción: </div>
<div>
    <!-- <input type="text" name="nombreRefaccion" class="form-control" id="nombreRefaccionDes" readonly value="{{$consulta->nombreRefaccion}}"> -->
    <textarea name="nombreRefaccion" id="nombreRefaccionDes" rows="2" class="form-control" readonly>{{$consulta->nombreRefaccion}}</textarea>
</div>
