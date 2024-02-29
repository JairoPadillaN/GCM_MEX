@if($cuantos>=1)
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th>Razòn social</th>
            <th>rfc</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporte as $cl)
        <tr>
            <td class="sorting_1">{{$cl->razonSocial}}</td>
            <td class="sorting_1">{{$cl->rfc}}</td>
            <td>
                <a href="{{URL::action('ClientesController@eliminarClientes',['idc'=>$cl->idc])}}" type="submit"
                    class="btn btn-xs btn-danger"><i class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>
                <a href="{{URL::action('ClientesController@modificaClientes',['idc'=>$cl->idc])}}"
                    class="btn btn-xs btn-info" style="width:67px;"><i class="ace-icon fa fa-pencil bigger-120">
                        Editar</i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
No existen clientes
@endif