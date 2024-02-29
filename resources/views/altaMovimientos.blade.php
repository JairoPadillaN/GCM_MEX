@extends('principal')
@section('contenido')

		  <div class="header">
          <h1 class="page-header">Alta Movimientos <small>Registre nuevos movimientos</small></h1>
			</div>

		{{Form::open(['route' => 'GuardarMov','files'=>true])}}
		{{Form::token()}}

		        <div id="page-inner">
              <div class="row">
                <div class="col-xs-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <div class="card-title"><div class="title">Datos del movimiento</div></div>
        						</div>

          					<div class="panel-body">
							<div class="form-group col-md-6">
	            					<div class="sub-title">Fecha:</div>
													@if($errors->first('fecha'))
													<i> {{ $errors->first('fecha') }}</i>
													@endif
	              					<div>{{Form::date('fecha', \Carbon\Carbon::now(),['class' => 'form-control'])}}</div>

							</div>


							<div class="form-group col-md-6">

									<div class="sub-title" >Monto:</div>
													@if($errors->first('monto'))
													<i> {{ $errors->first('monto') }}</i>
													@endif
									<div>{{Form::text('monto', old('monto'), ['class' => 'form-control', 'placeholder' => 'Ejemplo: 100'])}}</div>
							</div>

							<div class="form-group col-md-4">

			            			<div>{{Form::hidden('tipo', 'S')}}</div>
			            			<div class="sub-title">Tipo de gasto:</div>
			              			<select name = 'idTipoGastos' class="form-control rounded-0">
													@foreach($tipoG as $tg)
														@if($tg->activo=="Si")
															<option value='{{$tg->idTipoGastos}}'>{{$tg->nombreTipoGasto}}</option>
														@endif
													@endforeach
									</select>
							</div>


							<div class="form-group col-md-4">

								            <div class="sub-title">Empleado:</div>
								            <div><select name = 'idu' class="form-control rounded-0">
															@foreach($usuario as $us)
															@if($us->activo=="Si")
														<option value='{{$us->idu}}'>{{$us->nombreUsuario}} {{$us->aPaterno}} {{$us->aMaterno}} </option>
														@endif
															@endforeach
														</select></div>

					        </div>


							<div class="form-group col-md-4">
											<div class="sub-title">Cliente:</div>
              				<div><select name = 'idc' class="form-control rounded-0">
												@foreach($cli as $cli)
												@if($cli->activo=="si")
                                            <option value='{{$cli->idc}}'>{{$cli->razonSocial}}</option>
                                        @endif
												@endforeach
											</select></div>

							</div>



							<div class="form-group col-md-6">
				             	<div class="sub-title">Detalle:</div>
												@if($errors->first('detalle'))
											 <i> {{ $errors->first('detalle') }}</i>
												@endif
											<div><textarea name="detalle" class="form-control rounded-0"></textarea></div>




							</div>

							<div class="form-group col-md-6">
								<div class="sub-title">Motivo:</div>
												@if($errors->first('razon'))
												<i> {{ $errors->first('razon') }}</i>
												@endif
					            <div><textarea name="razon" class="form-control rounded-0"></textarea></div>
							</div>


					            <br><center>
					            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}

                                </div>
                          </div>
                     </div>
                  </div>
              </div>
  @stop
