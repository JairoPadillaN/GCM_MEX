@extends('principal')
@section('contenido')

<script type="text/javascript">
    function filtrarCuentas() {
        var nombreOempresa = $("#nombreOempresa").val().replace(' ','_');        
        $("#filtroCuentas").load('{{url('reporteCuentasAbajo')}}' + '?nombreOempresa=' + nombreOempresa);
    };

    function limpiar(){
        $("#nombreOempresa").val("");
        var nombreOempresa = $("#nombreOempresa").val();
        $("#filtroCuentas").load('{{url('reporteCuentasAbajo')}}' + '?nombreOempresa=' + nombreOempresa);
    };
</script>


<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1>Reporte de cuentas</h1>
        </div>
        <div class="panel-body">
            <div class="row">            
                <div class="col-sm-3">
                    <input type='text' id='nombreOempresa' onkeyup="filtrarCuentas()" placeholder="Filtrar cuentas por nombre o empresa" class="form-control rounded-0">
                </div>                
                <div class="col-sm-2">
                    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar busqueda</button>
                </div>
                <div class="col-sm-2">
                    @if(Session::get('sesiontipo')!="Compras")
                        <a href="{{asset('altaCuentas')}}"><button type="button" class="btn btn-primary ">Agregar nueva cuenta</button></a>
                    @endif
                </div>            
            </div>
            <br><br>
            
            <div id='filtroCuentas'>
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
            </div>
        </div>
    </div>
    <!--End Advanced Tables -->
</div>

@stop