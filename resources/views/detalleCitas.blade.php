@extends('principal')
@section('contenido')

{{Form::token()}}

<style type="text/css">
.zoom {
    /* Aumentamos la anchura y altura durante 2 segundos */
    transition: width 3s, height 3s, transform 3s;
    -moz-transition: width 3s, height 3s, -moz-transform 3s;
    -webkit-transition: width 3s, height 3s, -webkit-transform 3s;
    -o-transition: width 3s, height 3s, -o-transform 3s;
}

.zoom:hover {
    /* tranformamos el elemento al pasar el mouse por encima al doble de
           su tamaño con scale(2). */
    transform: scale(4);
    -moz-transform: scale(4);
    /* Firefox */
    -webkit-transform: scale(4);
    /* Chrome - Safari */
    -o-transform: scale(4);
    /* Opera */
}

img.zoom {
    width: 60px;
    height: 60px;
    -webkit-transition: all .2s ease-in-out;
    -moz-transition: all .2s ease-in-out;
    -o-transition: all .2s ease-in-out;
    -ms-transition: all .2s ease-in-out;
}
</style>

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
    <div class="panel-heading">
            <h1>Gestión de citas / visitas técnicas</h1><br><br>
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">

                <div class="sub-title">Fecha de alta:</div>
                <div>
                        {{Form::text('fechaAlta',($consulta->fechaAlta),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <div class="sub-title">Empresa que da seguimiento:</div>
                <div>
                        {{Form::text('empresaSeguimiento',($consulta->empresaSeguimiento),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <div class="sub-title">Registro por: </div>
                <div>
                    <input type='hidden' name='idu' id='idu' value="{!! Session::get('sesionidu')!!}">
                    <input type="text" name="registradoPor" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                            Session::get('sesionmaterno')!!}">
                </div>

                


                <div class="sub-title">* Cliente:</div>
                <div>
                    <select name='idc' id='idc' class="form-control" disabled>
                        <option value="{{$idClienteSel}}">{{$nombreCliente}}</option>
                        
                    </select>
                </div>

                <div class="sub-title">* Sucursal:</div>
                <div>
                    <select name='idSucursal' id='idp' class="form-control" disabled>
                        <option value="{{$idSucursalSel}}">{{$nombreSucursal}}</option>                        
                    </select>
                </div>

                <div class="sub-title">* Contacto:</div>
                <div>
                        {{Form::text('contacto',($consulta->contacto),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <div class="sub-title">Fecha de cita:</div>
                <div>
                        {{Form::date('fechaCita',($consulta->fechaCita),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <div class="sub-title">Hora: </div>
                <div>
                        {{Form::time('hora',($consulta->hora),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <div class="sub-title">Lugar cita </div>
                <div>
                        {{Form::text('lugar',($consulta->lugar),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <div class="sub-title">* Responsable:</div>
                <div>
                    <select name='idu'  class="form-control" disabled>
                        <option value="{{$idUsuarioSel}}">{{$nombreUsuario}} {{$paterno}} {{$materno}}</option>                        
                    </select>
                </div>

                
                <div class="sub-title">Observaciones previa cita:</div>
                <div>
                        {{Form::textarea('observacionCita',($consulta->observacionCita),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

            </div>

            <!-- Columna dos -->
            <div class="form-group col-md-6">
                <div class="sub-title">Fecha real de visita:</div>
                <div>
                        {{Form::date('fechaReal',($consulta->fechaReal),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <div class="sub-title">Resultados cita:</div>
                <div>
                        {{Form::textarea('resultados',($consulta->resultados),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

                <!-- <div class='sub-title'>Servicios a ofrecer: </div>
                <div class='row'>
                    
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
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'manttoPreventivo',false, array('disabled'))}}  Mantenimiento preventivo</div>
                    @endif                
                    </div>
                </div> -->

                <!-- <div class='sub-title'>Marcas: </div>
                <div class='row'>
                  
                <div class="form-group col-md-6">
               

                    @if(in_array('marca1',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca1',true, array('disabled'))}} Marca 1</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca1',false,array('disabled'))}} Marca 1</div>
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
                        <div>{{Form::checkbox('marcas[]', 'marca4',true, array('disabled'))}} Marca 4</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca4',false, array('disabled'))}} Marca 4</div>
                    @endif

                    @if(in_array('marca5',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca5',true,array('disabled'))}} Marca 5</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca5',false, array('disabled'))}} Marca 5</div>
                    @endif
                        
                        
                    </div>
                </div>
 -->
                <div class="sub-title">Estatus:</div>
                <div>
                        {{Form::text('estatus',($consulta->estatus),['class' => 'form-control', 'readonly' => 'true'])}}
                </div>

            
            </div>
            
        </div>
        <center>
            <a href="{{asset('reporteCitas')}}"><button type="button" class="btn btn-default">Regresar</button></a>
        </center>
        <br>
    </div>
</div>


@stop