@extends('principal')
@section('contenido')

		  <div class="header">
          <h1 class="page-header">Reporte de Marcas</h1>
			</div>
            <div id="page-inner">

            <div class="row">
            	<div class="panel-heading"><a href="{{asset('altaMarcas')}}"><button type="button" class="btn btn-primary ">Agregar nueva marca</button></a></div>
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
																			<tr>
																				
																				<th>Nombre de las marcas</th>
																				<th>Opciones</th>
																			</tr>
                                    </thead>
																		<tbody>
																      @foreach($consulta as $m)
																    <tr>
																      
																      <td>{{$m->nombreMarca}}</td>
																     
																	<td>@if($m->activo=='si')
																          <a href="{{URL::action('MarcasController@eliminarMarcas',['idMarca'=>$m->idMarca])}}"  
																			type="submit" class="btn btn-xs btn-danger"><i class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
																          </a> <br>
																		 
																          <a href="{{URL::action('MarcasController@modificarMarcas',['idMarca'=>$m->idMarca])}}" class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
																		  <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
																          </a>
																         @else
																          <a href="{{URL::action('MarcasController@restaurarMarcas',['idMarca'=>$m->idMarca])}}" class="btn btn-xs btn-warning" style="width:67px">
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
            </div>
    </div>
	@stop
