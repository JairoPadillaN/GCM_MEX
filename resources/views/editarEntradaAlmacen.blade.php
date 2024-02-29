@extends('principal')
@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
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
                // archivoFactura: {required:true},
                
			},
			messages: 
			{
                idProveedor: {required: 'Elige un proveedor'},
                numeroFactura: {required: 'Ingresa el número de factura'},
                montoFactura: {required: 'Ingresa el monto de factura'},
                recibidoPor: {required: 'Recibido por es obligatorio'},
                // archivoFactura: {required: 'Archivo es obligatorio'},
                
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>
<!--{{Form::open(['route' => 'editarEntradaAlmacen','id' => 'formularioEntrada','files'=>true])}}
{{Form::token()}}-->

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
                <h1>Modificar Entrada Almacen</h1>
        </div>
        <form action="{{ route('editarEntradaAlmacen') }}" method='POST' enctype='multipart/form-data' id="formularioEntrada">
            @csrf
            {{ Form::hidden('idEntradaEditar',($consulta->idEntrada),['class' => 'form-control rounded-0', 'readonly'])}}
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
                                            {{Form::date('fechaCompra',($consulta->fechaCompra),['class' => 'form-control'])}}
                                    </div>
                                    <div class="sub-title">Registrado por:</div>
                                    <div>
                                        <input type='hidden' name='idu' id='idu' value="{{$consulta->idu}}">
                                        <input type="text" name="usuarioEquipos" class="form-control" readonly="true" value="{{$usuario->nombreUsuario}} {{$usuario->aPaterno}} {{$usuario->aMaterno}}">
                                    </div>              
                                    <div class="sub-title">* Proveedor:</div>
                                    <div>
                                            @if($errors->first('idProveedor'))
                                                <i> {{ $errors->first('idProveedor') }}</i>
                                            @endif
                                            <select name='idProveedor' class="form-control">
                                                <option value='{{$idProvSel}}'>{{$nombreProveedor}}</option>
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
                                            {{Form::text('recibidoPor',($consulta->recibidoPor),['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                                    </div>
                                    
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="sub-title">* Número de factura:</div>
                                    <div>
                                            @if($errors->first('numeroFactura'))
                                            <i> {{ $errors->first('numeroFactura') }}</i>
                                            @endif
                                            {{Form::text('numeroFactura', ($consulta->numeroFactura),['class' => 'form-control', 'placeholder'=>'Ejemplo: 1230'])}}
                                    </div>
                                    <div class="sub-title">* Monto factura:</div>
                                    <div>
                                            @if($errors->first('montoFactura'))
                                            <i> {{ $errors->first('montoFactura') }}</i>
                                            @endif
                                            {{Form::text('montoFactura',($consulta->montoFactura),['class' => 'form-control', 'placeholder'=>'Ejemplo: 405.00'])}}
                                    </div>
                                    <div class="sub-title">Tipo de moneda:</div>
                                    <div>
                                            @if($errors->first('moneda'))
                                            <i> {{ $errors->first('moneda') }}</i>
                                            @endif
                                            @if ($consulta->moneda == 'MXN')
                                            {{Form::radio('moneda','MXN',true)}} MXN&nbsp;&nbsp;&nbsp;
                                            {{Form::radio('moneda','USD')}} USD&nbsp;&nbsp;&nbsp;
                                            @else
                                            {{Form::radio('moneda','MXN')}} MXN&nbsp;&nbsp;&nbsp;
                                            {{Form::radio('moneda','USD',true)}} USD&nbsp;&nbsp;&nbsp;
                                            @endif
                                    </div><br>
                                    <div class="sub-title">* Archivo de factura:</div>
                                    <div>
                                            @if($errors->first('archivoFactura'))
                                            <i> {{ $errors->first('archivoFactura') }}</i>
                                            @endif
                                            {{Form::file('archivoFactura',['class' => 'form-control'])}}
                                            
                                    </div>
                                    <div class="sub-title">Archivo</div>
                                    <div>
                                    @foreach($archivoEditar as $archivo)
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
                        
                            <div class="form-group col-md-6">
                                
                                    <div class="sub-title">* Marca:</div>
                                    <div>
                                        @if($errors->first('marca'))
                                        <i> {{ $errors->first('marca') }}</i>
                                        @endif
                                        <!-- {{Form::text('marca',old ('marca'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Husky'])}} -->
                                        <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                                            <option value="">Seleccione una marca</option>
                                            @foreach($marca as $mr)
                                            @if($mr->activo=="Si")
                                            <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                </div>
                                <div class="sub-title">* Tipo de refacción:</div>
                                    <div>
                                        @if($errors->first('idTipoRefacciones'))
                                        <i> {{ $errors->first('idTipoRefacciones') }}</i>
                                        @endif
                                        
                                        <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                                            <option value="">Seleccione un tipo de refacción</option>
                                        </select>
                                    </div>
                                    <div class="sub-title">* Nombre de SKU:</div>
                                    <div>
                                        <select name='' class="form-control mi-selector" id="nombreRefaccion">
                                            <option value="">Seleccione un SKU</option>
                                        </select>                                    
                                    </div>  
                                <div id="datosSKU">
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
                                
                            </div>

                            <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <div class="sub-title">* Precio unitario compra:</div>
                                        <div>
                                            @if($errors->first('precioLista'))
                                            <i> {{ $errors->first('precioLista') }}</i>
                                            @endif
                                            {{Form::text('precioLista',old ('precioLista'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00','id'=>'precio'])}}
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
                                    {{Form::radio('tipoProducto','Para cambio')}} Para cambio&nbsp;&nbsp;&nbsp;
                                </div>
                                <div class="sub-title">* Cantidad:</div>
                                    <div>
                                        @if($errors->first('cantidad'))
                                        <i> {{ $errors->first('cantidad') }}</i>
                                        @endif
                                        {{Form::text('cantidad',old ('cantidad'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 3','id'=>'cantidad'])}}
                                </div>
                                <div class="sub-title">Foto placa</div>
                                <div>
                                    {{Form::file('fotoPlaca')}}
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
                        
                    </div>
                </div> 
                
                <center>
                    <!-- {{Form::submit('Guardar',['class' => 'btn  btn-success','id' => "guardar"])}} -->
                    <button type="submit" class="btn btn-success" id="guardar">Guardar Entrada</button>
                </center><br>       
                
        </form>
        <div>
                @include('reporteEntradaAgregada')
        </div>
        <div id="reporteRefacciones">
        </div>
    </div>
</div>
<script>
    $(function(){
            $("#agregarRefaccion").click(function(e){
                var refaccion=$("#idTipoRefacciones").val();
            var precio=$("#precio").val();
            var cantidad=$("#cantidad").val();
            var marca=$("#idMarcaRefa").val();

            if(marca==''){
                $("#idMarcaRefa").css('border-color', 'red');
                $("#idMarcaRefa").css('color', 'red');
                alert("Marca es requerido")
                e.preventDefault(); 
            }else if(refaccion==''){
                alert('Tipo de refacción es requerido')
                $("#idTipoRefacciones").css('border-color', 'red');
                $("#idTipoRefacciones").css('color', 'red');
                e.preventDefault(); 
                }else if(nombreRefaccion==''){
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
                            }else {

            
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formularioEntrada"));
            //formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "{{ route('registrarRefacciones') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false,
            })
                .done(function(res){
                    console.log("Res-->" + res);
                    $("#reporteRefacciones").html(res);
                }).fail( function( jqXHR, textStatus, errorThrown ) {
                    console.log("Error: " + textStatus);
                    console.log("errorThrown: " + errorThrown);
                    console.log("jqXHR: " + jqXHR);
                });
                            }
        });
    });
</script>

<script>
$(document).ready(function(){
$("#idMarcaRefa").change(function(){
    // alert("hola");
    $("#idTipoRefacciones").load('{{url('comboTipoRefaccion')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
    $('#parte, #serie, #codigo, #nombreRefaccion').val('');
});
$("#idTipoRefacciones").change(function(){
    // alert("hola");
    $("#nombreRefaccion").load('{{url('comboSKU')}}' + '?r=' + Date.now() + '&idTipoRefacciones=' + this.options[this.selectedIndex].value);
    $('.mi-selector').select2();
    $('#parte, #serie, #codigo').val('');
});
$("#nombreRefaccion").change(function(){
    // alert("hola");
    $("#datosSKU").load('{{url('datosSKUEntrada')}}' + '?r=' + Date.now() + '&idPartesVenta=' + this.options[this.selectedIndex].value);
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