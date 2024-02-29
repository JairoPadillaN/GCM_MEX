
@extends('principal')
@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    
    $("#guardar").click(function(){
		$("#formularioEditarEntrada").validate({
			rules: {
                idProveedor: {required:true},
                idOrden: {required:true},
                recibidoPor: {required:true}
			},
			messages: 
			{
                idProveedor: {required: 'Elige un proveedor'},
                idOrden: {required: 'Elige una factura'},
                recibidoPor: {required: 'Recibido por es obligatorio'}   
            },
		});
    });
    
});
</script>
<!-- {{Form::open(['route' => 'guardarEntradaAlmacen','id' => 'formularioEntrada','files'=>true])}}
{{Form::token()}} -->
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
                <h1>Registro Entrada Almacen: Ordenes de Compra</h1>
        </div>
        <form action="{{ route('guardarEntradaOrden') }}" method='POST' enctype='multipart/form-data' id="formularioEditarEntrada">
        @csrf
        {{ Form::hidden('idEntradaOrden',$idSiguiente,['class' => 'form-control rounded-0', 'readonly', 'id'=>'idEntrada'])}}
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
                                    @if($errors->first('fechaCompra'))
                                    <i> {{ $errors->first('fechaCompra') }}</i>
                                    @endif
                                    {{Form::date('fechaCompra',\Carbon\Carbon::now(),['class' => 'form-control'])}}
                            </div>
                            <div class="sub-title">Registrado por:</div>
                            <div>
                                <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                                <input type="text" name="usuarioEquipos" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                                                Session::get('sesionmaterno')!!}">
                            </div>              
                            <div class="sub-title">* Proveedor:</div>
                            <div>
                                    @if($errors->first('idProveedor'))
                                        <i> {{ $errors->first('idProveedor') }}</i>
                                    @endif
                                    <select name='idProveedor' class="form-control" id="idProveedor">
                                        <option value=''>Seleciona proveedor</option>
                                        @foreach($proveedor as $prov)
                                        @if($prov->activo=="Si")
                                        <option value='{{$prov->idProveedor}}'>{{$prov->razonSocialProv}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                            </div>
                            <div class="sub-title">* Recibido en almacen por:</div>
                            <div>
                                    @if($errors->first('recibidoPor'))
                                    <i> {{ $errors->first('recibidoPor') }}</i>
                                    @endif
                                    {{Form::text('recibidoPor',old('recibidoPor'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                            </div>
                            
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">* Número de factura:</div>
                            <div>
                                <select name="idOrden" id="idOrden" class="form-control">
                                    <option value="">Selecciona numero de factura</option>
                                </select>
                                <!-- {{Form::text('numeroFactura', old('numeroFactura'),['class' => 'form-control', 'placeholder'=>'Ejemplo: 1230'])}} -->
                            </div>
                            <div id="infoFactura">
                                <div class="sub-title">* Monto factura:</div>
                                <div>
                                    {{Form::text('montoFactura',old('montoFactura'),['class' => 'form-control', 'readonly'=>'true','id'=>'montoFactura'])}}    
                                </div>
                                <div class="sub-title">Tipo de moneda:</div>
                                <div>
                                        {{Form::radio('moneda','MXN', true,['disabled'])}} MXN&nbsp;&nbsp;&nbsp;
                                        {{Form::radio('moneda','USD',false,['disabled'])}} USD&nbsp;&nbsp;&nbsp;
                                </div>
                                <div class="sub-title">* Archivo de factura:</div>
                                <div id="imagenPDF"> 
                                    <!-- {{Form::file('archivoFactura',['class' => 'form-control','id'=>'archivo','readonly'])}} -->
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="panel-body">
                    
                        <div class="form-group col-md-6">                            
                            
                            <div class="sub-title">Nombre de SKU:</div>
                                <div>
                                    <select name='idProducto' class="form-control" id="idProducto">
                                        <option value="">Seleccione un SKU</option>
                                    </select>                                    
                                </div>  
                            <div id="datosProducto">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="sub-title">Número de parte:</div>
                                        <div>
                                            @if($errors->first('numeroParte'))
                                            <i> {{ $errors->first('numeroParte') }}</i>
                                            @endif
                                            {{Form::text('numeroParte',old ('numeroParte'),['class' => 'form-control', 'readonly','id'=>'parte'])}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="sub-title">Número de serie:</div>
                                        <div>
                                            @if($errors->first('serie'))
                                            <i> {{ $errors->first('serie') }}</i>
                                            @endif
                                            {{Form::text('serie',old ('serie'),['class' => 'form-control', 'readonly'])}}
                                        </div>
                                    </div>
                                </div>  
                                <div class="sub-title">Código:</div>
                                <div id='codigoRefaccion'>
                                    <input class="form-control" type='text' name='codigoRefaccion' id='codigoRefaccion' readonly='readonly'>
                                </div>
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
                            <div class="sub-title">Tipo producto:</div>
                            <div>
                                {{Form::radio('tipoProducto','Refacciones Reparación',true)}} Refacciones para reparación&nbsp;&nbsp;&nbsp;
                                {{Form::radio('tipoProducto','Refacciones Venta')}} Refacciones Venta &nbsp;&nbsp;&nbsp;
                                {{Form::radio('tipoProducto','Para cambio')}} Para cambio &nbsp;&nbsp;&nbsp;
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div id="datosProducto2">
                                <div class="row" id="">
                                    <div class="col-sm-6">
                                    <div class="sub-title">* Precio unitario compra(MXN):</div>
                                    <div>
                                        {{Form::text('precioLista',old ('precioLista'),['class' => 'form-control','readonly'])}}
                                    </div>
                                    <div class="sub-title">* Precio unitario compra(USD):</div>
                                    <div>
                                        {{Form::text('precioLista',old ('precioLista'),['class' => 'form-control','readonly'])}}
                                    </div>
                                    </div>
                                    <div class="col-sm-6">
                                    <div class="sub-title">Precio unitario venta(MXN):</div>
                                    <div>
                                        {{Form::text('precioUltimo',old ('precioUltimo'),['class' => 'form-control','readonly'])}}
                                    </div>
                                    <div class="sub-title">Precio unitario venta(USD):</div>
                                    <div>
                                        {{Form::text('precioUltimo',old ('precioUltimo'),['class' => 'form-control','readonly'])}}
                                    </div>
                                    </div>
                                </div>
                                <div class="sub-title">* Cantidad:</div>
                                <div>
                                    {{Form::text('cantidad',old ('cantidad'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 3','readonly'])}}
                                </div>
                                
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
                            <div class="sub-title"><button type="button" class="btn btn-success" id="agregarRefaccion">
                                <span class="glyphicon glyphicon-plus-sign"></span> Agregar refacción
                                </button>
                            </div>
                            
                            
                        </div>
                    </div>
                    
                    <center>
                    <button type="submit" class="btn btn-success" id="guardar">Guardar Entrada</button>
                    <button type="button" class="btn btn-success btn-danger" id="cancelar">Cancelar Compra</button>    
                    
                    </center><br>       
            
                </div>  
        </form>
            </div> 
            

            <div id="reporteRefacciones"></div>
            
            </div>
            
        
</div>

<script>
    $(function(){
            $("#agregarRefaccion").click(function(e){
            var precio=$("#precio").val();
            var cantidad=$("#cantidad").val();
            var proveedor=$("#idProveedor").val();
            var nombreRefaccion=$("#idProducto").val();

            if(nombreRefaccion==''){
                alert('Nombre de refacción es requerido')
                $("#nombreRefaccion").css('border-color', 'red');
                $("#nombreRefaccion").css('color', 'red');
                e.preventDefault(); 
            }else if (precio==''){
                alert('Precio es requerido')
                $("#precio").css('border-color', 'red');
                $("#precio").css('color', 'red');
                e.preventDefault(); 
                }else if(cantidad==''){
                    alert('Cantidad es requerida')
                    $("#cantidad").css('border-color', 'red');
                    $("#cantidad").css('color', 'red');
                    e.preventDefault(); 
                    }else if(proveedor==''){
                        alert('Es requerido el proveedor de la compra')
                        $("#idProveedor").css('border-color', 'red');
                        $("#idProveedor").css('color', 'red');
                        e.preventDefault(); 
                        }else {

            
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formularioEditarEntrada"));
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "{{ route('registrarRefacciones') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false
            })
                .done(function(res){
                    $("#reporteRefacciones").html(res);
                });
                        }
        });
    });
</script>
<script>
$(document).ready(function(){

    $("#idProveedor").change(function(){
        $("#idOrden").load('{{url('comboNumeroOrden')}}' + '?r=' + Date.now() + '&idProveedor=' + $("#idProveedor").val());
        $('#montoFactura, #idProducto').val('');
    });
    $("#idOrden").change(function(){
        $("#infoFactura").load('{{url('infoOrdenCompra')}}' + '?r=' + Date.now() + '&idOrden=' + $("#idOrden").val());
        $("#idProducto").load('{{url('productosOrdenCompra')}}' + '?r=' + Date.now() + '&idOrden=' + $("#idOrden").val());
    });
    $("#idProducto").change(function(){
        $("#datosProducto").load('{{url('datosProducto')}}' + '?r=' + Date.now() + '&idProducto=' + $("#idProducto").val());
        $("#datosProducto2").load('{{url('datosProducto2')}}' + '?r=' + Date.now() + '&idProducto=' + $("#idProducto").val());
    });

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



});

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