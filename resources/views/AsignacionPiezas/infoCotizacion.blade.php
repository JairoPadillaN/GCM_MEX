<h4>Informacion de Cotización</h4><br>
<div class="row">
    <div class="form-group col-md-6">
        <div class="form-group row">
            <div class="col-sm-4"><b>Registrado por:</b></div>
            <div class="col-sm-8">                        
            {{Form::text('usuario',$consultaCotizacion->usuario,['class' => 'form-control','id'=>'fechaCotizacion','readonly'])}}
            {{Form::hidden('idFactura',$consultaCotizacion->idFactura,['class' => 'form-control','id'=>'fechaCotizacion','readonly'])}}
            {{Form::text('idCotizacion',$consultaCotizacion->idCotizacion,['class' => 'form-control','id'=>'idCotizacion','readonly'])}}
            {{Form::text('tipoMoneda',$consultaCotizacion->tipoMoneda,['class' => 'form-control rounded-0','id'=>'tipoMoneda','readonly'])}}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3"><b>*Fecha:</b></div>
            <div class="col-sm-9">
                {{Form::date('fechaCotizacion',$consultaCotizacion->fechaCotizacion,['class' => 'form-control','id'=>'fechaCotizacion','readonly'])}}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3"><b>*Sucursal:</b></div>
            <div class="col-sm-9">
                <select name='idSucursal' id='comboSucursal' class="form-control" readonly>
                    <option value="{{$consultaCotizacion->idSucursal}}">{{$consultaCotizacion->sucursal}}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3"><b>Dirigido a:</b></div>
            <div class="col-sm-9">
                <select name='dirigidoA' class="form-control comboContacto" readonly>
                    <option value="{{$consultaCotizacion->dirigidoA}}">{{$consultaCotizacion->dirigidoA}}</option>                                    
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-2"><b>Cc:</b></div>
            <div class="col-sm-10">
                <select name='conCopia' class="form-control comboContacto" readonly>
                    <option value="{{$consultaCotizacion->conCopia}}">{{$consultaCotizacion->conCopia}}</option>                                    
                </select>
            </div>
        </div> 

    </div>

    <div class="form-group col-md-6">
        <div class="form-group row">
            <div class="col-sm-5"><b>Número de cotización:</b></div>
            <div class="col-sm-7 numeroCotizacionGenerado">
                {{Form::text('numeroCotizacion',$consultaCotizacion->numeroCotizacion,['class' => 'form-control', 'readonly'])}}
            </div>
        </div>
        
        <div class='form-group row'>
            <div class='col-md-12'><b>*Notas y condiciones comerciales:</b></div>
            <div class="col-md-6">
                <div class="row">
                    @if($consultaCotizacion->check1 == "si")
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check1','si',true,['id'=>'check1','disabled'])}}</div>
                    @else
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check1','si',false,['id'=>'check1','disabled'])}}</div>
                    @endif
                    <div class="col-md-10">
                        {{ Form::text('textoCheck1',$consultaCotizacion->textoCheck1,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                    </div>
                </div>
                <div class="row">
                    @if($consultaCotizacion->check2 == "si")
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check2','si',true,['id'=>'check2','disabled'])}}</div>
                    @else
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check2','si',false,['id'=>'check2','disabled'])}}</div>
                    @endif
                    <div class="col-md-10">
                        {{ Form::text('textoCheck2',$consultaCotizacion->textoCheck2,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                    </div>
                </div>
                <div class="row">
                    @if($consultaCotizacion->check3 == "si")
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check3','si',true,['id'=>'check3','disabled'])}}</div>
                    @else
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check3','si',false,['id'=>'check3','disabled'])}}</div>
                    @endif
                    <div class="col-md-10">
                        {{ Form::text('textoCheck3',$consultaCotizacion->textoCheck3,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                    </div>
                </div>
                <div class="row">
                    @if($consultaCotizacion->check4 == "si")
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check4','si',true,['id'=>'check4','disabled'])}}</div>
                    @else
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check4','si',false,['id'=>'check4','disabled'])}}</div>
                    @endif
                    <div class="col-md-10">
                        {{ Form::text('textoCheck4',$consultaCotizacion->textoCheck4,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    @if($consultaCotizacion->check5 == "si")
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check5','si',true,['id'=>'check5','disabled'])}}</div>
                    @else
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check5','si',false,['id'=>'check5','disabled'])}}</div>
                    @endif
                    <div class="col-md-10">
                        {{ Form::text('textoCheck5',$consultaCotizacion->textoCheck5,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                    </div>
                </div>
                <div class="row">
                    @if($consultaCotizacion->check6 == "si")
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check6','si',true,['id'=>'check6','disabled'])}}</div>
                    @else
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check6','si',false,['id'=>'check6','disabled'])}}</div>
                    @endif
                    <div class="col-md-10">
                        {{ Form::text('textoCheck6',$consultaCotizacion->textoCheck6,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                    </div>
                </div>
                <div class="row">
                    @if($consultaCotizacion->check7 == "si")
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check7','si',true,['id'=>'check7','disabled'])}}</div>
                    @else
                    <div class="col-md-1" style="padding-top:8px;">
                        {{ Form::checkbox('check7','si',false,['id'=>'check7','disabled'])}}</div>
                    @endif
                    <div class="col-md-10">
                        {{ Form::text('textoCheck7',$consultaCotizacion->textoCheck7,['class' => 'form-control','style'=>'margin: 2px;','readonly'])}}
                    </div>
                </div>
            </div>
        </div>
                                   
    </div>  

    <div class="from-gropu col-md-12"></div>
        <div style="text-align:center;">
            <b>Equipos en cotización</b>
        </div>
    </div>
    <br>

    <div style="text-align:center;"> 
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr style="background-color: #E8E8E8;">                                
                        <th><font SIZE=2>Tipo de equipo</font></th>
                        <th><font SIZE=2>Descripción</font></th>
                        <th><font SIZE=2>Equipo</font></th>
                        <th><font SIZE=2>Modelo</font></th>
                        <th><font SIZE=2>GCMid Parte</font></th>
                        <th><font SIZE=2>Tiempo de entrega</font></th>
                        <th><font SIZE=2>Total SKU's</font></th>
                        <th><font SIZE=2>SKU Asignados</font></th> 
                        <th style="width: 15%;"><font SIZE=2>Opciones</font></th> 
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
                                            @if($equiposNYp->idEquipos == $dEn->idEquipos)
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
                                            @if($equiposNYp->idEquipos == $dEn->idEquipos)
                                                {{$dEn->codigo}}
                                                @break;
                                            @endif                
                                        @endforeach
                                    </font>
                                </td>
                            @endif
                            <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->semanasEntrega}}</font></td>
                            <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->piezas}}</font></td>
                            <td style="text-align:center;"><font SIZE=2>{{$equiposNYp->piezasOcupadas}}</font></td>
                            <td style="text-align:center;"><font SIZE=2>   
                                <!-- <form action="">
                                @csrf
                                    <input type="text" value="{{$equiposNYp->idEquipos}}" name="idEquipos">
                                    <input type="text" value="{{$idFactura}}" name="idFactura">
                                    <input type="text" value="{{$equiposNYp->idRepCotizacion}}" name="idRepCotizacion">
                                    <button type="button" class="btn btn-info servicioAsignado" style='width:40px; height: 35px;'>
                                        <i class="fa fa-eye" aria-hidden="true" ></i>agre
                                    </button>
                                    <a href="{{URL::action('AsignacionPiezasController@servicioAsignado',array('idEquipos'=>$equiposNYp->idEquipos,'idFactura'=>$idFactura,'idRepCotizacion'=>$equiposNYp->idRepCotizacion))}}" class="btn btn-success"
                                        type="submit"><span class="glyphicon glyphicon-plus-sign"></span>
                                    </a>
                                </form>-->
                                
                                <form action="">
                                <a href="{{URL::action('AsignacionPiezasController@servicioAsignado',array('idCotizacion'=>$equiposNYp->idCotizacion,'idEquipos'=>$equiposNYp->idEquipos,
                                        'idFactura'=>$idFactura,'idRepCotizacion'=>$equiposNYp->idRepCotizacion,'tipoCotizacion'=>$equiposNYp->tipoCotizacion))}}"
                                         class="btn btn-success"
                                        type="submit"><span class="glyphicon glyphicon-plus-sign"></span>
                                    </a>
                                @csrf
                                    <input type="hidden" value="{{$equiposNYp->idEquipos}}" name="idEquipos">
                                    <input type="hidden" value="{{$equiposNYp->idRepCotizacion}}" name="idRepCotizacion">
                                    <button type="button" class="btn btn-info detalle" style='width:40px; height: 35px;'>
                                        <i class="fa fa-eye" aria-hidden="true" ></i>
                                    </button>
                                </form>
                                </font>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> 
    </div> 
    
    
</div>  <br><br><br>
<div id="detalle"></div>
<div id="modalCoti"></div>
<script>
$(document).ready(function(){
    $(".detalle").click(function(){
        $("#detalle").load('{{url('detalleServicioPiezas')}}' + '?r=' + Date.now() + $(this).closest('form').serialize()); 
    });
    // $(".servicioAsignado").click(function(){
    //     $("#detalle").load('{{url('servicioAsignado')}}' + '?r=' + Date.now() + $(this).closest('form').serialize()); 
    //     alert("hola");
    // });
    $(".verDetallePartida").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();
    var tipoMoneda=$('#tipoMoneda').val();
    $('#modalCoti').load('{{url('detallePartidasCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida+'&tipoMoneda='+tipoMoneda);
    e.preventDefault();
});

});
</script>