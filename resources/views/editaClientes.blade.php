@extends('principal')
@section('contenido')
<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
});
</script>
{{Form::open(['route' => 'modificaClientes2','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificación de clientes <small>* Campos Requeridos</small></h1><br><br>
            <div class="card-title">
                <div class="title"></div>
            </div>
        </div>
        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home" aria-selected="true">Datos del cliente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Actividades</a>
                </li>    
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <!-- Tab Datos del Cliente -->
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="form-group col-md-6">
                        <div class="sub-title">* Razón social:</div>
                        <div>
                            @if($errors->first('razonSocial'))
                            <p><i> {{ $errors->first('razonSocial') }}</i></p>
                            @endif
                            {{Form::hidden('idc',$consulta->idc)}}
                            {{Form::text('razonSocial',($consulta->razonSocial),['class' => 'form-control', 'placeholder' => 'Ejemplo: Coca Cola'])}}
                        </div>

                        <div class="sub-title">Número de proveedor:</div>
                        <div>
                            @if($errors->first('numeroProveedor'))
                            <p><i> {{ $errors->first('numeroProveedor') }}</i></p>
                            @endif
                            {{Form::hidden('idc',$consulta->idc)}}
                            {{Form::text('numeroProveedor',($consulta->numeroProveedor),['class' => 'form-control', 'placeholder' => 'Ejemplo: 000129'])}}
                        </div>

                        <div class="sub-title">* RFC:</div>
                        <div>
                            @if($errors->first('rfc'))
                            <p><i> {{ $errors->first('rfc') }}</i></p>
                            @endif
                            {{Form::hidden('idc',$consulta->idc)}}
                            {{Form::text('rfc',($consulta->rfc),['class' => 'form-control', 'placeholder' => 'Ejemplo: COCA23432CFD'])}}
                        </div>
                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->

                        <!--Aqui inicia el cuadro de textoque se vera en el formulario-->
                        <div class="sub-title">* Contacto:</div>
                        <div>
                            @if($errors->first('contacto'))
                            <p><i> {{ $errors->first('contacto') }}</i></p>
                            @endif
                            {{Form::text('contacto',($consulta->contacto),['class' => 'form-control', 'placeholder' => 'Ejemplo: 7223981764'])}}
                        </div>
                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
                        <div class="sub-title">*Días de Pago:</div>
                        <div>
                            <select class="form-control rounded-0" name="diasDePago">
                                <option value="<?php echo ($consulta->diasDePago) ?>"><?php echo ($consulta->diasDePago) ?>
                                </option>
                                <option value="0">0</option>
                                <option value="7">7</option>                        
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                                <option value="60">60</option>
                                <option value="90">90</option>
                            </select>
                        </div>



                    </div>

                    <div class="form-group col-md-6">


                        <div class="sub-title">* Calle:</div>
                        <div>
                            @if($errors->first('calle'))
                            <p><i> {{ $errors->first('calle') }}</i></p>
                            @endif
                            {{Form::text('calle',($consulta->calle),['class' => 'form-control', 'placeholder' => 'Ejemplo: Libertad'])}}
                        </div>

                        <div class="sub-title">* Número:</div>
                        <div>
                            @if($errors->first('num'))
                            <p><i> {{ $errors->first('num') }}</i></p>
                            @endif
                            {{Form::text('num',($consulta->num),['class' => 'form-control', 'placeholder' => 'Ejemplo: 364'])}}
                        </div>

                        <div class="sub-title">* Colonia:</div>
                        <div>
                            @if($errors->first('colonia'))
                            <p><i> {{ $errors->first('colonia') }}</i></p>
                            @endif
                            {{Form::text('colonia',($consulta->colonia),['class' => 'form-control', 'placeholder' => 'Ejemplo: Álvaro Obregón'])}}
                        </div>
                        <div class="sub-title">* Tipo de cliente:</div>
                        <div>
                            @if($errors->first('tipoCliente'))
                            <i> {{ $errors->first('tipoCliente') }}</i>
                            @endif

                            @if($consulta->tipoCliente=='prospecto')
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'prospecto', true)}} Prospecto
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'activo', false)}} Activo
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'noActivo', false)}} No Activo
                            </div>
                            @endif
                            @if($consulta->tipoCliente=='activo')
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'prospecto', false)}} Prospecto
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'activo', true)}} Activo
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'noActivo', false)}} No Activo
                            </div>
                            @endif
                            @if($consulta->tipoCliente=='noActivo')
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'prospecto', false)}} Prospecto
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'activo', false)}} Activo
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'noActivo', true)}} No Activo
                            </div>
                            @endif
                            @if($consulta->tipoCliente=='')
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'prospecto', false)}} Prospecto
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'activo', false)}} Activo
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('tipoCliente', 'noActivo', false)}} No Activo
                            </div>
                            @endif
                        </div>
                        
                        <br>
                        <div class="sub-title">* Tipo de cliente:</div>
                        <div>
                            @if($errors->first('empresaPertenece'))
                            <i> {{ $errors->first('empresaPertenece') }}</i>
                            @endif

                            @if($consulta->empresaPertenece=='SURJA')
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'SURJA', true)}} SURJA
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'GCM', false)}} GCM
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'CYM', false)}} CYM
                            </div>
                            @endif
                            @if($consulta->empresaPertenece=='GCM')
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'SURJA', false)}} SURJA
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'GCM', true)}} GCM
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'CYM', false)}} CYM
                            </div>
                            @endif
                            @if($consulta->empresaPertenece=='CYM')
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'SURJA', false)}} SURJA
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'GCM', false)}} GCM
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'CYM', true)}} CYM
                            </div>
                            @endif
                            @if($consulta->empresaPertenece=='')
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'SURJA', false)}} SURJA
                            </div>
                            <div class="form-group col-md-3">
                                {{Form::radio('empresaPertenece', 'GCM', false)}} GCM
                            </div>
                            <div class="form-group col-md-2">
                                {{Form::radio('empresaPertenece', 'CYM', false)}} CYM
                            </div>
                            @endif
                        </div>

                        <!--Cierran div principales de los panels Datos Cliente -->
                    </div>
                    <div class="form-group col-md-12"><br>
                    <center>
                        {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                        <a href="{{asset('consultaClientes')}}"><button type="button" class="btn btn-default">Cancelar</button></a>
                    </center><br>
                    </div>
                </div>
                <!-- Tab de reporte de seguimiento -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"><br>
                    <h3>Reporte de Actividades</h3><br><br>
                    
                    @if($cuantos==0)
                    <div align="center" class="alert alert-info" role="alert">¡No tiene Actividades!</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #ABEBC6">
                                        <th>Folio</th>
                                        <th>Fecha de creación</th>
                                        <th>Asunto</th>
                                        <th>Creado por</th>
                                        <th>Cliente / Sucursal</th>
                                        <th>Periódo de atención</th>
                                        <th>Nivel de atencion</th>
                                        <th>Área responsable</th>
                                        <th>Actividad</th>
                                        <th>Avance</th>
                                        <th>Atendido por</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seguimiento as $seg)
                                            <tr>

                                                <td>{{$seg->folio}}</td>
                                                <td>{{$seg->fechaCreacionFormato}}</td>
                                                <td>{{$seg->asunto}}</td>
                                                <td>{{$seg->nombreUsuario}} {{$seg->aPaterno}} {{$seg->aMaterno}} </td>
                                                <td>{{$seg->clienteSucursal}}</td>
                                                <td>{{$seg->fechaInicioFormato}} - {{$seg->fechaTerminoFormato}} </td>
                                                <td>{{$seg->importanciaSeguimiento}}</td>
                                                <td>{{$seg->nombreArea}}</td>
                                                <td>{{$seg->nombreActividad}}</td>
                                                
                                                <?php 
                                                
                                                        $atendidoPor =\DB::select("SELECT CONCAT(SUM(r),' de ',COUNT(*)) AS atendidopor
                                                                    FROM (SELECT a.idsegactividad, a.idu,seguimiento($seg->idSegActividad, a.idu ) , IF(seguimiento($seg->idSegActividad, a.idu )>=1,1,0) AS r
                                                                    FROM asignacionseguimientos AS a
                                                                    INNER JOIN usuarios AS u ON u.idu = a.idu
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        
                                                        $porcentaje= \DB::select("SELECT t1.idsegactividad,TRUNCATE(AVG(t1.porcentaje),0) AS porcentaje
                                                                    FROM
                                                                    (SELECT idsegactividad, idu,IF(ultimoporcentaje($seg->idSegActividad,idu)IS NULL,0,ultimoporcentaje($seg->idSegActividad,idu)) AS porcentaje
                                                                    FROM asignacionseguimientos
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        $cuantoPor=count($porcentaje);
                                                        $cuantoAtendido=count($atendidoPor);
                                                        


                                                ?>
                                                
                                                @if($cuantoPor=='0')
                                                    <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                @else
                                                
                                                    @if($porcentaje[0]->porcentaje == 0)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 1 && $porcentaje[0]->porcentaje < 11)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 10%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 11 && $porcentaje[0]->porcentaje < 21)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 20%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 21 && $porcentaje[0]->porcentaje < 31)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 30%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 31 && $porcentaje[0]->porcentaje < 41)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 40%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 41 && $porcentaje[0]->porcentaje < 51)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 50%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 51 && $porcentaje[0]->porcentaje < 61)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 60%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 61 && $porcentaje[0]->porcentaje < 71)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 70%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 71 && $porcentaje[0]->porcentaje < 81)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 80%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 81 && $porcentaje[0]->porcentaje < 91)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 90%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 91 && $porcentaje[0]->porcentaje < 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 95%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje == 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                @endif
                                                @if($cuantoAtendido=='0')
                                                <td>0 de 0</td>
                                                @else
                                                <td>{{$atendidoPor[0]->atendidopor}}</td>
                                                @endif
                                                <td>
                                                <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"> Detalle
                                                    </a> 
                                                <!-- @if($stipo == 'Administrador')
                                                    @if($seg->activo=='Si')
                                                    <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"><i
                                                            class="ace-icon fa fa-trash-o bigger-120"> Detalle</i>
                                                    </a> 
                                                    <br>
                                                    <a href="{{URL::action('seguimientoActController@eliminarSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-danger"><i
                                                            class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                                    </a> <br>

                                                    <a href="{{URL::action('seguimientoActController@modificarSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                                        <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                                    </a>
                                                    @else
                                                    <a href="{{URL::action('seguimientoActController@restaurarSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        class="btn btn-xs btn-warning" style="width:67px">
                                                        Restaurar</a>
                                                    @endif

                                                    @else
                                                    <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"><i
                                                            class="ace-icon fa fa-trash-o bigger-120"> Detalle</i>
                                                    </a> 

                                                    
                                                @endif -->
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div><!-- Termina Tab de seguimiento-->

            </div>

            
        </div>
        
    </div>
</div>

@stop