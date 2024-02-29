@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        //      alert ("hola");
        $("#filtroSucursales").load('{{url('reporteSucursalesAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#limpiar").click(function() {
        $("#sucursal").val("");
        $("#idc").val("");
        $("#filtroSucursales").load('{{url('reporteSucursalesAbajo')}}' + '?' + $(this).closest('form').serialize());

    });


});
</script>

<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Sucursales</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">                    
                    <div class="col-lg-3">
                        <input type='text' name='sucursal' id='sucursal'
                            placeholder="Filtrar sucursales por nombre" class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        <select name='idc' id='idc' class="form-control">
                            <option value="">Filtrar por cliente</option>
                            @foreach($cliente as $cliente)
                            <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <button type="button" class="btn  btn-default" name="agrega" id="agrega">Buscar</button>
                        <button type="button" class="btn  btn-default" id="limpiar">Limpiar busqueda</button>
                    </div>
                    <div class="col-lg-3" align="right">
                        @if(Session::get('sesiontipo')=="Administrador")
                        <a href="{{asset('altaSucursales')}}"><button type="button" class="btn btn-primary ">Agregar
                        nueva sucursal</button></a>
                        @endif
                    </div>
                </div>
                <br>
                <div id='filtroSucursales'>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Sucursal</th>
                                    <th>Cliente</th>
                                    <th><center>Opciones</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consulta as $suc)
                                <tr>
                                    <td class="sorting_1">{{$suc->sucursal}}</td>
                                    <td class="sorting_1">{{$suc->cli}}</td>
                                    @if(Session::get('sesiontipo')=="Administrador")
                                    <td align="center">
                                        @if($suc->activo=='Si')
                                        <a href="{{URL::action('SucursalesController@eliminarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                                            type="submit" class="btn btn-xs btn-danger"><i
                                                class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>

                                        <a href="{{URL::action('SucursalesController@modificarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                                            class="btn btn-xs btn-info" style="width:67px;"><i
                                                class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                        @else
                                        <a href="{{URL::action('SucursalesController@restaurarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                                            class="btn btn-xs btn-warning" style="width:67px">
                                            Restaurar</a>
                                        @endif
                                    </td>
                                    @endif
                                    @if(Session::get('sesiontipo')=="Vendedor")
                                    <td align="center">
                                        @if($suc->activo=='Si')
                                        <a href="{{URL::action('SucursalesController@modificarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                                            class="btn btn-xs btn-info" style="width:67px;"><i
                                                class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                        @endif
                                    </td>
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