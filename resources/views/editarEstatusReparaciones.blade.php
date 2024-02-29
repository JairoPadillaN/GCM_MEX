@extends('principal')
@section('contenido')

<div class="header">
	<h1 class="page-header">Modificación de estatus de reparación</h1>
</div>

{{Form::open(['route' => 'editarEstatus','files'=>true])}}
{{Form::token()}}

<div id="page-inner">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">

				<div class="panel-heading">
					<div class="card-title">
						<div class="title">Datos del estatus</div>
					</div>
				</div>

				<div class="panel-body">

					<div class="form-group col-md-6">
						<div class="sub-title">Nombre del estatus:</div>
						<div>
							@if($errors->first('nombreEstatus'))
							<p><i> {{ $errors->first('nombreEstatus') }}</i></p>
							@endif
							{{Form::hidden('idEstatus',$consulta->idEstatus)}}
							{{Form::text('nombreEstatus',$consulta->nombreEstatus,['class' => 'form-control', 'placeholder' => 'Ejemplo: Pendiente'])}}
						</div>
						<br>
						<center>
							{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
							<a href="{{asset('reporteEstatusReparaciones')}}"><button type="button"
									class="btn btn-default">Cancelar</button></a>
					</div>

				</div>
			</div>
		</div>
	</div>
	@stop