@if($notaInternaYskuEquivalente->skuEquivalente != "")
    <div class="form-group row">
        <div class="col-sm-12" ><b>SKU Equivalente:</b>        
            {{Form::text('skuEquivalente',$notaInternaYskuEquivalente->skuEquivalente,['class' => 'form-control rounded-0','readonly'])}}
        </div>
        <br>
        <div class="col-sm-12" ><b>Ver SKU Equivalente en cotización:</b>
            Si <input type="radio" name="verSku" value="Si" >&nbsp;&nbsp;&nbsp;&nbsp;
            No <input type="radio" name="verSku" value="No" checked>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
    </div>
@endif

@if($notaInternaYskuEquivalente->notaInterna != "")
    <div class="form-group row">
        <div class="col-sm-12" ><b>Nota interna para venta:</b>
            <textarea name="" id="" class="form-control rounded-0" rows="2" readonly>{{$notaInternaYskuEquivalente->notaInterna}}</textarea>
        </div>
    </div>
@endif