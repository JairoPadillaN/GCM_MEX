@extends('principal')
@section('contenido')

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Detalle de refacción</h1>
        </div>

        <div class="panel-body">
            <div class="">
            <ul class="nav nav-tabs">
            <li class="active" id='tab1'><a href="#registro" data-toggle="tab"><label for="">Refacción</label></a></li>
            <li class="" id='tab2'><a href="#profile" data-toggle="tab"><label for="">Ocupada / Disponible:</label></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="registro" role="tabpanel" aria-labelledby="home-tab">
            <div class="form-group col-md-6">
                    <div class="sub-title">Nombre de la refacción:</div>
                    <div>
                        {{Form::hidden('idRefaccion',$consulta->idRefaccion)}}
                        {{Form::text('nombreRefaccion',$consulta->nombreRefaccion,['class' => 'form-control', 'readonly' => 'true'])}}
                    </div>

                    @if($nombreMarca!="Sin Marca")
                    <div class="sub-title">Marca:</div>
                    <div>{{Form::text('marca',($nombreMarca),['class' => 'form-control', 'readonly' => 'true'])}}</div>

                    <div class="sub-title">*Tipo de refacción:</div>
                    <div>
                        <input type="text" value='{{$nomref}}' class="form-control" readonly="true">
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">Número de parte:</div>
                            <div>
                                {{Form::text('numeroParte',$consulta->numeroParte,['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Número de serie:</div>
                            <div>
                                {{Form::text('serie',$consulta->serie,['class' => 'form-control','readonly' => 'true'])}}
                            </div>
                        </div>                        
                    </div>
                    <div class="sub-title">Código:</div>
                            <div id='codigoRefaccion'>
                                {{Form::text('codigoRefaccion',$consulta->codigoRefaccion,['class' => 'form-control','readonly' => 'true'])}}
                            </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">Presentación:</div>
                            <div>
                                {{Form::text('presentacion',($consulta->presentacion),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Unidades por presentación:</div>
                            <div>
                                {{Form::text('unidades',($consulta->unidades),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                        </div>
                    </div>
                        
                </div>
                <div class="form-group col-md-6">
                    <div class="sub-title">Ubicación:</div>
                    <div>
                        {{Form::text('ubicacion',$consulta->ubicacion,['class' => 'form-control', 'readonly' => 'true'])}}
                    </div>
                    @if(Session::get('sesiontipo')=="Administrador")
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">Precio unitario compra:</div>
                            <div>
                                {{Form::text('precioLista',($consulta->precioLista),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Precio unitario venta:</div>
                            <div>
                                {{Form::text('precioUltimo',($consulta->precioUltimo),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="sub-title">Tipo producto:</div>
                        <div>
                            {{Form::text('tipoProducto',($consulta->tipoProducto),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                    <div class="sub-title">Cantidad:</div>
                        <div>
                            {{Form::text('cantidad',($consulta->cantidad),['class' => 'form-control', 'readonly' => 'true'])}}
                        </div>
                    <div class="sub-title">Observaciones:</div>
                        <div>
                            {{Form::textarea('observaciones',($consulta->observaciones),['class' => 'form-control', 'placeholder' => 'Escribe aqui las observaciones necesarias', 'rows' =>'5', 'readonly' => 'true'])}}
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="container-fluid">
                <br>

                    @if($equipos>0)
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="sub-title"> Folio Recepción</div>
                            <div>
                                {{Form::text('folioRecepcion',($consultaEquipos->folioRecepcion),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title"> Fecha de entrada</div>
                            <div>
                                {{Form::text('fechaRegistro',($consultaEquipos->fecha),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title"> Cliente</div>
                            <div>
                                {{Form::text('razonSocial',($consultaEquipos->razonSocial),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title"> Sucursal</div>
                            <div>
                                {{Form::text('sucursal',($consultaEquipos->sucursal),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="sub-title"> Ubicación</div>
                            <div>
                                {{Form::text('ubicacionEquipo',($consultaEquipos->ubicacionEquipo),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                            <div class="col-sm-4">
                                <div class="sub-title">Foto principal: </div>
                                <div>
                                    <center>
                                    @if($consultaEquipos->vistaSuperior =="" || $consultaEquipos->vistaSuperior =="Sin archivo")
                                        <div style="color:red" >Sin foto</div></center>
                                    @else
                                        <div><a target="_blank" href="{{asset ('public/archivos/'.$consultaEquipos->vistaSuperior)}}">
                                        <img src="{{asset ('public/archivos/'.$consultaEquipos->vistaSuperior)}}" height=100 width=100></a></div>
                                                    
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="sub-title">Vista frontal: </div>
                                <div>
                                    <center>
                                    @if($consultaEquipos->vistaFrente =="" || $consultaEquipos->vistaFrente =="Sin archivo")
                                        <div style="color:red" >Sin foto</div></center>
                                    @else
                                        <div><a target="_blank" href="{{asset ('public/archivos/'.$consultaEquipos->vistaFrente)}}">
                                        <img src="{{asset ('public/archivos/'.$consultaEquipos->vistaFrente)}}" height=100 width=100></a></div>
                                                    
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="sub-title">Foto placa 1: </div>
                                <div>
                                    <center>
                                    @if($consultaEquipos->placa_1 =="" || $consultaEquipos->placa_1 =="Sin archivo")
                                        <div style="color:red" >Sin foto</div></center>
                                    @else
                                        <div><a target="_blank" href="{{asset ('public/archivos/'.$consultaEquipos->placa_1)}}">
                                        <img src="{{asset ('public/archivos/'.$consultaEquipos->placa_1)}}" height=100 width=100></a></div>
                                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="sub-title"> GCM ID</div>
                            <div>{{Form::text('gcmid',($consultaEquipos->gcmid),['class' => 'form-control', 'readonly' => 'true'])}}</div>
                            <div class="sub-title"> Datos para certificado</div>
                            <div>{{Form::text('datos',($consultaEquipos->datos),['class' => 'form-control', 'readonly' => 'true'])}}</div>
                            <div class="sub-title"> Equipo</div>
                            <div>{{Form::text('equipo',($consultaEquipos->equipo),['class' => 'form-control', 'readonly' => 'true'])}}</div>
                            <div class="sub-title"> Marca-Modelo</div>
                            <div>{{Form::text('marcaModelo',($consultaEquipos->marcaModelo),['class' => 'form-control', 'readonly' => 'true'])}}</div>
                            <div class="sub-title"> Estatus entrega</div>
                            <div>{{Form::text('estatusEntrega',($consultaEquipos->estatusEntrega),['class' => 'form-control', 'readonly' => 'true'])}}</div>
                            <div class="sub-title"> Estatus reparacion</div>
                            <div>{{Form::text('estatus',($consultaEquipos->estatus),['class' => 'form-control', 'readonly' => 'true'])}}</div>
                        </div>
                        
                    </div>
                    
                        <button class="btn btn-info" role="link" onclick="window.location='{{URL::action('EquiposController@modificarEquipos',['idEquipos'=>$consultaEquipos->idEquipos])}}'">Ver más...</button>
                            
                    
                    @else
                        @if($consultaParte == '0')
                            @if($cuantoDisponible == '0')
                                <div class="alert alert-info">
                                    ¡¡Esta disponible!!
                                </div>
                            @else
                            <div class="panel-heading">
                                    <h2>Reporte</h2>
                                </div>
                                <div class="panel-body">
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
                                        
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($consultaDisponible as $piezas)
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
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        
                        @else
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-3">GCM ID Parte:</div>
                                    <div class="col-sm-9">
                                    {{Form::text('GCMidParte',($consultaParte->GCMidParte),['class' => 'form-control', 'readonly' => 'true'])}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">*Nombre:</div>
                                    <div class="col-sm-9">
                                        @if($errors->first('nombreParte'))
                                        <i> {{ $errors->first('nombreParte') }}</i>
                                        @endif
                                        {{Form::text('nombreParte', ($consultaParte->nombreParte),['class' => 'form-control rounded-0', 'readonly'=>'true'])}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">*Fecha de salida:</div>
                                    <div class="col-sm-8">
                                        @if($errors->first('fechaSalida'))
                                        <i> {{ $errors->first('fechaSalida') }}</i>
                                        @endif
                                        {{Form::text('fechaSalida', ($consultaParte->fecha),['class' => 'form-control rounded-0', 'readonly'=>'true'])}}
                                        
                                    </div>
                                </div>
                                ¿Qué se realizará?:<textarea name="queReparacion" rows="2" class="form-control rounded-0" readonly>{{$consultaParte->queReparacion}}</textarea>
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-4">Fecha de recepción:</div>
                                    <div class="col-sm-8">
                                    {{Form::text('fechaRecepcion', ($consultaParte->fechaRe),['class' => 'form-control rounded-0', 'readonly'=>'true'])}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">Fecha de entrega:</div>
                                    <div class="col-sm-8">
                                    {{Form::text('fechaEntrega', ($consultaParte->fechaEn),['class' => 'form-control rounded-0', 'readonly'=>'true'])}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">Orden de compra:</div>
                                    <div class="col-sm-8">
                                        {{Form::text('ordenCompra',($consultaParte->ordenCompra),['class' => 'form-control rounded-0', 'readonly'=>'true'])}}
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
                                        <!-- {{Form::file('archivoOrdenCompra',['class' => 'form-control rounded-0', 'readonly'=>'true'])}} -->
                                <br>
                                <div class="form-group row">
                                    <div class="col-sm-6">Monto:
                                        {{Form::text('montoReparacion',($consultaParte->montoReparacion),['id'=>'monto','class' => 'form-control rounded-0' , 'readonly'=>'true'])}}
                                    </div>
                                    <div class="col-sm-6">Tipo de moneda: <br>
                                    {{Form::text('tipoMoneda',($consultaParte->tipoMoneda),['class' => 'form-control rounded-0', 'readonly'=>'true'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-2">*Taller:</div>
                                    <div class="col-sm-10">
                                    <select name='idTaller' id='idTaller' class="form-control rounded-0" disabled>
                                                <option value=''>{{$nombreTaller}} - {{$tipoTaller}}</option>
                                                
                                            </select>    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">¿Quién llevó?:</div>
                                    <div class="col-sm-9">
                                    {{Form::text('quienllevo',($consultaParte->quienllevo),['class' => 'form-control rounded-0', 'readonly'=>'true'])}}
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">¿Quién recibió?:</div>
                                    <div class="col-sm-8">
                                        {{Form::text('quienRecibio',($consultaParte->quienRecibio),['class' => 'form-control rounded-0', 'readonly'=>'true'])}} 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">Prioridad:</div>
                                    <div class="col-sm-10">
                                        {{Form::text('prioridadRep',($consultaParte->prioridadRep),['class' => 'form-control rounded-0', 'readonly'=>'true'])}} 
                                    </div>
                                </div>
                                <div class="form-group row">
                                <div class="col-sm-3" align="center">*Foto:
                                        @foreach($consultaFotoParte as $consultaFp)
                                        @if($consultaFp->fotoParte=='Sin archivo')
                                        <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                        @else
                                        <a target="_blank" href="{{asset ('public/archivos/'.$consultaFp->fotoParte)}}">
                                            <img src="{{asset ('public/archivos/'.$consultaFp->fotoParte)}}" height=80 width=80>
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>                                      
                                    <div class="col-sm-10">
                                        <!-- {{Form::file('fotoParte',['class' => 'form-control rounded-0','id'=>'req4','onchange'=>'ValidarImagen(this);'])}} -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">*Estatus:</div>
                                    <div class="col-sm-10">
                                    <select name='idEstatus' id='idEstatus' class="form-control rounded-0" disabled>
                                                <option value=''>{{$nombreEstatus}}</option>
                                                
                                            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                    @endif
                </div>
            
            </div>
        </div>  
                
                <br>
                <center>
                    <!-- {{Form::submit('Guardar',['class' => 'btn  btn-default'])}} -->
                    <a href="{{asset('reporteRefacciones')}}"><button type="button"
                            class="btn btn-primary">Regresar</button></a>
            </div>
        </div>
    </div>
</div>
@stop