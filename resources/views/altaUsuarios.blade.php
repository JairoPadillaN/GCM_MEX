@extends('principal')
@section('contenido')

{{Form::open(['route' => 'GuardarUsuarios','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Alta de Usuarios <small>Registre nuevos usuarios</small></h1><br><br>

            <div class="card-title">
                <div class="title">Datos personales</div>
            </div>
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">
                <div class="sub-title">Nombre:</div>
                <div>
                    @if($errors->first('nombreUsuario'))
                    <i> {{ $errors->first('nombreUsuario') }}</i>
                    @endif
                    {{Form::text('nombreUsuario',old ('nombreUsuario'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis ó Juan José'])}}
                </div>


                <div class="sub-title">Apellido Paterno:</div>
                <div>
                    @if($errors->first('aPaterno'))
                    <i> {{ $errors->first('aPaterno') }}</i>
                    @endif
                    {{Form::text('aPaterno',old ('aPaterno'),['class' => 'form-control', 'placeholder' => 'Ejemplo: López'])}}
                </div>

                <div class="sub-title">Apellido Materno:</div>
                <div>
                    @if($errors->first('aMaterno'))
                    <i> {{ $errors->first('aMaterno') }}</i>
                    @endif
                    {{Form::text('aMaterno',old ('aMaterno'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Rosas'])}}
                </div>
            </div>

            <div class="form-group col-md-6">
                <div class="sub-title">Correo electrónico:</div>
                <div>
                    @if($errors->first('correo'))
                    <i> {{ $errors->first('correo') }}</i>
                    @endif
                    {{Form::email('correo',old ('correo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                </div>

                <div class="sub-title">Contraseña:</div>
                <div>
                    @if($errors->first('password'))
                    <i> {{ $errors->first('password') }}</i>
                    @endif
                    {{Form::password('password',old ('password'),['class' => 'form-control rounded-0'])}}
                </div>

                <div class="sub-title">Foto de perfil:</div>
                <div>
                    @if($errors->first('imagen'))
                    <i> {{ $errors->first('imagen') }}</i>
                    @endif
                    {{Form::file('imagen',['class' => 'form-control rounded-0'])}}
                </div>

                <div class="sub-title">Tipo:</div>
                <div>
                    @if($errors->first('tipo'))
                    <i> {{ $errors->first('tipo') }}</i>
                    @endif
                    <select name="tipo" class="form-control rounded-0">
                        <option selected="selected" value="">Seleccione el tipo de empleado</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Chofer">Chofer</option>
                        <option value="Tecnico">Técnico</option>
                        <option value="Vendedor">Vendedor</option>
                        <option value="Compras">Compras</option>
                        <option value="Almacen">Almacén</option>
                        <option value="Contador">Contador</option>
                    </select>
                </div>

                <div class="sub-title">Empresa a la que dará seguimiento:</div>
                    @if($errors->first('empresaSeguimiento'))
                    <i> {{ $errors->first('empresaSeguimiento') }}</i>
                    @endif  
                <div>                              
                    GCM {{Form::radio('empresaSeguimiento','GCM')}}&nbsp;&nbsp;&nbsp;&nbsp;
                    CYM {{Form::radio('empresaSeguimiento','CYM')}}&nbsp;&nbsp;&nbsp;&nbsp;
                    GCM y CYM {{Form::radio('empresaSeguimiento','GCMyCYM')}}&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="card-title">
                        <div class="title">Dirección</div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group col-md-6">
                        <div class="sub-title">Calle:</div>
                        <div>
                            @if($errors->first('calle'))
                            <i> {{ $errors->first('calle') }}</i>
                            @endif
                            {{Form::text('calle',old ('calle'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Hacienda San Diego ' ])}}
                        </div>

                        <div class="sub-title">Número:</div>
                        <div>
                            @if($errors->first('num'))
                            <i> {{ $errors->first('num') }}</i>
                            @endif
                            {{Form::text('num',old ('num'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 127'])}}
                        </div>

                        <div class="sub-title">Colonia:</div>
                        <div>
                            @if($errors->first('colonia'))
                            <i> {{ $errors->first('colonia') }}</i>
                            @endif
                            {{Form::text('colonia',old ('colonia'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Lázaro Cárdenas'])}}
                        </div>

                        <div class="sub-title">Municipio:</div>
                        <div>
                            @if($errors->first('municipio'))
                            <i> {{ $errors->first('municipio') }}</i>
                            @endif
                            {{Form::text('municipio',old ('municipio'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Lerma'])}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="sub-title">Estado:</div>
                        <div>
                            @if($errors->first('estado'))
                            <i> {{ $errors->first('estado') }}</i>
                            @endif
                            {{Form::text('estado',old ('estado'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Estado de México'])}}
                        </div>

                        <div class="sub-title">Teléfono de Casa:</div>
                        <div>
                            @if($errors->first('telCasa'))
                            <i> {{ $errors->first('telCasa') }}</i>
                            @endif
                            {{Form::text('telCasa',old ('telCasa'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 7282801927'])}}
                        </div>

                        <div class="sub-title">Teléfono Celular:</div>
                        <div>
                            @if($errors->first('telCel'))
                            <i> {{ $errors->first('telCel') }}</i>
                            @endif
                            {{Form::text('telCel',old ('telCel'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5501928384'])}}
                        </div>

                        <div class="sub-title">Licencia:</div>
                        <div>
                            @if($errors->first('licencia'))
                            <i> {{ $errors->first('licencia') }}</i>
                            @endif
                            {{Form::text('licencia',old ('licencia'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 480000000000'])}}
                        </div>
                    </div>

                    <br>
                    <center>
                        {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop