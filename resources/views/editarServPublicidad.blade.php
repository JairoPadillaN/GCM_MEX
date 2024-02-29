@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarServPublicidad','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Editar Registro de publicidad<small> *Campos obligatorios</small></h1>
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">

                <div class="sub-title">Fecha de alta:</div>
                <div>
                    @if($errors->first('fechaAlta'))
                    <i>{{ $errors->first('fechaAlta') }}</<i>
                        @endif
                        {{Form::hidden('idServPublicidad',$consulta->idServPublicidad,['class' => 'form-control'])}}
                        {{Form::date('fechaAlta',$consulta->fechaAlta,['class' => 'form-control'])}}
                </div>

                <div class="sub-title">*Empresa que da seguimiento:</div>
                @if($errors->first('empresaSeguimiento'))
                <i> {{ $errors->first('empresaSeguimiento') }}</i>
                @endif
                <div>
                    @if ($consulta->empresaSeguimiento == 'gcm')
                        GCM {{Form::radio('empresaSeguimiento','gcm', true, ['checked' => 'checked']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        C&M {{Form::radio('empresaSeguimiento','cym',false)}}
                    @else
                        GCM {{Form::radio('empresaSeguimiento','gcm',false)}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        C&M {{Form::radio('empresaSeguimiento','cym', true, ['checked' => 'checked', 'id'=>'nombreEmpresa1']) }}
                    @endif                
                </div>

                <div class="sub-title">Registro por: </div>
                <div>
                    <input type='hidden' name='idu' id='idu' value="{{$consulta->idu}}">
                    <input type="text" class="form-control" readonly="true" value="{{$consulta->us}}">
                </div>

                <div class="sub-title">* Cliente:</div>
                    @if($errors->first('idc'))
                        <i> {{ $errors->first('idc') }}</i>
                    @endif<div>
                    <select name='idc' id='idc' class="form-control" class="form-control rounded-0">
                        <option value='{{$idclienteConsulta}}'>{{$nombreCliente}}</option>
                            @foreach($clientesVariosYpersonal as $variosYpersonal)
                                @if($variosYpersonal->idc== 302 || $variosYpersonal->idc== 303)
                                    <option value='{{$variosYpersonal->idc}}'>{{$variosYpersonal->razonSocial}}</option>
                                @endif
                            @endforeach                            
                            @foreach($losOtrosclientesOrden as $clientesOrden)
                                @if($clientesOrden->activo=="si")
                                    <option value='{{$clientesOrden->idc}}'>{{$clientesOrden->razonSocial}}</option>
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
                            <option value='{{$idSucursalConsulta}}'>{{$nombreSucursal}}</option>
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
                        <option value='{{$nombreContacto}}'>{{$nombreContacto}}</option>
                        </select>
                    </div>
                    <div id="idmodal">
                        <!-- <button type="button" class="btn btn-xs btn-info" style="width: 30px; height: 30px; border-radius: 50%;" > -->
                        <i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter"></i>
                        <!-- </button> -->
                    </div> 
                    
                </div>      
                <div id='divinfo' class="alert alert-info">
                            Cliente: {{$nombreCliente}} ------- Sucursal: {{$nombreSucursal}} ------- Contacto: {{$nombreContacto}}
                </div>
                
            </div>
            
            
            <div class="form-group col-md-6">
                <div class='sub-title'>*Tipo de publicidad: </div>
                <div class='row'>
                    <div class="form-group col-md-6">
                        @if(in_array('Folletos Husky',$arrayCheckTipoPublicidad))
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Folletos Husky',true,['id'=>'check1'])}} Folletos Husky</div>
                        @else
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Folletos Husky',false,['id'=>'check1'])}} Folletos Husky</div>
                        @endif
                        
                        @if(in_array('Servo sistemas',$arrayCheckTipoPublicidad))
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Servo sistemas',true,['id'=>'check2'])}} Servo sistemas</div>
                        @else
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Servo sistemas',false,['id'=>'check2'])}} Servo sistemas</div>
                        @endif
                    </div>

                    <div class="form-group col-md-6">
                        @if(in_array('Hidraulica movil',$arrayCheckTipoPublicidad))
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Hidraulica movil',true,['id'=>'check3'])}} Hidraulica movil</div>
                        @else
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Hidraulica movil',false,['id'=>'check3'])}} Hidraulica movil</div>
                        @endif
                
                        @if(in_array('Otro',$arrayCheckTipoPublicidad))
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Otro',true,['id'=>'check4'])}} Otro</div>
                        @else
                            <div>{{Form::checkbox('tipoPublicidad[]', 'Otro',false,['id'=>'check4'])}} Otro</div>
                        @endif
                    </div>
                </div>

                <div class="sub-title">Descripción de actividad:</div>
                <div>
                    @if($errors->first('descripcionActividad'))
                    <i> {{ $errors->first('descripcionActividad') }}</i>
                    @endif
                    <textarea name="descripcionActividad" rows="4" class="form-control rounded-0"
                        placeholder="Agrega una descripción">{{$consulta->descripcionActividad}}</textarea>
                </div>

                <div class="sub-title">Contestó:</div>
                <div>
                    @if($errors->first('contesto'))
                    <i> {{ $errors->first('contesto') }}</i>
                    @endif
                    <textarea name="contesto" rows="4" class="form-control rounded-0"
                        placeholder="Ejemplo: contesto el Ing. Mauro">{{$consulta->contesto}}</textarea>
                </div>

                <div class="sub-title">*Estatus:</div>
                @if($errors->first('estatus'))
                <i> {{ $errors->first('estatus') }}</i>
                @endif
                <select name='estatus' class="form-control">
                    <option value="{{$consulta->estatus}}">{{$consulta->estatus}}</option>
                    <option value="Pendiente de respuesta">Pendiente de respuesta</option>
                    <option value="Atendió Publicidad">Atendió Publicidad</option>
                </select>

            </div>
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-default','id'=>'guardar'])}}
            <a href="{{asset('reporteServPublicidad')}}"><button type="button" class="btn btn-default">Cancelar</button></a>
        </center><br>
    </div>
</div>



<script type="text/javascript">
$(document).ready(function() {

    $("#guardar").click(function(e) {
        var check1 = document.getElementById("check1");
        var check2 = document.getElementById("check2");
        var check3 = document.getElementById("check3");
        var check4 = document.getElementById("check4");

        if (check1.checked == false && check2.checked == false && check3.checked == false && check4.checked == false) {
            alert ("El campo *Tipo de publicidad obligatorio.");
            e.preventDefault();
        }
    });

    $("#idc").change(function() {
        $("#idSucursal").load('{{url('comboca')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    });
    $("#idSucursal").change(function() {
        $("#ido").load('{{url('comboCon')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
        $("#idmodal").load('{{url('obtenerId')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
    });
    $("#idmodal").load('{{url('obtenerId')}}' + '?r=' + Date.now() + '&idSucursal=' + $( "#idSucursal" ).val());
});
$("#ido").change(function() {
    $("#divinfo").load('{{url('infoCita')}}' + '?' + $(this).closest('form').serialize());
    $("#divinfo").show("slow");
    // alert("mensaje correcto")
});
</script>
<style>
.fa-chevron-circle-right{
    color: blue;
    font-size: 30px;
}
</style>
@stop