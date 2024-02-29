@extends('principal')
@section('contenido')


		{{Form::open(['route' => 'detalleViajes'])}}
		{{Form::token()}}

			<div id="page-inner">
							<div class="row">
									<div class="col-xs-12">
											<div class="panel panel-default">
												<div class="header">
														<h1 class="	">
															Detalle del Viaje
														</h1>
												</div>
												{{Form::hidden('idViaje',$consulta->idViaje)}}

													<div class="panel-body">
														<div class="form-group col-md-6">
															<div class="sub-title">Usuario: <br>
																{{Form::text('usuarioViaje',($consulta->usuarioViaje),['class' => 'form-control', 'readonly' => 'true'])}}
															</div>
														</div>



														<div class="form-group col-md-6">
																	<div class="sub-title">
																		@if($errors->first('Hola'))
																		<p><i> {{ $errors->first('Hola') }}</i></p>
																		@endif
																		Número de Placas: {{Form::text('Hola', \Carbon\Carbon::now(),['class' => 'form-control', 'readonly' => 'true'])}}
																	</div>
														</div>

														<div class="form-group col-md-6">
																	<div class="sub-title">Vehiculo:

																	{{Form::text('idVehiculo',($vehiculoSel->nombreVehiculo),['class' => 'form-control','readonly' => 'true'])}}



																	</div>

																	<div class="sub-title">Tipo de viaje:</div>
																	{{Form::text('tipoViaje',($consulta->tipoViaje),['class' => 'form-control', 'readonly' => 'true'])}}

														</div>
														<div class="form-group col-md-6">
																	<div class="sub-title">Empresa a visitar:

																	{{Form::text('idc',($clienteSel->razonSocial),['class' => 'form-control','readonly' => 'true'])}}


																	</div>
														</div>

														<div class="form-group col-md-6">
																	<div class="sub-title">
																		Monto: {{Form::text('montoGasto',($consulta->montoGasto),['class' => 'form-control', 'readonly' => 'true'])}}
																	</div>
														</div>





															<div class="form-group col-md-6">
																		<div class="sub-title">Motivo:</div>
																		{{Form::textarea('motivoViaje',($consulta->motivoViaje),['class' => 'form-control', 'readonly' => 'true'])}}
															</div>
															<p></p><br><br>
															<div class="form-group col-md-6">
																		<div class="sub-title">Observaciones de la unidad:
																				{{Form::textarea('observacionSalida',($consulta->observacionSalida),['class' => 'form-control', 'readonly' => 'true'])}}
																		</div>
															</div>


															<div class="form-group col-md-6">
																	<h4>Datos de salida de la unidad</h4>


																	<div class="sub-title">
																				Fecha de Salida: {{Form::text('fechaInicio',($consulta->fechaInicio),['class' => 'form-control', 'readonly' => 'true'])}}
																	</div>


																				<div class="sub-title">
																					Kilometraje inicial: {{Form::text('kmActual',($consulta->kmActual),['class' => 'form-control', 'readonly' => 'true'])}}
																				</div>



																	<div class="sub-title">Liquido de frenos:</div>
																				{{Form::text('liquidoFrenos',($consulta->liquidoFrenos),['class' => 'form-control', 'readonly' => 'true'])}}
																	<div class="sub-title">Parabrisas:</div>
																				{{Form::text('parabrisas',($consulta->parabrisas),['class' => 'form-control', 'readonly' => 'true'])}}
																	<div class="sub-title">Aire de llantas:</div>
																				{{Form::text('llantasSalida',($consulta->llantasSalida),['class' => 'form-control', 'readonly' => 'true'])}}
																	<div class="sub-title">Luces:</div>
																				{{Form::text('lucesSalida',($consulta->lucesSalida),['class' => 'form-control', 'readonly' => 'true'])}}
																	<div class="sub-title">Anticongelante:</div>
																				{{Form::text('anticongelante',($consulta->anticongelante),['class' => 'form-control', 'readonly' => 'true'])}}



															</div>


															<div class="form-group col-md-6">
																<h4>Datos de entrego de la unidad</h4>


																<div class="sub-title">
																		@if($errors->first('fechaFin'))
																		<p><i> {{ $errors->first('fechaFin') }}</i></p>
																		@endif
																		Fecha de Entrega: {{Form::text('fechaFin',($consulta->fechaFin),['class' => 'form-control', 'readonly' => 'true'])}}
																	</div>

																	<div class="sub-title">


																					Kilometraje Final:
																					{{Form::text('kmFinal',($consulta->kmFinal),['class' => 'form-control', 'readonly' => 'true'])}}


																				</div>

																	<div class="sub-title">Golpes:</div>
																				{{Form::text('golpes',($consulta->golpes),['class' => 'form-control', 'readonly' => 'true'])}}
																	<div class="sub-title">Rayaduras:</div>
																				{{Form::text('rayaduras',($consulta->rayaduras),['class' => 'form-control', 'readonly' => 'true'])}}
															</div>
															<div class="form-group col-md-6">
																	<div class="sub-title">Aire de llantas:</div>
																				{{Form::text('llantasEntrega',($consulta->llantasEntrega),['class' => 'form-control', 'readonly' => 'true'])}}
																	<div class="sub-title">Luces:</div>
																				{{Form::text('lucesEntrega',($consulta->lucesEntrega),['class' => 'form-control', 'readonly' => 'true'])}}
															</div>
															<div class="form-group col-md-6">
																		<div class="sub-title">Observaciones de entrega de la unidad</div>
																		@if($errors->first('observacionEntrega'))
																		<p><i> {{ $errors->first('observacionEntrega') }}</i></p>
																		@endif
															      <div>{{Form::textarea('observacionEntrega',($consulta->observacionSalida),['class' => 'form-control', 'readonly' => 'true'])}}</div>
															</div>


								</div>

													<center><a href="{{asset('reporteViajes')}}"><button type="button" class="btn btn-warning">Cancelar</button></a></center>
											</div>
									</div>
					</div>
	</div>

@stop
