@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarServicioTecnico','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        
		<div class="panel-heading">
            <h1>Alta de servicio</h1>            
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">SKU:</div>
                <div>
                    @if($errors->first('claveServicio'))
                    <p><i> {{ $errors->first('claveServicio') }}</i></p>
                    @endif
                    {{Form::text('claveServicio',old ('claveServicio'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 012345'])}}
                </div>
                <div class="sub-title">Nombre:</div>
                <div>
                    @if($errors->first('servicioTecnico'))
                    <p><i> {{ $errors->first('servicioTecnico') }}</i></p>
                    @endif
                    {{Form::text('servicioTecnico',old ('servicioTecnico'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Traslados y vuelos redondos'])}}
                </div>

                <div class="sub-title">Detalle:</div>
                <div>
                    @if($errors->first('detalleServicio'))
                    <p><i> {{ $errors->first('detalleServicio') }}</i></p>
                    @endif
                    {{Form::textarea('detalleServicio',old ('detalleServicio'),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Monto en pesos:</div>
                <div>
                    @if($errors->first('montoPesos'))
                    <p><i> {{ $errors->first('montoPesos') }}</i></p>
                    @endif
                    {{Form::text('montoPesos',old ('montoPesos'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 200.40'])}}
                </div>

                <div class="sub-title">Monto en dólares:</div>
                <div>
                    @if($errors->first('montoDolares'))
                    <p><i> {{ $errors->first('montoDolares') }}</i></p>
                    @endif
                    {{Form::text('montoDolares',old ('montoDolares'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 200.40'])}}
                </div>

                <div class="sub-title">Unidad de medida:</div>
                <select name="unidadMedida" id="unidadMedida" class="form-control">
                    <option value="">Selecciona una opción</option>
                    <option value="Activity">Activity</option>
                    <option value="Acre">Acre</option>
		    <option value="Box">Box</option>
                    <option value="Day">Day</option>
                    <option value="Event">Event</option>
                    <option value="Foot">Foot</option>
		    <option value="Gallon">Gallon</option>
                    <option value="Hour">Hour</option>
                    <option value="Inch">Inch</option>
		    <option value="Km">Km</option>
 	            <option value="Ounce">Ounce</option>
                    <option value="Package">Package</option>
                    <option value="Person">Person</option>
                    <option value="Piece">Piece</option>
 		    <option value="Pound">Pound</option>
 	            <option value="Square Foot">Square Foot</option>
                    <option value="Yard">Yard</option>
                </select>

                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </div>
        </div>

    </div>
</div>
@stop