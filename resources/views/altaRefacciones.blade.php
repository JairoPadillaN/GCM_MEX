@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarRefacciones','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de refacciones</h1>
        </div>
        
        <div class="panel-body">
            <div class="">
                <div class="form-group col-md-6">
                    <div class="sub-title">Nombre de la refacción:</div>
                    <div>
                        @if($errors->first('nombreRefaccion'))
                        <i> {{ $errors->first('nombreRefaccion') }}</i>
                        @endif
                        {{Form::text('nombreRefaccion',old ('nombreRefaccion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico'])}}
                    </div>

                    <div class="sub-title">*Tipo de refacción:</div>
                    <div>
                        @if($errors->first('idTipoRefacciones'))
                        <i> {{ $errors->first('idTipoRefacciones') }}</i>
                        @endif
                        <select name='idTipoRefacciones' class="form-control">
                            <option value="">Seleccione un tipo de refacción</option>
                            @foreach($tipoRef as $tr)
                            @if($tr->activo=="Si")
                            <option value='{{$tr->idTipoRefacciones}}'>{{$tr->nombreTipoRefaccion}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="sub-title">Número de parte:</div>
                            <div>
                                @if($errors->first('numeroParte'))
                                <i> {{ $errors->first('numeroParte') }}</i>
                                @endif
                                {{Form::text('numeroParte',old ('numeroParte'),['class' => 'form-control', 'placeholder' => 'Ejemplo: E-1 ó 1652'])}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="sub-title">Número de serie:</div>
                            <div>
                                @if($errors->first('serie'))
                                <i> {{ $errors->first('serie') }}</i>
                                @endif
                                {{Form::text('serie',old ('serie'),['class' => 'form-control', 'placeholder' => 'Ejemplo: ABC123165-2'])}}
                            </div>
                        </div>
                    </div>   
                    <div class="sub-title">*Proveedor:</div>
                    <div>
                        @if($errors->first('idProveedor'))
                        <i> {{ $errors->first('idProveedor') }}</i>
                        @endif
                        <select name='idProveedor' class="form-control">
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedor as $prov)
                            @if($prov->activo=="Si")
                            <option value='{{$prov->idProveedor}}'>{{$prov->razonSocialProv}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <div class="sub-title">Ubicación:</div>
                    <div>
                        @if($errors->first('ubicacion'))
                        <i> {{ $errors->first('ubicacion') }}</i>
                        @endif
                        {{Form::text('ubicacion',old ('ubicacion'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Bodega'])}}
                    </div>

                    <div class="sub-title">Estatus:</div>
                    @if($errors->first('estatus'))
                    <i> {{ $errors->first('estatus') }}</i>
                    @endif
                    <div>
                        Stock {{Form::radio('estatus','Stock')}}&nbsp;&nbsp;&nbsp;
                        Solicitado {{Form::radio('estatus','Solicitado')}}&nbsp;&nbsp;&nbsp;
                        Enviado a reparar {{Form::radio('estatus','Enviado a reparar')}}
                    </div>
    <br>
                    <div class="sub-title">Precio lista:</div>
                    <div>
                        @if($errors->first('precioLista'))
                        <i> {{ $errors->first('precioLista') }}</i>
                        @endif
                        {{Form::text('precioLista',old ('precioLista'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                    </div>

                    <div class="sub-title">Precio último:</div>
                    <div>
                        @if($errors->first('precioUltimo'))
                        <i> {{ $errors->first('precioUltimo') }}</i>
                        @endif
                        {{Form::text('precioUltimo',old ('precioUltimo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                    </div>
                </div>
                <br>
                <center>
                    {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </div>
        </div>
    </div>
</div>
@stop