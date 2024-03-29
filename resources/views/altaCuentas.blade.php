@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#guardar").click(function(){
        
        // console.log("faaaaaaaaaaaaaaf");
		$("#formularioABC").validate({
            onclick: false, 
            onkeyup: false,
            ignore: [],
			rules: {
                empresaCuenta: {required:true},
                nombreCuenta: {required:true},
                numeroCu: {required:true},
                monedaCuenta: {required:true}
			},
			messages: 
			{
                empresaCuenta: {required: 'Elige una empresa'},
                nombreCuenta: {required: 'Ingresa un nombre'},
                numeroCu: {required: 'Ingresa un nombre'},
                monedaCuenta: {required: 'Selecciona un tipo de moneda'}
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>

{{Form::open(['route' => 'guardarCuentas','name' => 'formularioABC','id' => 'formularioABC','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de cuentas</h1>
        </div>

        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos</div>
            </div>

                <div class="form-group col-sm-6">
                    
                        <div class="sub-title">Empresa:</div>
                        <div>
                            GCM <input id="empresaCuenta" type="radio" name="empresaCuenta" value="gcm"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            C&M <input id="empresaCuenta" type="radio" name="empresaCuenta" value="cym" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            SURJA <input id="empresaCuenta" type="radio" name="empresaCuenta" value="surja"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                            PEDRO OCEGUEDA <input id="empresaCuenta" type="radio" name="empresaCuenta" value="pedro"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            YANETH ABILENE <input id="empresaCuenta" type="radio" name="empresaCuenta" value="yaneth"/>
                        </div>
                        
                        <div class="sub-title">Nombre:</div>
                        <div>
                            {{Form::text('nombreCuenta',old ('nombreCuenta'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Ahorros', 'id' => 'nombreCuenta'])}}
                        </div>

                        <div class="sub-title">Número:</div>
                        <div>
                        <input type="text" pattern="^-?\d+(?:.\d+)?$" id="numeroCu" name="numeroCu" class="form-control" placeholder="Ejemplo: 60.12 ó 30">    
                        </div>

                        <div class="sub-title">Descripción: </div>
                        <div>
                            {{Form::textarea('descripcionCuenta', old('descripcionCuenta'),['class' => 'form-control', 'placeholder' => 'Agregar información adicional'])}}
                        </div>

                        <div class="sub-title">Moneda:</div>
                            @if($errors->first('monedaCuenta'))
                            <i> {{ $errors->first('monedaCuenta') }}</i>
                            @endif
                            <div>
                                <select name='monedaCuenta' id="monedaCuenta" class="form-control">
                                    <option value="">Selecciona una moneda</option>
                                    <option value="MXN">MXN</option>
                                    <option value="USD">USD</option>
                                </select>
                                <div id = "alertaCliente" class="alert alert-warning">Debes seleccionar un tipo de moneda.</div>
                            </div>
                        
                        <div class="sub-title">Saldo:</div>
                        <div>
                            {{Form::text('saldoCuenta',old ('saldoCuenta'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 100'])}}
                        </div>

                        
                        <br>
                        <center>
                        {{Form::submit('Guardar',['class' => 'btn  btn-success','id' => 'guardar'])}}
                </div>
        </div>
    </div>
</div>


<style>
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
.alert-warning {
    display: none;
}
.alert-info {
    display: none;
}

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

input:invalid{
 background-color:pink;
}
</style>
@stop