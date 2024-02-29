<script type="text/javascript">
$(document).ready(function() {
    
    $("#guardarModal").click(function(){
        
        // console.log("faaaaaaaaaaaaaaf");
		$("#formularioSucursal").validate({
            onclick: false, 
            onkeyup: false,
            ignore: [],
			rules: {
                idc: {required:true},
                sucursal: {required:true},
                gcmid: {required:true},
                colonia: {required:true},
                calle: {required:true},
                num: {required:true},
                telVentas: {maxlength:10, minlength:10},
                telPlanta: {maxlength:10, minlength:10},
                telAlmacen: {maxlength:10, minlength:10},
                telGerente: {maxlength:10, minlength:10},
                telCompras: {maxlength:10, minlength:10},
                telefonoCuentasPP: {maxlength:10, minlength:10},
                telefonoCuentasPC: {maxlength:10, minlength:10},
                extenVentas: {maxlength:10},
                extenPlanta: {maxlength:10},
                extenAlmacen: {maxlength:10},
                extenGerente: {maxlength:10},
                extenCompras: {maxlength:10},
                extencionCuentasPP: {maxlength:10},
                extencionCuentasPC: {maxlength:10},

                
			},
			messages: 
			{
                idc: {required: 'Elige un cliente'},
                sucursal: {required: 'Ingresa la Sucursal'},
                gcmid: {required: 'La clave GCMid es obligatorio'},
                colonia: {required: 'La colonia es obligatorio'},
                calle: {required: 'La calle es obligatorio'},
                num: {required: 'El número es obligatorio'},
                num: {required: 'El número es obligatorio'},
                telVentas: {maxlength: 'El número de teléfono no es valido: Máximo 10 digitos',
                            minlength: 'El número de teléfono no es valido: Minimo 10 digitos'},
                telPlanta: {maxlength: 'El número de teléfono no es valido: Máximo 10 digitos',
                            minlength: 'El número de teléfono no es valido: Minimo 10 digitos'},            
                telAlmacen: {maxlength: 'El número de teléfono no es valido: Máximo 10 digitos',
                            minlength: 'El número de teléfono no es valido: Minimo 10 digitos'},
                telGerente: {maxlength: 'El número de teléfono no es valido: Máximo 10 digitos',
                            minlength: 'El número de teléfono no es valido: Minimo 10 digitos'},
                telCompras: {maxlength: 'El número de teléfono no es valido: Máximo 10 digitos',
                            minlength: 'El número de teléfono no es valido: Minimo 10 digitos'},
                telefonoCuentasPP: {maxlength: 'El número de teléfono no es valido: Máximo 10 digitos',
                            minlength: 'El número de teléfono no es valido: Minimo 10 digitos'},
                telefonoCuentasPC: {maxlength: 'El número de teléfono no es valido: Máximo 10 digitos',
                            minlength: 'El número de teléfono no es valido: Minimo 10 digitos'},
                extenVentas: {maxlength: 'El número de extensión no es valido: Máximo 10 digitos'},
                extenPlanta: {maxlength: 'El número de extensión no es valido: Máximo 10 digitos'},
                extenAlmacen: {maxlength: 'El número de extensión no es valido: Máximo 10 digitos'},
                extenGerente: {maxlength: 'El número de extensión no es valido: Máximo 10 digitos'},
                extenCompras: {maxlength: 'El número de extensión no es valido: Máximo 10 digitos'},
                extencionCuentasPP: {maxlength: 'El número de extensión no es valido: Máximo 10 digitos'},
                extencionCuentasPC: {maxlength: 'El número de extensión no es valido: Máximo 10 digitos'},
                
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>

@foreach($consulta as $consulta)

    <i class="fa fa-chevron-circle-right" data-toggle="modal" id="botonModal" data-target="#exampleModalCenter{{$idSucursal}}"></i>

<!-- Modal -->

<div class="modal fade" id="exampleModalCenter{{$idSucursal}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                    <div class="title"><strong>Datos de la sucursal</strong></div><br>
                                </div>
                                <div class="col-md-6" class="modal-title"><strong>* Cliente</strong><br>
                                    <div>
                                    <input type="hidden" name="idc" id= 'idc' class="form-control" readonly="true"
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
                                <div class="col-md-6 ml-auto"><strong>* Sucursal</strong><br>
                                    <div>
                                        <input type="hidden" name="idSucursal" id= 'idSucursal' class="form-control" readonly="true"
                                            value="{{$consulta->idSucursal}}">
                                        <input value="{{ $consulta->sucursal }}" name="sucursal" type="text"
                                            class="form-control" name="sucursal"><br>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><strong>* Clave para GCMid</strong>
                                    <div>
                                        <input value="{{ $consulta->gcmid }}" name="gcmid" type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="5"
                                            class="form-control"><br>
                                    </div>

                                </div>
                                <div class="col-md-6"><strong>* Colonia</strong>
                                    <div>
                                        <input value="{{ $consulta->colonia }}" name="colonia" type="text"
                                            class="form-control"><br>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><strong>* Calle</strong>
                                    <div>
                                        <input value="{{ $consulta->calle }}" name="calle" type="text"
                                            class="form-control" class="form-control"><br>
                                    </div>
                                </div>
                                <div class="col-md-6 ml-auto"><strong>* Número</strong>
                                    <div>
                                        <input value="{{ $consulta->num }}" name="num" type="text"
                                            class="form-control"><br>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><strong>Giro</strong>
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
                                <div class="col-md-6 ml-auto"><strong>Tipo de sucursal</strong>
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
                                <div class="col-md-12"><strong>Marcas</strong>
                                    <div class="row">
                                        <div class="col-lg-4">
                                        <div class=""><strong>HUSKY: </strong></div>
                                            @if(in_array('BAUMULLER',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'BAUMULLER',true,['id'=>'marca1'])}} BAUMULLER<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'BAUMULLER',false,['id'=>'marca1'])}} BAUMULLER<br>
                                            @endif
                                            @if(in_array('BECKHOFF',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'BECKHOFF',true,['id'=>'marca2'])}} BECKHOFF<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'BECKHOFF',false,['id'=>'marca2'])}} BECKHOFF<br>
                                            @endif
                                            @if(in_array('BOSCH_REXROTH',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'BOSCH_REXROTH',true,['id'=>'marca3'])}} BOSCH REXROTH<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'BOSCH_REXROTH',false,['id'=>'marca3'])}} BOSCH REXROTH<br>
                                            @endif
                                            @if(in_array('FANUC',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'FANUC',true,['id'=>'marca4'])}} FANUC<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'FANUC',false,['id'=>'marca4'])}} FANUC<br>
                                            @endif
                                            @if(in_array('HUSKY',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'HUSKY',true,['id'=>'marca5'])}} HUSKY<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'HUSKY',false,['id'=>'marca5'])}} HUSKY<br>
                                            @endif
                                            
                                            @if(in_array('MOOG',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'MOOG',true,['id'=>'marca6'])}} MOOG<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'MOOG',false,['id'=>'marca6'])}} MOOG<br>
                                            @endif
                                            
                                            @if(in_array('SIEMENS',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'SIEMENS',true,['id'=>'marca7'])}} SIEMENS<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'SIEMENS',false,['id'=>'marca7'])}} SIEMENS<br>
                                            @endif
                                        </div>
                                        <div class="col-lg-4">
                                        <div class=""><strong>AUTOMATIZACIÓN Y CNC / ROBÓTICA:</strong></div>    
                                            @if(in_array('ABB',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'ABB',true,['id'=>'marca8'])}} ABB<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'ABB',false,['id'=>'marca8'])}} ABB<br>
                                            @endif   
                                            @if(in_array('ALLEN-BRADLEY',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'ALLEN-BRADLEY',true,['id'=>'marca9'])}} ALLEN-BRADLEY<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'ALLEN-BRADLEY',false,['id'=>'marca9'])}} ALLEN-BRADLEY<br>
                                            @endif   
                                            @if(in_array('DELTA',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'DELTA',true,['id'=>'marca10'])}} DELTA<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'DELTA',false,['id'=>'marca10'])}} DELTA<br>
                                            @endif   
                                            @if(in_array('FANUC',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'FANUC',true,['id'=>'marca11'])}} FANUC<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'FANUC',false,['id'=>'marca11'])}} FANUC<br>
                                            @endif   
                                            @if(in_array('KOLLMORGEN',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'KOLLMORGEN',true,['id'=>'marca12'])}} KOLLMORGEN<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'KOLLMORGEN',false,['id'=>'marca12'])}} KOLLMORGEN<br>
                                            @endif   
                                            @if(in_array('LENZE',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'LENZE',true,['id'=>'marca13'])}} LENZE<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'LENZE',false,['id'=>'marca13'])}} LENZE<br>
                                            @endif   
                                            @if(in_array('PILZ',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'PILZ',true,['id'=>'marca14'])}} PILZ<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'PILZ',false,['id'=>'marca14'])}} PILZ<br>
                                            @endif   
                                            @if(in_array('REXROTH_INDRAMAT',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'REXROTH_INDRAMAT',true,['id'=>'marca15'])}} REXROTH INDRAMAT<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'REXROTH_INDRAMAT',false,['id'=>'marca15'])}} REXROTH INDRAMAT<br>
                                            @endif   
                                            @if(in_array('SCHNEIDER',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'SCHNEIDER',true,['id'=>'marca16'])}} SCHNEIDER<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'SCHNEIDER',false,['id'=>'marca16'])}} SCHNEIDER<br>
                                            @endif   
                                            @if(in_array('SICK',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'SICK',true,['id'=>'marca17'])}} SICK<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'SICK',false,['id'=>'marca17'])}} SICK<br>
                                            @endif   
                                            @if(in_array('SIEMENS',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'SIEMENS',true,['id'=>'marca18'])}} SIEMENS<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'SIEMENS',false,['id'=>'marca18'])}} SIEMENS<br>
                                            @endif   
                                            @if(in_array('TOSHIBA',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'TOSHIBA',true,['id'=>'marca19'])}} TOSHIBA<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'TOSHIBA',false,['id'=>'marca19'])}} TOSHIBA<br>
                                            @endif   
                                            @if(in_array('YASKAWA',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'YASKAWA',true,['id'=>'marca20'])}} YASKAWA<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'YASKAWA',false,['id'=>'marca20'])}} YASKAWA<br>
                                            @endif   
                                        </div>
                                        <div class="col-lg-4">
                                        <div class=""><strong>HIDRÁULICA:</strong></div>
                                            @if(in_array('ATOS',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'ATOS',true,['id'=>'marca21'])}} ATOS<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'ATOS',false,['id'=>'marca21'])}} ATOS<br>
                                            @endif   
                                            @if(in_array('DANFOSS',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'DANFOSS',true,['id'=>'marca22'])}} DANFOSS<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'DANFOSS',false,['id'=>'marca22'])}} DANFOSS<br>
                                            @endif   
                                            @if(in_array('DENISON',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'DENISON',true,['id'=>'marca23'])}} DENISON<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'DENISON',false,['id'=>'marca23'])}} DENISON<br>
                                            @endif   
                                            @if(in_array('EATON_VICKERS',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'EATON_VICKERS',true,['id'=>'marca24'])}} EATON VICKERS<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'EATON_VICKERS',false,['id'=>'marca24'])}} EATON VICKERS<br>
                                            @endif   
                                            @if(in_array('KOMATSU',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'KOMATSU',true,['id'=>'marca25'])}} KOMATSU<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'KOMATSU',false,['id'=>'marca25'])}} KOMATSU<br>
                                            @endif   
                                            @if(in_array('MOOG_HIDRA',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'MOOG_HIDRA',true,['id'=>'marca26'])}} MOOG<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'MOOG_HIDRA',false,['id'=>'marca26'])}} MOOG<br>
                                            @endif   
                                            @if(in_array('CALZONI',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'CALZONI',true,['id'=>'marca33'])}} CALZONI<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'CALZONI',false,['id'=>'marca33'])}} CALZONI<br>
                                            @endif   
                                            @if(in_array('PARKER',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'PARKER',true,['id'=>'marca27'])}} PARKER<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'PARKER',false,['id'=>'marca27'])}} PARKER<br>
                                            @endif   
                                            @if(in_array('REXROTH',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'REXROTH',true,['id'=>'marca28'])}} REXROTH<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'REXROTH',false,['id'=>'marca28'])}} REXROTH<br>
                                            @endif   
                                            @if(in_array('SAUER_DANFOOS',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'SAUER_DANFOOS',true,['id'=>'marca29'])}} SAUER DANFOOS<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'SAUER_DANFOOS',false,['id'=>'marca29'])}} SAUER DANFOOS<br>
                                            @endif   
                                            @if(in_array('TOKYO_KEIKI',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'TOKYO_KEIKI',true,['id'=>'marca30'])}} TOKYO KEIKI<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'TOKYO_KEIKI',false,['id'=>'marca30'])}} TOKYO KEIKI<br>
                                            @endif   
                                            @if(in_array('VOLVO',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'VOLVO',true,['id'=>'marca31'])}} VOLVO<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'VOLVO',false,['id'=>'marca31'])}} VOLVO<br>
                                            @endif   
                                            @if(in_array('YUKEN',$arrayCheckMarcas))
                                                {{Form::checkbox('marcas[]', 'YUKEN',true,['id'=>'marca32'])}} YUKEN<br>
                                            @else
                                                {{Form::checkbox('marcas[]', 'YUKEN',false,['id'=>'marca32'])}} YUKEN<br>
                                            @endif   
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                            <div class="col-md-6 ml-auto"><strong>Servicios a ofrecer</strong><br>
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
                                    <div class="title"><strong>Datos de Venta</strong></div><br>
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
            alert ("¡¡Sucursal modificada correctamente!!" );
            $("#ido").load('{{url('comboCon')}}' + '?r=' + Date.now() + '&idSucursal=' + $("#idSucursal").val());
            
        }
    });
});
</script>

<style>
.fa-chevron-circle-right{
    color: blue;
    font-size: 30px;
}
input.error {
    border-color: #f00 !important;
}

small.required {
    color:#f00;
}

textarea.error {
    border-color: #f00 !important;
}

.error {
    color: #f00 !important;
}
</style>