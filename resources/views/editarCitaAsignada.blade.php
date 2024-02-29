@extends('principal')
@section('contenido')

{{Form::open(['route' => 'editarCitasAsignada','files'=>true])}}
{{Form::token()}}


<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificar citas / visitas técnicas</h1><br><br>
            <div class="card-title">
                <div class="title">Citas asignadas</div>
            </div>
        </div>

        <div class="panel-body">
            <div class="form-group col-md-6">
            
                <div class="sub-title">Fecha de alta:</div>
                <div>
                    {{Form::date('fechaAlta',($consulta->fechaAlta),['class' => 'form-control','readonly' => 'true'])}}
                </div>

                <div class="sub-title">Empresa que da seguimiento:</div>
                <div>
                {{Form::text('empresaSeguimiento',($consulta->empresaSeguimiento),['class' => 'form-control','readonly' => 'true'])}}
                    <!-- @if ($consulta->empresaSeguimiento == 'gcm')
                        GCM {{Form::radio('empresaSeguimiento','gcm', true, ['checked' => 'checked']) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        C&M {{Form::radio('empresaSeguimiento','cym',false)}}
                    @else
                        GCM {{Form::radio('empresaSeguimiento','gcm',false)}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        C&M {{Form::radio('empresaSeguimiento','cym', true, ['checked' => 'checked', 'id'=>'nombreEmpresa1']) }}
                    @endif                 -->
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
                <div>
                {{Form::hidden('idc',$consulta->idc)}}
                        <input type="text" name="idClienteSel" class="form-control" readonly="true" value="{{$nombreCliente}}">
                    <!-- <select name='idc' id='idc' class="form-control" >
                            <option value="{{$idClienteSel}}">{{$nombreCliente}}</option>
                            
                    </select> -->
                </div>

                <div class="sub-title">* Sucursal:</div>
                <div>
                {{Form::hidden('idSucursal',$consulta->idSucursal)}}
                <input type="text" name="idSucursalSel" class="form-control" readonly="true" value="{{$nombreSucursal}}">
                    <!-- <select name='idSucursal' id='idp' class="form-control">
                        <option value="{{$idSucursalSel}}">{{$nombreSucursal}}</option>
                    </select> -->
                </div>

                <div class="sub-title">* Contacto:</div>
                <div id='prueba'>
                {{Form::hidden('contacto',$consulta->contacto)}}
                <input type="text" name="contacto" class="form-control" readonly="true" value="{{$nombreContacto}}">
                    <!-- <select name='contacto' id='ido' class="form-control" >
                        <option value="{{$consulta->contacto}}">{{$nombreContacto}}</option>
                    </select> -->
                </div>


                <div class="sub-title">Fecha de cita:</div>
                <div>
                    {{Form::date('fechaCita',($consulta->fechaCita),['class' => 'form-control','readonly' => 'true'])}}
                </div>

                <div class="sub-title">Hora: </div>
                <div>
                    {{Form::time('hora',($consulta->hora),['class' => 'form-control','readonly' => 'true'])}}
                </div>

                <div class="sub-title">Lugar cita </div>
                <div>
                    {{Form::text('lugar',($consulta->lugar),['class' => 'form-control','readonly' => 'true'])}}
                </div>

                <div class="sub-title">* Responsable atender cita:</div>
                <div>
                {{Form::hidden('responsable',$consulta->responsable)}}
                <input type="text" name="idUsuarioSel" class="form-control" readonly="true" value="{{$nombreUsuario}} {{$paterno}} {{$materno}} ">
                    <!-- <select name='responsable' class="form-control" >
                        <option value="{{$idUsuarioSel}}">{{$nombreUsuario}} {{$paterno}} {{$materno}} </option>
                        
                    </select> -->
                </div>


                <div class="sub-title">Observaciones previa cita:</div>
                <div>
                    {{Form::textarea('observacionCita',($consulta->observacionCita),['class' => 'form-control','readonly' => 'true'])}}
                </div>

            </div>

            <!-- Columna dos -->
            <div class="form-group col-md-6">
                <div class="sub-title">Fecha real de visita:</div>
                <div>@if($errors->first('fechaReal'))
                <i> {{ $errors->first('fechaReal') }}</i>
                @endif
                
                    {{Form::date('fechaReal',($consulta->fechaReal),['class' => 'form-control'])}}
                </div>

                <div class="sub-title">Resultados cita:</div>
                <div>
                    {{Form::textarea('resultados',($consulta->resultados),['class' => 'form-control'])}}
                </div>

                <!-- <div class='sub-title'>Servicios a ofrecer: </div>
                <div class='row'>
                    @if($errors->first('serviciosOfrecer'))
                    <i> {{ $errors->first('serviciosOfrecer') }}</i>
                    @endif
                    <div class="form-group col-md-6">
                    @if(in_array('ventaRefacciones',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'ventaRefacciones',true,['id'=>'check1'])}} Venta de refacciones</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'ventaRefacciones',false,['id'=>'check1'])}} Venta de refacciones</div>
                    @endif

                    @if(in_array('REfurbished',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'REfurbished',true,['id'=>'check2'])}} REfurbished</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'REfurbished',false,['id'=>'check2'])}} REfurbished</div>
                    @endif

                    @if(in_array('soporteTecnico',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'soporteTecnico',true,['id'=>'check3'])}} Soporte técnico</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'soporteTecnico',false,['id'=>'check3'])}} Soporte técnico</div>
                    @endif

                    </div>

                    <div class="form-group col-md-6">
                    @if(in_array('reparaciones',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'reparaciones',true,['id'=>'check4'])}} Reparaciones</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'reparaciones',false,['id'=>'check4'])}} Reparaciones</div>
                    @endif

                    @if(in_array('diseñoFabricacion',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'diseñoFabricacion',true,['id'=>'check5'])}} Diseño y fabricación</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'diseñoFabricacion',false,['id'=>'check5'])}} Diseño y fabricación</div>
                    @endif

                    @if(in_array('manttoPreventivo',$arrayCheckServicios))
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'manttoPreventivo',true,['id'=>'check6'])}}  Mantenimiento preventivo</div>
                    @else
                        <div>{{Form::checkbox('serviciosOfrecer[]', 'manttoPreventivo',false,['id'=>'check6'])}}  Mantenimiento preventivo</div>
                    @endif                
                    </div>
                </div>

                <div class='sub-title'>Marcas: </div>
                <div class='row'>
                    
                    <div class="form-group col-md-6">
                    
                    @if(in_array('marca1',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca1',true,['id'=>'marca1'])}} Marca 1</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca1',false,['id'=>'marca1'])}} Marca 1</div>
                    @endif

                    @if(in_array('marca2',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca2',true,['id'=>'marca2'])}} Marca 2</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca2',false,['id'=>'marca2'])}} Marca 2</div>
                    @endif

                    @if(in_array('marca3',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca3',true,['id'=>'marca3'])}} Marca 3</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca3',false,['id'=>'marca3'])}} Marca 3</div>
                    @endif

        
                    </div>

                    <div class="form-group col-md-6">
                    @if(in_array('marca4',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca4',true,['id'=>'marca4'])}} Marca 4</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca4',false,['id'=>'marca4'])}} Marca 4</div>
                    @endif

                    @if(in_array('marca5',$arrayCheckMarcas))
                        <div>{{Form::checkbox('marcas[]', 'marca5',true,['id'=>'marca5'])}} Marca 5</div>
                    @else
                        <div>{{Form::checkbox('marcas[]', 'marca5',false,['id'=>'marca5'])}} Marca 5</div>
                    @endif
                    
                        
                        
                    </div>
                </div> -->

                <div class="sub-title">Estatus:</div>
                @if($errors->first('estatus'))
                <i> {{ $errors->first('estatus') }}</i>
                @endif

                @if($consulta->estatus=='Atendida')
                <div class="form-group col-md-4">
                    Atendida {{Form::radio('estatus','Atendida', true)}}
                </div>
                <div class="form-group col-md-4">
                    En espera {{Form::radio('estatus','Espera', false)}}
                </div>
                <div class="form-group col-md-4">
                    Cancelada {{Form::radio('estatus','Cancelada',false)}}
                @endif
                @if($consulta->estatus=='Espera')
                <div class="form-group col-md-4">
                    Atendida {{Form::radio('estatus','Atendida', false)}}
                </div>
                <div class="form-group col-md-4">
                    En espera {{Form::radio('estatus','Espera', true)}}
                </div>
                <div class="form-group col-md-4">
                    Cancelada {{Form::radio('estatus','Cancelada',false)}}
                @endif
                @if($consulta->estatus=='Cancelada')
                <div class="form-group col-md-4">
                    Atendida {{Form::radio('estatus','Atendida', false)}}
                </div>
                <div class="form-group col-md-4">
                    En espera {{Form::radio('estatus','Espera', false)}}
                </div>
                <div class="form-group col-md-4">
                    Cancelada {{Form::radio('estatus','Cancelada',true)}}
                @endif
                @if($consulta->estatus=='')
                <div class="form-group col-md-4">
                    Atendida {{Form::radio('estatus','Atendida', false)}}
                </div>
                <div class="form-group col-md-4">
                    En espera {{Form::radio('estatus','Espera', false)}}
                </div>
                <div class="form-group col-md-4">
                    Cancelada {{Form::radio('estatus','Cancelada',false)}}
                @endif
                </div>
                
                    <!-- <div class="sub-title" class="col-md-6">Modificar sucursal &nbsp;&nbsp;
                    
                                    <i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter{{$idSucursalSel}}"></i>
                    
                    </div>  -->
                
            </div>
        </div>
        <center>
            {{Form::submit('Guardar',['class' => 'btn btn-success', 'id'=>'guardar'])}}
        </center><br>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter{{$idSucursalSel}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-footer bg-primary">
        <h3 class="modal-title" id="exampleModalLongTitle" align="center">Modificar sucursal</h3>
        
        </div>
        <div class="modal-body">
        <div id="mensajeConfirmacion">
            <div class="container-fluid">
                <div class="row">
                    <div class="card-title">
                        <div class="title">Datos de la sucursal</div><br>
                    </div>
                    <div class="col-md-6" class="modal-title">* Cliente<br>
                        <div>
                            <select name='idc' id='idc' class="form-control" rows="5"
                                class="form-control rounded-0" disabled>
                                <option value='{{$idClienteSel}}'>{{$nombreCliente}}</option>
                            </select><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">* Sucursal<br>
                        <div>
                            <input type="hidden" name="idSucursal" class="form-control" readonly="true"
                                value="{{$consulta->idSucursal}}">
                            <input value="{{ $nombreSucursal }}" name="sucursal" type="text"
                                class="form-control" name="sucursal"><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">* Clave para GCMid
                        <div>
                            <input value="{{$consultaSucursal->gcmid}}" name="gcmid" type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="5"
                            class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6">* Colonia
                        <div>
                            <input value="{{$consultaSucursal->colonia}}" name="colonia" type="text"
                            class="form-control"><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md-6">* Calle
                            <div>
                                <input value="{{ $consultaSucursal->calle }}" name="calle" type="text"
                                    class="form-control" class="form-control"><br>
                            </div>
                        </div>
                        <div class="col-md-6 ml-auto">* Número
                            <div>
                                <input value="{{ $consultaSucursal->num }}" name="num" type="text"
                                    class="form-control"><br>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-6">Giro
                        <div>
                            <select name="giro" class="form-control">
                                @if($consultaSucursal->giro=="")
                                <option value="">Seleccione un giro</option>
                                @else
                                <option value="{{$consultaSucursal->giro}}">{{$consultaSucursal->giro}}</option>
                                @endif
                                <option value="Alimentos">Alimentos</option>
                                <option value="Automotriz">Automotriz</option>
                                <option value="Construcción">Construcción </option>
                            </select><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Tipo de sucursal
                        <div>

                            @if($consultaSucursal->tipoSucursal=='prospecto')
                            Prospecto {{Form::radio('tipoSucursal', 'prospecto', true)}}&nbsp&nbsp&nbsp&nbsp
                            Activo {{Form::radio('tipoSucursal', 'activo', false)}} &nbsp&nbsp&nbsp&nbsp
                            No Activo {{Form::radio('tipoSucursal', 'noActivo', false)}}
                            @endif
                            @if($consultaSucursal->tipoSucursal=='activo')
                            Prospecto
                            {{Form::radio('tipoSucursal', 'prospecto', false)}}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            Activo {{Form::radio('tipoSucursal', 'activo', true)}}
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            No Activo {{Form::radio('tipoSucursal', 'noActivo', false)}}
                            @endif
                            @if($consultaSucursal->tipoSucursal=='noActivo')
                            Prospecto {{Form::radio('tipoSucursal', 'prospecto',false)}}&nbsp&nbsp&nbsp&nbsp
                            Activo {{Form::radio('tipoSucursal', 'activo',false)}} &nbsp&nbsp&nbsp&nbsp
                            No Activo {{Form::radio('tipoSucursal', 'noActivo',true)}}
                            @endif
                            @if($consultaSucursal->tipoSucursal=='')
                            Prospecto {{Form::radio('tipoSucursal', 'prospecto',false)}}&nbsp&nbsp&nbsp&nbsp
                            Activo {{Form::radio('tipoSucursal', 'activo',false)}} &nbsp&nbsp&nbsp&nbsp
                            No Activo {{Form::radio('tipoSucursal', 'noActivo',false)}}
                            @endif<br>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">Marca
                        <div class="row">
                            <div class="col-lg-6">
                                @if(in_array('Marca 1',$arrayCheckMarcasSucursales))
                                {{Form::checkbox('marcas[]', 'Marca 1',true,['id'=>'marca1'])}} Marca 1<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 1',false,['id'=>'marca1'])}} Marca 1<br>
                                @endif
                                @if(in_array('Marca 2',$arrayCheckMarcasSucursales))
                                {{Form::checkbox('marcas[]', 'Marca 2',true,['id'=>'marca2'])}} Marca 2<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 2',false,['id'=>'marca2'])}} Marca 2<br>
                                @endif
                                @if(in_array('Marca 3',$arrayCheckMarcasSucursales))
                                {{Form::checkbox('marcas[]', 'Marca 3',true,['id'=>'marca3'])}} Marca 3
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 3',false,['id'=>'marca3'])}} Marca 3
                                @endif
                            </div>
                            <div class="col-lg-6">
                                @if(in_array('Marca 4',$arrayCheckMarcasSucursales))
                                {{Form::checkbox('marcas[]', 'Marca 4',true,['id'=>'marca4'])}} Marca 4<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 4',false,['id'=>'marca4'])}} Marca 4<br>
                                @endif
                                @if(in_array('Marca 5',$arrayCheckMarcasSucursales))
                                {{Form::checkbox('marcas[]', 'Marca 5',true,['id'=>'marca5'])}} Marca 5<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 5',false,['id'=>'marca5'])}} Marca 5<br>
                                @endif
                                @if(in_array('Marca 6',$arrayCheckMarcasSucursales))
                                {{Form::checkbox('marcas[]', 'Marca 6',true,['id'=>'marca6'])}} Marca 6
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 6',false,['id'=>'marca6'])}} Marca 6
                                @endif<br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Servicios a ofrecer<br>
                        @if(in_array('Venta de refacciones',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Venta de refacciones',true,['id'=>'check1'])}}
                        Venta de
                        refacciones <br>
                        @else
                        {{Form::checkbox('servicios[]', 'Venta de refacciones',false,['id'=>'check1'])}}
                        Venta de
                        refacciones <br>
                        @endif

                        @if(in_array('Refurbished',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Refurbished',true,['id'=>'check2'])}}
                        Refurbished<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Refurbished',false,['id'=>'check2'])}}
                        Refurbished<br>
                        @endif

                        @if(in_array('Soporte técnico',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Soporte técnico',true,['id'=>'check3'])}} Soporte
                        técnico<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Soporte técnico',false,['id'=>'check3'])}} Soporte
                        técnico<br>
                        @endif

                        @if(in_array('Servicios de cambio',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Servicios de cambio',true,['id'=>'check4'])}}
                        Servicios de
                        cambio<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Servicios de cambio',false,['id'=>'check4'])}}
                        Servicios de
                        cambio<br>
                        @endif

                        @if(in_array('Reparaciones',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Reparaciones',true,['id'=>'check5'])}}
                        Reparaciones<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Reparaciones',false,['id'=>'check5'])}}
                        Reparaciones<br>
                        @endif

                        @if(in_array('Diseño y Fabricación',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Diseño y Fabricación',true,['id'=>'check6'])}}
                        Diseño y
                        Fabricación<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Diseño y Fabricación',false,['id'=>'check6'])}}
                        Diseño y
                        Fabricación<br>
                        @endif

                        @if(in_array('Mantenimiento Preventivo',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Mantenimiento Preventivo',true,['id'=>'check7'])}}
                        Mantenimiento Preventivo<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Mantenimiento Preventivo',false,['id'=>'check7'])}}
                        Mantenimiento Preventivo<br>
                        @endif

                        @if(in_array('Mantenimiento Correctivo',$arrayCheckServiciosSucursales))
                        {{Form::checkbox('servicios[]', 'Mantenimiento Correctivo',true,['id'=>'check8'])}}
                        Mantenimiento Correctivo<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Mantenimiento Correctivo',false,['id'=>'check8'])}}
                        Mantenimiento Correctivo<br>
                        @endif
                    </div>
                </div>

                <hr> <!--Aqui inicia el div para Datos de venta-->
                            
                <div class="row">
                    <div class="card-title">
                        <div class="title">Datos de Venta</div><br>
                    </div>
                    <div class="col-md-6">Contacto de ventas:
                        <div>
                            <input value="{{ $consultaSucursal->contactoVentas }}" name="contactoVentas" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono de Ventas:
                        <div>
                            <input value="{{ $consultaSucursal->telVentas }}" name="telVentas" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo de ventas:
                        <div>
                            <input value="{{ $consultaSucursal->correoVentas }}" name="correoVentas" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión de Ventas:
                        <div>
                            <input value="{{ $consultaSucursal->extenVentas }}" name="extenVentas" type="text" placeholder='Ejemplo: 52'
                                class="form-control"><br>
                        </div>
                    </div>
                </div>

                <hr> <!--Aqui inicia el div para Gerente de Planta-->
                <div class="row">
                    <div class="card-title">
                        <div class="title">Gerente de Planta</div><br>
                    </div>
                    <div class="col-md-6">Contacto del Gerente de Planta:
                        <div>
                            <input value="{{ $consultaSucursal->contactoPlanta }}" name="contactoPlanta" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono del Gerente de Planta:
                        <div>
                            <input value="{{ $consultaSucursal->telPlanta }}" name="telPlanta" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo del Gerente de Planta:
                        <div>
                            <input value="{{ $consultaSucursal->correoPlanta }}" name="correoPlanta" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión del Gerente de Planta:
                        <div>
                            <input value="{{ $consultaSucursal->extenPlanta }}" name="extenPlanta" type="text" placeholder='Ejemplo: 52'
                                class="form-control"><br>
                        </div>
                    </div>
                </div>

                <hr> <!--Aqui inicia el div para Datos de Almacen-->
                <div class="row">
                    <div class="card-title">
                        <div class="title">Datos del Almacen</div><br>
                    </div>
                    <div class="col-md-6">Contacto del Almacen:
                        <div>
                            <input value="{{ $consultaSucursal->contactoAlmacen }}" name="contactoAlmacen" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono del Almacen:
                        <div>
                            <input value="{{ $consultaSucursal->telAlmacen }}" name="telAlmacen" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo del Almacen:
                        <div>
                            <input value="{{ $consultaSucursal->correoAlmacen }}" name="correoAlmacen" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión del Almacen:
                        <div>
                            <input value="{{ $consultaSucursal->extenAlmacen }}" name="extenAlmacen" type="text" placeholder='Ejemplo: 52'
                                class="form-control"><br>
                        </div>
                    </div>
                </div>

                <hr> <!--Aqui inicia el div para Datos del Gerente-->
                <div class="row">
                    <div class="card-title">
                        <div class="title">Datos del Gerente</div><br>
                    </div>
                    <div class="col-md-6">Contacto del Gerente:
                        <div>
                            <input value="{{ $consultaSucursal->contactoGerente }}" name="contactoGerente" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono del Gerente:
                        <div>
                            <input value="{{ $consultaSucursal->telGerente }}" name="telGerente" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo del Gerente:
                        <div>
                            <input value="{{ $consultaSucursal->correoGerente }}" name="correoGerente" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión del Gerente:
                        <div>
                            <input value="{{ $consultaSucursal->extenGerente }}" name="extenGerente" type="text" placeholder='Ejemplo: 52'
                                class="form-control"><br>
                        </div>
                    </div>
                </div>

                <hr> <!--Aqui inicia el div para Datos de Compras-->
                <div class="row">
                    <div class="card-title">
                        <div class="title">Datos de Compras</div><br>
                    </div>
                    <div class="col-md-6">Contacto de Compras:
                        <div>
                            <input value="{{ $consultaSucursal->contactoCompras }}" name="contactoCompras" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono de Compras:
                        <div>
                            <input value="{{ $consultaSucursal->telCompras }}" name="telCompras" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo de Compras:
                        <div>
                            <input value="{{ $consultaSucursal->correoCompras }}" name="correoCompras" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión de Compras:
                        <div>
                            <input value="{{ $consultaSucursal->extenCompras }}" name="extenCompras" type="text" placeholder='Ejemplo: 52'
                                class="form-control"><br>
                        </div>
                    </div>
                </div>

                <hr> <!--Aqui inicia el div para Cuentas por pagar-->
                <div class="row">
                    <div class="card-title">
                        <div class="title">Cuentas por pagar</div><br>
                    </div>
                    <div class="col-md-6">Nombre:
                        <div>
                            <input value="{{ $consultaSucursal->nombreCuentasPP }}" name="nombreCuentasPP" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono:
                        <div>
                            <input value="{{ $consultaSucursal->telefonoCuentasPP }}" name="telefonoCuentasPP" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo:
                        <div>
                            <input value="{{ $consultaSucursal->correoCuentasPP }}" name="correoCuentasPP" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión:
                        <div>
                            <input value="{{ $consultaSucursal->extencionCuentasPP }}" name="extencionCuentasPP" type="text" placeholder='Ejemplo: 52'
                                class="form-control"><br>
                        </div>
                    </div>
                </div>

                <hr> <!--Aqui inicia el div para Cuentas por cobrar-->
                <div class="row">
                    <div class="card-title">
                        <div class="title">Cuentas por cobrar</div><br>
                    </div>
                    <div class="col-md-6">Nombre:
                        <div>
                            <input value="{{ $consultaSucursal->nombreCuentasPC }}" name="nombreCuentasPC" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono:
                        <div>
                            <input value="{{ $consultaSucursal->telefonoCuentasPC }}" name="telefonoCuentasPC" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo:
                        <div>
                            <input value="{{ $consultaSucursal->correoCuentasPC }}" name="correoCuentasPC" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión:
                        <div>
                            <input value="{{ $consultaSucursal->extencionCuentasPC }}" name="extencionCuentasPC" type="text" placeholder='Ejemplo: 52'
                                class="form-control"><br>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <div class="modal-footer text-center">
            <div id="botonGuardar">
                <button type="button" class="btn btn-primary" id="guardarModal" data-dismiss="modal">Guardar cambios</button>
            </div><br>
            <button type="button" class="btn btn-secondary btn-center" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function() {

$("#guardarModal").click(function(e) {
    var check1 = document.getElementById("check1");
    var check2 = document.getElementById("check2");
    var check3 = document.getElementById("check3");
    var check4 = document.getElementById("check4");
    var check5 = document.getElementById("check5");
    var check6 = document.getElementById("check6");
    var check7 = document.getElementById("check7");
    var check8 = document.getElementById("check8");

    var marca1 = document.getElementById("marca1");
    var marca2 = document.getElementById("marca2");
    var marca3 = document.getElementById("marca3");
    var marca4 = document.getElementById("marca4");
    var marca5 = document.getElementById("marca5");
    var marca6 = document.getElementById("marca6");

    if (check1.checked == false && check2.checked == false && check3.checked == false && check4.checked == false &&
        check5.checked == false && check6.checked == false && check7.checked == false && check8.checked == false) {
        alert ("El campo *Descripción de servicios a ofrecer es obligatorio.");
        e.preventDefault();
    }else if (marca1.checked == false && marca2.checked == false && marca3.checked == false && marca4.checked == false &&
        marca5.checked == false && marca6.checked == false) {
        alert ("El campo *Marcas es obligatorio.");
        e.preventDefault();
    }else{
        $("#mensajeConfirmacion").load('{{url('editarSucursalProspecto')}}' + '?' + $(this).closest('form').serialize());
        // document.getElementById("botonGuardar").innerHTML = "";
        alert ("¡¡Sucursal modificada correctamente!!");
        $("#ido").change();
        
        
    }
});
});


$("#idc").change(function() {
    $("#idp").load('{{url('comboSucEditar')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    // alert("mensaje correcto")
});
$("#idp").change(function() {
    $("#ido").load('{{url('comboConEditar')}}' + '?r=' + Date.now() + '&idp=' + this.options[this.selectedIndex].value);
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