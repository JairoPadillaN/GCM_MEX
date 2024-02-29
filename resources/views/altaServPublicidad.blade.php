@extends('principal')
@section('contenido')

{{Form::open(['route' => 'guardarServPublicidad','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de publicidad<small> *Campos obligatorios</small></h1>
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

                <div class="sub-title">*Empresa que da seguimiento:</div>
                @if($errors->first('empresaSeguimiento'))
                <i> {{ $errors->first('empresaSeguimiento') }}</i>
                @endif
                <div>
                    CYM {{Form::radio('empresaSeguimiento','CYM')}}
                    GCM {{Form::radio('empresaSeguimiento','GCM')}}
                </div>

                <div class="sub-title">Registro por: </div>
                <div>
                    <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                    <input type="text" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
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
                
                <div class="sub-title">Contacto:</div>
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
            <div id='divinfo' class="alert alert-info"></div>

            </div>

            <!-- Columna dos -->
            <div class="form-group col-md-6">
                <div class='sub-title'>*Tipo de publicidad: </div>
                <div class='row'>
                    <div class="form-group col-md-6">
                        <div>{{Form::checkbox('tipoPublicidad[]', 'Folletos Husky',false,['id'=>'check1'])}} Folletos Husky</div>
                        <div>{{Form::checkbox('tipoPublicidad[]', 'Servo sistemas',false,['id'=>'check2'])}} Servo sistemas</div>
                    </div>

                    <div class="form-group col-md-6">
                        <div>{{Form::checkbox('tipoPublicidad[]', 'Hidraulica movil',false,['id'=>'check3'])}} Hidraulica movil</div>
                        <div>{{Form::checkbox('tipoPublicidad[]', 'Otro',false,['id'=>'check4'])}} Otro</div>
                    </div>
                </div>

                <div class="sub-title">Descripción de actividad:</div>
                <div>
                    @if($errors->first('descripcionActividad'))
                    <i> {{ $errors->first('descripcionActividad') }}</i>
                    @endif
                    <textarea name="descripcionActividad" rows="4" class="form-control rounded-0"
                        placeholder="Agrega una descripción">{!! old('descripcionActividad') !!}</textarea>
                </div>

                <div class="sub-title">Contestó:</div>
                <div>
                    @if($errors->first('contesto'))
                    <i> {{ $errors->first('contesto') }}</i>
                    @endif
                    <textarea name="contesto" rows="4" class="form-control rounded-0"
                        placeholder="Ejemplo: contesto el Ing. Mauro">{!! old('contesto') !!}</textarea>
                </div>

                <div class="sub-title">*Estatus:</div>
                @if($errors->first('estatus'))
                <i> {{ $errors->first('estatus') }}</i>
                @endif
                <select name='estatus' class="form-control">
                    <option value="">Seleccionar estatus</option>
                    <option value="Pendiente de respuesta">Pendiente de respuesta</option>
                    <option value="Atendió Publicidad">Atendió Publicidad</option>
                </select>

            </div>
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-default','id'=>'guardar'])}}
        </center><br>
    </div>
</div>


<script type="text/javascript">
    $("#guardar").click(function(e) {
        var check1 = document.getElementById("check1");
        var check2 = document.getElementById("check2");
        var check3 = document.getElementById("check3");
        var check4 = document.getElementById("check4");
        if($("[type='checkbox']:checked").val()==undefined){
            alert ("El campo *Tipo de publicidad obligatorio.");
            e.preventDefault();
        }
    });

    $("#idc").change(function() {
        $("#idSucursal").load('{{url('comboca')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    });
    $("#idSucursal").change(function() {
        $("#ido").load('{{url('comboCon')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
    });

    $("#idSucursal").change(function() {
         $("#idmodal").load('{{url('obtenerId')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
       //  alert("sucursales");
    });
    $(document).ready(function() {
    $("#divinfo").hide(1500);
    
});
    $("#ido").change(function() {
    $("#divinfo").load('{{url('infoCita')}}' + '?' + $(this).closest('form').serialize());
    $("#divinfo").show("slow");
    // alert("mensaje correcto")
});

    
    $("#botonModal").click(function() {
        var comboCliente = $("#idc").val();
        var comboSucursal = $("#idSucursal").val();

        if (comboCliente == "" || comboSucursal ==""){

            if (comboCliente == ""){
                alert ("Debes seleccionar un cliente")
            }
            if (comboSucursal == ""){
                alert ("Debes seleccionar una sucursal")
                }
        }        
    });
</script>
<style>
.fa-chevron-circle-right{
    color: blue;
    font-size: 30px;
}
</style>
@stop