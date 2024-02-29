<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>

{{Form::open(['route' => 'guardarModificacionCotizacion','files'=>true])}}
{{Form::token()}}

<ul class="nav nav-tabs">
    <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Cotización</label></a></li>
    <li class="" id='tab2' style="visibility:visible;"><a href="#2" data-toggle="tab"><label for="">Equipos asignados</label></a></li>
</ul>

<form id="form_ev">
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
                                {{Form::hidden('idCotizacionAnterior',$idCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('idCotizacion',$cotizacionEditar1->idCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('idc',$cotizacionEditar1->idc,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('tipoMoneda',$cotizacionEditar1->tipoMoneda,['class' => 'form-control rounded-0','id'=>'tipoMoneda','readonly'])}}
                                <select name="idu" class="form-control rounded-0" id="idu">
                                    <option value="{{$cotizacionEditar1->idu}}">{{$cotizacionEditar1->usuario}}</option>
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
                        <input type="hidden" name="nombreUsuario" id="nombreUsuario" value="{{$cotizacionEditar1->nombreUsuario}}">
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
                                    <option value="{{$cotizacionEditar1->idSucursal}}">{{$cotizacionEditar1->sucursal}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-5"><b>Número de cotización:</b></div>
                            <div class="col-sm-7 numeroCotizacionGenerado">
                                {{Form::text('numeroCotizacion',$cotizacionEditar1->numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('cotizacionOriginal',$cotizacionEditar1->cotizacionOriginal,['class' => 'form-control rounded-0', 'readonly'])}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><b>Dirigido a:</b></div>
                            <div class="col-sm-9">
                                <select name='dirigidoA' class="form-control" id="comboContacto">
                                    <option value="{{$cotizacionEditar1->dirigidoA}}">{{$cotizacionEditar1->dirigidoA}}</option>
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
                                    <option value="{{$cotizacionEditar1->conCopia}}">{{$cotizacionEditar1->conCopia}}</option>
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
                                        <a target="_blank" href="{{asset ('archivos/'.$cotizacionFoto->archivoCotizacion)}}">
                                            @if(strpos($cotizacionFoto->archivoCotizacion,'pdf'))
                                                <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                            @else
                                                <img src="{{asset ('archivos/'.$cotizacionFoto->archivoCotizacion)}}" height=80 width=80>
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
                                <?php                            
                                    $montoTotal = number_format ($cotizacionEditar1->montoTotal,2);                                    
                                ?>
                                {{Form::text('montoTotalView',$montoTotal,['id'=>'','class' => 'form-control rounded-0', 'readonly'])}} 
                                {{Form::hidden('montoTotal',$cotizacionEditar1->montoTotal,['id'=>'monto','class' => 'form-control rounded-0', 'readonly'])}}
                            </div>
                            <div class="col-sm-6" id="validarTipo"><b>*Tipo de moneda:</b> <br>
                                @if ($cotizacionEditar1->tipoMoneda == 'MXN')
                                    MXN <input type="radio" name="tipoMoneda" id="MXN" class="tip" value="MXN" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD <input type="radio" name="tipoMoneda" id="USD" class="tip" value="USD" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div id="cambio"><br>
                                        <b>Cambio del dolar:</b><br> 
                                        {{ Form::text('cambio',$cotizacionEditar1->cambio,['id'=>'monto','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
                                    </div>
                                @elseif ($cotizacionEditar1->tipoMoneda == 'USD')
                                    MXN <input type="radio" name="tipoMoneda" id="MXN" class="tip" value="MXN" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD <input type="radio" name="tipoMoneda" id="USD" class="tip" value="USD" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div id="cambio"><br>
                                        <b>Cambio del dolar:</b><br> 
                                        {{ Form::text('cambio',$cotizacionEditar1->cambio,['id'=>'monto','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class='form-group row' id='validarNotas'>
                            <div class='col-md-12'><b>*Notas y condiciones comerciales:</b></div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar1->check1 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check1','si',true,['id'=>'check1'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check1','si',false,['id'=>'check1'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck1',$cotizacionEditar1->textoCheck1,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check2 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check2','si',true,['id'=>'check2'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check2','si',false,['id'=>'check2'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck2',$cotizacionEditar1->textoCheck2,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check3 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check3','si',true,['id'=>'check3'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check3','si',false,['id'=>'check3'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck3',$cotizacionEditar1->textoCheck3,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check4 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check4','si',true,['id'=>'check4'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check4','si',false,['id'=>'check4'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck4',$cotizacionEditar1->textoCheck4,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar1->check5 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check5','si',true,['id'=>'check5'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check5','si',false,['id'=>'check5'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck5',$cotizacionEditar1->textoCheck5,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check6 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check6','si',true,['id'=>'check6'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check6','si',false,['id'=>'check6'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck6',$cotizacionEditar1->textoCheck6,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check7 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check7','si',true,['id'=>'check7'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check7','si',false,['id'=>'check7'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck7',$cotizacionEditar1->textoCheck7,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check8 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check8','si',true,['id'=>'check8'])}}</div>                                    
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check8','si',false,['id'=>'check8'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck8',$cotizacionEditar1->textoCheck8,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='' id="validarEstatus">
                            <input type="hidden" value="{{$cotizacionEditar1->estatus}}" name="estatusAnterior">
                            <div class=''><b>*Estatus de cotización:</b></div>
                            <div class="row">
                                @if($cotizacionEditar1->estatus == "Pendiente de autorización")
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización',['checked' => 'checked'])}} Pendiente de autorización</div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                        <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                    </div>
                                @elseif($cotizacionEditar1->estatus == "En revisión")
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión',['checked' => 'checked'])}} En revisión
                                        </div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                        </div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                        <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                    </div>
                                @elseif($cotizacionEditar1->estatus == "Cancelada")
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                        </div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                        <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada',['checked' => 'checked'])}} Cancelada</div>
                                    </div>
                                @elseif($cotizacionEditar1->estatus == "Facturada")
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                        </div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                        <div>{{Form::radio('estatus','Facturada',true,['disabled'])}} Facturada</div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                    </div>
                                @else
                                    <div class="form-group col-md-7">
                                        <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                        <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                        </div>
                                        <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra" checked> Aceptada con orden de compra</div>
                                        <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
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
                <br>
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="form-group row">                            
                                <div class="col-sm-3">
                                    <b>*¿Que tipo de equipo cotizas?:</b>
                                </div>
                                <div class="col-sm-3" >
                                    <select name='tipoCotizacion' id='tipoCotizacion' class="form-control">
                                        <option value="">Seleccionar opción</option>                                       
                                        <option value="reparacion">Reparación</option>
                                        <option value="nuevo">Nuevo</option>
                                        <option value="refurbished">Refurbished y/o Repair</option>
                                    </select>
                                </div>
                        </div>
                    </div>                    
                </div>
                <div class="row seleccionarEquipo" style="visibility:hidden;">
                    <div class="form-group col-sm-6 seleccionarEquipo">
                        <div class="form-group row">                            
                                <div class="col-sm-3"><b>*Seleccionar equipo:</b></div>
                                <div class="col-sm-9">
                                    <select name='idEquipos' id='comboEquipos' class="form-control rounded-0 mi-selector">
                                        <option value="">Seleccione un equipo</option>
                                        @foreach($comboEquiposenCotizacion as $cEc)
                                            @if($cEc->activo=="Si")
                                                <option value='{{$cEc->idEquipos}}'>{{$cEc->GCMid}} - {{$cEc->complementoGCMid}}</option>
                                            @endif
                                        @endforeach
                                        @foreach($comboEquipos as $cE)
                                            @if($cE->activo=="Si")
                                                <option value='{{$cE->idEquipos}}'>{{$cE->GCMid}} - {{$cE->complementoGCMid}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="partida" id="partida" value="{{$partidaSiguiente}}">
                <div id="reporteCotizacionSucursal"></div>
                <div id="reporteReparaciones_ev">
                    <div id="borrando">
                        @if($cuantosEquiposCotizados < 1) 
                            <div class="alert alert-warning" role="alert" align="center">
                                <label for="">No se han agregado equipos a cotización.</label>
                                {{Form::hidden('validarCuantos',$cuantosEquiposCotizados,['class' => 'validarCuantos'])}}
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th colspan="9" style="text-align:right"><font SIZE=2>Total: {{$cotizacionEditar1->tipoMoneda}} ${{$suma}}</font></th>
                                            <th colspan="2"></th>
                                        </tr>
                                        <tr style="background-color: #C5EBFB;">
                                            <th><font SIZE=2>Tipo de equipo</font></th>
                                            <th><font SIZE=2>SKU / Descripción</font></th>
                                            <th><font SIZE=2>Equipo</font></th>
                                            <th><font SIZE=2>Modelo</font></th>
                                            <th><font SIZE=2>GCMid Parte</font></th>
                                            <th><font SIZE=2>Tiempo de entrega</font></th>
                                            <th><font SIZE=2>Piezas</font></th>
                                            <th style="width:400px;"><font SIZE=2>Costo unitario</font></th>
                                            <th style="width:400px;"><font SIZE=2>Costo total</font></th>
                                            <th><font SIZE=2>Notas</font></th>
                                            <th><font SIZE=2><center>Operaciones</center></font></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($equiposNuevosYreparados as $equiposNYp)
                                            <tr>
                                                <td style="text-align:center;width:100px;">                    
                                                    <font SIZE=2>
                                                        @if($equiposNYp->tipoCotizacion == "reparacion")
                                                            Reparación
                                                        @elseif($equiposNYp->tipoCotizacion == "nuevo")
                                                            Nuevo
                                                        @else
                                                            Refurbished
                                                        @endif
                                                    </font>
                                                </td>
                                                <td style="text-align:center;width:200px;">                    
                                                    <font SIZE=2>
                                                        {{$equiposNYp->descripcion}}
                                                    </font>
                                                </td>
                                                @if($equiposNYp->tipoCotizacion == "reparacion")
                                                    <td style="text-align:center;width:200px;">                    
                                                        <font SIZE=2>
                                                            @foreach($datosEquipoCotizado as $dEc)                
                                                                @if($equiposNYp->idEquipos == $dEc->idEquipos)
                                                                    {{$dEc->equipo}}
                                                                @endif                
                                                            @endforeach                    
                                                        </font>
                                                    </td>
                                                    <td style="text-align:center;width:200px;">                    
                                                        <font SIZE=2>
                                                            {{$equiposNYp->modelo}}
                                                        </font>
                                                    </td> 
                                                    @foreach($equiposReparados as $equipoR)
                                                        @if($equiposNYp->idEquipos == $equipoR->idEquipos)
                                                            <td style="text-align:center;width:200px;">
                                                                <font SIZE=2 color="#2F7DA7">
                                                                    <button value="{{$equiposNYp->partida}}" class="verDetallePartida" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;">
                                                                        {{$equipoR->GCMid}} - {{$equipoR->complementoGCMid}}
                                                                    </button>
                                                                </font>
                                                            </td>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <td style="text-align:center;width:200px;">
                                                        <font SIZE=2>
                                                            @foreach($datosEquiposNuevos as $dEn)                
                                                                @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                                    {{$dEn->nombreRefaccion}}
                                                                    @break;
                                                                @endif                
                                                            @endforeach 
                                                        </font>
                                                    </td>
                                                    <td style="text-align:center;width:200px;">                    
                                                        <font SIZE=2>
                                                            {{$equiposNYp->modelo}}
                                                        </font>
                                                    </td>
                                                    <td style="text-align:center;">
                                                        <font SIZE=2>
                                                            @foreach($datosEquiposNuevos as $dEn)                
                                                                @if($equiposNYp->idPartesVenta == $dEn->idPartesVenta)
                                                                    {{$dEn->numeroParte}}
                                                                    @break;
                                                                @endif                
                                                            @endforeach
                                                        </font>
                                                    </td>
                                                @endif
                                                <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->semanasEntrega}}</font></td>
                                                <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->piezas}}</font></td>
                                                <td style="text-align:center;width:350px;">
                                                    <font SIZE=2>
                                                        @if($equiposNYp->descuento !="")
                                                            <strike>{{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?></strike>
                                                            <br>
                                                            {{$cotizacionEditar1->tipoMoneda}} $
                                                            <?php 
                                                                //validando que no haga divicion entre cero y truene
                                                                if ($equiposNYp->piezas >=1) {
                                                                    $descuentoUnitario = $equiposNYp->montoFinanciamiento / $equiposNYp->piezas;
                                                                }else{
                                                                    $descuentoUnitario = $equiposNYp->montoFinanciamiento;
                                                                }
                                                                echo number_format ($descuentoUnitario,2);
                                                            ?>
                                                        @else
                                                            {{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                                        @endif
                                                    </font>
                                                </td>
                                                <td style="text-align:center; width:350px;">
                                                    <font SIZE=2>
                                                        @if($equiposNYp->descuento !="")
                                                            <strike>{{$cotizacionEditar1->tipoMoneda}} $ <?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?></strike>
                                                            <br>
                                                            - @if($equiposNYp->tipoDescuento == "porcentaje")
                                                                {{$equiposNYp->descuento}}% 
                                                            @else 
                                                                ${{$equiposNYp->descuento}}
                                                            @endif                                                    
                                                            <br> {{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                                        @else
                                                            {{$cotizacionEditar1->tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                                        @endif
                                                    </font>
                                                </td>    
                                                <td>
                                                    <font SIZE=2>
                                                        {{$equiposNYp->notas}}
                                                    </font>
                                                </td>
                                                <td align="center">
                                                    <form id='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                                        @csrf
                                                        {{Form::hidden('numeroCotizacionB',$cotizacionEditar1->numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                                        {{Form::hidden('tipoCotizacionB',$equiposNYp->tipoCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                                        {{Form::hidden('idSucursal',$cotizacionEditar1->idSucursal,['class' => 'form-control rounded-0', 'readonly'])}}
                                                        {{Form::hidden('idEquiposB',$equiposNYp->idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                                                        {{Form::hidden('tipoMonedaB',$cotizacionEditar1->tipoMoneda,['class' => 'form-control rounded-0', 'readonly'])}}
                                                        {{Form::hidden('partidaBorrar',$equiposNYp->partida,['class' => 'form-control rounded-0', 'readonly'])}}
                                                        <input type="hidden" value="{{$equiposNYp->idCotizacion}}" name="idCotizacionB" id="idCotizacion">
                                                        <input type="hidden" value="{{$equiposNYp->idRepCotizacion}}" name="idRepCotizacion">
                                                        @if($cotizacionEditar1->estatus == "En revisión")
                                                            <button value="{{$equiposNYp->partida}}" class="modificarMontoPartidaCotizacion btn btn-info btn-sm" type="button">
                                                                <i class="fa fa-pencil bigger"></i>
                                                            </button>
                                                        @endif
                                                        <button type="button" class="btn btn-sm btn-danger borrarReparacion">
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
                <div id='botonParaGuardar' style="text-align:center;">
                {{Form::submit('Guardar',['class' => 'btn  btn-success','id'=>'guardar'])}}
                <button type="button" class="btn btn-danger cancelarVersion">Cancelar</button>
                <div id="cargarModal"></div>
            </div>            
        </div>
    </div>
</form>
<script>
$("#idu").change(function(e) {
    var comboUsuario = document.getElementById("idu");
    var selectedUsuario = comboUsuario.options[comboUsuario.selectedIndex].text;
    document.getElementById("nombreUsuario").value = selectedUsuario;        
});
$(".verDetallePartida").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();
    var tipoMoneda=$('#tipoMoneda').val();
    $('#cargarModal').load('{{url('detallePartidasCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
    e.preventDefault(); 
});
$(".modificarMontoPartidaCotizacion").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();    
    var tipoMoneda=$('#tipoMoneda').val();
    $('#cargarModal').load('{{url('modificarMontoPartidaCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
    e.preventDefault(); 
});
</script>

<script>
    $('.mi-selector').click(function() {
        $('.mi-selector').select2();
    });    
</script>
<script>
    $("#tipoCotizacion").change(function(e) {
        var opcionTipo = $('#tipoCotizacion').val();
        // let comboSucursal = $("#comboSucursal").val();
        // $("#comboEquipos").load('{{url('comboEquiposCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + comboSucursal);
        switch (opcionTipo) {
            case 'reparacion':
                $("#reporteReparaciones_ev").empty();
                $('.seleccionarEquipo').css('display', 'block');
                $('.seleccionarEquipo').css('visibility', 'visible');
            break;                   
            case 'nuevo':
                $("#reporteReparaciones_ev").empty();
                $('.seleccionarEquipo').css('display', 'none');
                $("#reporteReparaciones_ev").load('{{url('cotizacionEquipoNuevo')}}' + '?' + $(this).closest('form').serialize(),
                    function() {
                        setTimeout(() => {
                            $("#marcas").select2({width:'100%'});
                            $("#partes").select2({width:'100%'});
                        }, 600);
                    }
                );
            break;
            case 'refurbished':
                $("#reporteReparaciones_ev").empty();
                $('.seleccionarEquipo').css('display', 'none');
                $("#reporteReparaciones_ev").load('{{url('cotizacionRefurbished')}}' + '?' + $(this).closest('form').serialize(),
                    function() {
                        setTimeout(() => {
                            $("#marcas").select2({width:'100%'});
                            $("#partes").select2({width:'100%'});
                        }, 600);
                    }
                );
            break;
        }
    });
    
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
    $(".borrarReparacion").click(function(e) {   
        let btn 

        if(e.target.classList.contains('fa')) {
            btn = e.target.parentElement;
        }else{
            btn = e.target;
        }
        
        console.log(e.target)
        console.log(btn)

        btn.classList.add("disabled");

        var formData = new FormData(btn.parentElement);
        
        $.ajax({
                data: formData,
                url: '{{url('borrarReparacion')}}',
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    setTimeout(() => {
                        document.querySelector("#tipoCotizacion").value = "nuevo"
                            
                            var event = new Event('change');
                            // Dispatch it.
                            document.querySelector("#tipoCotizacion").dispatchEvent(event);
                            
                            setTimeout(() => {
                                console.log(document.querySelector("#reporteReparaciones_ev").firstElementChild.nextElementSibling)
                                document.querySelector("#reporteReparaciones_ev").firstElementChild.nextElementSibling.remove()
                            }, 600);
                            document.querySelector("#tipoCotizacion").value = ""
                    }, 100);
                },
                error: function (err) {
                    console.log(err);
                }
            });
    });
    $("#comboEquipos").change(function(){
        $("#reporteReparaciones_ev").load('{{url('editarAsignacionEquiposCotizacion')}}' + '?' + $(this).closest('form').serialize());
    });
    $(".cancelarVersion").click(function(){
        // $("#reporteVersiones").empty();
        // $("#altaVersion").empty();
        $("#reporteVersiones").load('{{url('cancelarVersion')}}' + '?' + $(this).closest('form').serialize());
        location.reload();
    });
    $("#guardar").click(function(e){
        var validarCuantos = $('.validarCuantos').val();
        if (validarCuantos == 0) {
            alert('Error: La cotización no puede quedarse sin equipos, debe cancelar la modificación.');
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
        // let archivoCotizacion = $("#archivoCotizacion").val();
        // let archivoCotizacion2 = $("#Asubido").val();
        // if (archivoCotizacion == '' && archivoCotizacion2 == 0){
        //     $("#archivoCotizacion").css('border-color', 'red');
        //     $("#archivoCotizacion").css('color', 'red');            
        //     alert('El campo *Archivo de cotización es obligatorio.');
        //     e.preventDefault(); 
        // }else{
            $('#divOrdenCompra').css('visibility', 'visible');
        // }
    });
</script>

