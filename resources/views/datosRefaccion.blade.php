<div class="row">
    <div class="col-sm-6">
        <div class="sub-title">Numero de parte: </div>
        <div>
         {{Form::text('parte',$consultaRefacciones->numeroParte,['class' => 'form-control consulta','readonly' => 'true', 'id'=>'numeroParte'])}}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="sub-title">Numero de serie: </div>
        <div>
        {{Form::text('serieNum',$consultaRefacciones->serie,['class' => 'form-control consulta','readonly' => 'true', 'id'=>'numeroSerie'])}}
        </div>
    </div>

</div>
<div class="sub-title">Código:</div>
    <div id='codigoRefaccion'>
    {{Form::text('codigoRefaccion',$consultaRefacciones->codigoRefaccion,['class' => 'form-control consulta','readonly' => 'true', 'id'=>'codigo'])}}
<div class="sub-title">Observaciones:</div>
    <div id='codigoRefaccion'>
    {{Form::text('observaciones',$consultaRefacciones->observaciones,['class' => 'form-control consulta','readonly' => 'true', 'id'=>'observaciones'])}}
</div>

