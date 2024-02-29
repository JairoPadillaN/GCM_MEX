@extends('principalProv')
@section('contenidoProv')


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-body">
        
            {{Form::open(['route' => 'guardarCuentasProveedor','files'=>true])}}
            {{Form::token()}}

            <input type='hidden' name='idProveedor' value="{{$sidprov}}">
    
            <div class="row">
                <div class="form-group col-md-6">                    
                    <h3>Agregar archivos</h3>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <div class="sub-title">Cuenta bancaria:</div>
                    <div>
                        <center>
                            <div style='text-align'>                                
                                @if($consultaArchivosProveedor->archivoCuentas=='Sin archivo' || $consultaArchivosProveedor->archivoCuentas=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo cuentas bancarias</h6>
                                @else
                                    <a target="_blank" href="{{asset('archivos/'.$consultaArchivosProveedor->archivoCuentas)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar archivo cuentas bancarias</h6>
                                    </a>
                                @endif                                
                            </div>
                        </center>
                    </div>
                    <div>
                        {{Form::file('cuentaBancaria1',['class' => 'form-control rounded-0','id'=>'cuentaBancaria1','onchange'=>'ValidarImagen(this);'])}}
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="sub-title">Constancia situación fiscal:</div>
                    <div>
                        <center>
                            <div style='text-align'>                                
                                @if($consultaArchivosProveedor->constanciaFiscal=='Sin archivo' || $consultaArchivosProveedor->constanciaFiscal=='')
                                    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
                                    <h6>Sin archivo constancia fiscal</h6>
                                @else
                                    <a target="_blank" href="{{asset('archivos/'.$consultaArchivosProveedor->constanciaFiscal)}}">
                                        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
                                        <h6>Descargar archivo constancia fiscal</h6>
                                    </a>
                                @endif                                
                            </div>
                        </center>
                    </div>
                    <div>
                        {{Form::file('constanciaFiscal',['class' => 'form-control rounded-0','id'=>'constanciaFiscal','onchange'=>'ValidarImagen(this);'])}}
                    </div>
                </div>
            </div>

            <center>{{Form::submit('Guardar',['class' => 'btn  btn-success'])}}</center>
            <br>
        </div>
    </div>
</div>

<script>
    function ValidarImagen(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivoCotizacion").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivoCotizacion').val('');
        }
    }
</script>

@stop