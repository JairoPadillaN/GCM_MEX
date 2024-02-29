
<div id="reporteRefacciones">
    <div class="table-responsive" id="reporteBorrar">
    <div class="panel-heading">
                <h2>Reporte de refacciones</h2>
    </div>
        <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:90%" align="center">
            <thead>
                <tr style=" background-color: #78e08f;">
                    <th style= "width: 80px;">Foto placa</th>
                    <th style= "width: 80px;">Foto principal</th>
                    <th style= "width: 80px;">No. Parte</th>
                    <th style= "width: 200px;">Nombre Refacción</th>
                    <th style= "width: 100px;">Presentación</th>
                    <th style= "width: 100px;">Observaciones</th>
                    <th style= "width: 80px;">Cantidad</th>
                    @if(Session::get('sesiontipo')=="Administrador")
                    <th style= "width: 120px;">Precio Unitario</th>
                    <th style= "width: 100px;">Total</th>
                    @endif
                    <th style= "width: 150px;" >
                        <center>Opciones</center>
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach($consultaRefacciones as $refacciones)
                <tr>
                        @if($refacciones->fotoPlaca =="" || $refacciones->fotoPlaca =="Sin archivo")
                        <td style="color:red" align="center" >Sin foto</td>
                    @else
                        <td><a target="_blank" align="center"
                                href="{{asset ('public/archivos/'.$refacciones->fotoPlaca)}}"><img
                                    src="{{asset ('public/archivos/'.$refacciones->fotoPlaca)}}" height=70
                                    width=70></a></td>
                    @endif
                    @if($refacciones->fotoPrincipal =="" || $refacciones->fotoPrincipal =="Sin archivo")
                        <td style="color:red" align="center" >Sin foto</td>
                    @else
                        <td><a target="_blank" align="center"
                            href="{{asset ('public/archivos/'.$refacciones->fotoPrincipal)}}"><img
                                src="{{asset ('public/archivos/'.$refacciones->fotoPrincipal)}}" height=70
                                width=70></a></td>
                    @endif
                    <td>{{$refacciones->numeroParte}}</td>
                    <td>{{$refacciones->codigoRefaccion}}-{{$refacciones->nombreRefaccion}}</td>
                    <td>{{$refacciones->presentacion}}</td>
                    <td>{{$refacciones->observaciones}}</td>
                    <td>{{$refacciones->cantidad}}</td>
                    @if(Session::get('sesiontipo')=="Administrador")
                        @if($refacciones->moneda=="MXN")
                            <td>{{$refacciones->moneda}} ${{$refacciones->precioLista}}</td>
                            <td>{{$refacciones->moneda}} ${{($refacciones->precioTotal)}}</td>
                        @else
                            <td>{{$refacciones->moneda}} ${{$refacciones->precioCompraDol}}</td>
                            <td>{{$refacciones->moneda}} ${{($refacciones->precioTotalDol)}}</td>
                        @endif
                    @endif
                    
                    <td align="center">
                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                    @csrf
                            <!-- Button trigger modal debe ir fuera del form-->
                            <button value="{{$refacciones->idRefaccion}}" class="btn btn-success abrirModalLectura">
                                <i class="fa fa-eye" aria-hidden="true" ></i>
                            </button>

                            @if(Session::get('sesiontipo')=="Administrador")
                                @if($refacciones->cantidadOcupada==0)
                                
                                <!-- <button value="{{$refacciones->idRefaccion}}" class="btn btn-info abrirModal">
                                    <i class="ace-icon fa fa-pencil" aria-hidden="true" ></i>
                                </button>                               -->

                                <input type="hidden" value="{{$refacciones->idRefaccion}}" name="idRefaccion">
                                <input type="hidden" value="{{$refacciones->idEntrada}}" name="idEntrada">
                                <input type="hidden" value="{{$refacciones->idEntradaOrden}}" name="idEntradaOrden">
                                <button type="button" class="btn btn-sm btn-danger borrarRefaccion" style='width:40px; height: 35px;'>
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                                @endif
                            @endif
                    </form>   
                        </td> 
                        
                </tr>
            @endforeach
            @if(Session::get('sesiontipo')=="Administrador")
            <tr style=" background-color: #b2bec3;">
                <td colspan=8 align="center"><strong>Total</strong></td>
                @if($refacciones->moneda=="MXN")
                    <td><strong>{{$refacciones->moneda}} ${{$sumaTotal->total}}</strong></td>
                @else
                    <td><strong>{{$refacciones->moneda}} ${{$sumaTotal->totalDol}}</strong></td>
                @endif
            </tr>
            @endif
            </tbody>
        </table>
        
    </div>
</div>
<div id="modal"></div>
                


<script>
// $(".ver").click(function(){
// alert("ver");
// });
$(".borrarRefaccion").click(function(){
// alert("id Refaccion")
$("#reporteRefacciones").load('{{url('borrarRefaccionAgregada')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
});
$(".abrirModal").click(function (e) {
    var idRefaccion = $(this).val();
    $('#modal').load('{{url('modalEntradaAlmacen')}}' + '?idRefaccion='+idRefaccion);
    e.preventDefault();
});
$(".abrirModalLectura").click(function (e) {
    var idRefaccion = $(this).val();
    $('#modal').load('{{url('modalEntradaLectura')}}' + '?idRefaccion='+idRefaccion);
    e.preventDefault();
});
</script>

<style>
table{
    table-layout: fixed;
    width: 250px;
}
</style>