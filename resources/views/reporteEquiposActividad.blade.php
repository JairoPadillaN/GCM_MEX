@if($cuantosExis!=0)
<div class="alert alert-warning" role="alert">El equipo ya fue asignado</div>
@endif
<div id="reporteEquipos">
    <div class="table-responsive">
    <div class="panel-heading">
        <center>
        <h3>Reporte de equipos</h3>
        </center>
    </div>
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" >
            <thead>
                <tr style=" background-color: #AED6F1;">
                    <th>Foto principal</th>
                    <th>Foto frontal</th>
                    <th>Foto placa 1</th>
                    <th>Folio recepción</th>
                    <th>GCM ID</th>
                    <th>Fecha entrada</th>
                    <th>Cliente - Sucursal</th>
                    <th>Datos para certificado</th>
                    <th>Equipo</th>
                    <th>Marca - Modelo</th> 
                    <th>Importancia</th>
                    <th>Estatus</th>
                    <th>Piezas divididas</th> 
                    @if($botonDetalle=='activo')                  
                        <th>Eliminar</th>                   
                    @endif
                </tr>
            </thead>
            <tbody>
            @foreach($consultaEquipos as $equipos)
                <tr>
                    <!-- vista superior -->
                    @if($equipos->vistaSuperior =="" || $equipos->vistaSuperior =="Sin archivo")
                    <td style="color:red" align="center" >Pendiente por subir</td>
                    @else 
                    <td><a target="_blank" align="center"
                            href="{{asset ('public/archivos/'.$equipos->vistaSuperior)}}"><img
                                src="{{asset ('public/archivos/'.$equipos->vistaSuperior)}}" height=100
                                width=100></a></td>
                    @endif

                <!-- vista Frente -->
                    @if($equipos->vistaFrente =="Sin archivo" || $equipos->vistaFrente =="")
                    <td style="color:red" align="center" >Pendiente por subir</td>
                    @else 
                    <td><a target="_blank" align="center"
                            href="{{asset ('public/archivos/'.$equipos->vistaFrente)}}"><img
                                src="{{asset ('public/archivos/'.$equipos->vistaFrente)}}" height=100
                                width=100></a></td>
                    @endif
                
                <!-- placa_1 -->
                    @if($equipos->placa_1 =="" || $equipos->placa_1 =="Sin archivo")
                    <td style="color:red" align="center" >Pendiente por subir</td>
                    @else
                    <td><a target="_blank" align="center"
                            href="{{asset ('public/archivos/'.$equipos->placa_1)}}"><img
                                src="{{asset ('public/archivos/'.$equipos->placa_1)}}" height=100
                                width=100></a></td>
                    @endif
                    <td>{{$equipos->folioRecepcion}}</td>
                    <td>{{$equipos->gcmid}}</td>
                    <td>{{$equipos->fechar}}</td>
                    <td>{{$equipos->cliSuc}}</td>
                    <td>{{$equipos->datos}}</td>
                    <td>{{$equipos->equipo}}</td>
                    <td>{{$equipos->marcaModelo}}</td>
                    @if($equipos->importancia=='Alta')
                    <td align="center"><br><img src="{{asset ('public/archivos/circulo-rojo-png-4.png')}}" height=50 width=50></td>
                    @endif

                    @if($equipos->importancia=='Media')
                    <td align="center"><br><img src="{{asset ('public/archivos/circulo-amarillo.png')}}" height=45 width=45></td>
                    @endif

                    @if($equipos->importancia=='Baja')
                    <td align="center"><br><img src="{{asset ('public/archivos/circulo-verde.png')}}" height=50 width=65></td>
                    @endif
                    <td>{{$equipos->estatus }}</td>
                    <td>{{$equipos->canti }}</td>
                    @if($botonDetalle=='activo')
                    <td>       
                    <form action="">
                    @csrf                     
                        <input type="hidden" value="{{$equipos->idSegActividad}}" name="idSegBorrar">
                        <input type="hidden" value="{{$equipos->idEquipoActividad}}" name="idEquipoActBorrar">
                        <input type="hidden" value="{{$equipos->idEquipos}}" name="idEquipoBorrar">
                        <button type="button" class="btn btn-sm btn-danger borrarEquipo" style='width:40px; height: 35px;'>
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </button>
                    </form>
                    </td>
                    @endif
                    
                </tr>
            @endforeach
            </tbody>
        </table>
        
    </div>
</div>

<script>
$(document).ready(function(){
    $('.borrarEquipo').click(function(){
        $("#reporteEquipos").load('{{url('borrarEquipoActividad')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
    });
});
</script>