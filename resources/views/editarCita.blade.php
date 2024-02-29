@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarCitas','files'=>true,'id' => 'formid'])}}
{{Form::token()}}
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificar citas / visitas técnicas</h1><br><br>
            <div class="card-title">
                <div class="title">Citas creadas</div>
            </div>
        </div>


        <div class="panel-body">
            <div class="form-group col-md-6">
            
                <div class="sub-title">Fecha de alta:</div>
                @if($errors->first('fechaAlta'))
                <i> {{ $errors->first('fechaAlta') }}</i>
                @endif
                <div>
                    {{Form::date('fechaAlta',($consulta->fechaAlta),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Empresa que da seguimiento:</div>
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
                                    
                                    {{Form::hidden('idu',$consulta->idu)}}
                                    <input type="text" name="registradoPor" class="form-control" readonly="true" value="{{$consulta->registradoPor}}">
                                </div>
                <div>
                
                        {{Form::hidden('idCita',$consulta->idCita)}}
                    
                </div>


                <div class="sub-title">* Cliente:</div>
                @if($errors->first('idc'))
                <i> {{ $errors->first('idc') }}</i>
                @endif<div>
                    <select name='idc' id='idc' class="form-control" >
                        <option value="{{$idClienteSel}}">{{$nombreCliente}}</option>
                        @foreach($clienteOrden as $cliente)
                        
                        <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                        
                        @endforeach

                    </select>
                    
                </div>

                <div class="sub-title">* Sucursal:</div>
                @if($errors->first('idSucursal'))
                <i> {{ $errors->first('idSucursal') }}</i>
                @endif
                <div>
                    <select name='idSucursal' id='idSucursal' class="form-control">
                        <option value="{{$idSucursalSel}}">{{$nombreSucursal}}</option>
                    </select>
                    
                </div>

                
                <div class="sub-title">Contacto:</div>
                @if($errors->first('contacto'))
                <i> {{ $errors->first('contacto') }}</i>
                @endif
                <div class="row">
                    <div id='prueba' class="col-md-11">
                        <select name='contacto' id='ido' class="form-control">
                            <option value="{{$consulta->contacto}}">{{$nombreContacto}}</option>
                        </select>
                    </div>
                    <div id="idmodal">
                            <!-- <button type="button" class="btn btn-xs btn-info" style="width: 30px; height: 30px; border-radius: 50%;" > -->
                            <i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter"></i>
                            <!-- </button> -->
                    </div> 
                </div>
                <div id="cargar"></div>
                <div id='divinfo' class="alert alert-info">
                            Cliente: {{$nombreCliente}} ------- Sucursal: {{$nombreSucursal}} ------- Contacto: {{$nombreContacto}}
                </div>
                <div class="sub-title">Fecha de cita:</div>
                <div>
                @if($errors->first('fechaCita'))
                    <i>{{ $errors->first('fechaCita') }}</<i>
                        @endif
                    {{Form::date('fechaCita',($consulta->fechaCita),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Hora: </div>
                <div>
                @if($errors->first('hora'))
                    <i> {{ $errors->first('hora') }}</i>
                    @endif
                    {{Form::time('hora',($consulta->hora),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Lugar cita </div>
                <div>
                @if($errors->first('lugar'))
                    <i> {{ $errors->first('lugar') }}</i>
                    @endif
                    {{Form::text('lugar',($consulta->lugar),['class' => 'form-control'])}}
                </div>


                


                <div class="sub-title">* Responsable atender cita:</div>
                @if($errors->first('responsable'))
                    <i> {{ $errors->first('responsable') }}</i>
                    @endif<div>
                    <select name='responsable' class="form-control">
                        <option value="{{$idUsuarioSel}}">{{$nombreUsuario}} {{$paterno}} {{$materno}} </option>
                        @foreach($usuarioOrden as $usuario)

                        <option value='{{$usuario->idu}}'>{{$usuario->nombreUsuario}} {{$usuario->aPaterno}} {{$usuario->aMaterno}}</option>
                        @endforeach
                    </select>
                    
                    
                </div>


                <div class="sub-title">Observaciones previa cita:</div>
                <div>
                @if($errors->first('observacionCita'))
                    <i> {{ $errors->first('observacionCita') }}</i>
                    @endif
                    {{Form::textarea('observacionCita',($consulta->observacionCita),['class' => 'form-control'])}}
                </div>

            </div>

            <!-- Columna dos -->
            <div class="form-group col-md-6">
                <div class="sub-title">Fecha real de visita:</div>
                <div>
                @if($errors->first('fechaReal'))
                    <i>{{ $errors->first('fechaReal') }}</<i>
                        @endif
                    {{Form::date('fechaReal',($consulta->fechaReal),['class' => 'form-control','readonly' => 'true'])}}
                </div>

                <div class="sub-title">Resultados cita:</div>
                <div>
                @if($errors->first('resultados'))
                    <i> {{ $errors->first('resultados') }}</i>
                    @endif
                    {{Form::textarea('resultados',($consulta->resultados),['class' => 'form-control','readonly' => 'true'])}}
                </div>

                <!-- <div class='sub-title'>Servicios a ofrecer: </div>
                <div class='row'>
                    @if($errors->first('serviciosOfrecer'))
                    <i> {{ $errors->first('serviciosOfrecer') }}</i>
                    @endif
                    <div class="form-group col-md-6">
                    @if(in_array('ventaRefacciones',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'ventaRefacciones',true, array('disabled'))}} Venta de refacciones</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'ventaRefacciones', false, array('disabled'))}} Venta de refacciones</div>
                    @endif

                    @if(in_array('REfurbished',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'REfurbished',true, array('disabled'))}} REfurbished</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'REfurbished', false, array('disabled'))}} REfurbished</div>
                    @endif

                    @if(in_array('soporteTecnico',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'soporteTecnico',true, array('disabled'))}} Soporte técnico</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'soporteTecnico', false, array('disabled'))}} Soporte técnico</div>
                    @endif

                    </div>

                    <div class="form-group col-md-6">
                    @if(in_array('reparaciones',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'reparaciones',true, array('disabled'))}} Reparaciones</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'reparaciones', false, array('disabled'))}} Reparaciones</div>
                    @endif

                    @if(in_array('diseñoFabricacion',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'diseñoFabricacion',true, array('disabled'))}} Diseño y fabricación</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'diseñoFabricacion', false, array('disabled'))}} Diseño y fabricación</div>
                    @endif

                    @if(in_array('manttoPreventivo',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'manttoPreventivo',true, array('disabled'))}}  Mantenimiento preventivo</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'manttoPreventivo', false, array('disabled'))}}  Mantenimiento preventivo</div>
                    @endif                
                    </div>
                </div>

                <div class='sub-title'>Marcas: </div>
                <div class='row'>
                   
                    <div class="form-group col-md-6">
                    @if(in_array('marca1',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca1',true, array('disabled'))}} Marca 1</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca1',false, array('disabled'))}} Marca 1</div>
                    @endif

                    @if(in_array('marca2',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca2',true, array('disabled'))}} Marca 2</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca2',false, array('disabled'))}} Marca 2</div>
                    @endif

                    @if(in_array('marca3',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca3',true, array('disabled'))}} Marca 3</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca3',false, array('disabled'))}} Marca 3</div>
                    @endif
                    </div>

                    <div class="form-group col-md-6">
                    @if(in_array('marca4',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca4',true,array('disabled'))}} Marca 4</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca4',false,array('disabled'))}} Marca 4</div>
                    @endif

                    @if(in_array('marca5',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca5',true,array('disabled'))}} Marca 5</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca5',false,array('disabled'))}} Marca 5</div>
                    @endif
                    
                        
                    </div>
                </div> -->

                <div class="sub-title">Estatus:</div>
                <div>
                        {{Form::text('estatus',($consulta->estatus),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

            </div>
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
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