@extends('principal')
@section('contenido')

		  <div class="header">
          <h1 class="page-header">Modificacion de Movimientos <small>Edite los datos de movimientos</small></h1>
			</div>

		{{Form::open(['route' => 'editarMovimientos'])}}
		{{Form::token()}}

		        <div id="page-inner">
              <div class="row">
                <div class="col-xs-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <div class="card-title"><div class="title">Datos</div></div>
        						</div>

          					<div class="panel-body">
							
					<div class="form-group col-md-6">
								{{Form::hidden('idm',$consulta->idm)}}
            					<div class="sub-title">Fecha:</div>
												@if($errors->first('fecha'))
												<p><i> {{ $errors->first('fecha') }}</i></p>
												@endif

								<div>{{Form::date('fecha',$consulta->fecha,['class' => 'form-control'])}}
								{{Form::hidden('tipo', 'S')}}</div>

            		</div>
              				
							
					<div class="form-group col-md-6">
								<div class="sub-title">Monto:</div>
											  @if($errors->first('monto'))
			 								 <p><i> {{ $errors->first('monto') }}</i></p>
			 								  @endif
								<div>{{Form::text('monto', $consulta->monto, ['class' => 'form-control', 'placeholder' => 'Ejemplo: 100'])}}</div>
					</div>	
							
						
					<div class="form-group col-md-4">
            					<div class="sub-title">Tipo de gasto:</div>
              				<div><select name = 'idTipoGastos'>
												<option value='{{$idtipoGastossel}}'>{{$nomTipoGastos}}</option>
												@foreach($tipoG as $tg)
														@if($tg->activo=="Si")
															<option value='{{$tg->idTipoGastos}}'>{{$tg->nombreTipoGasto}}</option>
														@endif
													@endforeach

											</select></div>
											
					</div>						
											

					<div class="form-group col-md-4">
					            <div class="sub-title">Empleado:</div>
					            <div><select name = 'idu'>
											<option value='{{$idusuariosel}}'>{{$nomusuario}} {{$aPaterno}} {{$aMaterno}}</option>
												@foreach($usuario as $us)
												@if($us->activo=="Si")
                                        <option value='{{$us->idu}}'>{{$us->tipo}} - {{$us->nombreUsuario}} {{$us->aPaterno}}
                                            {{$us->aMaterno}} </option>
                                    @endif
												@endforeach
											</select></div>
											
					</div>
					
					<div class="form-group col-md-4">
								<div class="sub-title">Cliente:</div>
								<div><select name = 'idc'>
											<option value='{{$idclientesel}}'>{{$nomcli}}</option>
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
											 <p><i> {{ $errors->first('detalle') }}</i></p>
												@endif
											<div>{{Form::textarea('detalle',$consulta->detalle)}}</div>
					</div>
					
					
					<div class="form-group col-md-6">
					            <div class="sub-title">Motivo:</div>
												@if($errors->first('razon'))
												<p><i> {{ $errors->first('razon') }}</i></p>
												@endif
					            <div>{{Form::textarea('razon',$consulta->razon)}}</div>

					</div>					

					

					            <br><center>
					            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
								<a href="{{asset('reporteMovimientos')}}"><button type="button" class="btn btn-warning">Cancelar</button></a>
                                </div>
                          </div>
                     </div>
                  </div>
              </div>
  @stop
