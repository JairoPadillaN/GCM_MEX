@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#guardar").click(function(){
		$("#formularioRefaccion").validate({
            onclick: false, 
            onkeyup: false,
			rules: {
                idMarcaRefa: {required:true},
                nombreTipoRefaccion: {required:true},
			},
			messages: 
			{
                idMarcaRefa: {required: 'Elige una marca'},
                nombreTipoRefaccion: {required: 'Ingresa el nombre del tipo de refacción'},
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>

{{Form::open(['route' => 'guardarTipoRef','files'=>true, 'id' => 'formularioRefaccion'])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de tipos de refacción</h1>
        </div>
        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos del tipo de refacción</div>
            </div>
            <div class="form-group col-md-6">
                <div class="sub-title">Marca:</div>
                    <div>
                        @if($errors->first('marca'))
                        <i> {{ $errors->first('marca') }}</i>
                        @endif
                        <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                            <option value="">Seleccione una marca</option>
                            @foreach($marca as $mr)
                            @if($mr->activo=="Si")
                            <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                <div class="sub-title">Nombre del tipo de refacción:</div>
                <div>
                    @if($errors->first('nombreTipoRefaccion'))
                    <p><i> {{ $errors->first('nombreTipoRefaccion') }}</i></p>
                    @endif
                    {{Form::text('nombreTipoRefaccion',old ('nombreTipoRefaccion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Display'])}}
                </div>
                <div class="sub-title">Código de refacción:</div>
                <div>
                    @if($errors->first('codigoTipoRef'))
                    <p><i> {{ $errors->first('codigoTipoRef') }}</i></p>
                    @endif
                    {{Form::text('codigoTipoRef',old ('codigoTipoRef'),['class' => 'form-control', 'placeholder' => 'Ejemplo: PN'])}}
                </div>
            </div>
            
        </div>
        <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-success', 'id' => 'guardar'])}}
                </center><br>    
    </div>

    <style>
input.error {
    border-color: #f00 !important;
}

small.required {
    color:#f00;
}

textarea.error {
    border-color: #f00 !important;
}

.error {
    color: #f00 !important;
}
</style>
    @stop