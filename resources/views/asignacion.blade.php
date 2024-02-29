@extends('principal')
@section('contenido')

<script type="text/javascript">
var porcentajes;
$(document).ready(function() {
    $("#fecha").change(function() {
        $("#carrito").load('{{url('reporteAsignacion')}}' + '?' + $(this).closest('form').serialize());
        $('#agrega').attr("disabled", false);
    });


    $('.borrar').click(function() {
        var llega = $("#porcentaje").val();
        porcentajes = parseInt(porcentajes) - parseInt(llega)
        // alert ('hai');
        formulario = this.form;
        $("#carrito").load('{{url('borraAsignacion')}}' + '?' + $(this).closest('form').serialize());
    });

});
</script>

<form>

    {{Form::open(['route' => 'asignacion','files'=>true])}}
    {{Form::token()}}

    <div class="col-xs-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Asignación de personal</h1>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-title">
                            Selecciona persona:
                        </div>
                        <div>
                            <input type="hidden" name='idFactura' id='idFactura' class="form-control rounded-0"
                                value='{{$factura->idFactura}}'>
                            <select name='idu' id='idu' class="form-control rounded-0">
                                @foreach($usuario as $us)
                                    @if($us->activo=="Si")
                                        <option value='{{$us->idu}}'>{{$us->tipo}} - {{$us->nombreUsuario}} {{$us->aPaterno}}
                                            {{$us->aMaterno}} </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-title">
                            Fecha de asignación:
                        </div>
                        <div>
                            <input type='date' name='fecha' id='fecha' class="form-control rounded-0">
                        </div>
                        <!-- <div class="sub-title">
								Porcentaje:
							</div> -->
                        <div>
                            <input type="hidden" name='porcentaje' disabled='false' id='porcentaje'
                                class="form-control rounded-0">
                        </div>
                    </div>
                </div>
                <b>
                    <div id='valida' style="color:#FF0000; text-align:center">
                    </div>
                </b>
                <div class="">
                    <br><button type="button" name="agrega" id="agrega" disabled='false'
                        class="btn btn-primary ">Asignar</button>
                    <a href="{{asset('reporteFacturasAsignadas')}}"><button type="button"
                            class="btn btn-default">Regresar</button></a>
                    <br>
                </div>
                <div id='carrito'>
                    <br>
                    <div class="row">
                        <div class="col-xs-6 col-md-4">
                            Cliente:{{Form::text('nombre',($consultaD->razonSocial),['class' => 'form-control','readonly'=> 'true'])}}
                        </div>
                        <div class="col-xs-6 col-md-4">
                            RFC:{{Form::text('nombre',($consultaD->rfc),['class' => 'form-control','readonly'=> 'true'])}}
                        </div>
                        <div class="col-xs-6 col-md-4">
                            Contacto:{{Form::text('nombre',($consultaD->contacto),['class' => 'form-control','readonly'=> 'true'])}}
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Puesto</th>
                                    <th>Usuario</th>
                                    <!-- <th>Porcentaje</th> -->
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultado as $r)
                                <tr>
                                    <td>{{$r->numeroFactura}}</td>
                                    <td>{{$r->tipo}}</td>
                                    <td>{{$r->nombreUsuario}} {{$r->aPaterno}} {{$r->aMaterno}}</td>
                                    <!-- <td>{{$r->porcentaje}}%</td> -->
                                    <td>
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                            name='frmdo{{$r->idAsigDet}}' id='frmdo{{$r->idAsigDet}}' target='_self'>
                                            <input type='hidden' value='{{$r->idAsigDet}}' name='idAsigDet'
                                                id='idAsigDet'>
                                            <input type='hidden' value='{{$r->idFactura}}' name='idFactura'
                                                id='idFactura'>
                                            <input type='button' name='borrar' class='borrar' id='borrar'
                                                value='Borrar' />
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
var porcentajes = 0;
$(document).ready(function() {
    function trunc(x, posiciones = 0) {
        var s = x.toString()
        var l = s.length
        var decimalLength = s.indexOf('.') + 1
        var numStr = s.substr(0, decimalLength + posiciones)
        return Number(numStr)
    }

    $("#porcentaje").keyup(function() {
        const limite = 100;
        porcentaje = parseInt($("#porcentaje").val());
        if (porcentaje > limite) {
            $("#valida").text('La asignacion no puede ser mayor al 100% ');
            $('#agrega').attr("disabled", true);
            porcentaje = 0;
        } else {
            $("#valida").text('');
            $('#agrega').attr("disabled", false);
            porcentaje = 0;
        }
    });

    $("#agrega").click(function() {
        //	alert ("hola");
        var llega = $("#porcentaje").val();
        porcentajes = parseInt(porcentajes) + parseInt(llega);
        var fechita = $("#fecha").val();
        var tope = $('#validtext').val();
        console.log(porcentajes);
        console.log(tope);
        if (fechita == '') {
            $("#valida").text('Error: Debe seleccionar una fecha')
            $('#agrega').attr("disabled", true);
        } else {
            $("#valida").text('')
            if (tope >= 100) {
                $('#valida').attr("display:inline");
                $("#valida").text('Error: Limite de asignación alcanzado.');
                $('#agrega').attr("disabled", true);
            } else {
                $('#agrega').attr("disabled", false);
                // }

                // $('#agrega').attr("disabled", false);
                // if(llega < 1){
                // 	$("#valida").text('Error: La asignación no puede ser menor al 1%.');
                // 	$('#agrega').attr("disabled", true);
                // }
                // else{
                // 	$('#agrega').attr("disabled", false);
                // }

                // if ( porcentajes > 100){
                // 	$('#agrega').attr("disabled", true);
                // 	$("#valida").text('Limite alcanzado: Verifica la distribución de los porcentajes.');
                // 	porcentajes = parseInt(porcentajes) - parseInt(llega) 
                // } 
                // else if(porcentajes > 0) {
                // $("#limite").val($("#limite").val()-$("#porcentaje").val());
                $("#carrito").load('{{url('carrito')}}' + '?' + $(this).closest('form').serialize());
            }

        }
    });

    $("#porcentaje").keyup(function() {
        var valor = $(this).val();
        var tope = $('#validtext').val();
        var limite = 100 - tope;
        var fechita = $("#fecha").val();
        //$('#agrega').attr("disabled", false);


        if (valor > limite) {
            $("#valida").text('Error: El valor supera el limite de asignación.');
            $('#agrega').attr("disabled", true);
        } else {
            $('#agrega').attr("disabled", false);
        }

    });
});
</script>
@stop