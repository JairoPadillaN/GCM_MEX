@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    
    $("#guardar").click(function(){
        
        // console.log("faaaaaaaaaaaaaaf");
		$("#formularioPaquete").validate({
            onclick: false, 
            onkeyup: false,
            ignore: [],
			rules: {
                nombrePaquete: {required:true},
                             
			},
			messages: 
			{
                nombrePaquete: {required: 'Ingresa un nombre para el paquete.'},                
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>

{{Form::open(['route' => 'guardarPaquetes','id' => 'formularioPaquete','files'=>true])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Paquetes</h1>
        </div>
        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">Nombre del paquete:</div>
                <div>
                    {{Form::text('nombrePaquete',old ('nombrePaquete'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Paquete 127', 'id' => 'nombrePaquete'])}}
                </div>
            </div>
        </div>
        <center> {{Form::submit('Guardar',['class' => 'btn  btn-success','id' => "guardar"])}}
        </center><br>

    </div>

    
    <style>
    input.error {
        border-color: #f00 !important;
    }

    small.required {
        color: #f00;
    }

    textarea.error {
        border-color: #f00 !important;
    }

    .error {
        color: #f00 !important;
    }
    </style>

    @stop