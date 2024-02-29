@extends('principal')
@section('contenido')

		{{Form::token()}}

			<div id="page-inner">
							<div class="row">
									<div class="col-xs-12">
											<div class="panel panel-default">
												<div class="header">
														<h1 class="	">
															 Detalle de viajes
														</h1>
												</div><br>

													<div class="panel-body">
													<div class="form-group col-md-6">
														<h3>Datos de salida</h3>

																	<div class="sub-title">Usuariox:</div><div>
																		{{Form::text('usuarioViaje',($consulta->usuarioViaje),['class' => 'form-control', 'readonly' => 'true'])}}
																		{{Form::hidden('idViaje',$consulta->idViaje)}}<!--Este cuadro no se vera en la vista ya que es tipo hidden "oculto" solo sera para obetener el ide a modificar y lo enviara al metodo editarUsuario-->
																	</div>
																	<div class="row">
																		 <div class="form-group col-md-6">
																					<div class="sub-title">Fecha de Salida: </div><div>
																						{{Form::text('fechaInicio',($consulta->fechaInicio),['class' => 'form-control', 'readonly' => 'true'])}}
																					</div>
																			</div>
																			<div class="form-group col-md-6">
 																					<div class="sub-title">Fecha de Finzalización: </div><div>
																						{{Form::text('fechaFin',($consulta->fechaFin),['class' => 'form-control', 'readonly' => 'true'])}}
 																					</div>
 																			</div>

																			<div class="form-group col-md-6">
																					<div class="sub-title">Tipo de viaje:</div>
																					{{Form::text('tipoViaje',($consulta->tipoViaje),['class' => 'form-control', 'readonly' => 'true'])}}
																					</div>

																			<div class="form-group col-md-6">
																					<div class="sub-title">Empresa a visitar:</div><div>
																						<select name = 'idc' class="form-control" style="width:220px; height:34px;" disabled >
																						@foreach($cliente as $cliente)
																						<option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
																						@endforeach
																						</select>
																					</div>
																			</div>
																			<div class="form-group col-md-6">
																						<div class="sub-title">Monto del viaje:</div><div>
																							{{Form::text('montoGasto',($consulta->montoGasto),['class' => 'form-control', 'readonly' => 'true'])}}
																						</div>
																			</div>
																	</div>
																	<div class="row">
																			<div class="form-group col-md-6">
																							<div class="sub-title">Motivo:</div><div>
																							<textarea name="motivoViaje" rows="6" cols="64" disabled>{!!($consulta->motivoViaje)!!}</textarea></div>
																			</div>
																	</div>
													</div>

													<div class="form-group col-md-6">
																<h3>Estado del vehículo</h3>
																<div class="row">
																			<div class="sub-title">Vehiculo:</div><div>
																				<select name = 'idVehiculo' class="form-control" disabled>
																				@foreach($vehiculo as $vehiculo)
																				<option value='{{$vehiculo->idVehiculo}}'>{{$vehiculo->nombreVehiculo}} {{$vehiculo->tipoVehiculo}} {{$vehiculo->placas}} {{$vehiculo->idMarca}}</option>
																				@endforeach
																				</select>
																			</div>
													   	<div class="form-group col-md-6">
																<div class="sub-title">Evidencia Km y gasolina salida:</div><div>
																	<center>
																		@foreach($consultaImg as $consImg)
																			<img src="{{asset ('public/archivos/'.$consImg->fotoEvidenciaSalida)}}" height=60 width=60> <!---archivo---->
																	@endforeach
																</center>
																</div>
												 			</div>
															<div class="form-group col-md-6">
																<div class="sub-title">Kilometraje inicial: </div><div>
																	{{Form::text('kmActual',($consulta->kmActual),['class' => 'form-control', 'readonly' => 'true'])}}
																</div>
															</div>
														</div>
													<div class="row">
															<div class="form-group col-md-6">
																	<div class="sub-title">Evidencia Km y gasolina entrega:</div><div>
																		<center>
																			@foreach($consultaImg2 as $consImg2)
																				<img src="{{asset ('public/archivos/'.$consImg2->fotoEvidenciaRegreso)}}" height=60 width=60> <!---archivo---->
																			@endforeach
																	</center>
																	</div>
															</div>
															<div class="form-group col-md-6">
																<div class="sub-title">Kilometraje Final: </div><div>
																	{{Form::text('kmActual',($consulta->kmFinal),['class' => 'form-control', 'readonly' => 'true'])}}
																</div>
															</div>

													</div>
														<div class="row">
															<div class="form-group col-md-6">
																		<div class="sub-title">Parabrisas:</div>
																		<div>
																			{{Form::text('parabrisas',($consulta->parabrisas),['class' => 'form-control', 'readonly' => 'true'])}}
																		</div>
																		<div class="sub-title">Luces:</div>
																		<div>
																			{{Form::text('lucesSalida',($consulta->lucesSalida),['class' => 'form-control', 'readonly' => 'true'])}}
																		</div>
																		<div class="sub-title">Anticongelante:</div>
																		<div>
																			{{Form::text('anticongelante',($consulta->anticongelante),['class' => 'form-control', 'readonly' => 'true'])}}
																		</div>
																		<div class="sub-title">Liquido de frenos:</div>
																		<div>
																			{{Form::text('liquidoFrenos',($consulta->liquidoFrenos),['class' => 'form-control', 'readonly' => 'true','style'=>'30px'])}}
																		</div>
																		<div class="sub-title">Aire de llantas:</div>
																		<div>
																			{{Form::text('llantasSalida',($consulta->llantasSalida),['class' => 'form-control', 'readonly' => 'true'])}}
																		</div>
															</div>
															<div class="row">
																<div class="form-group col-md-6">
																	<div class="sub-title">Golpes:</div>
																		<div>
																			{{Form::text('golpes',($consulta->golpes),['class' => 'form-control', 'readonly' => 'true'])}}
																		</div>
																		<div class="sub-title">Rayaduras:</div>
																			<div>
																			{{Form::text('rayaduras',($consulta->rayaduras),['class' => 'form-control', 'readonly' => 'true'])}}
																			</div>
																	<div class="sub-title">Aire de llantas:</div>
																			<div>
																			{{Form::text('llantasEntrega',($consulta->llantasEntrega),['class' => 'form-control', 'readonly' => 'true'])}}
																			</div>
																  <div class="sub-title">Luces:</div>
																			<div>
																			{{Form::text('lucesEntrega',($consulta->lucesEntrega),['class' => 'form-control', 'readonly' => 'true'])}}
																			</div>
																			<div class="sub-title">Observaciones de entrega de la unidad:</div><div>
																		  <textarea name="observacionEntrega" rows="7" cols="28" disabled>{!!($consulta->observacionEntrega)!!}</textarea></div>
																</div>
															</div>
														</div>
															<div class="row">
															<div class="form-group col-md-6">
																		<div class="sub-title" style="margin-top:-155px; !important">Observaciones de salida de la unidad:</div><div>
																			<textarea name="observacionSalida" rows="7" cols="28" disabled>{!!($consulta->observacionSalida)!!}</textarea></div>
															</div>
															</div>
													</div>
													</div>
														<center>
															<a href="{{asset('reporteViajes')}}"><button type="button" class="btn btn-default">Regresar</button></a>
														</center>
													<br>
											</div>
									</div>
					</div>
	</div>

@stop
