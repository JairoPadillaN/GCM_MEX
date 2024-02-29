@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        //      alert ("hola");
        $("#filtroClientes").load('{{url('reporteClientesAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#limpiar").click(function() {
        $("#razonSocial").val("");
        $("#rfc").val("");
        $("#filtroClientes").load('{{url('reporteClientesAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
});
</script>

<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Clientes</h1> <br>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <input type='text' name='razonSocial' id='razonSocial'
                            placeholder="Filtrar clientes por razón social" class="form-control rounded-0">
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <input type='text' name='rfc' id='rfc' placeholder="Filtrar clientes por RFC"
                            class="form-control rounded-0">
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <button type="button" class="btn  btn-default" name="agrega" id="agrega">Buscar</button>
                        <button type="button" class="btn  btn-default" id="limpiar">Limpiar busqueda</button>
                    </div>
                    <div class="col-xs-6 col-md-3" align="right">
                        <a href="{{asset('altaClientes')}}"><button type="button" class="btn btn-primary">Agregar nuevo
                            cliente</button></a>                        
                    </div>
                </div>
                <br>
                <div id='filtroClientes'>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Razòn social</th>
                                    <th>RFC</th>
                                    <th><center>Opciones</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consulta as $cl)
                                <tr>
                                    <td class="sorting_1">{{$cl->razonSocial}}</td>
                                    <td class="sorting_1">{{$cl->rfc}}</td>
                                    @if(Session::get('sesiontipo')=="Administrador")
                                    <td align="center">                          
                                            <a href="{{URL::action('ClientesController@eliminarClientes',['idc'=>$cl->idc])}}"
                                                type="submit" class="btn btn-xs btn-danger"><i
                                                    class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>                            
                                            <a href="{{URL::action('ClientesController@modificaClientes',['idc'=>$cl->idc])}}"
                                                class="btn btn-xs btn-info" style="width:67px;"><i
                                                    class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                        </td>
                                    @else
                                        @if($cl->activo=='si')
                                        <td align="center">                          
                                            <a href="{{URL::action('ClientesController@modificaClientes',['idc'=>$cl->idc])}}"
                                                class="btn btn-xs btn-info" style="width:67px;"><i
                                                    class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                        </td>
                                        @endif
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop