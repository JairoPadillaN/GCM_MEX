@extends('principal')
@section('contenido')


{{Form::open(['route' => 'editarProspectos','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificación de investigación de prospectos</h1>
        </div>
        <div class="panel-body">
            <div class="card-title">
                <div class="sub-title">Datos</div>
            </div>
            <div class="row" style="">
                <div class="col-md-6">
                    {{Form::hidden('idInvProspectos',$consulta->idInvProspectos)}}
                    <div class="sub-title">Fecha de alta:</div>
                    <div>
                        <!-- @if($errors->first('razonSocialProv'))
                        <i> {{ $errors->first('razonSocialProv') }}</i>
                        @endif -->
                        {{Form::date('fechaAlta',$consulta->fechaAlta,['class' => 'form-control'])}}
                    </div>

                    <div class="sub-title">Empresa que da seguimiento:</div>
                    <div>
                        @if ($consulta->empresaSeguimiento == 'GCM')
                        GCM{{Form::radio('empresaSeguimiento','GCM', true, ['checked' => 'checked'])}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        CYM {{Form::radio('empresaSeguimiento','CYM',false, [])}}&nbsp;&nbsp;&nbsp;
                        @else
                        GCM {{Form::radio('empresaSeguimiento','GCM',false, [])}}&nbsp;&nbsp;&nbsp;
                        CYM {{Form::radio('empresaSeguimiento','CYM', true, ['checked' => 'checked'])}}
                        @endif
                    </div>

                    <div class="sub-title">Registrado por:</div>
                    <div>
                        <input type='hidden' name='idu' id='idu' value="{{$consulta->idu}}">
                        <input type="text" name="registradoPor" class="form-control" readonly="true"
                            value="{{$consulta->registradoPor}}">
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
                                    <option value='{{$idclientesel}}'>{{$nomcli}}</option>
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
                            <div class="sub-title">Sucursal:</div>
                            @if($errors->first('idSucursal'))
                            <i> {{ $errors->first('idSucursal') }}</i>
                            @endif
                            <div id='combop'>
                                <select name='idSucursal' id='idp' class="form-control">
                                    <option value='{{$idSucursalSel}}'>{{$nomSuc}}</option>
                                </select>
                            </div>
                            <div id="idmodal"> 
                                <i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter{{$idSucursalSel}}"></i>
                            </div>
                        </div>
                    </div>

                    <div class="sub-title">Descripción de actividad:</div>
                    <div>
                        {{Form::textarea('descripcionActividad',$consulta->descripcionActividad,['class' => 'form-control'])}}
                    </div>
                </div>
            </div>
            <br><br>
            <center>
                {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
            </center>
        </div>
    </div>
</div>

<script>
$("#idc").change(function() {
    $("#idp").load('{{url('comboSucursalesActivo')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    // alert("sucursales");
});

$("#idp").change(function() {
        $("#idmodal").load('{{url('obtenerId')}}' + '?r=' + Date.now() + '&idSucursal=' + this.options[this.selectedIndex].value);
        // alert("sucursales");
    });

</script>

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
                                <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                            </select><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">* Sucursal<br>
                        <div>
                            <input type="hidden" name="idSucursal" class="form-control" readonly="true"
                                value="{{$consulta->idSucursal}}">
                            <input value="{{ $nomSuc }}" name="sucursal" type="text"
                                class="form-control" name="sucursal"><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">* Clave para GCMid
                        <div>
                            <input value="{{$sucursalSel->gcmid}}" name="gcmid" type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="5"
                            class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6">* Colonia
                        <div>
                            <input value="{{$sucursalSel->colonia}}" name="colonia" type="text"
                            class="form-control"><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md-6">* Calle
                            <div>
                                <input value="{{ $sucursalSel->calle }}" name="calle" type="text"
                                    class="form-control" class="form-control"><br>
                            </div>
                        </div>
                        <div class="col-md-6 ml-auto">* Número
                            <div>
                                <input value="{{ $sucursalSel->num }}" name="num" type="text"
                                    class="form-control"><br>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-6">Giro
                        <div>
                            <select name="giro" class="form-control">
                                @if($sucursalSel->giro=="")
                                <option value="">Seleccione un giro</option>
                                @else
                                <option value="{{$sucursalSel->giro}}">{{$sucursalSel->giro}}</option>
                                @endif
                                <option value="Alimentos">Alimentos</option>
                                <option value="Automotriz">Automotriz</option>
                                <option value="Construcción">Construcción </option>
                            </select><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Tipo de sucursal
                        <div>

                            @if($sucursalSel->tipoSucursal=='prospecto')
                            Prospecto {{Form::radio('tipoSucursal', 'prospecto', true)}}&nbsp&nbsp&nbsp&nbsp
                            Activo {{Form::radio('tipoSucursal', 'activo', false)}} &nbsp&nbsp&nbsp&nbsp
                            No Activo {{Form::radio('tipoSucursal', 'noActivo', false)}}
                            @endif
                            @if($sucursalSel->tipoSucursal=='activo')
                            Prospecto
                            {{Form::radio('tipoSucursal', 'prospecto', false)}}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            Activo {{Form::radio('tipoSucursal', 'activo', true)}}
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            No Activo {{Form::radio('tipoSucursal', 'noActivo', false)}}
                            @endif
                            @if($sucursalSel->tipoSucursal=='noActivo')
                            Prospecto {{Form::radio('tipoSucursal', 'prospecto',false)}}&nbsp&nbsp&nbsp&nbsp
                            Activo {{Form::radio('tipoSucursal', 'activo',false)}} &nbsp&nbsp&nbsp&nbsp
                            No Activo {{Form::radio('tipoSucursal', 'noActivo',true)}}
                            @endif
                            @if($sucursalSel->tipoSucursal=='')
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
                                @if(in_array('Marca 1',$arrayCheckMarcas))
                                {{Form::checkbox('marcas[]', 'Marca 1',true,['id'=>'marca1'])}} Marca 1<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 1',false,['id'=>'marca1'])}} Marca 1<br>
                                @endif
                                @if(in_array('Marca 2',$arrayCheckMarcas))
                                {{Form::checkbox('marcas[]', 'Marca 2',true,['id'=>'marca2'])}} Marca 2<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 2',false,['id'=>'marca2'])}} Marca 2<br>
                                @endif
                                @if(in_array('Marca 3',$arrayCheckMarcas))
                                {{Form::checkbox('marcas[]', 'Marca 3',true,['id'=>'marca3'])}} Marca 3
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 3',false,['id'=>'marca3'])}} Marca 3
                                @endif
                            </div>
                            <div class="col-lg-6">
                                @if(in_array('Marca 4',$arrayCheckMarcas))
                                {{Form::checkbox('marcas[]', 'Marca 4',true,['id'=>'marca4'])}} Marca 4<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 4',false,['id'=>'marca4'])}} Marca 4<br>
                                @endif
                                @if(in_array('Marca 5',$arrayCheckMarcas))
                                {{Form::checkbox('marcas[]', 'Marca 5',true,['id'=>'marca5'])}} Marca 5<br>
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 5',false,['id'=>'marca5'])}} Marca 5<br>
                                @endif
                                @if(in_array('Marca 6',$arrayCheckMarcas))
                                {{Form::checkbox('marcas[]', 'Marca 6',true,['id'=>'marca6'])}} Marca 6
                                @else
                                {{Form::checkbox('marcas[]', 'Marca 6',false,['id'=>'marca6'])}} Marca 6
                                @endif<br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Servicios a ofrecer<br>
                        @if(in_array('Venta de refacciones',$arrayCheckServicios))
                        {{Form::checkbox('servicios[]', 'Venta de refacciones',true,['id'=>'check1'])}}
                        Venta de
                        refacciones <br>
                        @else
                        {{Form::checkbox('servicios[]', 'Venta de refacciones',false,['id'=>'check1'])}}
                        Venta de
                        refacciones <br>
                        @endif

                        @if(in_array('Refurbished',$arrayCheckServicios))
                        {{Form::checkbox('servicios[]', 'Refurbished',true,['id'=>'check2'])}}
                        Refurbished<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Refurbished',false,['id'=>'check2'])}}
                        Refurbished<br>
                        @endif

                        @if(in_array('Soporte técnico',$arrayCheckServicios))
                        {{Form::checkbox('servicios[]', 'Soporte técnico',true,['id'=>'check3'])}} Soporte
                        técnico<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Soporte técnico',false,['id'=>'check3'])}} Soporte
                        técnico<br>
                        @endif

                        @if(in_array('Servicios de cambio',$arrayCheckServicios))
                        {{Form::checkbox('servicios[]', 'Servicios de cambio',true,['id'=>'check4'])}}
                        Servicios de
                        cambio<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Servicios de cambio',false,['id'=>'check4'])}}
                        Servicios de
                        cambio<br>
                        @endif

                        @if(in_array('Reparaciones',$arrayCheckServicios))
                        {{Form::checkbox('servicios[]', 'Reparaciones',true,['id'=>'check5'])}}
                        Reparaciones<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Reparaciones',false,['id'=>'check5'])}}
                        Reparaciones<br>
                        @endif

                        @if(in_array('Diseño y Fabricación',$arrayCheckServicios))
                        {{Form::checkbox('servicios[]', 'Diseño y Fabricación',true,['id'=>'check6'])}}
                        Diseño y
                        Fabricación<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Diseño y Fabricación',false,['id'=>'check6'])}}
                        Diseño y
                        Fabricación<br>
                        @endif

                        @if(in_array('Mantenimiento Preventivo',$arrayCheckServicios))
                        {{Form::checkbox('servicios[]', 'Mantenimiento Preventivo',true,['id'=>'check7'])}}
                        Mantenimiento Preventivo<br>
                        @else
                        {{Form::checkbox('servicios[]', 'Mantenimiento Preventivo',false,['id'=>'check7'])}}
                        Mantenimiento Preventivo<br>
                        @endif

                        @if(in_array('Mantenimiento Correctivo',$arrayCheckServicios))
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
                            <input value="{{ $sucursalSel->contactoVentas }}" name="contactoVentas" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono de Ventas:
                        <div>
                            <input value="{{ $sucursalSel->telVentas }}" name="telVentas" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo de ventas:
                        <div>
                            <input value="{{ $sucursalSel->correoVentas }}" name="correoVentas" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión de Ventas:
                        <div>
                            <input value="{{ $sucursalSel->extenVentas }}" name="extenVentas" type="text" placeholder='Ejemplo: 52'
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
                            <input value="{{ $sucursalSel->contactoPlanta }}" name="contactoPlanta" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono del Gerente de Planta:
                        <div>
                            <input value="{{ $sucursalSel->telPlanta }}" name="telPlanta" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo del Gerente de Planta:
                        <div>
                            <input value="{{ $sucursalSel->correoPlanta }}" name="correoPlanta" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión del Gerente de Planta:
                        <div>
                            <input value="{{ $sucursalSel->extenPlanta }}" name="extenPlanta" type="text" placeholder='Ejemplo: 52'
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
                            <input value="{{ $sucursalSel->contactoAlmacen }}" name="contactoAlmacen" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono del Almacen:
                        <div>
                            <input value="{{ $sucursalSel->telAlmacen }}" name="telAlmacen" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo del Almacen:
                        <div>
                            <input value="{{ $sucursalSel->correoAlmacen }}" name="correoAlmacen" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión del Almacen:
                        <div>
                            <input value="{{ $sucursalSel->extenAlmacen }}" name="extenAlmacen" type="text" placeholder='Ejemplo: 52'
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
                            <input value="{{ $sucursalSel->contactoGerente }}" name="contactoGerente" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono del Gerente:
                        <div>
                            <input value="{{ $sucursalSel->telGerente }}" name="telGerente" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo del Gerente:
                        <div>
                            <input value="{{ $sucursalSel->correoGerente }}" name="correoGerente" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión del Gerente:
                        <div>
                            <input value="{{ $sucursalSel->extenGerente }}" name="extenGerente" type="text" placeholder='Ejemplo: 52'
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
                            <input value="{{ $sucursalSel->contactoCompras }}" name="contactoCompras" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono de Compras:
                        <div>
                            <input value="{{ $sucursalSel->telCompras }}" name="telCompras" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo de Compras:
                        <div>
                            <input value="{{ $sucursalSel->correoCompras }}" name="correoCompras" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión de Compras:
                        <div>
                            <input value="{{ $sucursalSel->extenCompras }}" name="extenCompras" type="text" placeholder='Ejemplo: 52'
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
                            <input value="{{ $sucursalSel->nombreCuentasPP }}" name="nombreCuentasPP" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono:
                        <div>
                            <input value="{{ $sucursalSel->telefonoCuentasPP }}" name="telefonoCuentasPP" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo:
                        <div>
                            <input value="{{ $sucursalSel->correoCuentasPP }}" name="correoCuentasPP" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión:
                        <div>
                            <input value="{{ $sucursalSel->extencionCuentasPP }}" name="extencionCuentasPP" type="text" placeholder='Ejemplo: 52'
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
                            <input value="{{ $sucursalSel->nombreCuentasPC }}" name="nombreCuentasPC" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Teléfono:
                        <div>
                            <input value="{{ $sucursalSel->telefonoCuentasPC }}" name="telefonoCuentasPC" type="text" placeholder='Ejemplo: 5518273652'
                                class="form-control"><br>
                        </div>
                    </div>
                    
                    <div class="col-md-6">Correo:
                        <div>
                            <input value="{{ $sucursalSel->correoCuentasPC }}" name="correoCuentasPC" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                class="form-control"><br>
                        </div>
                    </div>
                    <div class="col-md-6 ml-auto">Extensión:
                        <div>
                            <input value="{{ $sucursalSel->extencionCuentasPC }}" name="extencionCuentasPC" type="text" placeholder='Ejemplo: 52'
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


<script>
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
        }
    });
});
</script>

<style>
.fa-chevron-circle-right{
    color: blue;
    font-size: 30px;
    padding:50;
}
</style>
@stop