<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<ul class="nav nav-tabs">
    @if($activarTab==1)
        <li class="active"><a href="#1" data-toggle="tab"><label for="">Editar información de la parte</label> <label style="color:#FF0000;">{{$consultaParte->GCMidParte}}</label></a></li>
    @elseif($activarTab ==3)
        <li class="active"><a href="#3" data-toggle="tab"><label for="">Refurbished y/o repair</label></a></li>
    @else
        <!-- <li class="active"><a href="#2" data-toggle="tab"><label for="">Servicios a realizar</label> <label style="color:#FF0000;">{{$GCMidParte->GCMidParte}}</label></a></li> -->
        <li class="active"><a href="#3" data-toggle="tab"><label for="">Refurbished y/o repair</label></a></li>
        <li><a href="#4" data-toggle="tab"><label for="">Etiqueta Qr</label></a></li>
    @endif

</ul>

{{Form::open(['route' => 'guardarEdicionParte','files'=>true])}}
{{Form::token()}}

<!-- Agregue un tab 3-->
<div class="tab-content">
    @if($activarTab==1)

    <div class="tab-pane active" id="1">
        <div class="container-fluid">
            <br>
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-3">GCM ID Parte:</div>
                        <div class="col-sm-9">
                            {{Form::hidden('numParte',$consultaParte->numParte,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('idParte',$consultaParte->idParte,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('idEquipos',$consultaParte->idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('GCMid',$consultaParte->GCMid,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::text('GCMidParte',$consultaParte->GCMidParte,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">*Nombre:</div>
                        <div class="col-sm-9">
                            @if($errors->first('nombreParte'))
                            <i> {{ $errors->first('nombreParte') }}</i>
                            @endif
                            {{Form::text('nombreParte',$consultaParte->nombreParte,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Flecha 234252','id'=>'req1'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">*Fecha de salida:</div>
                        <div class="col-sm-8">
                            @if($errors->first('fechaSalida'))
                            <i> {{ $errors->first('fechaSalida') }}</i>
                            @endif
                            {{Form::date('fechaSalida',$consultaParte->fechaSalida,['class' => 'form-control','id'=>'req2'])}}
                        </div>
                    </div>
                    ¿Qué se realizará?:<textarea name="queReparacion" rows="2" class="form-control rounded-0"
                        placeholder="Ejemplo: Reparar Flecha que esta rota de la punta">{!!($consultaParte->queReparacion)!!}</textarea>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-4">Fecha de recepción:</div>
                        <div class="col-sm-8">
                            {{Form::date('fechaRecepcion',$consultaParte->fechaRecepcion,['class' => 'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">Fecha de entrega:</div>
                        <div class="col-sm-8">
                            {{Form::date('fechaEntrega',$consultaParte->fechaEntrega,['class' => 'form-control'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">Orden decompra:</div>
                        <div class="col-sm-8">
                            {{Form::text('ordenCompra',$consultaParte->ordenCompra,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: OC-000043'])}}
                        </div>
                    </div>
                   Archivo de la Orden de compra:
                            @foreach($consultaArchivoOC as $consultaAOC)
                                @if($consultaAOC->archivoOrdenCompra=='Sin archivo')
                                <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                @else
                                <center>                                    
                                    <a target="_blank" href="{{asset('archivos/'.$consultaAOC->archivoOrdenCompra)}}" style=" margin-top:1%; margin-bottom: 1%; border-radius: 40px;"
                                        class="btn btn-success btn-xs">{{$consultaAOC->archivoOrdenCompra}} <span class="glyphicon glyphicon-floppy-save"></span></a>                                    
                                </center>
                                @endif                    
                            @endforeach
                            {{Form::file('archivoOrdenCompra',['class' => 'form-control rounded-0'])}}
                        <br>
                    <div class="form-group row">
                        <div class="col-sm-6">Monto:
                            {{Form::text('montoReparacion',$consultaParte->montoReparacion,['id'=>'monto','class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 50000'])}}
                        </div>
                        <div class="col-sm-6">Tipo de moneda: <br>                            
                            @if ($consultaParte->tipoMoneda == 'MXN')
                            MXN {{Form::radio('tipoMoneda','MXN', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            USD {{Form::radio('tipoMoneda','USD',false, [])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            @elseif ($consultaParte->tipoMoneda == 'USD')
                            MXN {{Form::radio('tipoMoneda','MXN', false,[])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            USD {{Form::radio('tipoMoneda','USD',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            @else
                            MXN {{Form::radio('tipoMoneda','MXN', false,[])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            USD {{Form::radio('tipoMoneda','USD',false, [])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-2">*Taller:</div>
                        <div class="col-sm-10">

                            @if($errors->first('idTaller'))
                            <i> {{ $errors->first('idTaller') }}</i>
                            @endif<div>
                                <select name='idTaller' id='idTaller' class="form-control rounded-0">
                                    <option value='{{$idTallerSel}}'>{{$nombreTaller}} - {{$tipoTaller}}</option>
                                    @foreach($taller as $Taller)
                                    @if($Taller->activo=="Si")
                                    <option value='{{$Taller->idTaller}}'>{{$Taller->nombreTaller}} -
                                        {{$Taller->tipoTaller}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">¿Quién llevó?:</div>
                        <div class="col-sm-9">
                            {{Form::text('quienllevo',$consultaParte->quienllevo,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Pedro Perez Lopez'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">¿Quién recibió?:</div>
                        <div class="col-sm-8">
                            {{Form::text('quienRecibio',$consultaParte->quienRecibio,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Carlos Robles Carmona'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">Prioridad:</div>
                        <div class="col-sm-10">
                            @if ($consultaParte->prioridadRep == 'Urgente')
                            Urgente {{Form::radio('prioridadRep','Urgente', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            Normal {{Form::radio('prioridadRep','Normal',false, [])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            @elseif ($consultaParte->prioridadRep == 'Normal')
                            Urgente {{Form::radio('prioridadRep','Urgente', false,[])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            Normal {{Form::radio('prioridadRep','Normal',true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            @else
                            Urgente {{Form::radio('prioridadRep','Urgente', false,[])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            Normal {{Form::radio('prioridadRep','Normal',false, [])}}&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3" align="center">*Foto:
                            @foreach($consultaFotoParte as $consultaFp)
                            @if($consultaFp->fotoParte=='Sin archivo')
                            <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                            @else
                            <a target="_blank" href="{{asset ('archivos/'.$consultaFp->fotoParte)}}">
                                <img src="{{asset ('archivos/'.$consultaFp->fotoParte)}}" height=80 width=80>
                            </a>
                            @endif
                            @endforeach
                        </div>                        
                    </div>
                        {{Form::file('fotoParte',['class' => 'form-control rounded-0','id'=>'req4','onchange'=>'ValidarImagen(this);'])}}
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-2">*Estatus:</div>
                        <div class="col-sm-10">
                            @if($errors->first('idEstatus'))
                            <i> {{ $errors->first('idEstatus') }}</i>
                            @endif<div>
                                <select name='idEstatus' id='idEstatus' class="form-control rounded-0">
                                    <option value='{{$idEstatusSel}}'>{{$nombreEstatus}}</option>
                                    @foreach($estatusReparado as $estatusR)
                                    @if($estatusR->activo=="Si")
                                    <option value='{{$estatusR->idEstatus}}'>{{$estatusR->nombreEstatus}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-9">Seguimiento de entrega de técnico:</div>
                        <div class="col-sm-10">
                        {{Form::textarea('seguimientoTecnico',$consultaParte->seguimientoTecnico,['class' => 'form-control rounded-0'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
        @if($equipoActivo->equipoActivo == 'Cerrado')
            <div class="" align="center">
            </div>

        @else 
            <div class="" align="center">
                <button type="submit" class="btn btn-success btn-sm" id="guardarEditarParte">
                    <span class="glyphicon glyphicon-plus-sign"></span> Guardar
                </button>
            </div>
        @endif
        </div>
    </div>
@elseif($activarTab==3)
    <div class="tab-pane active" id="3">
        <div class="container-fluid">                        
            <br>
            <!-- <button type="button" class="btn btn-success btn-default" id="nuevaRefaccion"> Crear refacción nueva </button> -->
            <div id="formRefacciones" style="display:none">
                <form>
                    <div class="form-group col-md-6">
                        <input type="hidden" value="{{$GCMidParte->idParte}}" name="idParte">
                        <input type="hidden" value="{{$idRef}}" name="idRefaccion">
                                <div class="sub-title">Nombre de la refacción:</div>
                                <div>
                                    @if($errors->first('nombreRefaccion'))
                                    <i> {{ $errors->first('nombreRefaccion') }}</i>
                                    @endif
                                    {{Form::text('nombreRefaccion',old ('nombreRefaccion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico'])}}
                                </div> 

                            <div class="sub-title">*Tipo de refacción:</div>
                            <div>
                                <select name="idTipoRefacciones" class="form-control">
                                    <option value="">Seleccione un tipo de refacción:</option>
                                        @foreach($comboTipoRefacciones as $comboTipoRef)
                                            @if($comboTipoRef->activo=="Si")
                                                <option value='{{$comboTipoRef->idTipoRefacciones}}'>{{$comboTipoRef->nombreTipoRefaccion}}</option>
                                            @endif
                                        @endforeach
                                </select>
                            </div>   

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Número de parte:</div>
                                    <div>
                                        @if($errors->first('numeroParte'))
                                        <i> {{ $errors->first('numeroParte') }}</i>
                                        @endif
                                        {{Form::text('numeroParte',old ('numeroParte'),['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652'])}}
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

                            <div class="sub-title">*Proveedor:</div>
                            <div>
                                <select name='idProveedor' class="form-control">
                                    <option value="">Seleccione un proveedor</option>
                                    @foreach($proveedor as $prov)
                                    @if($prov->activo=="Si")
                                    <option value='{{$prov->idProveedor}}'>{{$prov->razonSocialProv}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="sub-title">Ubicación:</div>
                        <div>
                            @if($errors->first('ubicacion'))
                            <i> {{ $errors->first('ubicacion') }}</i>
                            @endif
                            {{Form::text('ubicacion',old ('ubicacion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Bodega'])}}
                        </div>

                        <div class="sub-title">Estatus:</div>
                        @if($errors->first('estatus'))
                        <i> {{ $errors->first('estatus') }}</i>
                        @endif
                        <div>
                        <br>
                            Stock {{Form::radio('estatus','Stock')}}&nbsp;&nbsp;&nbsp;
                            Solicitado {{Form::radio('estatus','Solicitado')}}&nbsp;&nbsp;&nbsp;
                            Enviado a reparar {{Form::radio('estatus','Enviado a reparar')}}
                        </div>
                        <div class="sub-title">Precio lista:</div>
                        <div>
                            @if($errors->first('precioLista'))
                            <i> {{ $errors->first('precioLista') }}</i>
                            @endif
                            {{Form::text('precioLista',old ('precioLista'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                        </div>

                        <div class="sub-title">Precio último:</div>
                        <div>
                            @if($errors->first('precioUltimo'))
                            <i> {{ $errors->first('precioUltimo') }}</i>
                            @endif
                            {{Form::text('precioUltimo',old ('precioUltimo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                        </div>
                    </div>

                    <center><input type="button" class="btn btn-default" id="guardarRef" value="Guardar"></input></center>
                                
                </form>
            </div>
                
            <form>
                <div class="form-group row">
                    <div class="col-sm-2">*Tipo de parte:</div>
                        <div class="col-sm-6">
                        <select name='tipoProducto' class="form-control" id="tipoProducto">
                            <option value="">Seleccione un tipo de parte</option>
                            <option value="Nuevo">Nuevo</option>
                            <option value="Refurbished y/o repair">Refurbished y/o repair</option>
                        </select>
                        </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">*Marca:</div>
                        <div class="col-sm-6">
                        <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                            <option value="">Seleccione una marca</option>
                            @foreach($marca as $mr)
                            @if($mr->activo=="Si")
                            <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                            @endif
                            @endforeach
                        </select>
                        </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">*Tipo de refacción:</div>
                    <div class="col-sm-6">
                    <input type="hidden" value="{{$GCMidParte->idParte}}" name="idParte">
                        <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                            <option value="">Seleccione un tipo de refacción</option>
                        </select>
                        
                    </div>                
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">*Refacciones disponibles:</div>
                    <div class="col-sm-5">
                        <select name="idRefaccion" id="comboRef" class="form-control">
                            <option value="">Seleccione una refacción:</option>                            
                        </select>
                    </div>
                    <div id="preciosR"></div>                
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success btn-default" id="agregarRefaccion">
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar refacción
                        </button>
                    </div>
                </div>
            </form>

            <br><br>
            
            <div id="reporteRefaccionesAsignadas">
                @if($cuantasRefaccionesYaHay < 1) 
                    <div class="alert alert-warning" role="alert" align="center">
                            <label for="">No se han agregado refacciones.</label>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style=" background-color: #C5EBFB;">
                                    <th>Refacción</th>
                                    <th>Tipo de refacción</th>
                                    <th>Código</th>
                                    <th>
                                        <center>Operaciones</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($consultaRefaccionesAsignados as $cra)
                                <tr>
                                    <td>{{$cra->codigo}}-{{$cra->nombreRefaccion}}</td>
                                    <td>{{$cra->nombreTipoRefaccion}}</td>
                                    <td>{{$cra->codigo}}</td>
                                    <td align="center">
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                        <input type="hidden" value="{{$cra->idParte}}" name="idParte">
                                        <input type="hidden" value="{{$cra->idRefaccRepParte}}" name="idRefaccionBorrar">
                                            <button type="button" class="btn btn-sm btn-danger borrarRefaccion">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td colspan="4" style="text-align:right;">Total:</td>
                                    <td style="text-align:right;">${{$consultaSumaRef->total2}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
<!-- Hasta aqui termina -->
@else
    <div class="tab-pane" id="2">
        <div class="container-fluid">                        
        <br>
        <form>
            <div class="row">
                <div class="col-sm-1">*Alcance:</div>
                <div class="col-sm-7">
                <input type="hidden" value="{{$GCMidParte->idParte}}" name="idParteServicio">
                    <select name="idAlcance" id="comboAlcance" class="form-control">
                        <option value="">Seleccione una opción:</option>
                            @foreach($comboAlcances as $comboAlcances)
                                @if($comboAlcances->activo=="si")
                                    <option value='{{$comboAlcances->idAlcance}}'>{{$comboAlcances->alcance}}</option>
                                @endif
                            @endforeach 
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-2">*Servicios a realizar:</div>
                <div class="col-sm-6">
                    <div id="comboServTaller">
                        <select name="idServicioTaller" id="comboServicio" class="form-control">
                            <option value="">Seleccione un servicio:</option>
                        </select>
                    </div>
                </div>                
            </div>
            <br>
            <div class="row">
                <div class="col-sm-8">Aparece en cotización:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    Si {{Form::radio('tieneCotizacion','Si')}}&nbsp;&nbsp;&nbsp;
                    No {{Form::radio('tieneCotizacion','No')}}
                </div>
                <div id="preciosS"></div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-success btn-default" id="asignarServicio">
                        <span class="glyphicon glyphicon-plus-sign"></span> Asignar servicio
                    </button>
                </div>  
            </div>
            <br>
            <!-- <div class="row">
                <div class="col-sm-8">Tipo de moneda:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    MXN {{Form::radio('monedaCotizacion','MXN')}}&nbsp;&nbsp;&nbsp;
                    USD {{Form::radio('monedaCotizacion','USD')}}
                </div>                  
            </div> -->
            <!-- <br>
            <div class="row">
                <div class="col-sm-1">monto:</div>
                <div class="col-sm-7">
                    {{Form::text('montoCotizacion',old ('montoCotizacion'),['id'=>'monto','class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 50000'])}}
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-success btn-default" id="asignarServicio">
                        <span class="glyphicon glyphicon-plus-sign"></span> Asignar servicio
                    </button>
                </div>
            </div>         -->
        </form>

            <br>
            <div id="reporteServiciosAsignados">
                @if($cuantasYaHay < 1) 
                    <div class="alert alert-warning" role="alert" align="center">
                            <label for="">No se le han asignado servicios de reparación a esta pieza.</label>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style=" background-color: #C5EBFB;">
                                    <th>Alcance</th>
                                    <th>Nombre del servicio</th>
                                    <th>Precio Venta (Dólares)</th>
                                    <th>Cotizacion</th>
                                    <!-- <th>Tipo de moneda</th> -->
                                    <th>
                                        <center>Operaciones</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($consultaServiciosAsignados as $csa)
                                <tr>
                                <td>{{$csa->alcance}}</td>
                                <td>{{$csa->nombreServTaller}}</td>
                                <td>USD ${{$csa->precioDolar}}</td>
                                <td>{{$csa->tieneCotizacion}}</td>
                                
                                    <td align="center">
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                        <input type="hidden" value="{{$csa->idParte}}" name="idParte">
                                        <input type="hidden" value="{{$csa->idServReparacionParte}}" name="idServicioBorrar">
                                            <button type="button" class="btn btn-sm btn-danger borrarServicio">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                    
                                </tr>
                        @endforeach
                        <tr>
                        <td colspan="4" style="text-align:right;">Total:</td>
                        <td style="text-align:right;">${{$consultaSuma->total}}</td>
                        </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div> 
        
        </div>
    </div>
<!-- Hasta aqui termina -->
<div class="tab-pane active" id="3">
    <div class="container-fluid">                        
        <br>
        <!-- <button type="button" class="btn btn-success btn-default" id="nuevaRefaccion"> Crear refacción nueva </button> -->
        <div id="formRefacciones" style="display:none">
            <form>
                <div class="form-group col-md-6">
                    <input type="hidden" value="{{$idRef}}" name="idRefaccion">
                            <div class="sub-title">Nombre de la refacción:</div>
                            <div>
                                @if($errors->first('nombreRefaccion'))
                                <i> {{ $errors->first('nombreRefaccion') }}</i>
                                @endif
                                {{Form::text('nombreRefaccion',old ('nombreRefaccion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico'])}}
                            </div> 

                        <div class="sub-title">*Tipo de refacción:</div>
                        <div>
                            <select name="idTipoRefacciones" class="form-control">
                                <option value="">Seleccione un tipo de refacción:</option>
                                    @foreach($comboTipoRefacciones as $comboTipoRef)
                                        @if($comboTipoRef->activo=="Si")
                                            <option value='{{$comboTipoRef->idTipoRefacciones}}'>{{$comboTipoRef->nombreTipoRefaccion}}</option>
                                        @endif
                                    @endforeach
                            </select>
                        </div>   

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="sub-title">Número de parte:</div>
                                <div>
                                    @if($errors->first('numeroParte'))
                                    <i> {{ $errors->first('numeroParte') }}</i>
                                    @endif
                                    {{Form::text('numeroParte',old ('numeroParte'),['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652'])}}
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

                        <div class="sub-title">*Proveedor:</div>
                        <div>
                            <select name='idProveedor' class="form-control">
                                <option value="">Seleccione un proveedor</option>
                                @foreach($proveedor as $prov)
                                @if($prov->activo=="Si")
                                <option value='{{$prov->idProveedor}}'>{{$prov->razonSocialProv}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="sub-title">Ubicación:</div>
                    <div>
                        @if($errors->first('ubicacion'))
                        <i> {{ $errors->first('ubicacion') }}</i>
                        @endif
                        {{Form::text('ubicacion',old ('ubicacion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Bodega'])}}
                    </div>

                    <div class="sub-title">Estatus:</div>
                    @if($errors->first('estatus'))
                    <i> {{ $errors->first('estatus') }}</i>
                    @endif
                    <div>
                    <br>
                        Stock {{Form::radio('estatus','Stock')}}&nbsp;&nbsp;&nbsp;
                        Solicitado {{Form::radio('estatus','Solicitado')}}&nbsp;&nbsp;&nbsp;
                        Enviado a reparar {{Form::radio('estatus','Enviado a reparar')}}
                    </div>
                    <div class="sub-title">Precio lista:</div>
                    <div>
                        @if($errors->first('precioLista'))
                        <i> {{ $errors->first('precioLista') }}</i>
                        @endif
                        {{Form::text('precioLista',old ('precioLista'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                    </div>

                    <div class="sub-title">Precio último:</div>
                    <div>
                        @if($errors->first('precioUltimo'))
                        <i> {{ $errors->first('precioUltimo') }}</i>
                        @endif
                        {{Form::text('precioUltimo',old ('precioUltimo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                    </div>
                </div>

                <center><input type="button" class="btn btn-default" id="guardarRef" value="Guardar"></input></center>
                             
            </form>
        </div>
    @if($equipoActivo->equipoActivo != 'Cerrado')
        <form>
            <div class="form-group row">
                <div class="col-sm-2">*Tipo de parte:</div>
                    <div class="col-sm-6">
                    <select name='tipoProducto' class="form-control" id="tipoProducto">
                        <option value="">Seleccione un tipo de parte</option>
                        <option value="Nuevo">Nuevo</option>
                        <option value="Refurbished y/o repair">Refurbished y/o repair</option>
                        <option value="Paquete">Paquete</option>
                    </select>
                    </div>
            </div>
            <div id="paqueteform" style="display:none">
                <div class="form-group row">
                    <input type="hidden" value="{{$GCMidParte->idParte}}" name="idParte" id="idParte2" >
                    <div class="col-sm-2">Paquete:</div>
                    <div class="col-sm-6">
                        <select name="idPaquete" id="idPaquete" class="form-control mi-selector">
                            <option value="">Seleccione un paquete</option>
                            @foreach($paquetes as $p)
                            @if($p->activo=="Si")
                            <option value='{{$p->idPaquete}}'>{{$p->nombrePaquete}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="pac" id="paquete" >
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success btn-default" id="agregarPaquete">
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar paquete
                        </button>
                    </div>
                </div>
            </div>
            
            <div id = "form1" style="display:block">
                <div class="form-group row">
                    <div class="col-sm-2">*Marca:</div>
                        <div class="col-sm-6">
                        <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                            <option value="">Seleccione una marca</option>
                            @foreach($marca as $mr)
                            @if($mr->activo=="Si")
                            <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                            @endif
                            @endforeach
                        </select>
                        </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">*Tipo de refacción:</div>
                    <div class="col-sm-6">
                    <input type="hidden" value="{{$GCMidParte->idParte}}" name="idParte">
                        <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                            <option value="">Seleccione un tipo de refacción</option>
                        </select>
                        
                    </div>                
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">*Refacciones disponibles:</div>
                    <div class="col-sm-5">
                        <select name="idRefaccion" id="comboRef" class="form-control">
                            <option value="">Seleccione una refacción:</option>                            
                        </select>
                    </div>
                    <div id="preciosR"></div>                  
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success btn-default" id="agregarRefaccion">
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar refacción
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-5">Aparece en cotización:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                        Si {{Form::radio('apareceEnCoti','Si', true)}}&nbsp;&nbsp;&nbsp;
                        No {{Form::radio('apareceEnCoti','No')}}
                    </div>    
                
                </div>
            </div>
        </form>
    @endif
        <br><br>

        <div id="reporteRefaccionesAsignadas">
            @if($cuantasRefaccionesYaHay < 1) 
                <div class="alert alert-warning" role="alert" align="center">
                        <label for="">No se han agregado refacciones.</label>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr style=" background-color: #C5EBFB;">
                                <th>Refacción</th>
                                <th>Tipo de refacción</th>
                                <th>Tipo de producto</th>
                                <th>Código</th>
                                <th>Precio Venta (Dólares)</th>
                                <th>Cotización</th>
                                @if($equipoActivo->equipoActivo != 'Cerrado')
                                <th>
                                    <center>Operaciones</center>
                                </th>
                                @else
                                <th>
                                    <center>Total</center>
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($consultaRefaccionesAsignados as $cra)
                            <tr>
                                <td>{{$cra->codigo}}-{{$cra->nombreRefaccion}}</td>
                                <td>{{$cra->nombreTipoRefaccion}}</td>  
                                @if($cra->tipoProducto == 'Paquete')
                                <td>{{$cra->tipoProducto}}-{{$cra->nombrePaquete}}</td> 
                                @else
                                <td>{{$cra->tipoProducto}}</td> 
                                @endif                                 
                                <td>{{$cra->codigo}}</td>
                                <td>USD $ {{$cra->precioVentaDol}}</td>
                                <td>{{$cra->apareceEnCoti}}</td>
                                @if($equipoActivo->equipoActivo != 'Cerrado')
                                <td align="center">
                                    <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                    <input type="hidden" value="{{$cra->idParte}}" name="idParte">
                                    <input type="hidden" value="{{$cra->idRefaccRepParte}}" name="idRefaccionBorrar">
                                        <button type="button" class="btn btn-sm btn-danger borrarRefaccion">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                                @else
                                <td></td>
                                @endif
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="6" style="text-align:right;">Total:</td>
                            <td style="text-align:right;">${{$consultaSumaRef->total2}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>         
        </div>

    </div>
<!-- Hasta aqui termina -->
    <div class="tab-pane" id="4">
        <div class="container-fluid">
        <br>
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <div class="form-group row">
                        <div class="form-group col-md-6">
                        GCM ID:{{Form::text('GCMid',$consultaQr1->GCMid,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                        <div class="form-group col-md-6">
                        GCM ID Parte:{{Form::text('GCMidParte',$consultaQr1->GCMidParte,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>                 
                    </div>
                    <div class="form-group row">
                        <div class="form-group col-md-6">
                        Tipo de equipo:{{Form::text('tipoEquipo',$consultaQr2->tipoEquipo,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                        <div class="form-group col-md-6">
                        Subclasificación:{{Form::text('subtipoEquipo',$consultaQr2->subtipoEquipo,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>                 
                    </div>
                    <div class="form-group row">
                        <div class="form-group col-md-6">
                        Nombre de la pieza:{{Form::text('nombreParte',$consultaQr1->nombreParte,['class' => 'form-control rounded-0', 'readonly'])}} <br>
                        Servicios de reparacion:<textarea name="queReparacion" rows="3" class="form-control rounded-0" disabled>{{$consultaQr1->queReparacion}}</textarea>                        
                        </div>
                        <div class="form-group col-md-6">
                            <center>
                            <!-- {!! QrCode::size(117)->generate('GCM ID: '.$consultaQr1->GCMid."\n".'GCM ID parte: '.$consultaQr1->GCMidParte."\n".'Tipo de equipo: '.$consultaQr2->tipoEquipo."\n".'Subclasificación: '.$consultaQr2->subtipoEquipo."\n".'Nombre de la pieza: '.$consultaQr1->nombreParte."\n".'Servicios de reparacion: '.$consultaQr1->queReparacion); !!} -->
                            {!! QrCode::size(117)->generate('GCM ID parte: '.$consultaQr1->GCMidParte); !!}
                            <a href="{{URL::action('EquiposController@pdfQrParte',[$idParteServicios])}}" target="_blank"class="btn btn-info btn-lg">
                            <span class="glyphicon glyphicon-print"></span> Imprimir Qr 
                            </a> 
                            </center>
                        </div>                 
                    </div>                                                                           
                </div>
            </div>
        </div>
    </div>
@endif
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#guardarEditarParte").click(function(e) {
        let datos1 = $("#req1").val();
        let datos2 = $("#req2").val();
        let datos3 = $("#idTaller").val();
        let datos4 = $("#idEstatus").val();
        if (datos1 == '' || datos2 == '' || datos3 == '' || datos4 == '') {
            if (datos1 == '') {
                alert("El campo *Nombre es requerido");
            }
            if (datos2 == '') {
                alert("El campo *Fecha de salida es requerido");
            }
            if (datos3 == '') {
                alert("El campo *Taller es requerido");
            }if (datos4 == '') {
                alert("El campo *Estatus es requerido");
            }
            e.preventDefault();
        }
    });

    $("#asignarServicio").click(function(e) {
        let comboServicio = $("#comboServicio").val();
        let comboAlcance = $("#comboAlcance").val();
        if (comboServicio == '' ||comboAlcance == '') {
            if (comboAlcance == '') {
            alert("El campo *Alcance es requerido");
            e.preventDefault();
            }
            if (comboServicio == ''){
            alert("El campo *Servicio a realizar es requerido");
            e.preventDefault();
            }
        }  
        else{
            $("#reporteServiciosAsignados").load('{{url('asignarServicioTallerParte')}}' + '?' + $(this).closest('form').serialize());
            
        }
    });

    $(".borrarServicio").click(function() {
        $("#reporteServiciosAsignados").load('{{url('borrarServicioReparacionParte')}}' + '?' + $(this).closest('form').serialize());
    });

    $("#agregarRefaccion").click(function(e) {
        let comboRef = $("#comboRef").val();
        let comboTipoRef = $("#comboTipoRef").val();
        let tipoProducto = $("#tipoProducto").val();
        if (comboRef == '' | comboTipoRef == '' | tipoProducto =='') {            
            if (comboTipoRef == '') {
                alert("El campo *Tipo de refacción es requerido");
            }
            if (comboRef == '') {
                alert("El campo *Refacciones disponibles es requerido");
            }
            if (tipoProducto == '') {
                alert("El campo *Tipo de parte es requerido");
            }
            e.preventDefault();
        }else{
            $("#reporteRefaccionesAsignadas").load('{{url('agregarRefaccionParte')}}' + '?' + $(this).closest('form').serialize());
            $("#idMarcaRefa, #idTipoRefacciones, #comboRef, #tipoProducto").val('');
            
        }

    });
   
    $("#tipoProducto").click(function() {
        let tipoProducto = $("#tipoProducto").val();

        if (tipoProducto == 'Nuevo' || tipoProducto == 'Refurbished y/o repair') {
            $('#paqueteform').css('display', 'none'); 
            $('#form1').css('display', 'block'); 
        }

        if (tipoProducto == 'Paquete') {
            $('#paqueteform').css('display', 'block'); 
            $('#form1').css('display', 'none'); 
        }
        
        
      
    });

    $("#idPaquete").click(function() {
        $("#tipoProducto, #idPaquete, #paquete" ).val();
        $('.mi-selector').select2();
    });

    $("#agregarPaquete").click(function() {
        $("#reporteRefaccionesAsignadas").load('{{url('detallePac')}}'+ '?' + $(this).closest('form').serialize());
        var paquete = $("#paquete" ).val();

   });

    

    $("#comboServicio").change(function() {
        $("#preciosS").load('{{url('preciosServicios')}}' + '?r=' + Date.now() + '&idServicioTaller=' + this.options[this.selectedIndex].value) ;
    });
    $("#comboRef").change(function() {
        $("#preciosR").load('{{url('preciosRefacciones')}}' + '?r=' + Date.now() + '&idPartesVenta=' + this.options[this.selectedIndex].value) ;
    });
    $("#comboAlcance").change(function() {
        $("#comboServTaller").load('{{url('comboServTaller')}}' + '?r=' + Date.now() + '&idAlcance=' + this.options[this.selectedIndex].value) ;
    });

    $("#tipoProducto, #idTipoRefacciones").change(function() {
        $("#comboRef").load('{{url('comboRefacciones')}}' + '?' + $(this).closest('form').serialize());
    });

    $(".borrarRefaccion").click(function() {
        $("#reporteRefaccionesAsignadas").load('{{url('borrarRefaccionParte')}}' + '?' + $(this).closest('form').serialize());
    });

    $("#nuevaRefaccion").click(function() {
        $('#formRefacciones').css('display', 'block'); 
        
    });

    $("#guardarRef").click(function() {
    $("#guardarRef").load('{{url('nuevaRefaccion')}}' + '?' + $(this).closest('form').serialize());
    $("#reporteRefaccionesAsignadas").load('{{url('agregarRefaccionParte')}}' + '?' + $(this).closest('form').serialize());
    $("#formRefacciones").load('{{url('mensaje')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#idMarcaRefa").change(function(){
    // alert("hola");
    $("#idTipoRefacciones").load('{{url('comboTipoRefaccion')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
});
});
</script>
<script>
$("#monto").on({
  "focus": function(event) {
    $(event.target).select();
  },
  "keyup": function(event) {
    $(event.target).val(function(index, value) {
      return value.replace(/\D/g, "")
        .replace(/([0-9])([0-9]{2})$/, '$1.$2')
        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
    });
  }
});
</script>
<script>
function ValidarIm  agen(obj){
    var uploadFile = obj.files[0];
    let datos4 = $("#req4").val();
    if (!(/\.(jpeg|png|gif|jpg)$/i).test(uploadFile.name)) {
        alert('El archivo a seleccionado no es una imagen');
        $('#req4').val('');
    }          
}
</script>