@extends('principal')
@section('contenido')

{{Form::open(['route' => 'Guardar_Clientes','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de clientes nuevos nuevos<small>* Campos Requeridos</small></h1><br><br>
            <div class="card-title">
                <div class="title">Datos del cliente</div>
            </div>
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">

                <div class="sub-title">* Razón social:</div>
                <div>
                    @if($errors->first('razonSocial'))
                    <i>{{ $errors->first('razonSocial') }}</<i>
                        @endif
                        {{Form::text('razonSocial',old ('razonSocial'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Coca Cola'])}}
                </div>

                <div class="sub-title">Número de proveedor:</div>
                <div>
                    @if($errors->first('numeroProveedor'))
                    <i>{{ $errors->first('numeroProveedor') }}</<i>
                        @endif
                        {{Form::text('numeroProveedor',old ('numeroProveedor'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 000129'])}}
                </div>

                <div class="sub-title">* RFC:</div>
                <div>
                    @if($errors->first('rfc'))
                    <i> {{ $errors->first('rfc') }}</i>
                    @endif
                    {{Form::text('rfc',old ('rfc'),['class' => 'form-control', 'placeholder' => 'Ejemplo: COCA23432CFD'])}}
                </div>

                <div class="sub-title">* Contacto:</div>
                <div>
                    @if($errors->first('contacto'))
                    <i> {{ $errors->first('contacto') }}</i>
                    @endif
                    {{Form::text('contacto',old ('contacto'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 7223981764'])}}
                </div>

                <div class="sub-title">* Días de Pago:</div>
                <div>
                    <select class="form-control rounded-0" name="diasDePago">
                        <option selected="selected" value="">Seleccione los días de Pago</option>
                        <option value="0">0</option>
                        <option value="7">7</option>                        
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                        <option value="60">60</option>
                        <option value="90">90</option>
                    </select>
                </div>

            </div>

            <div class="form-group col-md-6">

                <div class="sub-title">* Calle:</div>
                <div>
                    @if($errors->first('calle'))
                    <i> {{ $errors->first('calle') }}</i>
                    @endif
                    {{Form::text('calle',old ('calle'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Libertad'])}}
                </div>

                <div class="sub-title">* Número:</div>
                <div>
                    @if($errors->first('num'))
                    <i> {{ $errors->first('num') }}</i>
                    @endif
                    {{Form::text('num',old ('num'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 364'])}}
                </div>

                <div class="sub-title">* Colonia:</div>
                <div>
                    @if($errors->first('colonia'))
                    <i> {{ $errors->first('colonia') }}</i>
                    @endif
                    {{Form::text('colonia',old ('colonia'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Álvaro Obregón'])}}
                </div>
                <div class="sub-title">* Tipo de cliente:</div>
                    <div>
                        @if($errors->first('tipoCliente'))
                        <i> {{ $errors->first('tipoCliente') }}</i>
                        @endif<div class="row">
                        <div class="form-group col-md-3"> {{Form::radio('tipoCliente', 'prospecto')}} Prospecto</div>
                        <div class="form-group col-md-3"> {{Form::radio('tipoCliente', 'activo')}} Activo</div>
                        <div class="form-group col-md-3"> {{Form::radio('tipoCliente', 'noActivo')}} No Activo</div>
                    </div>
                </div>

                <div class="sub-title">* Empresa a la que pertenece:</div>
                    <div>
                        @if($errors->first('empresaPertenece'))
                        <i> {{ $errors->first('empresaPertenece') }}</i>
                        @endif<div class="row">
                        <div class="form-group col-md-3"> {{Form::radio('empresaPertenece', 'SURJA')}} SURJA</div>
                        <div class="form-group col-md-3"> {{Form::radio('empresaPertenece', 'GCM')}} GCM</div>
                        <div class="form-group col-md-3"> {{Form::radio('empresaPertenece', 'CYM')}} CYM</div>
                    </div>
                </div>
            </div>
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
        </center><br>
    </div>
</div>

@stop