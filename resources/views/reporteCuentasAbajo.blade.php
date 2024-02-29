@if($cuantos>=1)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Número</th>
                    <th>Descripción</th>
                    <th>Moneda</th>
                    <th>Saldo</th>
                    @if(Session::get('sesiontipo')!="Compras")
                        <th>Opciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($consulta as $cu)
                <tr>
                    <td>{{$cu->nombreCuenta}}</td>
                    <td>{{$cu->empresaCuenta}}</td>
                    <td>{{$cu->numeroCu}}</td>
                    <td>{{$cu->descripcionCuenta}}</td>
                    <td>{{$cu->monedaCuenta}}</td>
                    <td>{{$cu->saldoCuenta}}</td>
                    @if(Session::get('sesiontipo')!="Compras")
                    <td>                            
                        @if($cu->activo=='Si')
                        <a href="{{URL::action('cuentasController@eliminarCuentas',['idCuenta'=>$cu->idCuenta])}}"
                            type="submit" class="btn btn-xs btn-danger"><i
                                class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                        </a> <br>

                        <a href="{{URL::action('cuentasController@modificarCuentas',['idCuenta'=>$cu->idCuenta])}}"
                            class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                            <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                        </a>
                        @else
                        <a href="{{URL::action('cuentasController@restaurarCuentas',['idCuenta'=>$cu->idCuenta])}}"
                            class="btn btn-xs btn-warning" style="width:67px">
                            Restaurar</a>
                        @endif
                    </td>
                    @endif                            
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    Sin resultados
@endif