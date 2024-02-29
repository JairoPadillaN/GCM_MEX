<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>

<form action="">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="cargar" data-toggle="pill" href="#1">Cotizacion</a>
        </li>
        <li class="nav-item">
            <a data-toggle="pill" href="#2">Actividades</a>
        </li>    
    </ul>
    <!-- <ul class="nav nav-tabs">
        <li class="active"><a href="#1" data-toggle="tab"><label for="">Cotización</label></a></li>           
    </ul> -->
    <div class="tab-content">
        <div class="tab-pane active" id="1">            
            <div class="container-fluid">            
                <br>
                <div class="alert row" style="background-color:#DBCECE" role="alert">                    
                    {{Form::hidden('tipoMoneda',$cotizacionEditar1->tipoMoneda,['class' => 'form-control rounded-0','id'=>'tipoMoneda','readonly'])}}
                    <div class="row">
                        <div class="col-md-3">
                            <b>Visualizar monto: </b>
                            Si{{Form::radio('verTotal','Si',true)}}&nbsp;&nbsp;
                            No{{Form::radio('verTotal','No',false)}}
                        </div>
                        <div class="col-md-6">
                            <b>Publicidad:</b>
                                SURJA {{Form::radio('publicidad','5',true)}}&nbsp;
                                Automatización {{Form::radio('publicidad','1',false)}}&nbsp;
                                CNC {{Form::radio('publicidad','2',false)}}&nbsp;
                                Hidráulica {{Form::radio('publicidad','3',false)}}&nbsp;
                                Husky {{Form::radio('publicidad','4',false)}}&nbsp;
                                CYM Group {{Form::radio('publicidad','6',false)}}
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="idCotizacion" value="{{$idCotizacion}}">    
                            <button id="pdf" class="btn btn-default btn-sm" style="color: #fff;background-color: #7E0A0A;border-color: #7E0A0A;">
                                <span class="glyphicon glyphicon-print"></span> Generar cotización en PDF
                            </button>                           
                        </div>                     
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <b>Visualizar cambio de Dólar: </b>
                            Si{{Form::radio('verCambio','Si',true)}}&nbsp;&nbsp;
                            No{{Form::radio('verCambio','No',false)}}
                        </div>                        
                    </div>                                                
                </div>                
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-4"><b>Registrado por:</b></div>
                            <div class="col-sm-8">                        
                                <input type="text" name="usuarioViaje" class="form-control" readonly value="{{$cotizacionEditar1->usuario}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Fecha:</b></div>
                            <div class="col-sm-9">
                                {{Form::date('fechaCotizacion',$cotizacionEditar1->fechaCotizacion,['class' => 'form-control','id'=>'fechaCotizacion','readonly'])}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Sucursal:</b></div>
                            <div class="col-sm-9">
                                <select name='idSucursal' id='comboSucursal' class="form-control" readonly>
                                    <option value="{{$cotizacionEditar1->idSucursal}}">{{$cotizacionEditar1->sucursal}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-5"><b>Número de cotización:</b></div>
                            <div class="col-sm-7 numeroCotizacionGenerado">
                                {{Form::text('numeroCotizacion',$cotizacionEditar1->numeroCotizacion,['class' => 'form-control', 'readonly'])}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><b>Dirigido a:</b></div>
                            <div class="col-sm-9">
                                <select name='dirigidoA' class="form-control comboContacto" readonly>
                                    <option value="{{$cotizacionEditar1->dirigidoA}}">{{$cotizacionEditar1->dirigidoA}}</option>                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2"><b>Cc:</b></div>
                            <div class="col-sm-10">
                                <select name='conCopia' class="form-control comboContacto" readonly>
                                    <option value="{{$cotizacionEditar1->conCopia}}">{{$cotizacionEditar1->conCopia}}</option>                                    
                                </select>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <div class="col-sm-2"><b>Monto:</b></div>
                            <div class="col-sm-4">
                            <?php                            
                                $montoYmoneda = $cotizacionEditar1->tipoMoneda." $".$cotizacionEditar1->montoTotal;
                            ?>
                                {{Form::text('montoTotal',$montoYmoneda,['id'=>'monto','class' => 'form-control', 'readonly'])}}
                            </div>                          
                        </div>
                        <!-- <div class="form-group row">                    
                            <div class="col-sm-12"><b>*Tipo de moneda:</b><br>
                                @if ($cotizacionEditar1->tipoMoneda == 'MXN')
                                    MXN {{Form::radio('tipoMoneda','MXN',true,['disabled'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD {{Form::radio('tipoMoneda','USD',false,['disabled'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                                @elseif ($cotizacionEditar1->tipoMoneda == 'USD')
                                    MXN {{Form::radio('tipoMoneda','MXN',false,['disabled'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD {{Form::radio('tipoMoneda','USD',true,['disabled'])}}&nbsp;&nbsp;&nbsp;&nbsp;
                                @endif
                            </div>
                        </div> -->
                    </div>

                    <div class="form-group col-md-6">
                        
                        <div class='form-group row'>
                            <div class='col-md-12'><b>*Notas y condiciones comerciales:</b></div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar1->check1 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check1','si',true,['id'=>'check1','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check1','si',false,['id'=>'check1','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck1',$cotizacionEditar1->textoCheck1,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check2 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check2','si',true,['id'=>'check2','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check2','si',false,['id'=>'check2','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck2',$cotizacionEditar1->textoCheck2,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check3 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check3','si',true,['id'=>'check3','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check3','si',false,['id'=>'check3','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck3',$cotizacionEditar1->textoCheck3,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check4 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check4','si',true,['id'=>'check4','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check4','si',false,['id'=>'check4','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck4',$cotizacionEditar1->textoCheck4,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar1->check5 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check5','si',true,['id'=>'check5','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check5','si',false,['id'=>'check5','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck5',$cotizacionEditar1->textoCheck5,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check6 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check6','si',true,['id'=>'check6','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check6','si',false,['id'=>'check6','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck6',$cotizacionEditar1->textoCheck6,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check7 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check7','si',true,['id'=>'check7','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check7','si',false,['id'=>'check7','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck7',$cotizacionEditar1->textoCheck7,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar1->check8 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check8','si',true,['id'=>'check8','disabled'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">
                                        {{ Form::checkbox('check8','si',false,['id'=>'check8','disabled'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck8',$cotizacionEditar1->textoCheck8,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class="">
                                @if($cotizacionEditar1->estatus == "Pendiente de autorización")
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',false,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',true,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',false,['disabled'])}} Aceptada con orden de compra</div>
                                    <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
                                    <div>{{Form::radio('estatus','Cancelada',false,['disabled'])}} Cancelada</div>                                    
                                </div>                                                                                   
                                @elseif($cotizacionEditar1->estatus == "En revisión")
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',true,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',false,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',false,['disabled'])}} Aceptada con orden de compra</div>
                                    <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
                                    <div>{{Form::radio('estatus','Cancelada',false,['disabled'])}} Cancelada</div>                                    
                                </div>                                
                                                            
                                @elseif($cotizacionEditar1->estatus == "Cancelada")
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',false,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',false,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',false,['disabled'])}} Aceptada con orden de compra</div>
                                    <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
                                    <div>{{Form::radio('estatus','Cancelada',true,['disabled'])}} Cancelada</div>
                                </div>                                                                
                                @elseif($cotizacionEditar1->estatus == "Facturada")
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',false,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',false,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',false,['disabled'])}} Aceptada con orden de compra</div>
                                    <div>{{Form::radio('estatus','Facturada',true,['disabled'])}} Facturada</div>
                                    <div>{{Form::radio('estatus','Cancelada',false,['disabled'])}} Cancelada</div>
                                </div>                                                                
                                @else
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',false,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',false,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',true,['disabled'])}} Aceptada con orden de compra</div>
                                    <div>{{Form::radio('estatus','Facturada',false,['disabled'])}} Facturada</div>
                                    <div>{{Form::radio('estatus','Cancelada',false,['disabled'])}} Cancelada</div>                                    
                                </div>                                                                                   
                                @endif                                
                            </div>
                        </div>
                        <div class='col-md-6'><b>*Archivo:</b>
                            <div>
                                @foreach($fotoCotizacionEditar as $cotizacionFoto)
                                    @if($cotizacionFoto->archivoCotizacion =='Sin archivo')
                                        <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                    @else
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
                        </div>
                        <div class='col-md-6'><b>*Archivo Orden de Compra:</b>
                            <div>
                                @if($cotizacionEditar1->estatus == "Aceptada con orden de compra" || $cotizacionEditar1->estatus == "Facturada")
                                    @foreach($archivoOrdenCompra as $archivoOC)
                                        @if($archivoOC->archivo =='Sin archivo')
                                            <h5 align="center"><span class="label label-warning">Sin archivo de orden de compra</span></h5>
                                        @else
                                            <a target="_blank" href="{{asset ('archivos/'.$archivoOC->archivo)}}">
                                                @if(strpos($archivoOC->archivo,'pdf'))
                                                    <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                                @else
                                                    <img src="{{asset ('archivos/'.$archivoOC->archivo)}}" height=80 width=80>
                                                @endif
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <h5 align="center"><span class="label label-warning">Sin archivo de orden de compra</span></h5>
                                @endif
                            </div>
                        </div>                                   
                    </div>                    
                </div>
                <div style="text-align:center;">
                    <b>Equipos en cotización</b>
                </div>
                <br>
                <div style="text-align:center;">
                    @if($cotizacionEditar1->noEquipos < 1) 
                        <div class="alert alert-warning" role="alert" align="center">
                            <label for="">No se han agregado equipos a cotización.</label>                            
                        </div>
                    @else                    
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #E8E8E8;">                                
                                        <th><font SIZE=2>Tipo de equipo</font></th>
                                        <th><font SIZE=2>SKU / Descripción</font></th>
                                        <th><font SIZE=2>Equipo</font></th>
                                        <th><font SIZE=2>Modelo</font></th>
                                        <th><font SIZE=2>GCMid Parte</font></th>
                                        <th><font SIZE=2>Tiempo de entrega</font></th>
                                        <th><font SIZE=2>Piezas</font></th>
                                        <th><font SIZE=2>Costo unitario</font></th>
                                        <th><font SIZE=2>Costo total</font></th>
                                        <th><font SIZE=2>Notas</font></th>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <button type="button" class="btn btn-info" id="cerrar">
                        Cerrar
                    </button>
                </div>
                <div id="cargarModal"></div>
            </div>
        </div>                                    
        <div class="tab-pane active" id="2">            
            <br><h3>Reporte de Actividades</h3><br><br>
            @if($cuantosSegui==0)
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
                                    
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
    $(".cargar").click();
});
$("#cerrar").click(function() {
    location.reload();
    $("#altaVersion").empty();
    $('.actualizar').attr("disabled", false);
    $('.editar').attr("disabled", false);
});

$("#pdf").click(function (e) {
    var a=$('#idCotizacion').val();
    var b=$('input:radio[name=verTotal]:checked').val();
    var c=$('input:radio[name=publicidad]:checked').val();
    var d=$('input:radio[name=verCambio]:checked').val();

    window.open('{{url('pdfCotizacion')}}' + '?idCotizacion='+a+'&verTotal='+b+'&pld='+c + '&cD='+d);
    e.preventDefault();

});

$(".verDetallePartida").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();
    var tipoMoneda=$('#tipoMoneda').val();
    $('#cargarModal').load('{{url('detallePartidasCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
    e.preventDefault();
});

</script>
