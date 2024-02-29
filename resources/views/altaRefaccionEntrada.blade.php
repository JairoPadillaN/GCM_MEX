@extends('principal')
@section('contenido')
<script type="text/javascript">
$(document).ready(function() {
    
    $("#guardar").click(function(){
        
        // console.log("faaaaaaaaaaaaaaf");
		$("#formularioEntrada").validate({
            onclick: false, 
            onkeyup: false,
            ignore: [],
			rules: {
                idProveedor: {required:true},
                numeroFactura: {required:true},
                montoFactura: {required:true},
                recibidoPor: {required:true},
                archivoFactura: {required:true},

                
			},
			messages: 
			{
                idProveedor: {required: 'Elige un proveedor'},
                numeroFactura: {required: 'Ingresa el número de factura'},
                montoFactura: {required: 'Ingresa el monto de factura'},
                recibidoPor: {required: 'Recibido por es obligatorio'},
                archivoFactura: {required: 'Archivo es obligatorio'},
                
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
                <h1>Agregar Refacción</h1>
        </div>
        
        <div id="confirmar"></div><br>
        <!-- TABS -->
        <ul class="nav nav-tabs">
            <li class="active" id='tab1'><a href="#registro" data-toggle="tab"><label for="">Entrada Almacen</label></a></li>
            <li class="" id='tab2'><a href="#profile" data-toggle="tab"><label for="">Refacciones</label></a></li>
        </ul>

        <!-- Contenido tabs -->
            
            <div class="tab-content">
                <div class="tab-pane active" id="registro" role="tabpanel" aria-labelledby="home-tab">
                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <div class="sub-title">Fecha de compra:</div>
                                <div>
                                {{Form::text('fechaCompra',$consultaEntradas->fechaCompra,['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                                </div>
                                <div class="sub-title">Registrado por:</div>
                                <div>
                                    <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                                    <input type="text" name="usuarioEquipos" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                                                    Session::get('sesionmaterno')!!}">
                                </div>              
                                <div class="sub-title">Proveedor:</div>
                                <div>
                                        @if($errors->first('idProveedor'))
                                            <i> {{ $errors->first('idProveedor') }}</i>
                                        @endif
                                        {{Form::text('idProveedor',$consultaEntradas->idProveedor,['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                                </div>
                                <div class="sub-title">Recibido en almacen por:</div>
                                <div>
                                        @if($errors->first('recibidoPor'))
                                        <i> {{ $errors->first('recibidoPor') }}</i>
                                        @endif
                                        {{Form::text('recibidoPor',$consultaEntradas->recibidoPor,['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                                </div>
                                
                            </div>

                            <div class="form-group col-md-6">
                                <div class="sub-title">* Número de factura:</div>
                                <div>
                                        @if($errors->first('numeroFactura'))
                                        <i> {{ $errors->first('numeroFactura') }}</i>
                                        @endif
                                        {{Form::text('numeroFactura', $consultaEntradas->numeroFactura,['class' => 'form-control', 'placeholder'=>'Ejemplo: 1230'])}}
                                </div>
                                <div class="sub-title">Monto factura:</div>
                                <div>
                                        @if($errors->first('montoFactura'))
                                        <i> {{ $errors->first('montoFactura') }}</i>
                                        @endif
                                        {{Form::text('montoFactura',$consultaEntradas->montoFactura,['class' => 'form-control', 'placeholder'=>'Ejemplo: 405.00'])}}
                                        <!-- {{Form::text('montoFactura',$sumaTotal->total,['class' => 'form-control', 'placeholder'=>'Ejemplo: 405.00'])}} -->
                                </div>
                                <div class="sub-title">Tipo de moneda:</div>
                                <div>
                                {{Form::text('moneda',$consultaEntradas->moneda,['class' => 'form-control', 'placeholder'=>'Ejemplo: 405.00'])}}
                                </div>
                                <div class="sub-title">Archivo de factura:</div>
                                <div>
                                @foreach($archivoEntrada as $archivo)
                                @if($archivo->archivoFactura =='Sin archivo')
                                                        <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                                    @else
                                        <a target="_blank" href="{{asset ('public/archivos/'.$archivo->archivoFactura)}}">
                                            @if(strpos($archivo->archivoFactura,'pdf'))
                                                <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                            @else
                                                <img src="{{asset ('public/archivos/'.$archivo->archivoFactura)}}" height=80 width=80>
                                            @endif
                                            
                                        </a>
                                        @endif
                                        @endforeach
                                </div>
                                
                            </div>
                        </div>
                </div>

                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="panel-body">
{{Form::open(['route' => 'registrarRefacciones','id' => 'formularioEntrada','files'=>true])}}
{{Form::token()}} 
{{ Form::text('idEntrada',$consultaEntradas->idEntrada,['class' => 'form-control rounded-0', 'readonly', 'id'=>'idEntrada'])}}
                        <div class="form-group col-md-6">
                            <div class="sub-title">Nombre de la refacción:</div>
                                    <div>
                                        @if($errors->first('nombreRefaccion'))
                                        <i> {{ $errors->first('nombreRefaccion') }}</i>
                                        @endif
                                        {{Form::text('nombreRefaccion',old ('nombreRefaccion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico'])}}
                                    </div>
                            <div class="sub-title">*Marca:</div>
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
                            
                            <div class="sub-title">*Tipo de refacción:</div>
                                <div>
                                    @if($errors->first('idTipoRefacciones'))
                                    <i> {{ $errors->first('idTipoRefacciones') }}</i>
                                    @endif
                                    <!-- aqui falraalgo -->
                                    <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                                        <option value="">Seleccione un tipo de refacción</option>
                                    </select>
                                </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Número de parte:</div>
                                    <div>
                                        @if($errors->first('numeroParte'))
                                        <i> {{ $errors->first('numeroParte') }}</i>
                                        @endif
                                        {{Form::text('numeroParte',old ('numeroParte'),['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652','id'=>'parte'])}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Número de serie:</div>
                                    <div>
                                        @if($errors->first('serie'))
                                        <i> {{ $errors->first('serie') }}</i>
                                        @endif
                                        {{Form::text('serie',old ('serie'),['class' => 'form-control', 'placeholder' => 'Ejemplo: ABC123165-2'])}}
                                    </div>
                                </div>
                            </div>  
                            <div class="sub-title">Código:</div>
                            <div id='codigoRefaccion'>
                                <input class="form-control" type='text' name='codigoRefaccion' id='codigoRefaccion' readonly='readonly'>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Presentación:</div>
                                    <div>
                                        @if($errors->first('presentacion'))
                                        <i> {{ $errors->first('presentacion') }}</i>
                                        @endif
                                        {{Form::radio('presentacion','Pieza',true, array('id'=>'Pieza'))}} Pieza &nbsp;&nbsp;&nbsp;
                                        {{Form::radio('presentacion','Caja', array('id'=>'Caja'))}} Caja &nbsp;&nbsp;&nbsp;
                                        {{Form::radio('presentacion','Equipo', array('id'=>'Equipo'))}} Equipo &nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Unidades por presentación:</div>
                                    <div>
                                        @if($errors->first('unidades'))
                                        <i> {{ $errors->first('unidades') }}</i>
                                        @endif
                                        {{Form::text('unidades',old ('unidades'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 12'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Ubicación:</div>
                                <div>
                                    @if($errors->first('ubicacion'))
                                    <i> {{ $errors->first('ubicacion') }}</i>
                                    @endif
                                    {{Form::text('ubicacion',old ('ubicacion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Bodega'])}}
                                </div>
                        </div>

                        <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                    <div class="sub-title">Precio unitario compra:</div>
                                    <div>
                                        @if($errors->first('precioLista'))
                                        <i> {{ $errors->first('precioLista') }}</i>
                                        @endif
                                        {{Form::text('precioLista',old ('precioLista'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00', 'id'=>'precio'])}}
                                    </div>
                                    </div>
                                    <div class="col-sm-6">
                                    <div class="sub-title">Precio unitario venta:</div>
                                    <div>
                                        @if($errors->first('precioUltimo'))
                                        <i> {{ $errors->first('precioUltimo') }}</i>
                                        @endif
                                        {{Form::text('precioUltimo',old ('precioUltimo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                                    </div>
                                    </div>
                                </div>
                            <div class="sub-title">Tipo producto:</div>
                            <div>
                                @if($errors->first('tipoProducto'))
                                <i> {{ $errors->first('tipoProducto') }}</i>
                                @endif
                                {{Form::radio('tipoProducto','Refacciones Reparación',true)}} Refacciones para reparación&nbsp;&nbsp;&nbsp;
                                {{Form::radio('tipoProducto','Refacciones Venta')}} Refacciones Venta &nbsp;&nbsp;&nbsp;
                                {{Form::radio('tipoProducto','Para cambio')}} Para cambio &nbsp;&nbsp;&nbsp;
                            </div>
                            <div class="sub-title">Cantidad:</div>
                                <div>
                                    @if($errors->first('cantidad'))
                                    <i> {{ $errors->first('cantidad') }}</i>
                                    @endif
                                    {{Form::text('cantidad',old ('cantidad'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 3','id'=>'cantidad'])}}
                            </div>
                            <div class="sub-title">Foto placa</div>
                            <div>
                                {{Form::file('fotoPlaca',['id'=>'foto'])}}
                            </div>
                            <br>
                            <div class="sub-title">Foto principal</div>
                            <div>
                                {{Form::file('fotoPrincipal')}}
                            </div>
                            <br>
                            <div class="sub-title">Observaciones:</div>
                            <div>
                                {{Form::textarea('observaciones',old ('observaciones'),['class' => 'form-control', 'placeholder' => 'Escribe aqui las observaciones necesarias', 'rows' =>'5'])}}
                            </div>
                            <!-- <div class="sub-title"><button type="button" class="btn btn-success btn-default" id="agregarRefaccion">
                                <span class="glyphicon glyphicon-plus-sign"></span> Agregar refacción
                                </button>
                            </div> -->
                            
                            
                        </div>
                    </div>
                    <div id="reporteRefacciones"></div>
                    <div>
                @include('reporteEntradaAgregada')
            </div>
                  
                <center>

                <!-- {{Form::submit('Agregar refacciòn',['class' => 'btn  btn-success','id' => "guardar"])}} -->
                <div class="sub-title"><button type="submit" class="btn btn-success btn-default" id="agregarRefaccion">
                                <span class="glyphicon glyphicon-plus-sign"></span> Agregar refacción
                                </button>
                            
                <button type="button" class="btn btn-success btn-danger" id="cancelar">
                    Cancelar Compra
                </button>
                </div>
                </center><br>       
                </div>
                
                
            </div> 
            </div>
            
        
    </div>
</div>


<script>
$(document).ready(function(){


$(".borrarRefaccion").click(function(){
// alert("id Refaccion")
$("#reporteRefacciones").load('{{url('borrarRefaccionAgregada')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
});

$("#cancelar").click(function(){
    // var idEntrada=$("#idEntrada").val();
    // alert(idEntrada);
     $("#reporteRefacciones").load('{{url('cancelarEntrada')}}' + '?r=' + Date.now() + '&idEntrada=' + $("#idEntrada").val());
     window.location.href = "reporteEntradaAlmacen";
});

$("#idMarcaRefa").change(function(){
    // alert("hola");
    $("#idTipoRefacciones").load('{{url('comboTipoRefaccion')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
});
$("#parte").change(function(){
    // alert("asf");
    $("#codigoRefaccion").load('{{url('generarCodigo')}}' + '?' + $(this).closest('form').serialize());
    // $("#codigoRefaccion").load('{{url('generarCodigo')}}' + '?parte=' + this.value);
});

});

</script>
<script>
function ValidarArchivo(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivo").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivo').val('');
        }
    }
</script>

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