@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarViajes','files'=>true, 'id' => 'formid'])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>
                Modificar viaje
            </h1>
        </div><br>
        <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
        <div class="panel-body">

            <ul class="nav nav-tabs">
                <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Datos de salida</label></a></li>
                <li class="" id='tab2' style="visibility:visible;"><a href="#2" data-toggle="tab"><label for="">Servicios</label></a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="1">
                    <div class="form-group col-md-6">
                        <div class="row">
                            <h4>Datos de salida</h4>

                            <div class="sub-title">Usuariox:</div>
                            <div>
                                {{Form::text('usuarioViaje',($consulta->usuarioViaje),['class' => 'form-control', 'readonly' => 'true'])}}
                                {{Form::hidden('idViaje',$consulta->idViaje,['id'=>'idViaje'])}}
                                <!--Este cuadro no se vera en la vista ya que es tipo hidden "oculto" solo sera para obetener el ide a modificar y lo enviara al metodo editarUsuario-->
                                {{Form::hidden('idVehiculo',$consulta->idVehiculo)}}
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="sub-title">Fecha de Salida: </div>
                                    <div>
                                        {{Form::date('fechaInicio',$consulta->fechaInicio,['class' => 'form-control','id' => 'fechaInicio'])}}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="sub-title">Fecha de Finzalización: </div>
                                    <div>
                                        {{Form::date('fechaFin',$consulta->fechaFin,['class' => 'form-control'])}}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="sub-title">Tipo de viaje:</div>
                                    @if($errors->first('tipoViaje'))
                                    <i> {{ $errors->first('tipoViaje') }}</i>
                                    @endif
                                    @if ($consulta->tipoViaje == 'GCM')
                                    GCM {{Form::radio('tipoViaje','GCM', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Personal {{Form::radio('tipoViaje','Personal',false, [])}}&nbsp;&nbsp;&nbsp;
                                    CYM {{Form::radio('tipoViaje','CYM',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Proveedores {{Form::radio('tipoViaje','Proveedores',false, [])}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                    @if ($consulta->tipoViaje == 'Personal')
                                    GCM {{Form::radio('tipoViaje','GCM', false,[])}}&nbsp;&nbsp;&nbsp;
                                    Personal
                                    {{Form::radio('tipoViaje','Personal',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    CYM {{Form::radio('tipoViaje','CYM',false, [])}}&nbsp;&nbsp;&nbsp;
                                    Proveedores {{Form::radio('tipoViaje','Proveedores',false, [])}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                    @if ($consulta->tipoViaje == 'CYM')
                                    GCM {{Form::radio('tipoViaje','GCM', false,[])}}&nbsp;&nbsp;&nbsp;
                                    Personal {{Form::radio('tipoViaje','Personal',false,[])}}&nbsp;&nbsp;&nbsp;
                                    CYM {{Form::radio('tipoViaje','CYM',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    Proveedores {{Form::radio('tipoViaje','Proveedores',false, [])}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                    @if ($consulta->tipoViaje == 'Proveedores')
                                    GCM {{Form::radio('tipoViaje','GCM', false,[])}}&nbsp;&nbsp;&nbsp;
                                    Personal {{Form::radio('tipoViaje','Personal',false,[])}}&nbsp;&nbsp;&nbsp;
                                    CYM {{Form::radio('tipoViaje','CYM',false,[])}}&nbsp;&nbsp;&nbsp;
                                    Proveedores
                                    {{Form::radio('tipoViaje','Proveedores',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="sub-title">Monto del viaje:</div>
                                    <div>
                                        {{Form::text('montoGasto',($consulta->montoGasto),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5000'])}}
                                    </div>
                                </div>
                                <form>
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Empresa a visitar:</div>
                                        @if($errors->first('idc'))
                                        <i> {{ $errors->first('idc') }}</i>
                                        @endif
                                        <div>
                                            <select name='idc' id='idc' class="form-control" rows="5"
                                                class="form-control rounded-0">
                                                <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                                @foreach($cliente as $clienteu)
                                                @if($clienteu->idc== 302 || $clienteu->idc== 303)
                                                <option value='{{$clienteu->idc}}'>{{$clienteu->razonSocial}}</option>
                                                @endif
                                                @endforeach
                                                @foreach($clientec as $cli)
                                                @if($cli->activo=="si")
                                                <option value='{{$cli->idc}}'>{{$cli->razonSocial}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="sub-title">Sucursal:</div>
                                        @if($errors->first('sucursal'))
                                        <i> {{ $errors->first('sucursal') }}</i>
                                        @endif
                                        <div id='combop'>
                                            <select name='idSucursal' id='idp' class="form-control">
                                                <option value='{{$idSucursal}}'>{{$nomsuc}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <br>
                                <div id='divinfo' class="alert alert-info">
                                Cliente: {{$nomcli}} ------- Sucursal: {{$nomsuc}}
                                </div>

                            <div class="sub-title">Motivo:</div>
                            <div>
                                <textarea name="motivoViaje" rows="5"
                                    class="form-control rounded-0">{!!($consulta->motivoViaje)!!}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <h4>Estado del vehículo</h4>
                        <div class="sub-title">Vehiculo:</div>
                        <div>
                            <select name='idVehiculo' class="form-control" id="opcVehiculo">
                                <option value='{{$idVehiculosel}}'>{{$nomVehiculo}} {{$tipoV}} {{$placas}}</option>
                                @foreach($vehiculo as $v)
                                @if($v->activo=="Si")
                                <option value='{{$v->idVehiculo}}'>{{$v->nombreVehiculo}} {{$v->tipoVehiculo}}
                                    {{$v->placas}} {{$v->idMarca}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="sub-title">Evidencia Km y gasolina salida:</div>
                                <div>
                                    @foreach($consultaImg as $consImg)
                                    <img src="{{asset ('/archivos/'.$consImg->fotoEvidenciaSalida)}}" height=60 width=60>
                                    <!---archivo---->
                                    @endforeach <br><br>

                                    {{Form::file('fotoEvidenciaSalida',['class' => 'form-control'])}}

                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">Kilometraje inicial: </div>
                                <div>
                                    {{Form::text('kmActual',($consulta->kmActual),['class' => 'form-control', 'readonly' => 'true', 'id' => 'recibeKm'])}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="sub-title">Evidencia Km y gasolina entrega:</div>
                                <div>
                                    @foreach($consultaImg2 as $consImg2)
                                    <img src="{{asset ('/archivos/'.$consImg2->fotoEvidenciaRegreso)}}" height=60
                                        width=60>
                                    <!---archivo---->
                                    @endforeach <br><br>
                                    {{Form::file('fotoEvidenciaRegreso',['class' => 'form-control'])}}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="sub-title">Kilometraje Final: </div>
                                <div>
                                    @if($errors->first('kmFinal'))
                                    <i> {{ $errors->first('kmFinal') }}</i>
                                    @endif
                                    {{Form::text('kmFinal',($consulta->kmFinal),['class' => 'form-control','id' => 'idKmFin'])}}
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="sub-title">Parabrisas:</div>
                                @if($errors->first('parabrisas'))
                                <i> {{ $errors->first('parabrisas') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->parabrisas == 'Si')
                                    Si {{Form::radio('parabrisas','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('parabrisas','No',false, [])}}
                                    @else
                                    Si {{Form::radio('parabrisas','Si', true, []) }}
                                    No {{Form::radio('parabrisas','No',false, ['checked' => 'checked'])}}
                                    @endif
                                </div>
                                <div class="sub-title">Luces:</div>
                                @if($errors->first('lucesSalida'))
                                <i> {{ $errors->first('lucesSalida') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->lucesSalida == 'Si')
                                    Si {{Form::radio('lucesSalida','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('lucesSalida','No',false, [])}}
                                    @else
                                    Si {{Form::radio('lucesSalida','Si', true, []) }}
                                    No {{Form::radio('lucesSalida','No',false, ['checked' => 'checked'])}}
                                    @endif
                                </div>
                                <div class="sub-title">Anticongelante:</div>
                                @if($errors->first('anticongelante'))
                                <i> {{ $errors->first('anticongelante') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->anticongelante == 'Si')
                                    Si {{Form::radio('anticongelante','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('anticongelante','No',false, [])}}
                                    @else
                                    Si {{Form::radio('anticongelante','Si', true, []) }}
                                    No {{Form::radio('anticongelante','No',false, ['checked' => 'checked'])}}
                                    @endif
                                </div>

                                <div class="sub-title">Liquido de frenos:</div>
                                @if($errors->first('liquidoFrenos'))
                                <i> {{ $errors->first('liquidoFrenos') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->liquidoFrenos == 'Si')
                                    Si {{Form::radio('liquidoFrenos','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('liquidoFrenos','No',false, [])}}
                                    @else
                                    Si {{Form::radio('liquidoFrenos','Si', true, []) }}
                                    No {{Form::radio('liquidoFrenos','No',false, ['checked' => 'checked'])}}
                                    @endif
                                </div>
                                <div class="sub-title">Aire de llantas:</div>
                                @if($errors->first('llantasSalida'))
                                <i> {{ $errors->first('llantasSalida') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->llantasSalida == 'Si')
                                    Si {{Form::radio('llantasSalida','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('llantasSalida','No',false, [])}}
                                    @else
                                    Si {{Form::radio('llantasSalida','Si', true, []) }}
                                    No {{Form::radio('llantasSalida','No',false, ['checked' => 'checked'])}}
                                    @endif
                                </div>
                                <div class="sub-title">Unidad limpia salida:</div>
                                @if($errors->first('unidadLimpia'))
                                <i> {{ $errors->first('unidadLimpia') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->unidadLimpia == 'Si')
                                    Si {{Form::radio('unidadLimpia','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('unidadLimpia','No',false, [])}}
                                    @else
                                    Si {{Form::radio('unidadLimpia','Si', true, []) }}
                                    No {{Form::radio('unidadLimpia','No',false, ['checked' => 'checked'])}}
                                    @endif
                                </div>

                                <div class="sub-title">Observaciones de salida de la unidad:</div>
                                <div>
                                    <textarea name="observacionSalida" rows="5"
                                        class="form-control rounded-0">{!!($consulta->observacionSalida)!!}</textarea>
                                </div>

                            </div>


                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="sub-title">Golpes:</div>
                                    @if($errors->first('golpes'))
                                    <i> {{ $errors->first('golpes') }}</i>
                                    @endif
                                    <div>
                                        @if ($consulta->golpes == 'Si')
                                        Si {{Form::radio('golpes','Si', true, ['checked' => 'checked']) }}
                                        No {{Form::radio('golpes','No',false, [])}}
                                        @else
                                        Si {{Form::radio('golpes','Si', true, []) }}
                                        No {{Form::radio('golpes','No',false, ['checked' => 'checked'])}}
                                        @endif
                                    </div>
                                    <div class="sub-title">Rayaduras:</div>
                                    @if($errors->first('rayaduras'))
                                    <i> {{ $errors->first('rayaduras') }}</i>
                                    @endif
                                    <div>
                                        @if ($consulta->rayaduras == 'Si')
                                        Si {{Form::radio('rayaduras','Si', true, ['checked' => 'checked']) }}
                                        No {{Form::radio('rayaduras','No',false, [])}}
                                        @else
                                        Si {{Form::radio('rayaduras','Si', true, []) }}
                                        No {{Form::radio('rayaduras','No',false, ['checked' => 'checked'])}}
                                        @endif
                                    </div>
                                    <div class="sub-title">Aire de llantas:</div>
                                    @if($errors->first('llantasEntrega'))
                                    <i> {{ $errors->first('llantasEntrega') }}</i>
                                    @endif
                                    <div>
                                        @if ($consulta->llantasEntrega == 'Si')
                                        Si {{Form::radio('llantasEntrega','Si', true, ['checked' => 'checked']) }}
                                        No {{Form::radio('llantasEntrega','No',false, [])}}
                                        @else
                                        Si {{Form::radio('llantasEntrega','Si', true, []) }}
                                        No {{Form::radio('llantasEntrega','No',false, ['checked' => 'checked'])}}
                                        @endif
                                    </div>
                                    <div class="sub-title">Luces:</div>
                                    @if($errors->first('lucesEntrega'))
                                    <i> {{ $errors->first('lucesEntrega') }}</i>
                                    @endif
                                    <div>
                                        @if ($consulta->lucesEntrega == 'Si')
                                        Si {{Form::radio('lucesEntrega','Si', true, ['checked' => 'checked']) }}
                                        No {{Form::radio('lucesEntrega','No',false, [])}}
                                        @else
                                        Si {{Form::radio('lucesEntrega','Si', true, []) }}
                                        No {{Form::radio('lucesEntrega','No',false, ['checked' => 'checked'])}}
                                        @endif
                                    </div>
                                    <div class="sub-title">Unidad limpia regreso:</div>
                                @if($errors->first('unidadLimpiaEntrega'))
                                <i> {{ $errors->first('unidadLimpiaEntrega') }}</i>
                                @endif
                                <div>
                                    @if ($consulta->unidadLimpiaEntrega == 'Si')
                                    Si {{Form::radio('unidadLimpiaEntrega','Si', true, ['checked' => 'checked']) }}
                                    No {{Form::radio('unidadLimpiaEntrega','No',false, [])}}
                                    @else
                                    Si {{Form::radio('unidadLimpiaEntrega','Si', true, []) }}
                                    No {{Form::radio('unidadLimpiaEntrega','No',false, ['checked' => 'checked'])}}
                                    @endif
                                </div>
                                    <div class="sub-title">Observaciones de entrega de la unidad:</div>
                                    <div>
                                        <textarea name="observacionEntrega" rows="5"
                                            class="form-control rounded-0">{!!($consulta->observacionEntrega)!!}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>        
                </div>

                <div class="tab-pane fade" id="2">                    
                    <div class="col-md-12">
                        <center>
                            <h4>Asignacion de viajes a servicios</h4>
                        </center>
                    </div>

                    <div>
                        <br><br>
                        <div class="col-md-1">
                            <b>Servicios:</b>
                        </div>
                        <div class="col-md-9">
                            <select name="" id="comboServicios" class="form-control">
                                <option value="">Seleccionar servicio</option>
                                @foreach($comboFacturas as $comboFacturas)
                                    <option value='{{$comboFacturas->idServicios}}'>{{$comboFacturas->idServicios}} - {{$comboFacturas->cliente}} - {{$comboFacturas->sucursal}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success btn-default" id="asignarServicio">
                                <span class="glyphicon glyphicon-plus-sign"></span> Asignar
                            </button>
                        </div>
                    </div>
                    <br><br><br>
                    <div id="reporteServiciosAgregados" style="text-align:center">
                        @if(count($consultaGastos)>0)
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="width:90px;background-color: #C5EBFB;text-align:center">Folio de servicio</th>
                                        <th style="background-color: #C5EBFB;text-align:center">Cliente</th>
                                        <th style="background-color: #C5EBFB;text-align:center">sucursal</th>            
                                        <th style="background-color: #C5EBFB;text-align:center">Monto</th>
                                        <th style="background-color: #C5EBFB;text-align:center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultaGastos as $consultaGastos)
                                    <tr>
                                        <td>{{$consultaGastos->idServicios}}</td>
                                        <td style="text-align:left">{{$consultaGastos->cliente}}</td>
                                        <td style="text-align:left">{{$consultaGastos->sucursal}}</td>
                                        <td>{{$consultaGastos->total}}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger eliminarAsignacion" value="{{$consultaGastos->id}}">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                        <div class="alert alert-warning" role="alert" align="center">
                            <label for="">Sin asignaciones</label>                            
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-default','id' => 'guardar'])}}
            <a href="{{asset('reporteViajes')}}"><button type="button" class="btn btn-default">Cancelar</button></a>
        </center>
        <br>
        
    </div>

    <br>
</div>


<script>
    $("#asignarServicio").click(function() {
        var idServicios = $("#comboServicios").val();
        var fechaInicio = $("#fechaInicio").val();
        var idViaje = $("#idViaje").val();
        
        if (idServicios == '') {
            $("#comboServicios").css('border-color', 'red');
            $("#comboServicios").css('color', 'red');
            alert('Error: Se debe seleccionar un servicio.')
        } else {
            $("#reporteServiciosAgregados").text('cargando..');
            $('#reporteServiciosAgregados').load('{{url('asignarServicioAviaje')}}' + '?r=' + Date.now() + '&idServicios=' + idServicios + '&fechaInicio=' + fechaInicio + '&idViaje=' + idViaje);           
        }
        
    });
    
    $(".eliminarAsignacion").click(function() {
        $('.eliminarAsignacion').attr("disabled", true);
        $("#reporteServiciosAgregados").load('{{url('eliminarServicioAviaje')}}' + '?' +'idGasto='+ $(this).val());
    });
</script>

<script>
    $(document).ready(function() {
        $('#opcVehiculo').change(function() {
            var kilometraje = $("#opcVehiculo :selected").attr("name");
            console.log(kilometraje);
            $("#recibeKm").val(kilometraje);
        });

        $("#idc").change(function() {
            $("#idp").load('{{url('comboca')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
        });
        $("#idp").change(function() {
            $("#divinfo").load('{{url('info')}}' + '?' + $(this).closest('form').serialize());
            $("#divinfo").show("slow");
        });

        $('#formid').on('submit', function(e) {

            var va = validaDatos();
            console.log(va);
            if (va) {
                console.log('todo bien');
            } else {
                alert('ERROR: El Kilometraje Final debe ser mayor al Kilmetraje incial')
                e.preventDefault();
            }
        })

        function validaDatos() {

            var kmIni = $('#recibeKm').val();
            var x = parseInt(kmIni);
            console.log(kmIni);
            var a = $('#idKmFin').val();
            var y = parseInt(a);
            console.log(y);
            if (x < y) {
                return true;
            } else {
                return false;
            }
        }

    })
</script>

@stop