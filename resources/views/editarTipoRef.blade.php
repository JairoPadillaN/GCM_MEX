@extends('principal')
@section('contenido')



{{Form::open(['route' => 'editarTipoRef','files'=>true])}}
{{Form::token()}}


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">

        <div class="panel-heading">
            <h1>Modificación de tipos de refacción</h1>
        </div>

        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos del taller</div>
            </div>
            <div class="form-group col-md-6">
                <div class="sub-title">Marca:</div>
                    <div>
                        @if($errors->first('marca'))
                        <i> {{ $errors->first('marca') }}</i>
                        @endif
                        <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                            <option value="{{$idMarcaSel}}">{{$marca}}</option>
                            @foreach($marcaNoSel as $noSel)
                                <option value="{{$noSel->idMarcaRefa}}">{{$noSel->marcaRefaccion}}</option>
                            @endforeach
                        </select>
                    </div>
                <div class="sub-title">Nombre del tipo de refacción:</div>
                <div>
                    {{Form::hidden('idTipoRefacciones',$consulta->idTipoRefacciones)}}

                    @if($errors->first('nombreTipoRefaccion'))
                    <p><i> {{ $errors->first('nombreTipoRefaccion') }}</i></p>
                    @endif
                    {{Form::text('nombreTipoRefaccion',$consulta->nombreTipoRefaccion,['class' => 'form-control', 'placeholder' => 'Ejemplo: Display'])}}
                </div>
                <div class="sub-title">Código de refacción:</div>
                <div>
                    @if($errors->first('codigoTipoRef'))
                    <p><i> {{ $errors->first('codigoTipoRef') }}</i></p>
                    @endif
                    {{Form::text('codigoTipoRef',$consulta->codigoTipoRef,['class' => 'form-control', 'placeholder' => 'Ejemplo: PN'])}}
                </div>
            </div>
            
            
        </div>
        <center>
                {{Form::submit('Guardar',['class' => 'btn  btn-success'])}}
                <a href="{{asset('reporteTipoRef')}}"><button type="button"
                        class="btn btn-default">Cancelar</button></a>
                        </center><br>
    </div>
    @stop