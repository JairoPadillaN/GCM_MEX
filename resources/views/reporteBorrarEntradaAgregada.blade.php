<div><div class="table-responsive">
<h2>entrada borrada</h2>
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr style=" background-color: #C5EBFB;">
                                    <th>No. Parte</th>
                                    <th>Nombre</th>
                                    <th>Presentación</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th>
                                        <center>Operaciones</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($consultaRefacciones as $reporte)
                            <tr>
                                    <td>{{$reporte->numeroParte}}</td>
                                    <td>{{$reporte->nombreRefaccion}}</td>
                                    <td>{{$reporte->presentacion}}</td>
                                    <td>{{$reporte->cantidad}}</td>
                                    <td>{{$reporte->precioUltimo}}</td>
                                    <td>{{($reporte->cantidad)*($reporte->precioUltimo)}}</td>
                                    
                                    <td align="center">
                                    <form action='' method='POST' enctype='application/x-www-form-urlencoded' target='_self'>
                                        @csrf
                                            <input type="hidden" value="{{$reporte->idRefaccion}}" name="idRefaccion">
                                            <input type="hidden" value="{{$reporte->idEntrada}}" name="idEntrada">
                                            <button type="button" class="btn btn-sm btn-danger borrarRefaccion">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{$reporte->idRefaccion}}">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </button>
                                       </form> 
                                    </td>
                                    
                                </tr>
                            @endforeach  
                            </tbody>
                        </table>
                    </div>
                    </div>

<script>
$("#ver").click(function(){
alert("ver");
});
$(".borrarRefaccion").click(function(){
alert("id Refaccion")
$("#reporteRefacciones").load('{{url('borrarRefaccionAgregada')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
});

</script>