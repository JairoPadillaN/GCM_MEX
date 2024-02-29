@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarMarcasRefaccion','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de Marcas de refacción</h1>
        </div>
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">Marca:</div>
                    <div>
                        @if($errors->first('marcaRefaccion'))
                        <i> {{ $errors->first('marcaRefaccion') }}</i>
                        @endif
                        {{Form::text('marcaRefaccion',old ('marcaRefaccion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Husky'])}}
                    </div>
                <div class="sub-title">Código:</div>
                    <div>
                        @if($errors->first('codigoMarca'))
                        <i> {{ $errors->first('codigoMarca') }}</i>
                        @endif
                        @if($cuantosHay ==1 ) 
                        <label style="color:red"> El codigo {{$codigoMarca}} ya existe</label>
                        @endif
                        {{Form::text('codigoMarca',old ('codigoMarca'),['class' => 'form-control', 'placeholder' => 'Ejemplo: H', 'onkeyup'=>'javascript:this.value=this.value.toUpperCase();'])}}
                    </div>
                <div class="sub-title">Codigo RFQ:</div>
                    <div>
                        @if($errors->first('MarcaRFQ'))
                        <i> {{ $errors->first('MarcaRFQ') }}</i>
                        @endif
                        {{Form::text('MarcaRFQ',old ('MarcaRFQ'),['class' => 'form-control', 'placeholder' => 'Ejemplo: HUS  (Debe se mayor a tres letras)', 'onkeyup'=>'javascript:this.value=this.value.toUpperCase();'])}}
                    </div>
            </div>            
            </div>  
                       
        </div>
        <div><center> {{Form::submit('Guardar',['class' => 'btn  btn-success'])}}</center></div> <br>
    </div>
</div>


@stop