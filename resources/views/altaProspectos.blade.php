@extends('principal')
@section('contenido')


{{Form::open(['route' => 'guardarProspectos','files'=>true])}}
{{Form::open(['route' => 'editarSucursales','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de investigación de prospectos</h1>
        </div>
        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos</div>
            </div>
            <div class="row" style="">
                <div class="col-md-6">
                    <div class="sub-title">Fecha de alta:</div>
                    <div>
                        @if($errors->first('fechaAlta'))
                        <i> {{ $errors->first('fechaAlta') }}</i>
                        @endif
                        {{Form::date('fechaAlta',old ('fechaAlta'),['class' => 'form-control'])}}
                    </div>

                    <div class="sub-title">Empresa que da seguimiento:</div>
                    <div>
                        GCM <input type="radio" name="empresaSeguimiento" value="GCM" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        CYM <input type="radio" name="empresaSeguimiento" value="CYM" />
                    </div>

                    <div class="sub-title">Registrado por:</div>
                    <div>
                        <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                        <input type="text" name="registradoPor" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                                    Session::get('sesionmaterno')!!}">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sub-title">Cliente:</div>
                            @if($errors->first('idc'))
                            <i> {{ $errors->first('idc') }}</i>
                            @endif<div>
                                <select name='idc' id='idc' class="form-control">
                                    <option value="">Seleccionar empresa</option>
                                    @foreach($cliente as $cliente)
                                    @if($cliente->activo=="si")
                                    @if($cliente->tipoCliente=="activo" || $cliente->tipoCliente=="prospecto")
                                    <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                    @endif
                                    @endif
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                        
                            <div class="sub-title">Sucursal:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            @if($errors->first('idSucursal'))
                            <i> {{ $errors->first('idSucursal') }}</i>
                            @endif
                            <div id='combop'>
                                <select name='idSucursal' id='idSucursal' class="form-control">
                                    <option value="">Seleccionar sucursal</option>
                                </select>
                            </div>
                            <div id="idmodal">
                                <!-- <input type="text" value = ' Hola'> -->
                                
                                <i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter"></i>
                            </div>
                           
                            
                        </div>
                        
                    </div>
                    <div class="row">
                    <div class="col-md-11">
                        <div id='divinfo' class="alert alert-info"></div>
                        </div>
                    </div>

                    <!-- <div id="edicionSucursal"></div> -->
                    <div>
                    
                        <!-- <form action="">
                        <div class="sub-title">*Descripción de servicios a ofrecer:</div>
                    <div class="row"> 
                        <div class="col-lg-5">
                            {{Form::checkbox('servicios[]', 'Venta de refacciones',false,['id'=>'check1'])}} Venta de refacciones <br>
                            {{Form::checkbox('servicios[]', 'Refurbished',false,['id'=>'check2'])}} Refurbished<br>
                            {{Form::checkbox('servicios[]', 'Soporte técnico',false,['id'=>'check3'])}} Soporte técnico<br>
                            {{Form::checkbox('servicios[]', 'Servicios de cambio',false,['id'=>'check4'])}} Servicios de cambio    
                        </div>
                        <div class="col-lg-7">
                            {{Form::checkbox('servicios[]', 'Reparaciones',false,['id'=>'check5'])}} Reparaciones<br>
                            {{Form::checkbox('servicios[]', 'Diseño y Fabricación',false,['id'=>'check6'])}} Diseño y Fabricación<br>
                            {{Form::checkbox('servicios[]', 'Mantenimiento Preventivo',false,['id'=>'check7'])}} Mantenimiento Preventivo<br> 
                            {{Form::checkbox('servicios[]', 'Mantenimiento Correctivo',false,['id'=>'check8'])}} Mantenimiento Correctivo<br>
                        </div>
                    </div>

                    <div class="sub-title">*Marcas:</div>
                    <div class="row"> 
                        <div class="col-lg-5">
                            {{Form::checkbox('marcas[]', 'Marca 1',false,['id'=>'marca1'])}} Marca 1<br>
                            {{Form::checkbox('marcas[]', 'Marca 2',false,['id'=>'marca2'])}} Marca 2<br>
                            {{Form::checkbox('marcas[]', 'Marca 3',false,['id'=>'marca3'])}} Marca 3
                        </div>
                        <div class="col-lg-6">
                            {{Form::checkbox('marcas[]', 'Marca 4',false,['id'=>'marca4'])}} Marca 4<br>
                            {{Form::checkbox('marcas[]', 'Marca 5',false,['id'=>'marca5'])}} Marca 5<br>
                            {{Form::checkbox('marcas[]', 'Marca 6',false,['id'=>'marca6'])}} Marca 6
                        </div>
                    </div>
                </div>
                </div> -->
                        </form>    
                    </div>

                    <div class="sub-title">Descripción de actividad:</div>
                    <div>
                        {{Form::textarea('descripcionActividad',old ('descripcionActividad'),['class' => 'form-control'])}}
                    </div><br>
                </div>
            </div>
            <div>
            <center>
                {{Form::submit('Guardar',['class' => 'btn  btn-default', 'id'=>'guardar'])}}
            </center>
        </div>
    </div>
</div>




<script>
$(document).ready(function() {
    $("#divinfo").hide(1500);
    $("#idc").change(function() {
        $("#idSucursal").load('{{url('comboSucursalesActivo')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
        // alert("sucursales");
    });

    $("#idSucursal").change(function() {
        $("#idmodal").load('{{url('obtenerId')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
        // alert("sucursales");
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
    $("#idSucursal").change(function() {
    $("#divinfo").load('{{url('info')}}' + '?' + $(this).closest('form').serialize());
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