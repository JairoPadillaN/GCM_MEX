@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        // alert("java");
        $("#usuariosSeg").load('{{url('seguimientoUsuarios')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });

    $('.borrar').click(
        function() {
            // alert("borrar");
            $("#usuariosSeg").load('{{url('borrarSeg')}}' + '?' + $(this).closest('form').serialize());
        }
    );
    $('#idu').change(function() {
            $("#correo").load('{{url('correoUsuario')}}' + '?r=' + Date.now() + '&idu=' + this.options[this.selectedIndex].value);
        }
    );
});
</script>



<form method='POST'>
@csrf
    <div class="col-xs-12" style="margin-top:-55px">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>Asignar usuarios a la actividad</h1><br>
            </div>

            <div class="panel-body">

                <div class="form-group col-md-6">
                    <input type="hidden" name="idSegActividad" id="idSegActividad" class="form-control" readonly="true" value="{{$consulta->idSegActividad}}">
                    <div class="sub-title">Asigna un usuario:</div>
                    <div>
                        <select name='idu' id="idu" class="form-control">
                            <option value="">Selecciona un usuario</option>
                            @foreach($usuarios as $cu)
                            @if($cu->activo=="Si")
                            <option value='{{$cu->idu}}'>{{$cu->nombreUsuario}} {{$cu->aPaterno}} {{$cu->aMaterno}} -
                                {{$cu->tipo}}</option>
                            @endif
                            @endforeach

                        </select>
                    </div>
                    <input type="hidden" id="correo" name="correo">

                    <div class="sub-title">¿Qué realizará?:</div>
                        <div>
                            @if($errors->first('queRealiza'))
                            <p><i> {{ $errors->first('queRealiza') }}</i></p>
                            @endif
                            {{Form::text('queRealiza',old ('queRealiza'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Revisiones'])}}
                        </div>
                    <br>
                    <div class="" align="right">
                        <button type="button" class="btn btn-success btn-default" id="agrega">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                        </button>
                    </div>

                    <div id="usuariosSeg">
                        @if($cuantosSeg == 0)
                        <br>
                        <div class="alert alert-warning" role="alert" align="center">
                            <label for="">Sin usuarios asignados</label>
                        </div>
                        @else
                        <br>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #E8E8E8;">
                                        <th>
                                            <font SIZE=2>Nombre usuario</font>
                                        </th>
                                        <th>
                                            <font SIZE=2>Tipo de usuario</font>
                                        </th>
                                        <th>
                                            <font SIZE=2>¿Qué realizará</font>
                                        </th>
                                        <th>
                                            <font SIZE=2>Opciones</font>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seguimiento as $sg)
                                    <tr>
                                        <td>{{$sg->nombreUsuario}} {{$sg->aPaterno}} {{$sg->aMaterno}}</td>
                                        <td>{{$sg->tipo}}</td>
                                        <td>{{$sg->queRealiza}}</td>

                                        <td align="center">
                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                                name='frmdo{{$sg->idAsigSeguimiento}}'
                                                id='frmdo{{$sg->idAsigSeguimiento}}' target='_self'>
                                                <input type='hidden' value='{{$sg->idAsigSeguimiento}}'
                                                    name='idAsigSeguimiento' id='idAsigSeguimiento'>
                                                <input type='hidden' value='{{$sg->idSegActividad}}'
                                                    name='idSegActividad' id='idSegActividad'>
                                                <button type="button" class="btn btn-sm btn-danger borrar">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                    <center>
                        <a href="{{asset('reporteSeguimientoVista')}}"><button type="button" class="btn btn-default">Regresar</button></a>
                    </center><br>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>
@stop