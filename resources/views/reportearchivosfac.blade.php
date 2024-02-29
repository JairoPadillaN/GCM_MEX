<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<br>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
<thead>
                                    <tr style=" background-color: #78e08f;">
                                        <th style="width: 80px;">Agregado por</th>
                                        <th style="width: 80px;">Número de archivo</th>
                                        <th style="width: 80px;">Tipo de archivo</th>
                                        <th style="width: 80px;">Archivo</th>
                                        <th style="width: 80px;">Observaciones</th>
                                        <th style="width: 150px;">
                                            <center>Opciones</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consultaArchivosFac as $aF)
                                    <tr>
                                    <td>{{$aF->nombreUsuario}}</td>
                                    <td>{{$aF->numeroArchivo}}</td>
                                    <td>{{$aF->tipoArchivo}}</td>
                                    @if($aF->archivoFac =="" || $aF->archivoFac =="Sin archivo")
                                        <td style="color:red" align="center" >Sin archivo</td>
                                    @else
                                        <td align="center"><a target="_blank" align="center"
                                                href="{{asset ('/archivos/'.$aF->archivoFac)}}"><img
                                                    src="{{asset('img/archivosi.png')}}" height=70
                                                    width=70></a></td>
                                    @endif

                                    <td>{{$aF->observacionesAF}}</td>
                                    <td align="center">
                                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                        @csrf
                                                    <input type="hidden" value="{{$aF->idArchivoFactura}}" name="idArchivoFactura">
                                                    <input type="hidden" value="{{$aF->idFactura}}" name="idFactura">
                                                    <button type="button" class="btn btn-sm btn-danger borrarArchivofac1" style='width:40px; height: 35px;'>
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                        </form>   
                                    </td>
                                    
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
</div>


<script type="text/javascript">
$(function() {
    $('.borrarArchivofac1').click(
        function() {
            // alert("borrar");
            $("#reporteArchivos").load('{{url('borrarArchivosFac')}}' + '?' + $(this).closest('form').serialize());
        });
});
</script>