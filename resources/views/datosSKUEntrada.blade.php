<div class="row">
    <div class="col-sm-6">
        <div class="sub-title">Número de parte:</div>
        <div>
            {{Form::text('numeroParte',$consultaDatosSKU->numeroParte,['class' => 'form-control', 'readonly', 'id'=>'parte'])}}
            {{Form::hidden('nombreRefaccion',$consultaDatosSKU->nombreRefaccion,['class' => 'form-control', 'readonly'])}}
            {{Form::hidden('idPartesVenta',$consultaDatosSKU->idPartesVenta,['class' => 'form-control', 'readonly'])}}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="sub-title">Número de serie:</div>
        <div>
            {{Form::text('serie',$consultaDatosSKU->serie,['class' => 'form-control', 'readonly', 'id'=>'serie'])}}
        </div>
    </div>
</div>  
<div class="sub-title">Código:</div>
<div id='codigoRefaccion'>
{{Form::text('codigoRefaccion',$consultaDatosSKU->codigo,['class' => 'form-control', 'readonly', 'id'=>'codigo'])}}
    <!-- <input class="form-control" type='text' name='codigoRefaccion' id='codigoRefaccion' readonly='readonly'> -->
</div>
