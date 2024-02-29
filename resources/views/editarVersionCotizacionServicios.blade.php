<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>

{{Form::open(['route' => 'guardarModificacionCotizacionServicios','files'=>true])}}
{{Form::token()}}

<ul class="nav nav-tabs">
    <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Cotización</label></a></li>
    <li class="" id='tab2' style="visibility:visible;"><a href="#2" data-toggle="tab"><label for="">Servicios asignados</label></a></li>
</ul>

<form action="">
    <div class="tab-content">
        <div class="tab-pane active" id="1">
            <div id='a'></div>
            <div class="container-fluid">
                <br>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-4"><b>Registrado por:</b></div>
                            <div class="col-sm-8">
                                {{Form::hidden('idCotizacionAnterior',$idCotizacionServicios,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('idCotizacionServicios',$cotizacionEditar->idCotizacionServicios,['id'=>'idCotizacionServicios','class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('idc',$cotizacionEditar->idc,['class' => 'form-control rounded-0', 'readonly'])}}
                                <select name="idu" class="form-control rounded-0" id="idu">
                                    <option value="{{$cotizacionEditar->idu}}">{{$cotizacionEditar->usuario}}</option>
                                    <option style="background-color: #dddddde0;" disabled>Administradores</option>
                                        @foreach($comboUsuariosAdmon as $comboUsuariosAdmon)
                                            <option value="{{$comboUsuariosAdmon->idu}}">{{$comboUsuariosAdmon->nombreUsuario}} {{$comboUsuariosAdmon->aPaterno}} {{$comboUsuariosAdmon->aMaterno}}</option>
                                        @endforeach
                                    <option style="background-color: #dddddde0;" disabled>Vendedores</option>
                                        @foreach($comboUsuariosVendedor as $comboUsuariosVendedor)
                                            <option value="{{$comboUsuariosVendedor->idu}}">{{$comboUsuariosVendedor->nombreUsuario}} {{$comboUsuariosVendedor->aPaterno}} {{$comboUsuariosVendedor->aMaterno}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="nombreUsuario" id="nombreUsuario" value="{{$cotizacionEditar->nombreUsuario}}">
                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Fecha:</b></div>
                            <div class="col-sm-9">
                                <?php $dat=date("Y-m-d");?>                                
                                {{Form::date('fechaCotizacion',$dat,['class' => 'form-control','id'=>'fechaCotizacion'])}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Sucursal:</b></div>
                            <div class="col-sm-9">
                                <select name='idSucursal' id='comboSucursal' class="form-control" disabled>
                                    <option value="{{$cotizacionEditar->idSucursal}}">{{$cotizacionEditar->sucursal}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-5"><b>Número de cotización:</b></div>
                            <div class="col-sm-7 numeroCotizacionGenerado">
                                {{Form::text('numeroCotizacionVista',$cotizacionEditar->numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('cotizacionOriginal',$cotizacionEditar->cotizacionOriginal,['class' => 'form-control rounded-0', 'readonly'])}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><b>Dirigido a:</b></div>
                            <div class="col-sm-9">
                                <select name='dirigidoA' class="form-control" id="comboContacto">
                                    <option value="{{$cotizacionEditar->dirigidoA}}">{{$cotizacionEditar->dirigidoA}}</option>
                                        @foreach($consulta as $s)
                                            <option value='{{$s->nombreCont}}'>{{$s->nombreCont}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2"><b>Cc:</b></div>
                            <div class="col-sm-10">
                                <select name='conCopia' class="form-control">
                                    <option value="{{$cotizacionEditar->conCopia}}">{{$cotizacionEditar->conCopia}}</option>
                                        @foreach($comboConCopia as $comboConCopia)
                                            <option value='{{$comboConCopia->nombreCont}}'>{{$comboConCopia->nombreCont}} - {{$comboConCopia->sucursal}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3" align="center">archivo:
                                @foreach($fotoCotizacionEditar as $cotizacionFoto)
                                    @if($cotizacionFoto->archivoCotizacion =='Sin archivo')
                                        <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                        <input type="hidden" value="0" id="Asubido">
                                    @else
                                    <input type="hidden" value="1" id="Asubido">
                                        <a target="_blank" href="{{asset ('public/archivos/'.$cotizacionFoto->archivoCotizacion)}}">
                                            @if(strpos($cotizacionFoto->archivoCotizacion,'pdf'))
                                                <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                            @else
                                                <img src="{{asset ('public/archivos/'.$cotizacionFoto->archivoCotizacion)}}" height=80 width=80>
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-sm-9">
                                <b>Archivo de
                                    cotización:</b>{{Form::file('archivoCotizacion',['class' => 'form-control rounded-0','id'=>'archivoCotizacion','onchange'=>'ValidarImagen(this);'])}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-6"><b>Monto:</b>
                                {{Form::text('montoTotal',$cotizacionEditar->montoTotal,['id'=>'monto','class' => 'form-control rounded-0', 'readonly'])}}
                            </div>
                            <div class="col-sm-6" id="validarTipo"><b>*Tipo de moneda:</b> <br>
                                @if ($cotizacionEditar->tipoMoneda == 'MXN')
                                    MXN <input type="radio" name="tipoMoneda" id="MXN" class="tip" value="MXN" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD <input type="radio" name="tipoMoneda" id="USD" class="tip" value="USD" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div id="cambio" style=""><br>
                                        <b>Cambio del dolar:</b><br> 
                                        {{ Form::text('cambio',$cotizacionEditar->cambio,['id'=>'monto','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
                                    </div>
                                @elseif ($cotizacionEditar->tipoMoneda == 'USD')
                                    MXN <input type="radio" name="tipoMoneda" id="MXN" class="tip" value="MXN" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD <input type="radio" name="tipoMoneda" id="USD" class="tip" value="USD" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div id="cambio" style=""><br>
                                        <b>Cambio del dolar:</b><br> 
                                        {{ Form::text('cambio',$cotizacionEditar->cambio,['id'=>'monto','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class='form-group row' id='validarNotas'>
                            <div class='col-md-12'><b>*Notas y condiciones comerciales:</b></div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar->check1 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check1','si',true,['id'=>'check1'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check1','si',false,['id'=>'check1'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck1',$cotizacionEditar->textoCheck1,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check2 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check2','si',true,['id'=>'check2'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check2','si',false,['id'=>'check2'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck2',$cotizacionEditar->textoCheck2,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check3 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check3','si',true,['id'=>'check3'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check3','si',false,['id'=>'check3'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck3',$cotizacionEditar->textoCheck3,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check4 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check4','si',true,['id'=>'check4'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check4','si',false,['id'=>'check4'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck4',$cotizacionEditar->textoCheck4,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar->check5 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check5','si',true,['id'=>'check5'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check5','si',false,['id'=>'check5'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck5',$cotizacionEditar->textoCheck5,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check6 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check6','si',true,['id'=>'check6'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check6','si',false,['id'=>'check6'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck6',$cotizacionEditar->textoCheck6,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check7 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check7','si',true,['id'=>'check7'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check7','si',false,['id'=>'check7'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck7',$cotizacionEditar->textoCheck7,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check8 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check8','si',true,['id'=>'check8'])}}</div>                                    
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check8','si',false,['id'=>'check8'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck8',$cotizacionEditar->textoCheck8,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='' id="validarEstatus">
                            <div class=''><b>*Estatus de cotización:</b></div>
                            <div class="row">
                                @if($cotizacionEditar->estatus == "Pendiente de autorización")
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización',['checked' => 'checked'])}} Pendiente de autorización</div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                    </div>
                                @elseif($cotizacionEditar->estatus == "En revisión")
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión',['checked' => 'checked'])}} En revisión
                                        </div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                        </div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                    </div>
                                @elseif($cotizacionEditar->estatus == "Cancelada")
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                        </div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada',['checked' => 'checked'])}} Cancelada</div>
                                    </div>
                                @else
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                        </div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra" checked> Aceptada con orden de compra</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                    </div>
                                @endif
                            </div>
                            <div class=""style="visibility:hidden;" id="divOrdenCompra">
                                <b>*Orden de compra:</b><input class="form-control" placeholder="Ejemplo: AC286639" name="ordenCompra" type="text">
                                <b>*Orden de compra:</b>{{ Form::file('archivo',['class' => 'form-control rounded-0','id'=>'archivoOrden','onchange'=>'ValidarImagen2(this);'])}}
                            </div>
                        </div>                        
                    </div>                    
                </div>
                <div style="text-align:center;">
                <button type="button" class="btn btn-danger cancelarVersion">Cancelar</button>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="2">
            <div class="container-fluid">
                <div class="form-group row">
                    <div class="col-sm-7 numeroCotizacionGenerado" style="visibility:hidden">
                    </div>                
                    <div class="col-sm-6" id="divServicio">
                        <div class="form-group"><b>*Servicio:</b>                        
                            <select name="idServicioTecnico" id="servicio" class="form-control rounded-0" rows="4" onblur=calcularFinanciamiento()>
                                <option value="">Seleccionar opcion</option>
                                @foreach($comboServiciosTecnicos as $comboServiciosTecnicos)
                                    <option value="{{$comboServiciosTecnicos->idServicioTecnico}}">{{$comboServiciosTecnicos->claveServicio}} {{$comboServiciosTecnicos->servicioTecnico}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="detalle"><b>*Detalle:</b>                        
                            <textarea name="detalle" id="" class="form-control rounded-0" rows="3" onblur=calcularFinanciamiento()></textarea>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class=""><b>Notas:</b>                        
                            <textarea name="notas" id="" class="form-control rounded-0" rows="4" onblur=calcularFinanciamiento()></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <div class=""><b>Días de pago:</b>
                            <select class="form-control rounded-0" name="diasDePago" id="dias" onchange=calcularFinanciamiento()>
                                <option selected="selected" value="{{$diasDePago}}">{{$diasDePago}}</option>
                                <option value="0">0</option>
                                <option value="30">30</option>                        
                                <option value="60">60</option>
                                <option value="90">90</option>
								<option value="120">120</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class=""><b>*Semanas de entrega:</b>
                            <select name='semanasEntrega' id='semanasEntrega' class="form-control">
                                <option value="">Seleccionar opción</option>
                                <option value='NA'>NA</option>
                                <option value='Stock'>Stock</option>
                                <option value='2-4 días hábiles (bussines days)'>2-4 días hábiles (bussines days)</option>
                                <option value='De 3 a 5 días(Days)'>De 3 a 5 días(Days)</option>
                                <option value='5-7 días hábiles (bussines days)'>5-7 días hábiles (bussines days)</option>
                                <option value='7-11 días hábiles (bussines days)'>7-11 días hábiles (bussines days)</option>
                                <option value='11-15 días hábiles (bussines days)'>11-15 días hábiles (bussines days)</option>
                                <option value='4 semanas (Weeks)'>4 semanas (Weeks)</option>
                                <option value='5 semanas (Weeks)'>5 semanas (Weeks)</option>
                                <option value='6 semanas (Weeks)'>6 semanas (Weeks)</option>
                                <option value='7 semanas (Weeks)'>7 semanas (Weeks)</option>
                                <option value='8 semanas (Weeks)'>8 semanas (Weeks)</option>
                                <option value='9 semanas (Weeks)'>9 semanas (Weeks)</option>
                                <option value='10 semanas (Weeks)'>10 semanas (Weeks)</option>
                                <option value='10 a 12 semanas (Weeks)'>10 a 12 semanas (Weeks)</option>
                                <option value='12 a 14 semanas (Weeks)'>12 a 14 semanas (Weeks)</option>
                                <option value='13 a 15 semanas (Weeks)'>13 a 15 semanas (Weeks)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3" id="divMontoServicio">
                        <div class="" ><b>*Monto del servicio:</b></div>
                        <div class="" style="" id="montoDiv">
                            {{Form::text('montoServicio',old('montoServicio'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'montoServicio','onblur'=>'calcularFinanciamiento()','readonly'])}}
                        </div>                 
                    </div>                        

                    <div class="col-sm-3" style="">
                        <div><b>*Con financiamiento:</b></div>
                        {{Form::text('montoEquipoInicial','0',['class' => 'form-control rounded-0','id'=>'conFinanciamiento','onblur'=>'calcularPorcentaje()','readonly'])}}
                    </div>
                    <div class="col-sm-3" style="">
                        <div><b>*Cantidad:</b></div>
                        {{Form::text('cantidad',old('cantidad'),['class' => 'form-control rounded-0','placeholder' => 'Ejemplo: 1','id'=>'piezas','onblur'=>'calcularPorcentaje()','onclick'=>'calcularFinanciamiento()'])}}
                    </div>
                    <div class="col-sm-3" style="" id="unidadMedida">
                        <div><b>*Unidad de medida:</b></div>
                        {{Form::text('observacionCantidad',old('observacionCantidad'),['class' => 'form-control rounded-0','placeholder' => 'Ejemplo: personas, horas etc.','id'=>'obs','onblur'=>'calcularPorcentaje()','readonly'])}}
                    </div>
                </div>                        

                <div class="form-group row">
                    <div class="col-sm-4"><b>*Financiamiento Total:</b>
                        {{Form::text('montoEquipo','0',['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'totalFin','readonly'])}}
                    </div>
                    <div class="col-sm-5" style="">
                        <div><b>Aplicar descuento:</b></div>
                            Si <input type="radio" name="aplicaDescuento" id="siAplica" value="Si" onclick=calcularFinanciamiento()>&nbsp;&nbsp;&nbsp;&nbsp;
                            No <input type="radio" name="aplicaDescuento" id="noAplica" value="No" checked onclick=calcularFinanciamiento()>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-5" id="divSinDescuento">
                        <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion">
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar servicio  a cotización
                        </button>                        
                    </div>
                </div>

                <div id="descuentos" class="alert alert-primary" role="alert" style="display:none;">
                    <div class="form-group row divConDescuento" style="display:none;">
                        <div class="form-group row">
                            <div class="col-sm-6" id="validarTipoDescuento"><div><b>*Tipo de descuento:</b></div>                
                                <div>
                                    Porcentaje (%)<input type="radio" name="tipoDescuento" id="porcentaje" value="porcentaje">&nbsp;&nbsp;
                                    Cantidad ($)<input type="radio" name="tipoDescuento" id="cantidad" value="cantidad">&nbsp;&nbsp;
                                </div>
                                <div>
                                    {{Form::text('descuento',old('descuento'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'descuento','onblur'=>'calcularDescuento()'])}}                            
                                </div>
                            </div>
                        
                            <div class="col-sm-6">
                                <div>
                                    <b>*Financiamiento con descuento:</b> 
                                </div>
                                <br>
                                <div>
                                    {{Form::text('montoFinanciamiento','0',['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'totalDescuento','readonly'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 form-group row divConDescuento" style="display:none;">
                            <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion">
                                <span class="glyphicon glyphicon-plus-sign"></span> Agregar servicio  a cotización
                            </button>                            
                        </div>                
                    </div>
                    
                </div>
                <div id="reporteCotizacionSucursal"></div>
                <div id="reporteReparaciones" class="">
                    @if($cuantosEquiposCotizados < 1) 
                        <div class="alert alert-warning" role="alert" align="center">
                            <label for="">No se han agregado servicios a la cotización.</label>
                            {{Form::hidden('validarCuantos',$cuantosEquiposCotizados,['class' => 'validarCuantos'])}}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th colspan="6" style="text-align:right"><font SIZE=2>Total: {{$cotizacionEditar->tipoMoneda}} ${{$suma}}</font></th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr style="background-color: #C5EBFB;">
                                        <th><font SIZE=2>Servicio</font></th>
                                        <th><font SIZE=2>Detalle</font></th>
                                        <th><font SIZE=2>Tiempo de entrega</font></th>
                                        <th><font SIZE=2>Cantidad</font></th>
                                        <th style="width:350px;"><font SIZE=2>Costo unitario</font></th>
                                        <th style="width:350px;"><font SIZE=2>Costo total</font></th>
                                        <th><font SIZE=2>Notas</font></th>
                                        <th><font SIZE=2><center>Operaciones</center></font></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviciosEncotizacion as $sEc)
                                        <tr>                    
                                            <td style="text-align:center;width:300px;">                    
                                                {{$sEc->servicioTecnico}}
                                            </td>
                                            <td style="text-align:center;width:300px;">                    
                                                {{$sEc->detalle}}
                                            </td>
                                            <td style="text-align:center;">
                                            @if($sEc->semanasEntrega !="NA")
                                                {{$sEc->semanasEntrega}}
                                            @endif
                                            </td>                    
                                            <td style="text-align:center;">{{$sEc->cantidad}} {{$sEc->observacionCantidad}}</td>                    
                                            <td style="text-align:center;">
                                                <font SIZE=2>
                                                    @if($sEc->descuento !="")
                                                        <strike>{{$sEc->tipoMoneda}} $<?php echo number_format ($sEc->montoEquipo,2)?></strike>
                                                        <br>                                                   
                                                        {{$sEc->tipoMoneda}} $<?php $descuentoUnitario = $sEc->montoFinanciamiento / $sEc->cantidad; echo number_format ($descuentoUnitario,2);?>
                                                    @else
                                                        {{$sEc->tipoMoneda}} ${{$sEc->montoEquipo}}
                                                    @endif
                                                    @if($cotizacionEditar->estatus == "En revisión")
                                                        <br>                                                        
                                                        <button value="{{$sEc->idDetalleCotizacionServ}}" class="modificarMontoPartidaCotizacionServicios" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;color:blue">
                                                            <u>Modificar costo</u>
                                                        </button>
                                                    @endif                                            
                                                </font>
                                            </td>
                                            <td style="text-align:center;">
                                                <font SIZE=2>
                                                    @if($sEc->descuento !="")
                                                        <strike><?php $totalff = $sEc->cantidad * $sEc->montoEquipo; echo $sEc->tipoMoneda . ' $'.$totalff; ?></strike>
                                                        <br>
                                                        - @if($sEc->tipoDescuento == "porcentaje")
                                                            {{$sEc->descuento}} % 
                                                            @else 
                                                            $ {{$sEc->descuento}}
                                                            @endif
                                                        <br>{{$sEc->tipoMoneda}} ${{$sEc->montoFinanciamiento}}
                                                    @else
                                                        {{$sEc->tipoMoneda}} ${{$sEc->montoFinanciamiento}}
                                                    @endif                                            
                                                </font>
                                            </td>
                                            <td>
                                                {{$sEc->notas}}
                                            </td>
                                            <td align="center">
                                                <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                    {{Form::hidden('numeroCotizacionB',$cotizacionEditar->numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                                    {{Form::hidden('idSucursal',$cotizacionEditar->idSucursal,['class' => 'form-control rounded-0','id'=>'idSucursal', 'readonly'])}}
                                                    {{Form::hidden('tipoMonedaB',$cotizacionEditar->tipoMoneda,['class' => 'form-control rounded-0', 'readonly'])}}
                                                    <input type="hidden" value="{{$sEc->idCotizacionServicios}}" name="idCotizacionServicios">
                                                    <input type="hidden" value="{{$sEc->idDetalleCotizacionServ}}" name="idDetalleCotizacionServ">
                                                    <button type="button" class="btn btn-sm btn-danger borrarServicio">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" style="text-align:right"><font SIZE=2>Total: {{$cotizacionEditar->tipoMoneda}} ${{$suma}}</font></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            <div id='botonParaGuardar' style="text-align:center;">
                {{Form::submit('Guardar',['class' => 'btn  btn-success','id'=>'guardar'])}}
                <button type="button" class="btn btn-danger cancelarVersion">Cancelar</button>
                <div id="cargarModal"></div>
            </div>
        </div>
    </div>
</form>
<script>
    $(".modificarMontoPartidaCotizacionServicios").click(function (e) {        
        var idDetalleCotizacionServ = $(this).val();
        var idCotizacionServicios=$('#idCotizacionServicios').val();            
        $('#cargarModal').load('{{url('modificarMontoPartidaCotizacionServicios')}}' + '?idCotizacionServicios='+idCotizacionServicios+'&idDetalleCotizacionServ='+idDetalleCotizacionServ);
        e.preventDefault(); 
    });

    $("#idu").change(function(e) {
        var comboUsuario = document.getElementById("idu");
        var selectedUsuario = comboUsuario.options[comboUsuario.selectedIndex].text;
        document.getElementById("nombreUsuario").value = selectedUsuario;        
    });
    $("#servicio").change(function() {
        document.getElementById("totalFin").value = 0;
        document.getElementById("montoServicio").value = 0;
        document.getElementById("conFinanciamiento").value = 0;
        document.getElementById("piezas").value = 0;
        document.getElementById("obs").value = "";
        $("#detalle").load('{{url('detalleServicio')}}' + '?r=' + Date.now() + '&idServicioTecnico=' + this.options[this.selectedIndex].value);
        $("#montoDiv").load('{{url('montoServicio')}}' + '?' + $(this).closest('form').serialize());
        $("#unidadMedida").load('{{url('unidadMedida')}}' + '?r=' + Date.now() + '&idServicioTecnico=' + this.options[this.selectedIndex].value);
    })

    $(".borrarServicio").click(function() {
        $("#reporteReparaciones").load('{{url('borrarServicio')}}' + '?' + $(this).closest('form').serialize());
    });
    $(".agregarReparacion").click(function() {
        var servicio = $("#servicio").val();
        var montoServicio = $("#montoServicio").val();
        var totalFin = $("#totalFin").val();
        var piezas = $("#piezas").val();
       

        if (montoServicio == 0 || totalFin == 0 || servicio == ""|| piezas == 0) {
            if(servicio == ""){
                $("#servicio").css('border-color', 'red');
                $("#servicio").css('color', 'red');   
                $("#divServicio").css('color', 'red');   
                alert("El campo servicio es obligatorio.");            
            }else if(montoServicio == 0){
                $("#montoServicio").css('border-color', 'red');
                $("#divMontoServicio").css('color', 'red');   
                alert("El monto del servicio es obligatorio.");
            }else if(piezas == 0){
                $("#piezas").css('border-color', 'red');
                $("#piezas").css('color', 'red');
                alert("La cantidad es obligatoria.");    
            }else{
                $("#totalFin").css('border-color', 'red');
                $("#totalFin").css('color', 'red');    
                alert("El financiamiento total es obligatorio.");
            }
        }else{
            $("#reporteReparaciones").load('{{url('asignarSercicioCotizacion')}}' + '?' + $(this).closest('form').serialize());
            $('#botonParaGuardar').css('visibility', 'visible');
            $('#atras').css('visibility', 'hidden');
        }
    });
</script>

<script>
    function calcularFinanciamiento() {
        var diasDePago = $("#dias").val();
        var monto = $("#montoServicio").val();
        var montoEquipo = parseFloat(monto);
        var calculo = 0;
        if(diasDePago >= 60 &&  diasDePago < 90){
            calculo = monto * 0.085;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 90 && diasDePago <120){
            calculo = monto * 0.17;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 120){
            calculo = monto * 0.255;
            var financiamiento = parseFloat(calculo);
            document.getElementById("conFinanciamiento").value = (financiamiento+montoEquipo).toFixed(2);
        }
		else{
            document.getElementById("conFinanciamiento").value = montoEquipo.toFixed(2);
        }
    }


    function calcularPorcentaje() {
        var montoFinanciamiento = $("#conFinanciamiento").val();
        var piezas = $("#piezas").val();
        var montoFinanciamiento = parseFloat(montoFinanciamiento);
        var piezas = parseFloat(piezas);

        var calculo = montoFinanciamiento * piezas;
        var financiamiento = parseFloat(calculo).toFixed(2);
        document.getElementById("totalFin").value = financiamiento;
        
        
    }

    function calcularDescuento(){
        var financiamiento = $("#totalFin").val();
        var descuento = $("#descuento").val();

        var financiamientoEquipo = parseFloat(financiamiento);
        var descuentoEquipo = parseFloat(descuento);
        
        var tipoDescuento=$('input:radio[name=tipoDescuento]:checked').val();
        
        if(tipoDescuento=="porcentaje"){
            var porcentajeDescuento = descuento/100;
            calculo = financiamiento * porcentajeDescuento;
            var descuentoCalculado = parseFloat(calculo);

            document.getElementById("totalDescuento").value = (financiamientoEquipo-descuentoCalculado).toFixed(2);
        }else if(tipoDescuento=="cantidad"){

            document.getElementById("totalDescuento").value = (financiamientoEquipo-descuentoEquipo).toFixed(2);
        }else
        {
            alert("Debe seleccionar el tipo de descuento");
            $("#validarTipoDescuento").css("color", "red");
        }
    }

    $("#porcentaje").click(function(){
        document.getElementById("descuento").value = "";
        document.getElementById("descuento").placeholder = "Ejemplo: 10";
    })

    $("#cantidad").click(function(){
        document.getElementById("descuento").value = "";
        document.getElementById("descuento").placeholder = "Ejemplo: 150";
    })

    $("#siAplica").click(function(){
        // $("#totalDescuento").attr('name', 'montoEquipo');
        // $("#totalFin").attr('name', 'montoAnterior');
        document.getElementById("descuento").value = "";
        document.getElementById("totalDescuento").value="";
        $('#divSinDescuento').css('display', 'none');
        $('.divConDescuento').css('display', 'block');
        $('#descuentos').css('display', 'block');
        document.getElementById("descuentos").style.backgroundColor = '#F0F9FF';
    })
    
    $("#noAplica").click(function(){
        // $("#totalDescuento").attr('name', 'sinMonto');        
        // $("#totalFin").attr('name', 'montoEquipo');       
        document.getElementById("descuento").value = "";
        document.getElementById("totalDescuento").value="";
        $('#divSinDescuento').css('display', 'block');
        $('.divConDescuento').css('display', 'none');
        $('#descuentos').css('display', 'none');    
    })
</script>

<script>
    $('.mi-selector').click(function() {
        $('.mi-selector').select2();
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
    function ValidarImagen(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivoCotizacion").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivoCotizacion').val('');
        }
    }
</script>
<script>
    function ValidarImagen2(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivoOrden").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivoOrden').val('');
        }
    }
</script>


<script>
    // $("#MXN").click(function(){ 
    //     $('#cambio').css('display', 'none');        
    // })
    
    // $("#USD").click(function(){
    //     $('#cambio').css('display', 'block');  
    // })
    $(".borrarReparacion").click(function() {   
        var idSucursal = $('#comboSucursal').val();         
        $("#reporteReparaciones").load('{{url('borrarReparacion')}}' + '?' + $(this).closest('form').serialize());
        // $("#comboEquipos").load('{{url('comboEquiposCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + idSucursal);
    });
    
    $(".cancelarVersion").click(function(e) {
        $("#reporteVersiones").load('{{url('cancelarVersionServicios')}}' + '?' + $(this).closest('form').serialize());
        location.reload()
    });
    $("#guardar").click(function(e){
        var validarCuantos = $('.validarCuantos').val();
        if (validarCuantos == 0) {
            alert('Error: La cotización no puede quedarse sin servicios, debe cancelar la modificación.');
            e.preventDefault();    
        }
        let fechaCotizacion = $("#fechaCotizacion").val();
        let comboSucursal = $("#comboSucursal").val();
        if (fechaCotizacion == '' || comboSucursal == ''){
            if(fechaCotizacion == ''){
                $("#fechaCotizacion").css('border-color', 'red');
                $("#fechaCotizacion").css('color', 'red');
                alert('El campo *Fecha es obligatorio.');
                e.preventDefault();    
            }
            if(comboSucursal == ''){
                $("#comboSucursal").css('border-color', 'red');
                $("#comboSucursal").css('color', 'red');
                alert('El campo *Sucursal es obligatorio.');
                e.preventDefault();    
            }            
        }else if($("[name='tipoMoneda']:checked").val()==undefined){
            $("#validarTipo").css("color", "red");
            alert('El campo *Tipo de moneda es obligatorio.');
            e.preventDefault();    
        }else if($("[type='checkbox']:checked").val()==undefined){
            $("#validarNotas").css("color", "red");
            $(".notas").css('border-color', 'red');
            alert('El campo *Notas y condiciones comerciales es obligatorio.');
            e.preventDefault();    
        }else if($("[name='estatus']:checked").val()==undefined){
            $("#validarEstatus").css("color", "red");
            alert('El campo *Estatus es obligatorio.');                                            
            e.preventDefault();    
        }                           
            
               
    });

    $("#aceptada").click(function(e) {
    //     let archivoCotizacion = $("#archivoCotizacion").val();
    //     let archivoCotizacion2 = $("#Asubido").val();
    //     if (archivoCotizacion == '' && archivoCotizacion2 == 0){
    //         $("#archivoCotizacion").css('border-color', 'red');
    //         $("#archivoCotizacion").css('color', 'red');            
    //         alert('El campo *Archivo de cotización es obligatorio.');
    //         e.preventDefault(); 
    //     }else{
            $('#divOrdenCompra').css('visibility', 'visible');
    //     }
    });
</script>

