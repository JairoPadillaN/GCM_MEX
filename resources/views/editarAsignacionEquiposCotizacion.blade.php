<div class="row">
    <form id="form_rep">
    @csrf
        <div class="form-group col-md-6">
            <div class="form-group row">
                <div class="col-sm-3"><b>Modelo:</b></div>
                <div class="col-sm-9">
                    {{Form::hidden('tipoCotizacion',$tipoCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                    {{Form::hidden('idEquipos',$idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                    {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0','id'=>'idSucursal', 'readonly'])}}
                    {{Form::hidden('numeroCotizacion',$numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                    {{Form::hidden('tipoMoneda',$tipoMoneda,['class' => 'form-control rounded-0', 'readonly'])}}
                    {{Form::hidden('idCotizacion',$idCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                    {{Form::text('modelo',$consulta->modelo,['class' => 'form-control rounded-0','id'=>'modelo', 'readonly'])}}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>Serie:</b></div>
                <div class="col-sm-9">
                    {{Form::text('serie',$consulta->serie,['class' => 'form-control rounded-0','id'=>'serie', 'readonly'])}}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>Recurso:</b></div>
                <div class="col-sm-9">
                    {{Form::text('recurso',$consulta->recurso,['class' => 'form-control rounded-0','id'=>'recurso','readonly'])}}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>Descripción de falla:</b></div>
                <div class="col-sm-9">
                    {{Form::textarea('descripcionFalla',$consulta->descripcionFalla,['class' => 'form-control rounded-0','id'=>'descripcionF', 'readonly','rows'=>'4'])}}
                </div>
            </div>

            <div class="alert alert-secondary" role="alert" style="background-color:#FFF3F3">
                <form>
                    <div class="form-group row">
                        <div id="divPartida">
                            <input type="hidden" name="partida" id="partida" value="{{$partidaSiguiente}}">
                        </div>
                        <div class="col-sm-3"><b>*Seleccionar pieza del equipo</b></div>
                        <div class="col-sm-9">
                            <select name='idParte' class="form-control" id="idParte">
                                <option value="">Seleccione un pieza</option>
                                @foreach($partesEquipos as $pE)
                                <option value='{{$pE->idParte}}'>{{$pE->GCMidParte}} {{$pE->nombreParte}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3"><b>*Tipo de parte:</b></div>
                        <div class="col-sm-9">
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
                            <!-- AQUI IBA EL ID PARTE -->
                            <div class="col-sm-3"><b>*Paquete:</b></div>
                            <div class="col-sm-9">
                                <select name="idPaquete" id="idPaquete" class="form-control mi-selector">
                                    <option value="">Seleccione un paquete</option>
                                    @foreach($paquetes as $p)
                                    @if($p->activo=="Si")
                                    <option value='{{$p->idPaquete}}'>{{$p->nombrePaquete}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12" style="text-align:right">
                                <button type="button" class="btn btn-success btn-default" id="agregarPaquete" onblur=calcularPorcentaje()>
                                    <span class="glyphicon glyphicon-plus-sign"></span> Agregar paquete
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="form1" style="display:block">
                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Marca:</b></div>
                            <div class="col-sm-9">
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
                            <div class="col-sm-3"><b>*Tipo de refacción:</b></div>
                            <div class="col-sm-9">
                                <!-- AQUI VA EL ID PARTE -->
                                <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                                    <option value="">Seleccione un tipo de refacción</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Refacciones disponibles:</b></div>
                            <div class="col-sm-9">
                                <select name="idRefaccion" id="comboRef" class="form-control">
                                    <option value="">Seleccione una refacción:</option>
                                </select>
                            </div>
                            <div id="preciosR"></div>
                        </div>                        
                        <div class="form-group row">
                            <div class="col-sm-12" id="divApareceEnCoti"><b>*Aparece en cotización: </b>
                                Si <input type="radio" name="apareceEnCoti" id="radioSi" value="Si">
                                No <input type="radio" name="apareceEnCoti" id="radioNo" value="No">
                            </div>
                        </div>
                        <div class="form-group row" id="datos" style="display:none;"></div>
                        <div class="form-group row">
                            <div class="col-sm-12" style="text-align:right">
                                <button type="button" class="btn btn-success btn-default" id="agregarRefaccion" onblur=calcularPorcentaje()>
                                    <span class="glyphicon glyphicon-plus-sign"></span> Agregar refacción
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>Descripción:</b></div>
                <div class="col-sm-9">
                    {{Form::text('descripcion',$campoDescripcion->descripcion,['class' => 'form-control rounded-0','id'=>'descripcionV'])}}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>Notas:</b></div>
                <div class="col-sm-9">
                    <textarea name="notas" id="notasV" class="form-control rounded-0" rows="4" onclick=calcularPorcentaje() onblur=calcularPorcentaje()></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>Días de pago:</b></div>
                <div class="col-sm-9">
                    <select class="form-control rounded-0" name="diasDePago" id="dias" onchange=calcularPorcentaje()>
                        <option selected="selected" value="{{$diasDePago}}">{{$diasDePago}}</option>
                        <option value="0">0</option>
                        <option value="30">30</option>
                        <option value="60">60</option>
                        <option value="90">90</option>
						<option value="120">120</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>*Semanas de entrega:</b></div>
                <div class="col-sm-9">
                    <select name='semanasEntrega' id='semanasEntrega' class="form-control">
                        <option value="">Seleccionar opción</option>
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

            <div class="form-group row">
                <div class="col-sm-3"><b>*Monto del equipo:</b></div>
                <div class="col-sm-9">
                    {{Form::text('montoEquipoInicial',0,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'montoEquipo','onblur'=>'calcularPorcentaje()'])}}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3"><b>*Financiamiento:</b></div>
                <div class="col-sm-4" style="">
                    {{Form::text('montoEquipo','0',['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'totalFin','readonly'])}}
                </div>
                <div class="col-sm-5" style="">
                    <div><b>Aplicar descuento:</b></div>
                    Si <input type="radio" name="aplicaDescuento" id="siAplica" value="Si" onclick=calcularPorcentaje()>&nbsp;&nbsp;&nbsp;&nbsp;
                    No <input type="radio" name="aplicaDescuento" id="noAplica" value="No" checked onclick=calcularPorcentaje()>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>

            <div class="form-group row" id="divSinDescuento">
                <div class="col-sm-3"></div>
                <div class="col-sm-3" style="">
                </div>
                <div class="col-sm-6" style="">
                    <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion"
                        onclick=calcularPorcentaje()>
                        <span class="glyphicon glyphicon-plus-sign"></span> Agregar equipo a cotización
                    </button>
                </div>
            </div>

            <div id="descuentos" class="alert alert-primary" role="alert" style="display:none;">
                <div class="form-group row divConDescuento" style="display:none;">
                    <div class="col-sm-6" id="validarTipoDescuento">
                        <div><b>*Tipo de descuento:</b></div>
                        <div>
                            Porcentaje (%)<input type="radio" name="tipoDescuento" id="porcentaje"
                                value="porcentaje">&nbsp;&nbsp;
                            Cantidad ($)<input type="radio" name="tipoDescuento" id="cantidad"
                                value="cantidad">&nbsp;&nbsp;
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
                <div class="form-group row divConDescuento" style="display:none;">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3" style="">
                    </div>
                    <div class="col-sm-6" style="">
                        <button type="button" class="btn btn-success btn-sm form-control rounded-0 agregarReparacion"
                            onclick=calcularPorcentaje()>
                            <span class="glyphicon glyphicon-plus-sign"></span> Agregar equipo a cotización
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="form-group col-md-6">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr style="background-color: #74B3E0;">
                        <th>
                            <font SIZE=2>Foto vista-superior</font>
                        </th>
                        <th>
                            <font SIZE=2>Foto vista-frontal</font>
                        </th>
                        <th>
                            <font SIZE=2>Foto Placa 1</font>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center; color:red">
                            <font SIZE=2>
                                @if($consulta->vistaSuperior=='' || $consulta->vistaSuperior=='Sin archivo')
                                Sin foto
                                @else
                                <a target="_blank" href="{{asset ('archivos/'.$consulta->vistaSuperior)}}">
                                    <img src="{{asset ('archivos/'.$consulta->vistaSuperior)}}" height=80
                                        width=80>
                                </a>
                                @endif
                            </font>
                        </td>
                        <td style="text-align:center; color:red">
                            <font SIZE=2>
                                @if($consulta->vistaFrente=='Sin archivo' || $consulta->vistaFrente=='')
                                Sin foto
                                @else
                                <a target="_blank" href="{{asset ('archivos/'.$consulta->vistaFrente)}}">
                                    <img src="{{asset ('archivos/'.$consulta->vistaFrente)}}" height=80 width=80>
                                </a>
                                @endif
                            </font>
                        </td>
                        <td style="text-align:center; color:red">
                            <font SIZE=2>
                                @if($consulta->placa_1=='Sin archivo' || $consulta->placa_1=='' )
                                Sin foto
                                @else
                                <a target="_blank" href="{{asset ('archivos/'.$consulta->placa_1)}}">
                                    <img src="{{asset ('archivos/'.$consulta->placa_1)}}" height=80 width=80>
                                </a>
                                @endif
                            </font>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="reporteRefaccionesAsignadas">
        </div>

    </div>
    
</div>
<div id="e"></div>
<div id="reporteReparaciones" class="">
    @if($cuantos>=1)
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th colspan="9" style="text-align:right"><font SIZE=2>Total: {{$tipoMoneda}} ${{$suma}}</font></th>
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
                                @foreach($equiposReparados as $equipoR)
                                    @if($equiposNYp->idEquipos == $equipoR->idEquipos)
                                        <td style="text-align:center;width:200px;">
                                            <font SIZE=2 color="#2F7DA7">
                                                <button value="{{$equiposNYp->partida}}" class="verDetallePartida" style="background-color: Transparent;background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;">
                                                    {{$equipoR->GCMid}} - {{$equipoR->complementoGCMid}}
                                                </button>
                                            </font>
                                        </td>
                                        @break;
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
                                <td style="text-align:center;width:200px;">
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
                                        <strike>{{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?></strike>
                                        <br>
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
                                        {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoEquipo,2)?>
                                    @endif
                                </font>
                            </td>
                            <td style="text-align:center;width:350px;">
                                <font SIZE=2>
                                    @if($equiposNYp->descuento !="")
                                        <strike>{{$tipoMoneda}} $<?php $totalff = $equiposNYp->montoEquipo * $equiposNYp->piezas; echo number_format ($totalff,2)?></strike>
                                        <br> {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
                                    @else
                                        {{$tipoMoneda}} $<?php echo number_format ($equiposNYp->montoFinanciamiento,2)?>
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
                                    {{Form::hidden('numeroCotizacionB',$numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('tipoCotizacionB',$equiposNYp->tipoCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('idSucursal',$idSucursal,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('idEquiposB',$equiposNYp->idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('tipoMonedaB',$tipoMoneda,['class' => 'form-control rounded-0', 'readonly'])}}
                                    {{Form::hidden('partidaBorrar',$equiposNYp->partida,['class' => 'form-control rounded-0', 'readonly'])}}
                                    <input type="hidden" value="{{$equiposNYp->idCotizacion}}" name="idCotizacionB" id="idCotizacion">
                                    <input type="hidden" value="{{$equiposNYp->idRepCotizacion}}" name="idRepCotizacion">
                                    @if($estatusCotizacion == "En revisión")
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
                        <tr>
                            <td colspan="9" style="text-align:right"><font SIZE=2><b>Total: {{$tipoMoneda}} ${{$suma}}</b></font></td>
                            <td colspan="2">
                            </td>                        
                        </tr>
                    </tbody>
                </table>
            </div>   
        </div>
        {{Form::hidden('validarCuantos',$cuantos,['class' => 'validarCuantos'])}}
    @else
        <div class="alert alert-warning" role="alert" align="center">
            <label for="">No se han agregado equipos a cotización.</label>
        </div>
        {{Form::hidden('validarCuantos',$cuantos,['class' => 'validarCuantos'])}}
    @endif
    <div id="cargarModal"></div>
</div>


<script>
$(".verDetallePartida").click(function (e) {
    var partida = $(this).val();
    var idCotizacion=$('#idCotizacion').val();    
    $('#cargarModal').load('{{url('detallePartidasCotizacion')}}' + '?idCotizacion='+idCotizacion+'&partida='+partida);
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
    $(".agregarReparacion").click(function() {
        var monto = $("#montoEquipo").val();
        var semanas = $("#semanasEntrega").val();
        var totalFin = $("#totalFin").val();

        if (semanas == "" || totalFin == 0 || monto == 0) {

            if(semanas == ""){
                $("#semanasEntrega").css('border-color', 'red');
                $("#semanasEntrega").css('color', 'red');   
                alert("Las semanas de entrega son obligatorias.");
            }
            if(totalFin == 0){
                $("#totalFin").css('border-color', 'red');
                $("#totalFin").css('color', 'red');    
                alert("El financiamiento total es obligatorio.");
            }
            if(monto == 0){
                $("#montoEquipo").css('border-color', 'red');
                $("#montoEquipo").css('color', 'red');   
                alert("El campo *Monto es obligatorio.");
            }

        }else{

            var formData = new FormData(document.getElementById("form_rep"));
            $.ajax({
                data: formData,
                url: '{{url('reporteDeReparacionesAbajo')}}',
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (res) {
                    setTimeout(() => {
                        // Create a new 'change' event
                        var event = new Event('change');
                        // Dispatch it.
                        document.querySelector("#comboEquipos").dispatchEvent(event);
                        // document.querySelector("#comboEquipos").value = '';
                    }, 100);
                },
                error: function (err) {
                    console.log(err);
                }
            });


            // $("#reporteReparaciones").load('{{url('editarEquiposEnCotizacion')}}' + '?' + $(this).closest('form').serialize());
            $("#reporteRefaccionesAsignadas").empty();
            // $("#comboEquipos").load('{{url('comboEquiposCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + idSucursal);
            $('#botonParaGuardar').css('visibility', 'visible');
            $("#montoEquipo,#totalFin").val('0.00');
            $("#semanasEntrega,#diasDePago,#notasV,#descripcionF,#descripcionV,#recurso,#serie,#modelo,#idParte").val('');
            $("#noAplica").click();
            $("[name='tipoDescuento']").prop("checked", false);
            document.getElementById("descuento").value = "";
            document.getElementById("totalDescuento").value="";            
        }
    });

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
                    // document.querySelector("#tipoCotizacion").value = 'refurbished';
                    // Create a new 'change' event
                    var event = new Event('change');
                    // Dispatch it.
                    document.querySelector("#comboEquipos").dispatchEvent(event);
                }, 100);
            },
            error: function (err) {
                console.log(err);
            }
        });

        // var idSucursal = $('#idSucursal').val();         
        // $("#reporteReparaciones").load('{{url('borrarReparacion')}}' + '?' + $(this).closest('form').serialize());
        // $("#reporteRefaccionesAsignadas").empty();
        // $("#comboEquipos").load('{{url('comboEquiposCotizacion')}}' + '?r=' + Date.now() + '&idSucursal=' + idSucursal);
    });

</script>
<script>
    function calcularPorcentaje() {
        var diasDePago = $("#dias").val();
        var monto = $("#montoEquipo").val();
        var montoEquipo = parseFloat(monto);
        var calculo = 0;
        if(diasDePago >= 60 &&  diasDePago < 90){
            calculo = monto * 0.085;
            var financiamiento = parseFloat(calculo);
            document.getElementById("totalFin").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 90 && diasDePago<120){
            calculo = monto * 0.17;
            var financiamiento = parseFloat(calculo);
            document.getElementById("totalFin").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 120){
            calculo = monto * 0.255;
            var financiamiento = parseFloat(calculo);
            document.getElementById("totalFin").value = (financiamiento+montoEquipo).toFixed(2);
        }
		else{
            document.getElementById("totalFin").value = montoEquipo;
            var financiamiento = $("#montoEquipo").val();            
        }

        setTimeout(() => {
            calcularDescuento()
        }, 200);
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
        }else if($('input:radio[name=aplicaDescuento]:checked').val() == "Si" && tipoDescuento == ""){
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

    $("#idMarcaRefa").change(function(){ 
        $("#idTipoRefacciones").text('cargando..');       
        $("#idTipoRefacciones").load('{{url('comboTipoRefaccion')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
    });
    
    $("#idTipoRefacciones").change(function() {
        $("#comboRef").text('cargando..');
        $("#comboRef").load('{{url('comboRefacciones')}}' + '?' + $(this).closest('form').serialize());
    });

    $("#agregarRefaccion").click(function(e) {
        let comboParte = $("#idParte").val();
        let tipoProducto = $("#tipoProducto").val();
        let comboMarca = $("#idMarcaRefa").val();
        let comboTipoRef = $("#idTipoRefacciones").val();
        let comboRef = $("#comboRef").val();        
                
        if (comboParte == '' || tipoProducto == '' || comboMarca =='' || comboTipoRef =='' || comboRef ==''|| $("[name='apareceEnCoti']:checked").val()==undefined) {            
            if (comboParte == '') {
                alert("Debes seleccionar una pieza.");
                $("#idParte").css("color", "red");
            }
            if (tipoProducto == '') {
                alert("El campo *Tipo de parte es requerido");
                $("#tipoProducto").css("color", "red");
            }
            if (comboMarca == '') {
                alert("El campo *Marca es requerido");
                $("#idMarcaRefa").css("color", "red");
            }
            if (comboTipoRef == '') {
                alert("El campo *Tipo de refacción es requerido");
                $("#idTipoRefacciones").css("color", "red");
            }
            if (comboRef == '') {
                alert("El campo *Refacciones disponibles es requerido");
                $("#comboRef").css("color", "red");
            }
                        
            if($("[name='apareceEnCoti']:checked").val()==undefined){
                $("#divApareceEnCoti").css("color", "red");
                alert('El campo *Aparece en cotización es obligatorio');
            }

        }else{
            $("#reporteRefaccionesAsignadas").load('{{url('agregarRefaccionParteCotizaciones')}}' + '?' + $(this).closest('form').serialize());
            $("#idMarcaRefa, #idTipoRefacciones, #comboRef, #tipoProducto").val('');
            $('#datos').empty();
            $("[name='apareceEnCoti']").prop("checked", false);

            
        }

    });
    
    $("#comboRef").change(function() {
        $('#datos').css('display', 'none');
        $("[name='apareceEnCoti']").prop("checked", false);
        var combo1 = document.getElementById("idParte");
        var comboParte = combo1.options[combo1.selectedIndex].text;

        var combo2 = document.getElementById("tipoProducto");
        var tipoProducto = combo2.options[combo2.selectedIndex].text;
        
        var combo3 = document.getElementById("idMarcaRefa");
        var comboMarca = combo3.options[combo3.selectedIndex].text;
        
        var combo4 = document.getElementById("idTipoRefacciones");
        var comboTipoRef = combo4.options[combo4.selectedIndex].text;

        var combo5 = document.getElementById("comboRef");
        var comboRef = combo5.options[combo5.selectedIndex].text;
        let comboRefConsulta = $("#comboRef").val();        
        var tipoMoneda=$('#tipoMoneda').val();
        document.getElementById('datos').innerHTML='<div class="alert alert-warning" role="alert"><b>Parte: </b>'+comboParte+'<br><b>Tipo de parte: </b>'+tipoProducto+'<br><b>Marca: </b>'+ comboMarca+'<br><b>Tipo de refaccion: </b>'+comboTipoRef+'<br><b>Refacción:  </b>'+comboRef+'<div id="apareceCot"></div>'+'<div id="costoR"></div>';
        $("#costoR").load('{{url('preciosRefaccionesCot')}}' + '?r=' + Date.now() + '&idPartesVenta=' + comboRefConsulta + '&tipoMoneda=' + tipoMoneda);
    });
        
    $("#radioSi").click(function(e) {        
        document.getElementById('apareceCot').innerHTML='<b>Ver en cotización: </b>Si';
        $('#datos').css('display', 'block');
    });
    $("#radioNo").click(function(e) {
        document.getElementById('apareceCot').innerHTML='<b>Ver en cotización: </b>No';
        $('#datos').css('display', 'block');
    });

    $("#comboRef").change(function() {
        $("#preciosR").load('{{url('preciosRefacciones')}}' + '?r=' + Date.now() + '&idPartesVenta=' + this.options[this.selectedIndex].value) ;
    });

    $("#agregarPaquete").click(function() {
        let comboParte = $("#idParte").val();
        let tipoProducto = $("#tipoProducto").val();
        let idPaquete = $("#idPaquete").val();
        
        if (comboParte == '' || tipoProducto == '' || idPaquete == ''){

            if (comboParte == '') {
                alert("Debes seleccionar una pieza.");
                $("#idParte").css("color", "red");
            }
            if (tipoProducto == '') {
                alert("El campo *Tipo de parte es requerido");
                $("#tipoProducto").css("color", "red");
            }
            if (idPaquete == '') {
                alert("El campo *Paquete es requerido");
                $("#idPaquete").css("color", "red");
            }
            
        }else{
            $("#reporteRefaccionesAsignadas").load('{{url('agregarPaqueteCotizacio')}}'+ '?' + $(this).closest('form').serialize());
            $("#tipoProducto,#idPaquete").val('');            
        }
    });

    $("#idParte").change(function() {
        $("#reporteRefaccionesAsignadas").load('{{url('refaccionesAsignadasCotizacion')}}' + '?' + $(this).closest('form').serialize());
    });
</script>
<script>
    $( document ).ready(function calcularPorcentaje() {
        var diasDePago = $("#dias").val();
        var monto = $("#montoEquipo").val();
        var montoEquipo = parseFloat(monto);
        var calculo = 0;
        if(diasDePago >= 60 &&  diasDePago < 90){
            calculo = monto * 0.07;
            var financiamiento = parseFloat(calculo);
            document.getElementById("totalFin").value = (financiamiento+montoEquipo).toFixed(2);
        }else if(diasDePago >= 90){
            calculo = monto * 0.14;
            var financiamiento = parseFloat(calculo);
            document.getElementById("totalFin").value = (financiamiento+montoEquipo).toFixed(2);
        }else{
            document.getElementById("totalFin").value = montoEquipo;
            var financiamiento = $("#montoEquipo").val();            
        }
    
    });
</script>