<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal" id="activar" style= "visibility:hidden">
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close" onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="text-align:center">
                    <font color="#836F24" size=3><b>ALCANCES</b></font>
                </div>
                @if($cuantasRefacciones > 0 || $cuantosPaquetes > 0)                   
                    <table class="" id="dataTables-example" border="1">
                        <thead>
                            @if($cuantasRefacciones > 0)
                            <tr style="background-color: #EDE5A6;">                                    
                                <th style="width:400px;">Refacciones</th>
                                <th>Tipo de producto</th>
                                <th>Visible en cotización</th>
                                <th>Precio de venta</th>                    
                            </tr>
                            @endif
                        </thead>
                        <tbody>                        
                            @if($cuantasRefacciones > 0)
                                <!-- <b>Refacciones:</b> -->                    
                                @foreach($refaccionesIndividuales as $refaccionesIn)
                                    @if($refaccionesIn->codigo != "")
                                        @if($refaccionesIn->tipoProducto == "Nuevo")                                            
                                            <tr>
                                                <td>
                                                    &nbsp;<b>•</b> {{$refaccionesIn->codigo}} - {{$refaccionesIn->nombreRefaccion}}
                                                </td>
                                                <td style="text-align:center;">{{$refaccionesIn->tipoProducto}}</td>
                                                <td style="text-align:center;">{{$refaccionesIn->apareceEnCoti}}</td>
                                                @if($tipoMoneda == "MXN")
                                                    <td style="text-align:right;">                                                        
                                                        @if($refaccionesIn->precioPesos != "" || $refaccionesIn->precioPesos > 0) 
                                                            MXN $ {{$refaccionesIn->precioPesos}}
                                                        @else
                                                            MXN $ 0.00
                                                        @endif                                                        
                                                    </td>                                    
                                                @else
                                                    <td style="text-align:right;">                                                                                                    
                                                        @if($refaccionesIn->precioDolar != "" || $refaccionesIn->precioDolar > 0) 
                                                            USD $ {{$refaccionesIn->precioDolar}}
                                                        @else
                                                            USD $ 0.00
                                                        @endif
                                                    </td>                                    
                                                @endif
                                                
                                            </tr>
                                        @else                                            
                                            <tr>
                                                <td>
                                                    &nbsp;<b>•</b> {{$refaccionesIn->codigo}} - {{$refaccionesIn->nombreRefaccion}}
                                                </td>
                                                <td style="text-align:center;">{{$refaccionesIn->tipoProducto}}</td>
                                                <td style="text-align:center;">{{$refaccionesIn->apareceEnCoti}}</td>
                                                @if($tipoMoneda == "MXN")
                                                    <td style="text-align:right;">                                                        
                                                        @if($refaccionesIn->precioPesos != "" || $refaccionesIn->precioPesos > 0) 
                                                            MXN $ {{$refaccionesIn->precioPesos}}
                                                        @else
                                                            MXN $ 0.00
                                                        @endif
                                                    </td>                                    
                                                @else
                                                    <td style="text-align:right;">                                                                                                    
                                                        @if($refaccionesIn->precioDolar != "" || $refaccionesIn->precioDolar > 0) 
                                                            USD $ {{$refaccionesIn->precioDolar}}
                                                        @else
                                                            USD $ 0.00
                                                        @endif
                                                    </td>                                    
                                                @endif
                                                
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td>
                                                &nbsp;<b>•</b> {{$refaccionesIn->nombreRefaccion}}
                                            </td>
                                            <td style="text-align:center;">{{$refaccionesIn->tipoProducto}}</td>
                                            <td style="text-align:center;">{{$refaccionesIn->apareceEnCoti}}</td>
                                            @if($tipoMoneda == "MXN")
                                                <td style="text-align:center;">                                                    
                                                    @if($refaccionesIn->precioPesos != "" || $refaccionesIn->precioPesos > 0) 
                                                        MXN $ {{$refaccionesIn->precioPesos}}
                                                    @else
                                                        MXN $ 0.00
                                                    @endif
                                                </td>                                    
                                            @else
                                                <td style="text-align:center;">                                                                                                
                                                    @if($refaccionesIn->precioDolar != "" || $refaccionesIn->precioDolar > 0) 
                                                        USD $ {{$refaccionesIn->precioDolar}}
                                                    @else
                                                        USD $ 0.00
                                                    @endif                                                    
                                                </td>                                    
                                            @endif
                                            
                                        </tr>
                                    @endif
                                @endforeach                           
                            @endif

                            @if($cuantosPaquetes > 0)
                                <tr style="background-color: #EDE5A6;">                                    
                                    <th style="width:450px;">Paquetes</th>
                                    <th>Tipo de producto</th>
                                    <th>Visible en cotización</th>
                                    <th>Precio de venta</th>                    
                                </tr>
                                
                                @foreach($paquetesNombres as $paqN)                                        
                                    <tr>
                                        <td colspan="4">
                                            &nbsp;<b>{{$paqN->nombrePaquete}} (Alcance):</b><br>
                                        </td>
                                    </tr>
                                

                                    @foreach($paquetes as $paq)    
                                        @if($paq->nombrePaquete == $paqN->nombrePaquete)
                                            @if($paq->codigo != "")
                                                @if($paq->tipoProducto == "Nuevo")                                                    
                                                    <tr>
                                                        <td>
                                                            &nbsp;<b>•</b> {{$paq->codigo}} - {{$paq->nombreRefaccion}}
                                                        </td>
                                                        <td style="text-align:center;">{{$paq->tipoProducto}}</td>
                                                        <td style="text-align:center;">{{$paq->apareceEnCoti}}</td>
                                                        @if($tipoMoneda == "MXN")
                                                             <td style="text-align:right;">
                                                                
                                                                @if($paq->precioPesos != "" || $paq->precioPesos > 0)                                                             
                                                                    MXN $ {{$paq->precioPesos}}
                                                                @else
                                                                    MXN $ 0.00
                                                                @endif
                                                            
                                                            </td>                                    
                                                        @else
                                                            <td style="text-align:right;">
                                                                                                        
                                                                @if($paq->precioDolar != "" || $paq->precioDolar > 0) 
                                                                    USD $ {{$paq->precioDolar}}
                                                                @else
                                                                    USD $ 0.00
                                                                @endif
                                                            
                                                            </td>                                    
                                                        @endif
                                                        
                                                    </tr>
                                                @else                                                    
                                                    <tr>
                                                        <td>
                                                            &nbsp;<b>•</b> {{$paq->codigo}} - {{$paq->nombreRefaccion}}
                                                        </td>
                                                        <td style="text-align:center;">{{$paq->tipoProducto}}</td>
                                                        <td style="text-align:center;">{{$paq->apareceEnCoti}}</td>
                                                        @if($tipoMoneda == "MXN")
                                                             <td style="text-align:right;">
                                                                
                                                                @if($paq->precioPesos != "" || $paq->precioPesos > 0)                                                             
                                                                    MXN $ {{$paq->precioPesos}}
                                                                @else
                                                                    MXN $ 0.00
                                                                @endif
                                                                
                                                            </td>                                    
                                                        @else
                                                            <td style="text-align:right;">
                                                                                                            
                                                                @if($paq->precioDolar != "" || $paq->precioDolar > 0) 
                                                                    USD $ {{$paq->precioDolar}}
                                                                @else
                                                                    USD $ 0.00
                                                                @endif
                                                            
                                                            </td>                                    
                                                        @endif
                                                        
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td>
                                                        &nbsp;<b>•</b> {{$paq->nombreRefaccion}}
                                                    </td>
                                                    <td style="text-align:center;">{{$paq->tipoProducto}}</td>
                                                    <td style="text-align:center;">{{$paq->apareceEnCoti}}</td>
                                                    @if($tipoMoneda == "MXN")
                                                        <td style="text-align:right;">
                                                        
                                                            @if($paq->precioPesos != "" || $paq->precioPesos > 0)                                                         
                                                                MXN $ {{$paq->precioPesos}}
                                                            @else
                                                                MXN $ 0.00
                                                            @endif
                                                            
                                                        </td>                                    
                                                    @else
                                                        <td style="text-align:right;">
                                                                                                    
                                                            @if($paq->precioDolar != "" || $paq->precioDolar > 0) 
                                                                USD $ {{$paq->precioDolar}}
                                                            @else
                                                                USD $ 0.00
                                                            @endif
                                                        
                                                        </td>                                    
                                                    @endif
                                                    
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach

                                @endforeach
                                
                            @endif
                            
                            @if($equiposNuevosYreparados->descuento !="")
                                <tr>
                                    <td colspan="3" style="text-align:right" class="sinBorder">
                                        Monto:
                                    </td>
                                    <td style="text-align:right" class="sinBorder">
                                        <strike>{{$tipoMoneda}} $ <?php $totalff = $equiposNuevosYreparados->montoEquipoInicial * $equiposNuevosYreparados->piezas; echo number_format ($totalff,2)?></strike>
                                    </td>
                                </tr>                                
                                
                                @if($equiposNuevosYreparados->montoEquipo > $equiposNuevosYreparados->montoEquipoInicial)
                                    <tr>
                                        <td colspan="3" style="text-align:right" class="sinBorder">
                                            Financiamiento:
                                        </td>
                                        <td style="text-align:right" class="sinBorder">
                                            + {{$tipoMoneda}} $<?php $masFinanciamientoDias = $equiposNuevosYreparados->montoEquipo - $equiposNuevosYreparados->montoEquipoInicial; echo number_format ($masFinanciamientoDias,2)?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right" class="sinBorder">
                                        </td>
                                        <td style="text-align:right" class="sinBorder">
                                            <strike>{{$tipoMoneda}} $ <?php $totalff = $equiposNuevosYreparados->montoEquipo * $equiposNuevosYreparados->piezas; echo number_format ($totalff,2)?></strike>
                                        </td>
                                    </tr>
                                @endif                                

                                <tr>
                                    <td colspan="3" style="text-align:right" class="sinBorder"></td>
                                    <td style="text-align:right" class="sinBorder">
                                        - @if($equiposNuevosYreparados->tipoDescuento == "porcentaje")
                                            {{$equiposNuevosYreparados->descuento}}% 
                                        @else 
                                            $ <?php echo number_format ($equiposNuevosYreparados->descuento)?>
                                        @endif
                                    </td>
                                </tr>
                                                                    
                                <tr>
                                    <td colspan="3" style="text-align:right" class="sinBorder">
                                        <b>Monto total:</b>
                                    </td>
                                    <td style="text-align:right" class="sinBorder">
                                        <b>
                                            {{$tipoMoneda}} $<?php echo number_format ($equiposNuevosYreparados->montoFinanciamiento,2)?>
                                        </b>
                                    </td>
                                </tr>
                            @else
                                @if($equiposNuevosYreparados->montoEquipo > $equiposNuevosYreparados->montoEquipoInicial)
                                    <tr>
                                        <td colspan="3" style="text-align:right" class="sinBorder">
                                            Financiamiento:
                                        </td>
                                        <td style="text-align:right" class="sinBorder">
                                            + {{$tipoMoneda}} $<?php $masFinanciamientoDias = $equiposNuevosYreparados->montoEquipo - $equiposNuevosYreparados->montoEquipoInicial; echo number_format ($masFinanciamientoDias,2)?>
                                        </td>
                                    </tr>                                                                        
                                @endif

                                <tr>
                                    <td colspan="3" style="text-align:right" class="sinBorder">
                                        <b>Monto total:</b>
                                    </td>
                                    <td style="text-align:right" class="sinBorder">
                                        <b>
                                            {{$tipoMoneda}} $<?php echo number_format ($equiposNuevosYreparados->montoFinanciamiento,2)?>
                                        </b>
                                    </td>
                                </tr>
                            @endif                                                                                            
                        </tbody>
                    </table>
                @else
                    <center>
                        <b>EQUIPO SIN REFACCIONES</b>
                    </center>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.sinBorder{
    /* border-top: 0px;
    border-right: 0px;
    border-bottom: 1px solid black;
    border-left: 0px; */
    border-style: none;
}
</style>


<script>
$(document).ready(function() {
    $("#activar").click();
});

function cerrarM() {
    $(".modal-backdrop").removeClass("modal-backdrop fade in");
}
</script>