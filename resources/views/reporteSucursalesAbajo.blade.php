@if($cuantos>=1)
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th>Sucursal</th>
            <th>Cliente</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporte as $suc)
        <tr>
            <td class="sorting_1">{{$suc->sucursal}}</td>
            <td class="sorting_1">{{$suc->cli}}</td>
            @if(Session::get('sesiontipo')=="Administrador")
            <td>
                @if($suc->activo=='Si')
                <a href="{{URL::action('SucursalesController@eliminarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                    type="submit" class="btn btn-xs btn-danger"><i class="ace-icon fa fa-trash-o bigger-120">
                        Eliminar</i></a>

                <a href="{{URL::action('SucursalesController@modificarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                    class="btn btn-xs btn-info" style="width:67px;"><i class="ace-icon fa fa-pencil bigger-120">
                        Editar</i></a>
                @else
                <a href="{{URL::action('SucursalesController@restaurarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                    class="btn btn-xs btn-warning" style="width:67px">
                    Restaurar</a>
                @endif
            </td>
            @endif
            @if(Session::get('sesiontipo')=="Vendedor")
            <td>

                @if($suc->activo=='Si')


                <a href="{{URL::action('SucursalesController@modificarSucursales',['idSucursal'=>$suc->idSucursal])}}"
                    class="btn btn-xs btn-info" style="width:67px;"><i class="ace-icon fa fa-pencil bigger-120">
                        Editar</i></a>
                @else

                @endif
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
@else
No existen sucursales
@endif
