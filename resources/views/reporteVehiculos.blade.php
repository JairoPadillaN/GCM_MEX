@extends('principal')
@section('contenido')

		  <div class="header">
          <h1 class="page-header">Reporte de Vehículos</h1>
			</div>
            <div id="page-inner">

            <div class="row">
							<div class="col-md-5">
								<a href="{{asset('AltaVehiculos')}}"><button type="button" class="btn btn-primary">Agregar nuevo vehículo</button></a>
							</div>
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">Vehículos registrados</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
																			<tr>
																				<th>Id</th>
																				<th>Nombre de vehiculo </th>
																				<th>Marca</th>
																				<th>Tipo</th>
																				<th>km Actual</th>
																				<th>Serial</th>
																				<th>Placas</th>
																				<th>Toneladas</th>
																				<th>Motor</th>
																				<th>Transmisión</th>
																				<th>Aseguradora</th>
																				<th>Número de póliza</th>
																				<th>Descripción de póliza</th>
																				<!--th>activo</th-->
																				<th>Opciones</th>
																			</tr>
                                    </thead>
                                    <tbody>
																			@foreach($consulta as $c)
																		<tr>
																			<td class="sorting_1">{{$c->idVehiculo}}</td>
																			<td class="sorting_1">{{$c->nombreVehiculo}}</td>
																			<td class="sorting_1">{{$c->nombreMarca}}</td>
																			<td class="sorting_1">{{$c->tipoVehiculo}}</td>
																			<td class="sorting_1">{{$c->kmActual}}</td>
																			<td class="sorting_1">{{$c->serial}}</td>
																			<td class="sorting_1">{{$c->placas}}</td>
																			<td class="sorting_1">{{$c->toneladas}}</td>
																			<td class="sorting_1">{{$c->motor}}</td>
																			<td class="sorting_1">{{$c->transmision}}</td>
																			<td class="sorting_1">{{$c->empresaAseguradora}}</td>
																			<td class="sorting_1">{{$c->numPoliza}}</td>
																			<td class="sorting_1">{{$c->descripcionPoliza}}</td>
																			<!--td class="sorting_1">{{$c->activo}}</td-->
																			<td>@if($c->activo=='Si')
																          <a href="{{URL::action('VehiculosController@eliminarVehiculos',['idVehiculo'=>$c->idVehiculo])}}" class="btn btn-xs btn-danger"><i class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>
																          <a href="{{URL::action('VehiculosController@modificarVehiculos',['idVehiculo'=>$c->idVehiculo])}}" class="btn btn-xs btn-info" style="width:67px; margin-top:5px"><i class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
																         @else
																          <a href="{{URL::action('VehiculosController@restaurarVehiculos',['idVehiculo'=>$c->idVehiculo])}}" class="btn btn-xs btn-warning" style="width:67px">
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
