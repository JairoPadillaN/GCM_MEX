@extends('principal')
@section('contenido')

		  <div class="header">
          <h1 class="page-header">Alta de Tipo de gastos <small>Registre nuevo tipo de gasto</small></h1>
			</div>

		{{Form::open(['route' => 'GuardarTipoGastos'])}}
		{{Form::token()}}

		        <div id="page-inner">
                       <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="sub-title">Nombre del gasto:</div>
                                    <div>
																			@if($errors->first('nombreTipoGasto'))
																			<p><i> {{ $errors->first('nombreTipoGasto') }}</i></p>
																			@endif
																				{{Form::text('nombreTipoGasto',old ('nombreTipoGasto'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Caja chica'])}}
                                    </div>

                                    <div class="sub-title">Tipo</div>
                                    <div>
																			@if($errors->first('tipo'))
																			<p><i> {{ $errors->first('tipo') }}</i></p>
																			@endif
                                    	<select name="tipo" class="form-control rounded-0">
																				<option selected="selected" value="">Seleccione un tipo</option>
                                        <option value="Entrada">Entrada</option>
                                        <option value="Salida">Salida</option>
                                      </select>
                                    </div>
																		<br><center>
																			{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                                </div>
                            </div>
                        </div>
                    </div>
							</div>
	@stop
