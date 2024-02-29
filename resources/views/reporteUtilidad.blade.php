@extends('principal')
@section('contenido')
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Utilidades </h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <form method = 'post' action="{{route('reporteUtilidadFiltro')}}">
                        {{csrf_field()}}
                    <div class="col-md-3">
                        Fecha de Inicio:<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>

                    <div class="col-md-3">
                        Fecha de Fin:<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    <div class="col-md-6" style="margin-top:10px">
                        <input type="submit" class="btn btn-primary" name="agrega" id="agrega" value = 'Aplicar Filtro'></input>
                        <a href="{{asset('reporteUtilidad')}}"><button type="button" class="btn btn-default">Limpiar filtro</button></a>                    
                        <a href=""><img src="{{asset('img/excelico.png')}}" height=50 width=50></a>
                    </div>                    
                    </form>
                </div>
                <br><br>
            </div>
        </div>
        <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="tUtilidades">
            <thead>
                <tr style="background-color:#D6EAF8">
                    <th>Folio de Servicio Asignado</th>
                    <th>Folio de Factura</th>
                    <th>Fecha de Servicio</th>
                    <th>Fecha de Pago</th>
                    <th>Cliente</th>
                    <th>Sucursal</th>
                    <th>Monto Cotización</th>
                    <th>Total</th>
                    <th>Total Gastos</th>
                    <th>Total Compras</th>
                    <th>Utilidad</th>
                </tr>                    

                <tr style="background-color:#D6EAF8">
                    <form method = 'post' action="{{route('reporteUtilidadFiltroFolio')}}">
                    {{csrf_field()}}
                        @if(isset($ReporteUtilidadesI)&&isset($ReporteUtilidadesF))
                            <input type='hidden' name='fechainicio' id='fechainicio' value="{{$ReporteUtilidadesI}}">
                            <input type='hidden' name='fechafinal'  id='fechafinal'  value="{{$ReporteUtilidadesF}}">
                        @endif
                        <th></th>
                        <th colspan='5'><input type='search' name='folio' id='folio' class="form-control rounded-0"></th>      
                    </form>   
                    <th colspan='5'></th>                                            
                </tr>
                
            </thead>
            <tbody>          
            @php
                $totalmex = 0;
                $totalcompras = 0;
                $totalgastos = 0;
                $totalutilidad = 0;
            @endphp   
            @foreach($ReporteUtilidades as $repUtil) 
            @php
                $date  = date_create($repUtil->fechafactura);
                $date2 = date_create($repUtil->fechapago);
            @endphp
                <tr>
                    <td align = "center">{{$repUtil->idservicios}}</td>
                    <td align = "center">{{$repUtil->numerofactura}}</td>
                    <td align = "center">{{$repUtil->fechafactura}}</td>
                    <td align = "center">{{$repUtil->fechapago}}</td>
                    <td>{{$repUtil->cliente}}</td>
                    <td align = "center">{{$repUtil->sucursal}}</td>
                    <td align = "right">{{$repUtil->tipomoneda}} ${{number_format($repUtil->montofactura,2)}}</td>
                    <td align = "right">MXN ${{number_format($repUtil->montopesos,2)}}</td>
                    <td align = "right">${{number_format($repUtil->totalgastos,2)}}</td>
                    <td align = "right">${{number_format($repUtil->totaloc,2)}}</td>
                    <td align = "right">MXN ${{number_format($repUtil->utilidad,2)}}</td>
                </tr>       
                    @php
                        $totalmex = $totalmex + $repUtil->montopesos;
                        $totalcompras = $totalcompras + $repUtil->totaloc;
                        $totalgastos = $totalgastos + $repUtil->totalgastos;
                        $totalutilidad = $totalutilidad + $repUtil->utilidad;
                    @endphp
            @endforeach
            <tfoot style="background-color:#D6EAF8">
                    <th colspan="7" align = "right">Totales</th>
                    <th  align = "right">MXN ${{number_format($totalmex,2)}}</th>
                    <th  align = "right">MXN ${{number_format($totalgastos,2)}}</th>
                    <th  align = "right">MXN ${{number_format($totalcompras,2)}}</th>
                    <th  align = "right">MXN ${{number_format($totalutilidad,2)}}</th>                                  
            </tfoot>          
        </table>   
            
        </a><br><br>                   
    </div>
    
    </div>                 
<?php $tipoSession = Session::get('sesiontipo'); ?>
<script>
let ReporteUtilidad = JSON.parse('{!!
    json_encode($ReporteUtilidades)!!}')
    console.log(ReporteUtilidad)
</script>

@stop