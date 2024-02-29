@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();

    $('#agregarUsuario').click(function() {
        var idu = $("#idu").val();
        var iduAsignado = $("#iduAsignado").val();
        $('#reporteUsuarios').load('{{url('asignarUsuarios')}}' + '?r=' + Date.now() + '&idu=' + idu + '&iduAsignado=' + iduAsignado);


    });

    $('.borrar').click(
        function() {
            // alert("borrar");
            $("#reporteUsuarios").load('{{url('borrarUsuariosAsignados')}}' + '?' + $(this).closest('form').serialize());
        });
});
</script>

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificación de usuarios</h1><br><br>
            <!-- 
            <div class="card-title">
                <div class="title">Datos personales</div>
            </div> -->
        </div>

        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home">Datos personales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Usuarios a cargo</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <form action="{{ route('editarUsuario') }}" method='POST' enctype='multipart/form-data'
                        id="formularioUs">
                        @csrf
                        <div class="form-group col-md-6">
                            <div class="sub-title">Nombre:</div>
                            <div>
                                @if($errors->first('nombreUsuario'))
                                <p><i> {{ $errors->first('nombreUsuario') }}</i></p>
                                @endif
                                {{Form::hidden('idu',$consulta->idu,['class' => 'form-control', 'id'=>'idu'])}}
                                <!--Este cuadro no se vera en la vista ya que es tipo hidden "oculto" solo sera para obetener el ide a modificar y lo enviara al metodo editarUsuario-->
                                {{Form::text('nombreUsuario',($consulta->nombreUsuario),['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis ó Juan José'])}}
                            </div>

                            <div class="sub-title">Apellido Paterno:</div>
                            <div>
                                @if($errors->first('aPaterno'))
                                <p><i> {{ $errors->first('aPaterno') }}</i></p>
                                @endif
                                {{Form::text('aPaterno',($consulta->aPaterno),['class' => 'form-control', 'placeholder' => 'Ejemplo: López'])}}
                            </div>

                            <div class="sub-title">Apellido Materno:</div>
                            <div>
                                @if($errors->first('aMaterno'))
                                <p><i> {{ $errors->first('aMaterno') }}</i></p>
                                @endif
                                {{Form::text('aMaterno',($consulta->aMaterno),['class' => 'form-control', 'placeholder' => 'Ejemplo: Rosas'])}}
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="sub-title">Correo electronico:</div>
                            <div>
                                @if($errors->first('correo'))
                                <p><i> {{ $errors->first('correo') }}</i></p>
                                @endif
                                {{Form::text('correo',($consulta->correo),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                            </div>

                            <div class="sub-title">Contraseña:</div>
                            <div>
                                @if($errors->first('password'))
                                <p><i> {{ $errors->first('password') }}</i></p>
                                @endif
                                {{Form::text('password',old('password'),['class' => 'form-control'])}}
                            </div>

                            <div class="sub-title">Foto de perfil:</div>
                            <div>
                                @foreach($consultaImg as $c)
                                <img src="{{asset ('archivos/'.$c->imagen)}}" height=50 width=50
                                    style="margin:10px; margin-top:-1px">
                                <!---archivo---->
                                @endforeach
                                {{Form::file('imagen',['class' => 'form-control rounded-0'])}}
                            </div>

                            <div class="sub-title">Tipo</div>
                            <div>
                                <select class="form-control rounded-0" name="tipo">
                                    <option value="<?php echo ($consulta->tipo) ?>"><?php echo ($consulta->tipo) ?>
                                    </option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Chofer">Chofer</option>
                                    <option value="Tecnico">Tecnico</option>
                                    <option value="Vendedor">Vendedor</option>
                                    <option value="Compras">Compras</option>
                                    <option value="Almacen">Almacén</option>
                                </select>
                            </div>
                            <div class="sub-title">Empresa a la que dará seguimiento:</div>
                            @if($errors->first('empresaSeguimiento'))
                            <i> {{ $errors->first('empresaSeguimiento') }}</i>
                            @endif
                            <div>
                                @if ($consulta->empresaSeguimiento == 'GCM')
                                GCM{{Form::radio('empresaSeguimiento','GCM', true, ['checked' => 'checked','id'=>'empresaSeguimiento']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                CyM
                                {{Form::radio('empresaSeguimiento','CYM',false, [ 'id'=>'empresaSeguimiento'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                GCM y CyM
                                {{Form::radio('empresaSeguimiento','GCMyCYM',false, [ 'id'=>'empresaSeguimiento'])}}

                                @elseif($consulta->empresaSeguimiento == 'CYM')
                                GCM{{Form::radio('empresaSeguimiento','GCM', false, ['id'=>'empresaSeguimiento']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                CyM
                                {{Form::radio('empresaSeguimiento','CYM',true, ['checked' => 'checked','id'=>'empresaSeguimiento'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                GCM y CyM
                                {{Form::radio('empresaSeguimiento','GCMyCYM',false, [ 'id'=>'empresaSeguimiento'])}}

                                @elseif($consulta->empresaSeguimiento == 'GCMyCYM')
                                GCM{{Form::radio('empresaSeguimiento','GCM', false, ['id'=>'empresaSeguimiento']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                CyM
                                {{Form::radio('empresaSeguimiento','CYM',true, ['checked' => 'checked','id'=>'empresaSeguimiento'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                GCM y CyM
                                {{Form::radio('empresaSeguimiento','GCMyCYM',false, [ 'id'=>'empresaSeguimiento'])}}

                                @else
                                GCM{{Form::radio('empresaSeguimiento','GCM', false, ['id'=>'empresaSeguimiento']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                CyM
                                {{Form::radio('empresaSeguimiento','CYM',false, ['id'=>'empresaSeguimiento'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                GCM y CyM
                                {{Form::radio('empresaSeguimiento','GCMyCYM',false, [ 'id'=>'empresaSeguimiento'])}}
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="card-title">
                                            <div class="title">Dirección</div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-md-6">
                                            <div class="sub-title">Calle:</div>
                                            <div>
                                                @if($errors->first('calle'))
                                                <p><i> {{ $errors->first('calle') }}</i></p>
                                                @endif
                                                {{Form::text('calle',($consulta->calle),['class' => 'form-control', 'placeholder' => 'Ejemplo: Hacienda San Diego '])}}
                                            </div>

                                            <div class="sub-title">Numero:</div>
                                            <div>
                                                @if($errors->first('num'))
                                                <p><i> {{ $errors->first('num') }}</i></p>
                                                @endif
                                                {{Form::text('num',($consulta->num),['class' => 'form-control', 'placeholder' => 'Ejemplo: 127'])}}
                                            </div>

                                            <div class="sub-title">Colonia:</div>
                                            <div>
                                                @if($errors->first('colonia'))
                                                <p><i> {{ $errors->first('colonia') }}</i></p>
                                                @endif
                                                {{Form::text('colonia',($consulta->colonia),['class' => 'form-control', 'placeholder' => 'Ejemplo: Lázaro Cárdenas'])}}
                                            </div>

                                            <div class="sub-title">Municipio:</div>
                                            <div>
                                                @if($errors->first('municipio'))
                                                <p><i> {{ $errors->first('municipio') }}</i></p>
                                                @endif
                                                {{Form::text('municipio',($consulta->municipio),['class' => 'form-control', 'placeholder' => 'Ejemplo: Lerma'])}}
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <div class="sub-title">Estado:</div>
                                            <div>
                                                @if($errors->first('estado'))
                                                <p><i> {{ $errors->first('estado') }}</i></p>
                                                @endif
                                                {{Form::text('estado',($consulta->estado),['class' => 'form-control', 'placeholder' => 'Ejemplo: Estado de México'])}}
                                            </div>

                                            <div class="sub-title">Telefono de Casa:</div>
                                            <div>
                                                @if($errors->first('telCasa'))
                                                <p><i> {{ $errors->first('telCasa') }}</i></p>
                                                @endif
                                                {{Form::text('telCasa',($consulta->telCasa),['class' => 'form-control', 'placeholder' => 'Ejemplo: 7282801927'])}}
                                            </div>

                                            <div class="sub-title">Telefono Celular:</div>
                                            <div>
                                                @if($errors->first('telCel'))
                                                <p><i> {{ $errors->first('telCel') }}</i></p>
                                                @endif
                                                {{Form::text('telCel',($consulta->telCel),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5501928384'])}}
                                            </div>

                                            <div class="sub-title">licencia:</div>
                                            <div>
                                                @if($errors->first('licencia'))
                                                <p><i> {{ $errors->first('licencia') }}</i></p>
                                                @endif
                                                {{Form::text('licencia',($consulta->licencia),['class' => 'form-control', 'placeholder' => 'Ejemplo: 480000000000'])}}
                                            </div>
                                        </div>

                                        <br>
                                        <center>
                                            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                                            <a href="{{asset('ReporteUsuarios')}}"><button type="button"
                                                    class="btn btn-default">Cancelar</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>


                <!-- tab de usuarios asignados -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                    <div class="form-group col-md-6">
                        <div class="sub-title">Asignar usuario:</div>
                        <div>
                            <select name='iduAsignado' id='iduAsignado' class="form-control">
                                <option value=''>Selecciona un usuario</option>
                                @foreach($usuarios as $u)
                                <option value="{{$u->idu}}">{{$u->usuario}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <br><br>
                        <button type="button" class="btn btn-success" id="agregarUsuario">
                            <span class="glyphicon glyphicon-plus-sign"></span> Asignar usuario
                        </button>
                    </div>
                    <div class="form-group col-md-8">
                        <div id="reporteUsuarios">
                            @if($cuantos == 0)
                            <br>
                            
                            <div class="alert alert-warning" role="alert" align="center">
                                <label for="">Sin usuarios asignados</label>
                            </div> 
                            @else
                            <br>
                            <!-- TIENE USUARIOS -->
                            <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #E8E8E8;">
                                        <th width = "90%">
                                            <font SIZE=2>Nombre usuario</font>
                                        </th>
                                        <th width = "10%">
                                            <font SIZE=2>Opciones</font>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($usuariosAsignados as $uA)
                                    <tr>
                                        <td>{{$uA->usuario}}</td>
                                        <td align="center">
                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                                name='frmdo{{$uA->idACargo}}' id='frmdo{{$uA->idACargo}}' target='_self'>
                                                <input type='hidden' value='{{$uA->idACargo}}' name='idACargo' id='idACargo'>
                                                <input type='hidden' value='{{$uA->idu}}' name='idu' id='idu'>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop