<div id="datosSKU">
    <div class="row">
        <div class="col-sm-6">
            <div class="sub-title">Número de parte:</div>
            <div>
                {{Form::text('numeroParte',$consultaProductosOrden->numeroParte,['class' => 'form-control', 'readonly','id'=>'parte'])}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="sub-title">Número de serie:</div>
            <div>
                {{Form::text('serie',$consultaProductosOrden->serie,['class' => 'form-control', 'readonly'])}}
            </div>
        </div>
    </div>  
    <div class="sub-title">Código:</div>
        {{Form::text('codigoRefaccion',$consultaProductosOrden->codigo,['class' => 'form-control', 'readonly'])}}
        {{Form::hidden('nombreRefaccion',$consultaProductosOrden->nombreRefaccion,['class' => 'form-control', 'readonly'])}}
    <!-- <div id='codigoRefaccion'>
        <input class="form-control" type='text' name='codigoRefaccion' id='codigoRefaccion' readonly='readonly'>
    </div> -->
</div>