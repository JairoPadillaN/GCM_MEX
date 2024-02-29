@extends('principal')
@section('contenido')

{{Form::open(['route' => 'GuardarCitas','files'=>true,'id' => 'formid'])}}
{{Form::token()}}


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Gestión de citas / visitas técnicas</h1><br><br>
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">

                <div class="sub-title">Fecha de alta:</div>
                <div>
                    @if($errors->first('fechaAlta'))
                    <i>{{ $errors->first('fechaAlta') }}</<i>
                        @endif
                        {{Form::date('fechaAlta', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Empresa que da seguimiento:</div>
                @if($errors->first('empresaSeguimiento'))
                <i> {{ $errors->first('empresaSeguimiento') }}</i>
                @endif
                <div>
                    C&M {{Form::radio('empresaSeguimiento','C&M')}}
                    GCM {{Form::radio('empresaSeguimiento','GCM', true)}}
                </div>

                <div class="sub-title">Registro por: </div>
                <div>
                    <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                    <input type="text" name="registradoPor" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                            Session::get('sesionmaterno')!!}">
                </div>
                
               

                <div class="sub-title">* Cliente:</div>
                @if($errors->first('idc'))
                <i> {{ $errors->first('idc') }}</i>
                @endif<div>
                    <select name='idc' id='idc' class="form-control">
                        <option value="">Seleccionar empresa</option>
                        @foreach($cliente as $cliente)
                        @if($cliente->activo=="si")
                        
                        <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                        
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="sub-title">* Sucursal:</div>
                @if($errors->first('idSucursal'))
                <i> {{ $errors->first('idSucursal') }}</i>
                    @endif<div>
                        <div id='combop'>
                            <select name='idSucursal' id='idSucursal' class="form-control">
                                <option value="">Seleccionar sucursal</option>
                            </select>
                        </div>
                    </div>

                <div class="sub-title">* Contacto:</div>
                @if($errors->first('contacto'))
                <i> {{ $errors->first('contacto') }}</i>
                @endif
                <div class="row">
                    <div id='prueba' class="col-md-11">
                        <select name='contacto' id='ido' class="form-control">
                            <option value="">Seleccionar contacto</option>
                        </select>
                    </div>
                    <div id="idmodal">
                            <!-- <button type="button" class="btn btn-xs btn-info" style="width: 30px; height: 30px; border-radius: 50%;" > -->
                            <i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter"></i>
                            <!-- </button> -->
                    </div> 
                </div>
                <div id="cargar"></div>
                <div id='divinfo' class="alert alert-info"></div>

                </div>
                <!-- segunda columna -->
                <div class="form-group col-md-6">
                <div class="sub-title">Fecha de cita:</div>
                <div>
                    @if($errors->first('fechaCita'))
                    <i>{{ $errors->first('fechaCita') }}</<i>
                        @endif
                        {{Form::date('fechaCita', \Carbon\Carbon::now(),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Hora: </div>
                <div>
                    @if($errors->first('hora'))
                    <i> {{ $errors->first('hora') }}</i>
                    @endif
                    {{Form::time('hora',old ('hora'),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Lugar cita </div>
                <div>
                    @if($errors->first('lugar'))
                    <i> {{ $errors->first('lugar') }}</i>
                    @endif
                    {{Form::text('lugar',old ('lugar'),['class' => 'form-control','placeholder' => 'Ejemplo: NAVE 3'])}}
                </div>

                <div class="sub-title">* Responsable atender cita:</div>
                <div>
                    @if($errors->first('responsable'))
                    <i> {{ $errors->first('responsable') }}</i>
                    @endif
                    <select name='responsable' id='idResponsable' class="form-control">
                        <option value="">Seleccionar responsable</option>
                        @foreach($usuario as $usuario)
                        @if($usuario->activo=="Si")
                        <option value='{{$usuario->idu}}'>{{$usuario->nombreUsuario}} {{$usuario->aPaterno}} {{$usuario->aMaterno}}</option>
                        @endif
                        @endforeach
                    </select>
                    
                </div>
                
                <div class="sub-title">Observaciones previa cita:</div>
                <div>
                    @if($errors->first('observacionCita'))
                    <i> {{ $errors->first('observacionCita') }}</i>
                    @endif
                    <textarea name="observacionCita" rows="5" 
                    class="form-control rounded-0" placeholder="Escribe una aclaración previa a la cita.">{!! old('observacionCita') !!}</textarea>
                </div>
                        
            </div>

            
            </div> 
            <center>
                {{Form::submit('Guardar',['class' => 'btn  btn-success'])}}
            </center><br>
        </div>
    </div>
    

<script type="text/javascript">
 $(document).ready(function() {
    $("#idc").change(function() {
        $("#idSucursal").load('{{url('comboca')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
        // alert("mensaje correcto")
    });
    
    $("#idSucursal").change(function() {
        $("#ido").load('{{url('comboCon')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
        // alert("mensaje correcto")
    });

    $("#idSucursal").change(function() {
         $("#idmodal").load('{{url('obtenerId')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
       //  alert("sucursales");
    });
    
    $("#botonModal").click(function() {
        var comboCliente = $("#idc").val();
        var comboSucursal = $("#idSucursal").val();
        if (comboCliente == "" || comboSucursal ==""){

            if (comboCliente == ""){
                alert ("Debe seleccionar un cliente")
            }
            if (comboSucursal == ""){
                alert ("Debe seleccionar una sucursal")
                }
        }
        
    });
    $(document).ready(function() {
    $("#divinfo").hide(1500);
    
});
    $("#ido").change(function() {
    $("#divinfo").load('{{url('infoCita')}}' + '?' + $(this).closest('form').serialize());
    $("#divinfo").show("slow");
    // alert("mensaje correcto")
});

        
    });
</script>
<style>
.fa-chevron-circle-right{
    color: blue;
    font-size: 30px;
}
</style>
@stop