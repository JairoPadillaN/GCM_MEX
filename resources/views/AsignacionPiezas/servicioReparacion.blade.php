@extends('principal')
@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h2>Asignación de piezas</h2>
        </div>
        <div class="panel-body">
 
            <form action="" method="post" id="formularioServicio">
                @csrf
                {{Form::hidden('idParte', $idParte,['class'=>'form-control','id'=>'idParte'])}}
                {{Form::hidden('idFactura', $idFactura,['class'=>'form-control'])}}
                {{Form::hidden('idRepCotizacion', $idRepCotizacion,['class'=>'form-control','id'=>'idRepCotizacion'])}}
                {{Form::hidden('idCotizacion', $idCotizacion,['class'=>'form-control','id'=>'idCotizacion'])}}
                <div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Fecha de asignación</div>
                        <div>
                            {{Form::date('fechaAsginacion', \Carbon\Carbon::now(),['class'=>'form-control'])}}
                        </div>
                        <div class="sub-title">Tipo de parte</div>
                        <div>
                            <select name="tipoProducto" id="tipoProducto" class="form-control">
                            <option value="">Seleccione tipo de parte</option>
                            @foreach($tipo as $tipo)
                                <option value="{{$tipo->tipoProducto}}">{{$tipo->tipoProducto}}</option>
                            @endforeach

                            </select>
                            
                        </div>

                        <div class="sub-title">Marca</div>
                        <div>
                            <select name="idMarcaRefa" id="idMarca" class="form-control">
                                <option value="">Seleccione marca</option>
                            </select>
                        </div>

                        <div class="sub-title">Tipo de refacción</div>
                        <div>
                            <select name="idTipoRefacciones" id="idTipoRefacciones" class="form-control">
                                <option value="">Seleccione tipo de refacción</option>
                            </select>
                            
                        </div>

                        <div class="sub-title">SKU disponibles</div>
                        <div>
                            <select name="idRefaccion" id="idRefaccion" class="form-control">
                                <option value="">Selecciona SKU</option> 
                                    
                            </select>
                        </div>
                        <div class="sub-title">Asignado por</div>
                        <div>
                            <input type="text" name="asignadoPor" class="form-control" readonly="true" 
                            value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!Session::get('sesionmaterno')!!}">
                            
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div id="infoFactura" class="infoFactura">
                            <div class="sub-title">A que servicio se asignan las piezas</div>
                            <div>
                                <input type="text" name="" class="form-control" readonly="true">
                            </div>
                            <div class="sub-title">Proveedor</div>
                            <div>
                                <input type="text" name="" class="form-control" readonly="true">
                            </div>
                            <div class="sub-title">Fecha compra</div>
                            <div>
                                <input type="text" name="" class="form-control" readonly="true">
                            </div>
                            <div class="sub-title">Cantidad disponible</div>
                            <div>
                                <input type="text" name="" class="form-control" readonly="true">
                            </div>

                            <div class="sub-title">En cotización</div>
                            <div>
                            <input type="text" name="" class="form-control" readonly="true">
                            <!-- {{Form::text('piezas', $consultaSolicitada->piezas,['class'=>'form-control', 'readonly', 'id'=>'piezasSolicitada'])}} -->
                            </div>
                        </div>
                        
                        
                        <div class="sub-title">Cantidad a ocupar</div>
                            <div>
                                {{Form::text('cantidadOcupada', old('fecha'),['class'=>'form-control', 'id'=>'piezasOcupar'])}}
                            </div>
                    </div>
                </div>
            <div id="cambio">
                
                <button type="button" id="guardarPieza" class="btn btn-success">
                    <span class="glyphicon glyphicon-plus-sign"></span> Agregar
                </button>
  
            </div>
            </form><br>

            <div class="alert alert-warning" role="alert" id="mensaje" style="display:none;">
                ¡La cantidad a ocupar no puede ser mayor a la cantidad solicitada!
            </div>

            <div class="alert alert-warning" role="alert" id="mensajeDisponible" style="display:none;">
                ¡La cantidad a ocupar no puede ser mayor a la cantidad disponible!
            </div>

            <div id="reporte">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr style="background-color: #D5F5E3;">                                
                            <th><font SIZE=2>SKU</font></th>
                            <th><font SIZE=2>Nombre</font></th>
                            <th><font SIZE=2>Serial/Modelo</font></th>
                            <th><font SIZE=2>Cantidad</font></th>
                            <th><font SIZE=2>Unidad de medida</font></th>
                            <th><font SIZE=2>Factura Proveedor</font></th>
                            <th><font SIZE=2>Proveedor</font></th>
                            <th><font SIZE=2>Fecha Compra</font></th> 
                            <th><font SIZE=2>Facturado a Servicio</font></th> 
                            <th><font SIZE=2>Opciones</font></th> 
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($consultaPiezas as $piezas)
                        <tr>
                            <td>{{$piezas->codigoRefaccion}}</td>
                            <td>{{$piezas->nombreRefaccion}}</td>
                            <td>{{$piezas->serie}}</td>
                            <td>{{$piezas->cantidadOcupada}}</td>
                            <td>{{$piezas->presentacion}}</td>
                            <td>{{$piezas->numeroFacturaProv}}</td>
                            <td>{{$piezas->razonSocialProv}}</td>
                            <td>{{$piezas->fechaCompra}}</td>
                            <td>{{$piezas->numeroFactura}}</td>
                            <td>
                            <form action="">
                            @csrf
                            <input type="hidden" value="{{$piezas->idPiezaAsig}}" name="idPiezaAsig">
                            <input type="hidden" value="{{$piezas->idRefaccion}}" name="idRefaccion">
                            <input type="hidden" value="{{$idParte}}" name="idParte">
                            <input type="hidden" value="{{$piezas->cantidadOcupada}}" name="cantidadEliminar">
                            <input type="hidden" value="{{$idRepCotizacion}}" name="idRepCotizacion">
                                <button type="button" class="btn btn-sm btn-danger borrarPieza" style='width:40px; height: 35px;'>
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
     
            <center>
                <a href="{{URL::action('AsignacionPiezasController@asignacionPiezas')}}" class="btn btn-success btn-warning" type="submit">Cerrar</a>
            </center>
        </div><!-- //final body -->
        
    </div>
</div>
<script>
$(document).ready(function(){
    var idCotizacion = document.getElementById('idCotizacion').value;
    $("#tipoProducto").change(function(){
        var tipoProducto = document.getElementById('tipoProducto').value;
        
        
        if(tipoProducto=="Refurbished y/o Repair"){
            tipo="Refurbished";
        }else{
            tipo = document.getElementById('tipoProducto').value;
        }
        $("#idMarca").load('{{url('comboMarcaPiezas')}}' + '?r=' + Date.now() + '&tipoProducto=' + tipo + '&idCotizacion=' + idCotizacion);
    });
    
    $("#idMarca").change(function(){
        var tipoProducto = document.getElementById('tipoProducto').value;
        
        if(tipoProducto=="Refurbished y/o Repair"){
            tipo="Refurbished";
        }else{
            tipo = document.getElementById('tipoProducto').value;
        }
        $("#idTipoRefacciones").load('{{url('comboTipoRefPiezas')}}' + '?r=' + Date.now() + '&idMarca=' + $("#idMarca").val() + '&tipoProducto=' + tipo + '&idCotizacion=' + idCotizacion);
    });

    $("#idTipoRefacciones").change(function(){
        var tipoProducto = document.getElementById('tipoProducto').value;
        
        if(tipoProducto=="Refurbished y/o Repair"){
            tipo="Refurbished";
        }else{
            tipo = document.getElementById('tipoProducto').value;
        }
        $("#idRefaccion").load('{{url('comboRefaccionPiezas')}}' + '?r=' + Date.now() + '&idTipoRefacciones=' + $("#idTipoRefacciones").val() + '&tipoProducto=' + tipo + '&idCotizacion=' + idCotizacion);
    });

    $("#idRefaccion").change(function(){
        var idParte = $("#idParte").val();
        var idRepCotizacion = $("#idRepCotizacion").val();
        var idCotizacion = $("#idCotizacion").val();
        // $("#infoFactura").load('{{url('combiarInputs')}}' + '?r=' + Date.now() + '&idRefaccion=' + $("#idRefaccion").val());
        $("#infoFactura").load('{{url('combiarInputs')}}' + '?r=' + Date.now() + '&idRefaccion='  + this.options[this.selectedIndex].value + '&idParte=' + idParte + '&idRepCotizacion=' + idRepCotizacion+ '&idCotizacion=' + idCotizacion);
    });

    $("#guardarPieza").click(function(e){
        
        var idRefaccion = $("#idRefaccion").val();
        var validarCantidad = $("#piezasOcupar").val();
        
        
        if(idRefaccion=="" || validarCantidad=="" || validarCantidad<=0){
            if(idRefaccion=='' ){
            $("#idRefaccion").css('border-color', 'red');
            $("#idRefaccion").css('color', 'red');  
            alert("Elige un SKU");
            }else if(validarCantidad=='' || validarCantidad<=0 ){
            $("#piezasOcupar").css('border-color', 'red');
            $("#piezasOcupar").css('color', 'red');  
            alert("Ingresa una cantidad mayor a 0");
            }
        }else{
            var solicitada = +document.getElementById("piezasSolicitada").value;
            var ocupar = +document.getElementById("piezasOcupar").value;
            var cantidad = +document.getElementById("cantidad").value;
            var total = +document.getElementById("total").value;
            var idParte = $("#idParte").val();
            var idRepCotizacion = $("#idRepCotizacion").val();
            // var totalOcupada=total+ocupar;

             // alert(totalOcupada);
            if(cantidad>=ocupar){
                    $("#reporte").load('{{url('guardarAsignacion')}}' + '?r=' + Date.now() + $(this).closest('form').serialize()); 
                    document.getElementById('mensaje').style.display = 'none';
                    document.getElementById('mensajeDisponible').style.display = 'none';
                    $("#idRefaccion, #proveedor, #numFactura, #fecha, #cantidad, #piezasOcupar").val('');
                    $("#idRefaccion").load('{{url('comboCodigoPiezas')}}' + '?r=' + Date.now() + '&idParte=' + idParte);
                    // $("#cambio").load('{{url('cambiarBoton')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
                    // $("#idPendiente").load('{{url('cambiarPendiente')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
                    
                    
            }else{
                document.getElementById('mensajeDisponible').style.display = 'block';
                    e.preventDefault();
            }
        }
       
    }); 

    $(".borrarPieza").click(function(){
        // alert("borrando");
        var idParte = $("#idParte").val();
        var idRepCotizacion = $("#idRepCotizacion").val();
        $("#reporte").load('{{url('borrarAsignacion')}}' + '?r=' + Date.now() + $(this).closest('form').serialize()); 
        $("#idRefaccion").load('{{url('comboCodigoPiezas')}}' + '?r=' + Date.now() + '&idParte=' + idParte);
        // $("#cambio").load('{{url('cambiarBoton')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
        // $("#idPendiente").load('{{url('cambiarPendiente')}}' + '?r=' + Date.now() + '&idRepCotizacion=' + idRepCotizacion);
    });
});

</script>
@stop

