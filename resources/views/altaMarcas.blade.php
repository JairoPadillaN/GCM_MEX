@extends('principal')
@section('contenido')

		  <div class="header">
          <h1 class="page-header">Alta de 	Marcas<small>Registre nuevas marcas</small></h1>
			</div>

		{{Form::open(['route' => 'GuardarMarcas','files'=>true])}}
		{{Form::token()}}

		        <div id="page-inner">
                       <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">

															<div class="panel-heading">
                                  <div class="card-title">
                                      <div class="title">Datos de la marca</div>
                                  </div>
                              </div>

                                <div class="panel-body">

								<div class="form-group col-md-6">
                                    <div class="sub-title">Nombre:</div>
                                    <div>
																			@if($errors->first('nombreMarca'))
																			<p><i> {{ $errors->first('nombreMarca') }}</i></p>
																			@endif
																				{{Form::text('nombreMarca',old ('nombreMarca'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Nissan'])}}
                                    </div>
								<br><center>
																{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
																</div>
                         	</div>
                     </div>
                  </div>
							</div>
	@stop
