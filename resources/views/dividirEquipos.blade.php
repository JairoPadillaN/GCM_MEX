@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        $("#crearParte").load('{{url('altaDividirParteAbajo')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });

    $(".editar").click(function() {
        $("#crearParte").load('{{url('editarParteAbajo')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });

    $(".borrar").click(function() {
        $("#reportitoDePartesCreadas").load('{{url('borrarParteCreada')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });

    $(".servicios").click(function() {
        $("#crearParte").load('{{url('serviciosParteCreada')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });

});
</script>

<form method='POST'>
    <div class="col-xs-12">
        <div class="panel panel-default" style="margin-top:-55px">
            <div class="panel-heading">
                <h1>División de equipos en partes para reparación</h1><br>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4">
                        Folio de recepción del
                        equipo:{{Form::text('folioRecepcionReporte',$consultap->folioRecepcion,['class' => 'form-control','readonly'])}}
                        Empresa que da el
                        seguimiento:{{Form::text('nombreEmpresaReporte',$consultap->nombreEmpresa,['class' => 'form-control','readonly'])}}
                        Registrador
                        por:{{Form::text('usuarioEquipos',$consultap->usuarioEquipos,['class' => 'form-control','readonly'])}}
                    </div>
                    <div class="col-lg-4">
                        Cliente:{{Form::text('razonSocial',$consultap->razonSocial,['class' => 'form-control','readonly'])}}
                        sucursal:{{Form::text('sucursal',$consultap->sucursal,['class' => 'form-control','readonly'])}}
                        GCM ID:{{Form::text('gcmid',$consultap->gcmid,['class' => 'form-control','readonly'])}}
                        {{Form::hidden('idEquipos',$consultap->idEquipos,['class' => 'form-control','readonly'])}}

                    </div>
                    <div class="col-lg-4">
                        Tipo:{{Form::text('tipoEquipo',$consultap->tipoEquipo,['class' => 'form-control','readonly'])}}
                        Subclasificación:{{Form::text('subtipoEquipo',$consultap->subtipoEquipo,['class' => 'form-control','readonly'])}}
                        Serie:{{Form::text('serie',$consultap->serie,['class' => 'form-control','readonly'])}}
                    </div>
                </div>


                <div class="row">                    
                    <div class="col-lg-8">
                        Datos de certificado:
                        <textarea rows="4" class="form-control rounded-0" readonly>{{$consultap->datosCertificado}}</textarea>                                      
                    </div>
                    <div class="col-lg-4">
                        Referencia del cliente:{{Form::text('recurso',$consultap->recurso,['class' => 'form-control','readonly'])}}
                    </div>
                </div>                
                <br>
                <div class="" align="right">

                @if($consultap->equipoActivo == 'Cerrado')
                        <a href="{{asset('reporteEquipos')}}">
                            <button type="button" class="btn btn-default">Regresar</button>
                        </a>
                @else
                    @if($stipo=='Tecnico')
                        <a href="{{asset('reporteEquipos')}}">
                            <button type="button" class="btn btn-default">Regresar</button>
                        </a>
                    @else
                        <button type="button" class="btn btn-success btn-default" id="agrega">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                        </button>
                        <a href="{{asset('reporteEquipos')}}">
                            <button type="button" class="btn btn-default">Regresar</button>
                        </a>
                    @endif
                @endif
                </div>
                <br>
                <div id='reportitoDePartesCreadas'>
                    @if($cuantasPartes < 1) <div class="alert alert-warning" role="alert" align="center">
                        <label for="">Este equipo no se ha dividido en partes</label>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr style=" background-color: #C5EBFB;">
                                <th><center>Foto</center></th>
                                <th>GCM ID PARTE</th>
                                <th>Nombre</th>
                                <th>Fecha salida</th>
                                <th>Fecha llegada</th>
                                <th>Taller</th>
                                <th>Tipo de servicio</th>
                                <th>Estatus</th>
                                <th>
                                    <center>Operaciones</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultaPartesCreadas as $cpc)
                            <tr>
                                <td>
                                    @if($cpc->fotoParte=='Sin archivo')
                                        <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                    @else
                                        <a target="_blank" href="{{asset ('archivos/'.$cpc->fotoParte)}}">
                                            <img src="{{asset ('archivos/'.$cpc->fotoParte)}}" height=80 width=80>
                                        </a>
                                    @endif
                                </td>
                                <td>{{$cpc->GCMidParte}}</td>
                                <td>{{$cpc->nombreParte}}</td>
                                <td>{{$cpc->fechaSalida}}</td>
                                <td>{{$cpc->fechaRecepcion}}</td>
                                <td>{{$cpc->nombreTaller}}</td>
                                <td>{{$cpc->prioridadRep}}</td>
                                <td>{{$cpc->nombreEstatus}}</td>
                                <td align="center">
                                    <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                    @if($consultap->equipoActivo == 'Cerrado')
                                        <input type="hidden" value="{{$cpc->idParte}}" name="idParteServicios" id='parServ'>
                                        <button type="button" class="btn btn-sm btn-warning servicios">
                                            <i class="glyphicon glyphicon-wrench" aria-hidden="true"></i>
                                        </button>
                                
                                        <input type="hidden" value="{{$cpc->idParte}}" name="idParte" id='par'>
                                        @if($stipo != 'Tecnico')
                                        <button type="button" class="btn btn-sm btn-info editar">
                                            <i class="ace-icon fa fa-pencil bigger"></i>
                                        </button>
                                        @endif
                                    @else
                                        <input type="hidden" value="{{$cpc->idParte}}" name="idParteServicios" id='parServ'>
                                        <button type="button" class="btn btn-sm btn-warning servicios">
                                            <i class="glyphicon glyphicon-wrench" aria-hidden="true"></i>
                                        </button>
                                
                                        <input type="hidden" value="{{$cpc->idParte}}" name="idParte" id='par'>
                                        @if($stipo != 'Tecnico')
                                        <button type="button" class="btn btn-sm btn-info editar">
                                            <i class="ace-icon fa fa-pencil bigger"></i>
                                        </button>
                                        @endif

                                        <input type="hidden" value="{{$cpc->idParte}}" name="idParteBorrar" id='parB'>
                                        <input type="hidden" value="{{$cpc->idEquipos}}" name="idEquipos" id='parB'>
                                        @if($stipo != 'Tecnico')
                                        <button type="button" class="btn btn-sm btn-danger borrar">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                    @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            <div id='crearParte'>
            </div>
        </div>
    </div>
    </div>
</form>
@stop