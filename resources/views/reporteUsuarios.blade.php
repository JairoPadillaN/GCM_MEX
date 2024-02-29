@extends('principal')
@section('contenido')

<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte de Usuarios</h1> <br>

            <a href="{{asset('AltaUsuarios')}}"><button type="button" class="btn btn-primary">Agregar nuevo
                    usuario</button></a>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido paterno</th>
                            <th>Apellido materno</th>
                            <th>Correo</th>
                            <!--th>Password</th-->
                            <th>Foto</th>
                            <th>Tipo</th>
                            <th>Calle</th>
                            <th>Número</th>
                            <th>Colonia</th>
                            <th>Municipio</th>
                            <th>Estado</th>
                            <th>Télefono de casa</th>
                            <th>Télefono celular</th>
                            <th>licencia</th>
                            <!--th>activo</th-->
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consulta as $c)
                        <tr>
                            <td class="sorting_1">{{$c->nombreUsuario}}</td>
                            <td class="sorting_1">{{$c->aPaterno}}</td>
                            <td class="sorting_1">{{$c->aMaterno}}</td>
                            <td class="sorting_1">{{$c->correo}}</td>
                            <!--<td class="sorting_1">{{$c->password}}</td-->
                            <td><img src="{{asset ('archivos/'.$c->imagen)}}" height=50 width=50></td>
                            <!---archivo---->
                            <td class="sorting_1">{{$c->tipo}}</td>
                            <td class="sorting_1">{{$c->calle}}</td>
                            <td class="sorting_1">{{$c->num}}</td>
                            <td class="sorting_1">{{$c->colonia}}</td>
                            <td class="sorting_1">{{$c->municipio}}</td>
                            <td class="sorting_1">{{$c->estado}}</td>
                            <td class="sorting_1">{{$c->telCasa}}</td>
                            <td class="sorting_1">{{$c->telCel}}</td>
                            <td class="sorting_1">{{$c->licencia}}</td>
                            <!--td class="sorting_1">{{$c->activo}}</td-->
                            <td>@if($c->activo=='Si')
                                <a href="{{URL::action('UsuariosController@eliminarUsuario',['idu'=>$c->idu])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>

                                <a href="{{URL::action('UsuariosController@modificarUsuario',['idu'=>$c->idu])}}"
                                    class="btn btn-xs btn-info" style="width:67px;"><i
                                        class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                @else
                                <a href="{{URL::action('UsuariosController@restaurarUsuario',['idu'=>$c->idu])}}"
                                    class="btn btn-xs btn-warning" style="width:67px">
                                    Restaurar</a>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>
    <!--End Advanced Tables -->
</div>
    @stop