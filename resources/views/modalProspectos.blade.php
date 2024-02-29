
@foreach($consulta as $consulta)

<i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter{{$idSucursal}}">modalcitas</i>

<!-- Modal -->

<div class="modal fade" id="exampleModalCenter{{$idSucursal}}" tabindex="-1" role="dialog"
aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-footer bg-primary">
            <h3 class="modal-title" id="exampleModalLongTitle" align="center">Modificar sucursal ddd</h3>

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
                                <input type="text" name="idc" id= 'idc' class="form-control" readonly="true"
                                        value="{{$consulta->idc}}">
                                    <select name='idc' id='idc' class="form-control" rows="5"
                                        class="form-control rounded-0">
                                        <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                        @foreach($cliente as $cli)
                                        @if($cli->activo=="si")
                                        <option value='{{$cli->idc}}'>{{$cli->razonSocial}}</option>
                                        @endif
                                        @endforeach
                                    </select><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">* Sucursal<br>
                                <div>
                                    <input type="text" name="idSucursal" id= 'idSucursal' class="form-control" readonly="true"
                                        value="{{$consulta->idSucursal}}">
                                    <input value="{{ $consulta->sucursal }}" name="sucursal" type="text"
                                        class="form-control" name="sucursal"><br>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">* Clave para GCMid
                                <div>
                                    <input value="{{ $consulta->gcmid }}" name="gcmid" type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="5"
                                        class="form-control"><br>
                                </div>

                            </div>
                            <div class="col-md-6">* Colonia
                                <div>
                                    <input value="{{ $consulta->colonia }}" name="colonia" type="text"
                                        class="form-control"><br>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">* Calle
                                <div>
                                    <input value="{{ $consulta->calle }}" name="calle" type="text"
                                        class="form-control" class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">* Número
                                <div>
                                    <input value="{{ $consulta->num }}" name="num" type="text"
                                        class="form-control"><br>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">Giro
                                <div>
                                    <select name="giro" class="form-control">
                                        @if($consulta->giro=="")
                                        <option value="">Seleccione un giro</option>
                                        @else
                                        <option value="{{$consulta->giro}}">{{$consulta->giro}}</option>
                                        @endif
                                        <option value="Alimentos">Alimentos</option>
                                        <option value="Automotriz">Automotriz</option>
                                        <option value="Construcción">Construcción </option>
                                    </select><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Tipo de sucursal
                                <div>

                                    @if($consulta->tipoSucursal=='prospecto')
                                    Prospecto {{Form::radio('tipoSucursal', 'prospecto', true)}}&nbsp&nbsp&nbsp&nbsp
                                    Activo {{Form::radio('tipoSucursal', 'activo', false)}} &nbsp&nbsp&nbsp&nbsp
                                    No Activo {{Form::radio('tipoSucursal', 'noActivo', false)}}
                                    @endif
                                    @if($consulta->tipoSucursal=='activo')
                                    Prospecto
                                    {{Form::radio('tipoSucursal', 'prospecto', false)}}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    Activo {{Form::radio('tipoSucursal', 'activo', true)}}
                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    No Activo {{Form::radio('tipoSucursal', 'noActivo', false)}}
                                    @endif
                                    @if($consulta->tipoSucursal=='noActivo')
                                    Prospecto {{Form::radio('tipoSucursal', 'prospecto',false)}}&nbsp&nbsp&nbsp&nbsp
                                    Activo {{Form::radio('tipoSucursal', 'activo',false)}} &nbsp&nbsp&nbsp&nbsp
                                    No Activo {{Form::radio('tipoSucursal', 'noActivo',true)}}
                                    @endif
                                    @if($consulta->tipoSucursal=='')
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
                                    <input value="{{ $consulta->contactoVentas }}" name="contactoVentas" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Teléfono de Ventas:
                                <div>
                                    <input value="{{ $consulta->telVentas }}" name="telVentas" type="text" placeholder='Ejemplo: 5518273652'
                                        class="form-control"><br>
                                </div>
                            </div>
                            
                            <div class="col-md-6">Correo de ventas:
                                <div>
                                    <input value="{{ $consulta->correoVentas }}" name="correoVentas" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Extensión de Ventas:
                                <div>
                                    <input value="{{ $consulta->extenVentas }}" name="extenVentas" type="text" placeholder='Ejemplo: 52'
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
                                    <input value="{{ $consulta->contactoPlanta }}" name="contactoPlanta" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Teléfono del Gerente de Planta:
                                <div>
                                    <input value="{{ $consulta->telPlanta }}" name="telPlanta" type="text" placeholder='Ejemplo: 5518273652'
                                        class="form-control"><br>
                                </div>
                            </div>
                            
                            <div class="col-md-6">Correo del Gerente de Planta:
                                <div>
                                    <input value="{{ $consulta->correoPlanta }}" name="correoPlanta" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Extensión del Gerente de Planta:
                                <div>
                                    <input value="{{ $consulta->extenPlanta }}" name="extenPlanta" type="text" placeholder='Ejemplo: 52'
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
                                    <input value="{{ $consulta->contactoAlmacen }}" name="contactoAlmacen" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Teléfono del Almacen:
                                <div>
                                    <input value="{{ $consulta->telAlmacen }}" name="telAlmacen" type="text" placeholder='Ejemplo: 5518273652'
                                        class="form-control"><br>
                                </div>
                            </div>
                            
                            <div class="col-md-6">Correo del Almacen:
                                <div>
                                    <input value="{{ $consulta->correoAlmacen }}" name="correoAlmacen" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Extensión del Almacen:
                                <div>
                                    <input value="{{ $consulta->extenAlmacen }}" name="extenAlmacen" type="text" placeholder='Ejemplo: 52'
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
                                    <input value="{{ $consulta->contactoGerente }}" name="contactoGerente" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Teléfono del Gerente:
                                <div>
                                    <input value="{{ $consulta->telGerente }}" name="telGerente" type="text" placeholder='Ejemplo: 5518273652'
                                        class="form-control"><br>
                                </div>
                            </div>
                            
                            <div class="col-md-6">Correo del Gerente:
                                <div>
                                    <input value="{{ $consulta->correoGerente }}" name="correoGerente" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Extensión del Gerente:
                                <div>
                                    <input value="{{ $consulta->extenGerente }}" name="extenGerente" type="text" placeholder='Ejemplo: 52'
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
                                    <input value="{{ $consulta->contactoCompras }}" name="contactoCompras" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Teléfono de Compras:
                                <div>
                                    <input value="{{ $consulta->telCompras }}" name="telCompras" type="text" placeholder='Ejemplo: 5518273652'
                                        class="form-control"><br>
                                </div>
                            </div>
                            
                            <div class="col-md-6">Correo de Compras:
                                <div>
                                    <input value="{{ $consulta->correoCompras }}" name="correoCompras" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Extensión de Compras:
                                <div>
                                    <input value="{{ $consulta->extenCompras }}" name="extenCompras" type="text" placeholder='Ejemplo: 52'
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
                                    <input value="{{ $consulta->nombreCuentasPP }}" name="nombreCuentasPP" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Teléfono:
                                <div>
                                    <input value="{{ $consulta->telefonoCuentasPP }}" name="telefonoCuentasPP" type="text" placeholder='Ejemplo: 5518273652'
                                        class="form-control"><br>
                                </div>
                            </div>
                            
                            <div class="col-md-6">Correo:
                                <div>
                                    <input value="{{ $consulta->correoCuentasPP }}" name="correoCuentasPP" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Extensión:
                                <div>
                                    <input value="{{ $consulta->extencionCuentasPP }}" name="extencionCuentasPP" type="text" placeholder='Ejemplo: 52'
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
                                    <input value="{{ $consulta->nombreCuentasPC }}" name="nombreCuentasPC" type="text"  placeholder= 'Ejemplo: Joel Herrera'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Teléfono:
                                <div>
                                    <input value="{{ $consulta->telefonoCuentasPC }}" name="telefonoCuentasPC" type="text" placeholder='Ejemplo: 5518273652'
                                        class="form-control"><br>
                                </div>
                            </div>
                            
                            <div class="col-md-6">Correo:
                                <div>
                                    <input value="{{ $consulta->correoCuentasPC }}" name="correoCuentasPC" type="text" placeholder= 'Ejemplo: ejemplo@ejemplo.com'
                                        class="form-control"><br>
                                </div>
                            </div>
                            <div class="col-md-6 ml-auto">Extensión:
                                <div>
                                    <input value="{{ $consulta->extencionCuentasPC }}" name="extencionCuentasPC" type="text" placeholder='Ejemplo: 52'
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



@endforeach

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
}
</style>