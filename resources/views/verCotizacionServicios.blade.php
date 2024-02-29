<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>

<form action="">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#1" data-toggle="tab"><label for="">Cotización</label></a></li>           
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="1">            
            <div class="container-fluid">            
                <br>
                <div class="alert row" style="background-color:#DBCECE" role="alert">
                    <div class="row">
                        <div class="col-md-3">
                            <b>Visualizar monto: </b>
                            Si {{Form::radio('verTotal','Si',true)}}&nbsp;&nbsp;
                            No {{Form::radio('verTotal','No',false)}}
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
                            <input type="hidden" id="idCotizacionServicios" value="{{$idCotizacionServicios}}">    
                            <button id="pdf" class="btn btn-default btn-sm" style="color: #fff;background-color: #7E0A0A;border-color: #7E0A0A;">
                                <span class="glyphicon glyphicon-print"></span> Generar cotización en PDF
                            </button>                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <b>Visualizar cambio de Dólar: </b>
                            Si {{Form::radio('verCambio','Si',true)}}&nbsp;&nbsp;
                            No {{Form::radio('verCambio','No',false)}}
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
                                    <div>{{Form::radio('estatus','Cancelada',false,['disabled'])}} Cancelada</div>
                                </div>                                                                                   
                                @elseif($cotizacionEditar1->estatus == "En revisión")
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',true,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',false,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',false,['disabled'])}} Aceptada con orden de compra</div>
                                    <div>{{Form::radio('estatus','Cancelada',false,['disabled'])}} Cancelada</div>
                                </div>                                
                                                            
                                @elseif($cotizacionEditar1->estatus == "Cancelada")
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',false,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',false,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',false,['disabled'])}} Aceptada con orden de compra</div>                                
                                    <div>{{Form::radio('estatus','Cancelada',true,['disabled'])}} Cancelada</div>
                                </div>                                                                
                                @else
                                <div class="form-group col-md-7">
                                    <div class=''><b>*Estatus de cotización:</b></div>
                                    <div>{{Form::radio('estatus','En revisión',false,['disabled'])}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',false,['disabled'])}} Pendiente de autorización</div>
                                    <div>{{Form::radio('estatus','Aceptada con orden de compra',true,['disabled'])}} Aceptada con orden de compra</div>
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
                                @if($cotizacionEditar1->estatus == "Aceptada con orden de compra")
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
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style="background-color: #E8E8E8;">
                                    <th><font SIZE=2>Servicio</font></th>
                                    <th><font SIZE=2>Detalle</font></th>
                                    <th><font SIZE=2>Tiempo de entrega</font></th>
                                    <th><font SIZE=2>Cantidad</font></th>
                                    <th style="width:350px;"><font SIZE=2>Costo unitario</font></th>
                                    <th style="width:350px;"><font SIZE=2>Costo total</font></th>
                                    <th><font SIZE=2>Notas</font></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviciosEncotizacion as $sEc)
                                    <tr>                    
                                        <td style="text-align:center;width:300px;">                    
                                            <font SIZE=2>
                                                {{$sEc->servicioTecnico}}                                        
                                            </font>
                                        </td>
                                        <td style="text-align:center;width:300px;">                    
                                            <font SIZE=2>
                                                {{$sEc->detalle}}                                        
                                            </font>
                                        </td>
                                        <td style="text-align:center;">
                                            <font SIZE=2>
                                                @if($sEc->semanasEntrega !="NA")
                                                    {{$sEc->semanasEntrega}}
                                                @endif                                        
                                            </font>
                                        </td>
                                        <td style="text-align:center;">
                                            <font SIZE=2>
                                                {{$sEc->cantidad}} {{$sEc->observacionCantidad}}
                                            </font>
                                        </td>                                                            
                                        <td style="text-align:center;">
                                            <font SIZE=2>
                                                @if($sEc->descuento !="")
                                                    <strike>{{$sEc->tipoMoneda}} $<?php echo number_format ($sEc->montoEquipo,2)?></strike>
                                                    <br>                                                   
                                                    {{$sEc->tipoMoneda}} $
                                                    <?php 
                                                        //validando que no haga divicion entre cero y truene
                                                        if ($sEc->cantidad >=1) {
                                                            $descuentoUnitario = $sEc->montoFinanciamiento / $sEc->cantidad;
                                                        }else{
                                                            $descuentoUnitario = $sEc->montoFinanciamiento;
                                                        }
                                                        echo number_format ($descuentoUnitario,2);
                                                    ?>
                                                @else
                                                    {{$sEc->tipoMoneda}} ${{$sEc->montoEquipo}}
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
                                        <font SIZE=2>
                                            {{$sEc->notas}}                                        
                                        </font>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-info" id="cerrar">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>                                    
    </div>
</form>
<script>
$("#cerrar").click(function() {
    location.reload();
    $("#altaVersion").empty();
    $('.actualizar').attr("disabled", false);
    $('.editar').attr("disabled", false);
});

$("#pdf").click(function (e) {
    var a=$('#idCotizacionServicios').val();
    var b=$('input:radio[name=verTotal]:checked').val();
    var c=$('input:radio[name=publicidad]:checked').val();
    var d=$('input:radio[name=verCambio]:checked').val();

    window.open('{{url('pdfCotizacionServicios')}}' + '?idCotizacionServicios='+a+'&verTotal='+b+'&pld='+c+ '&cD='+d);
    e.preventDefault(); 

});
</script>
