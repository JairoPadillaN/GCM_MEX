@extends('principal')
@section('contenido')
<script type="text/javascript">
$(document).ready(function() {
    $(".cargar").click();
});
</script>

{{Form::open(['route' => 'editarSucursales','files'=>true])}}
{{Form::token()}}

<script type="text/javascript">
$(document).ready(function() {
    $("#guardar").click(function(e) {
    //    alert("hola");
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
        var marca7 = document.getElementById("marca7");
        var marca8 = document.getElementById("marca8");
        var marca9 = document.getElementById("marca9");
        var marca10 = document.getElementById("marca10");
        var marca11 = document.getElementById("marca11");
        var marca12 = document.getElementById("marca12");
        var marca13 = document.getElementById("marca13");
        var marca14 = document.getElementById("marca14");
        var marca15 = document.getElementById("marca15");
        var marca16 = document.getElementById("marca16");
        var marca17 = document.getElementById("marca17");
        var marca18 = document.getElementById("marca18");
        var marca19 = document.getElementById("marca19");
        var marca20 = document.getElementById("marca20");
        var marca21 = document.getElementById("marca21");
        var marca22 = document.getElementById("marca22");
        var marca23 = document.getElementById("marca23");
        var marca24 = document.getElementById("marca24");
        var marca25 = document.getElementById("marca25");
        var marca26 = document.getElementById("marca26");
        var marca27 = document.getElementById("marca27");
        var marca28 = document.getElementById("marca28");
        var marca29 = document.getElementById("marca29");
        var marca30 = document.getElementById("marca30");
        var marca31 = document.getElementById("marca31");
        var marca32 = document.getElementById("marca32");
        var marca32 = document.getElementById("marca33");

        if (check1.checked == false && check2.checked == false && check3.checked == false && check4.checked == false &&
            check5.checked == false && check6.checked == false && check7.checked == false && check8.checked == false) {
            alert ("El campo *Descripción de servicios a ofrecer es obligatorio.");
            e.preventDefault();
        }


        if (marca1.checked == false && marca2.checked == false && marca3.checked == false && marca4.checked == false &&
            marca5.checked == false && marca6.checked == false && marca7.checked == false && marca8.checked == false &&
            marca9.checked == false && marca10.checked == false && marca11.checked == false && marca12.checked == false &&
            marca13.checked == false && marca14.checked == false && marca15.checked == false && marca16.checked == false &&
            marca17.checked == false && marca18.checked == false && marca19.checked == false && marca20.checked == false &&
            marca21.checked == false && marca22.checked == false && marca23.checked == false && marca24.checked == false &&
            marca25.checked == false && marca26.checked == false && marca27.checked == false && marca28.checked == false &&
            marca29.checked == false && marca30.checked == false && marca31.checked == false && marca32.checked == false &&
            marca33.checked == false) {
            alert ("El campo *Marcas es obligatorio.");
            e.preventDefault();
        }

        
        
    });


    $("#guardarCont").click(function(e) {  
        // alert("ajhdjsa");
        var puesto = $("#puesto").val();
        var nombreCont = $("#nombreCont").val();
        
        if(puesto == '' && nombreCont == ''){
            if(puesto == ''){
                $("#puesto").css('border-color','red');
                $("#puesto").css('color','red');
                alert("Ingresa un puesto");
            }
            if(nombreCont == ''){
                $("#nombreCont").css('border-color','red');
                $("#nombreCont").css('color','red');
                alert("Ingresa un nombre");
            }
        }else{
            $("#reporteCont").load('{{url('contactoSuc')}}' + '?' + $(this).closest('form').serialize());
        }
    });


//     function validateForm() {
//   var x = document.forms["myForm"]["puesto"].value;
//   if (x == "") {
//     alert("Name must be filled out");
//     return false;
//   }
//     }

//     $("#guardarCont").on("click",validateForm);  



});
</script>

<script type="text/javascript">
$(function() {
    $('.borrarCont').click(
        function() {
            // alert("borrar");
            $("#reporteCont").load('{{url('borrarCont')}}' + '?' + $(this).closest('form').serialize());
        }
    );
});
</script>

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Modificación de sucursales<small> *Campos Requeridos</small></h1>
        </div>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home" aria-selected="true">Datos de la sucursal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Actividades</a>
                </li>    
            </ul>
            
            
            <div class="tab-content" id="pills-tabContent">
                <!-- inicia primer tab -->
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="sub-title">* Cliente:</div>
                                <div>
                                    @if($errors->first('idc'))
                                    <i> {{ $errors->first('idc') }}</i>
                                    @endif
                                    <select name='idc' class="form-control" rows="5" class="form-control rounded-0">
                                        <option value='{{$idclientesel}}'>{{$nomcli}}</option>
                                        @foreach($cliente as $cli)
                                            @if($cli->activo=="si")
                                            @if($cli->tipoCliente=="activo" || $cli->tipoCliente=="prospecto")
                                                <option value='{{$cli->idc}}'>{{$cli->razonSocial}}</option>
                                            @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="sub-title">* Sucursal:</div>
                                <div>
                                    @if($errors->first('sucursal'))
                                    <i> {{ $errors->first('sucursal') }}</i>
                                    @endif
                                    {{Form::hidden('idSucursal',$consulta->idSucursal)}}
                                    {{Form::text('sucursal',($consulta->sucursal),['class' => 'form-control', 'placeholder' => 'Ejemplo: Toluca'])}}
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="sub-title">* Clave para GCMid:</div>
                                <div>
                                    @if($errors->first('gcmid'))
                                    <i> {{ $errors->first('gcmid') }}</i>
                                    @endif
                                    @if($cuantosHay ==1 )
                                    <label for=""> El GCMid ya existe</label>
                                    @endif
                                    {{Form::text('gcmid',($consulta->gcmid),['class' => 'form-control', 'placeholder' => 'Ejemplo: CCI','onkeyup'=>'javascript:this.value=this.value.toUpperCase();','maxlength'=>'5'])}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="sub-title">* Colonia:</div>
                                <div>
                                    @if($errors->first('colonia'))
                                    <i> {{ $errors->first('colonia') }}</i>
                                    @endif
                                    {{Form::text('colonia',($consulta->colonia),['class' => 'form-control', 'placeholder' => 'Ejemplo: Guadalupe'])}}
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="sub-title">* Calle:</div>
                                <div>
                                    @if($errors->first('calle'))
                                    <i> {{ $errors->first('calle') }}</i>
                                    @endif
                                    {{Form::text('calle',($consulta->calle),['class' => 'form-control', 'placeholder' => 'Ejemplo: Guadalupe Victoria'])}}
                                </div>
                            </div>
                            <div class="col-lg-4">

                                <div class="sub-title">* Número:</div>
                                <div>
                                    @if($errors->first('num'))
                                    <i> {{ $errors->first('num') }}</i>
                                    @endif
                                    {{Form::text('num',($consulta->num),['class' => 'form-control', 'placeholder' => 'Ejemplo: 892'])}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="sub-title">Zona Geográfica:</div>
                                <div>
                                    <select name="zonaGeografica" class="form-control">
                                        @if($consulta->zonaGeografica=="")
                                        <option value="">Seleccione una zona geográfica</option>
                                        @else
                                            <option value="{{$consulta->zonaGeografica}}">{{$consulta->zonaGeografica}}</option>
                                        @endif
                                        <option value="Bajio">Bajio</option>
                                        <option value="Centro">Centro</option>
                                        <option value="Norte">Norte </option>
                                        <option value="Sur">Sur </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="sub-title">Giro:</div>
                                <div>
                                    <select name="giro" class="form-control">
                                        @if($consulta->giro=="")
                                        <option value="">Seleccione un giro</option>
                                        @else
                                            <option value="{{$consulta->giro}}">{{$consulta->giro}}</option>
                                        @endif
                                        <option value="">Seleccione un giro</option>
                                        <option value="Agricola">Agricola</option>
                                        <option value="Alimentos">Alimentos</option>
                                        <option value="Automotriz">Automotriz</option>
                                        <option value="Construcción">Construcción </option>
                                        <option value="Farmaceutica">Farmacéutica </option>
                                        <option value="Flexible Plastics">Flexible Plastics </option>
                                        <option value="Manufactura">Manufactura </option>
                                        <option value="Pesada">Pesada  </option>
                                        <option value="Mineria">Minería  </option>
                                        <option value="OEM Fabricantes de Equipo">OEM Fabricantes de Equipo</option>
                                        <option value="Original">Original</option>
                                        <option value="Packaging">Packaging</option>
                                        <option value="Plasticos">Plásticos</option>
                                        <option value="Quimica">Química</option>
                                        <option value="Rigid Plastics">Rigid Plastics</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                            <div class="sub-title">* Tipo de sucursal:</div>
                                <div>
                                    @if($errors->first('tipoSucursal'))
                                    <i> {{ $errors->first('tipoSucursal') }}</i>
                                    @endif
                                    
                                    @if($consulta->tipoSucursal=='prospecto')
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'prospecto', true)}} Prospecto</div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'activo', false)}} Activo</div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'noActivo', false)}} No Activo</div>
                                    @endif
                                    @if($consulta->tipoSucursal=='activo')
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'prospecto', false)}} Prospecto</div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'activo', true)}} Activo </div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'noActivo', false)}} No Activo</div>
                                    @endif
                                    @if($consulta->tipoSucursal=='noActivo')
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'prospecto',false)}} Prospecto</div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'activo',false)}} Activo </div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'noActivo',true)}} No Activo </div>
                                    @endif
                                    @if($consulta->tipoSucursal=='')
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'prospecto',false)}} Prospecto</div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'activo',false)}} Activo </div>
                                    <div class="col-lg-4">{{Form::radio('tipoSucursal', 'noActivo',false)}} No Activo </div>
                                    @endif
                                </div>

                            
                                
                            </div>
                            
                        </div> 

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="sub-title">Representante de cuenta:</div>
                                @if($errors->first('idu'))
                                <i> {{ $errors->first('idu') }}</i>
                                @endif
                                <div>
                                @if($stipo == 'Administrador')
                                    @if($cuantosUsuarios === 0)
                                        <select name='idu' id="idu" class="form-control">
                                        <option value="">Seleccione un usuario </option>
                                        @foreach($consultaUsu as $cU)
                                        <option value="{{$cU->idu}}">{{$cU->usuario}}</option>
                                        @endforeach
                                        </select>
                                    @else
                                        <select name='idu' id="idu" class="form-control">
                                        <option value="{{$idUsu}}">{{$nomus}} {{$aPat}} {{$aMat}} </option>
                                        @foreach($consultaUsu as $cU)
                                        <option value="{{$cU->idu}}">{{$cU->usuario}}</option>
                                        @endforeach
                                        </select>
                                    @endif

                                @else

                                    @if($cuantosUsuarios === 0)
                                    <input type="hidden" readonly class="form-control" value="{{$consulta->idu}}" name="idu" id="idu">    
                                    <input type="text" readonly class="form-control">
                                    @else
                                        <input type="hidden" readonly class="form-control" value="{{$consulta->idu}}" name="idu" id="idu">   
                                        <input type="text" value = '{{$nomus}} {{$aPat}} {{$aMat}}' readonly class="form-control">
                                        
                                    @endif
                                   
                                @endif

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-5">
                            <div class="sub-title">*Descripción de servicios a ofrecer:</div>
                                <div class="row"> 
                                    <div class="col-lg-5">
                                        @if(in_array('Venta de refacciones',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Venta de refacciones',true,['id'=>'check1'])}} Venta de refacciones <br>
                                        @else
                                            {{Form::checkbox('servicios[]', 'Venta de refacciones',false,['id'=>'check1'])}} Venta de refacciones <br>
                                        @endif

                                        @if(in_array('Refurbished',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Refurbished',true,['id'=>'check2'])}} Refurbished<br>                        
                                        @else
                                            {{Form::checkbox('servicios[]', 'Refurbished',false,['id'=>'check2'])}} Refurbished<br>
                                        @endif

                                        @if(in_array('Soporte técnico',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Soporte técnico',true,['id'=>'check3'])}} Soporte técnico<br>
                                        @else
                                            {{Form::checkbox('servicios[]', 'Soporte técnico',false,['id'=>'check3'])}} Soporte técnico<br>
                                        @endif

                                        @if(in_array('Servicios de cambio',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Servicios de cambio',true,['id'=>'check4'])}} Servicios de cambio    
                                        @else
                                            {{Form::checkbox('servicios[]', 'Servicios de cambio',false,['id'=>'check4'])}} Servicios de cambio    
                                        @endif
                                    </div>
                                    <div class="col-lg-7">
                                        @if(in_array('Reparaciones',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Reparaciones',true,['id'=>'check5'])}} Reparaciones<br>
                                        @else
                                            {{Form::checkbox('servicios[]', 'Reparaciones',false,['id'=>'check5'])}} Reparaciones<br>
                                        @endif

                                        @if(in_array('Diseño y Fabricación',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Diseño y Fabricación',true,['id'=>'check6'])}} Diseño y Fabricación<br>
                                        @else
                                            {{Form::checkbox('servicios[]', 'Diseño y Fabricación',false,['id'=>'check6'])}} Diseño y Fabricación<br>
                                        @endif

                                        @if(in_array('Mantenimiento Preventivo',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Mantenimiento Preventivo',true,['id'=>'check7'])}} Mantenimiento Preventivo<br> 
                                        @else
                                            {{Form::checkbox('servicios[]', 'Mantenimiento Preventivo',false,['id'=>'check7'])}} Mantenimiento Preventivo<br> 
                                        @endif

                                        @if(in_array('Mantenimiento Correctivo',$arrayCheckServicios))
                                            {{Form::checkbox('servicios[]', 'Mantenimiento Correctivo',true,['id'=>'check8'])}} Mantenimiento Correctivo<br>
                                        @else
                                            {{Form::checkbox('servicios[]', 'Mantenimiento Correctivo',false,['id'=>'check8'])}} Mantenimiento Correctivo<br>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                            <div class="sub-title">*Marcas:</div>
                                <div class="row"> 
                                    <div class="col-lg-4">
                                    <div class=""><strong>HUSKY: </strong></div>
                                        @if(in_array('AMK',$arrayCheckMarcas))
                                            {{Form::checkbox('marcas[]', 'AMK',true,['id'=>'marca33'])}} AMK<br>
                                        @else
                                            {{Form::checkbox('marcas[]', 'AMK',false,['id'=>'marca33'])}} AMK<br>
                                        @endif
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
                                </div></div>
                            
                            
                        </div>                 
                    </div>

                    <!--Aqui termina el div para Datos de Cliente-->

                    <!-- carrito -->

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">                
                                <div class="panel-heading">
                                    <div class="card-title">
                                        <!-- <div class="title">Datos de Venta</div> -->
                                    </div>
                                </div>
                        
                            <div class="panel-body">
                    <form method='POST' name="myForm" id="myForm" >
                                <input type="hidden" value="{{$idclientesel}}">
                                {{Form::hidden('idSucursal',$consulta->idSucursal)}}

                                    <div class="form-group col-md-3">
                                            <div class="sub-title">Puesto:</div>

                                            <div>
                                            @if($errors->first('puesto'))
                                            <i> {{ $errors->first('puesto') }}</i>
                                            @endif
                                            
                                            {{Form::text('puesto',($consulta->puesto),['class' => 'form-control', 'placeholder' => 'Ejemplo: Ventas', 'id'=>'puesto'])}}
                                        </div>
                                            
                                                <!-- <select name="puesto" id="puesto" rows="5" class="form-control rounded-0" >
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="Ventas">Ventas</option>
                                                    <option value="Planta">Planta</option>
                                                    <option value="Almacén">Almacén</option>
                                                    <option value="Gerente">Gerente</option>
                                                    <option value="Compras">Compras</option>
                                                    <option value="Cuentas por pagar">Cuentas por pagar</option>
                                                    <option value="Cuentas por cobrar">Cuentas por cobrar</option>
                                                </select> -->
                                    </div>
                                    <div class="form-group col-md-6">
                                    <br><br><br><br><br>
                                    </div>

                                    <div class="form-group col-md-6">
                                        
                                        <div class="sub-title">Contacto:</div>
                                        <div>
                                            @if($errors->first('nombreCont'))
                                            <i> {{ $errors->first('nombreCont') }}</i>
                                            @endif
                                            {{Form::text('nombreCont',($consulta->nombreCont),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera', 'id'=>'nombreCont'])}}
                                        </div>
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
                                        <div class="sub-title">Correo:</div>
                                        <div>
                                            @if($errors->first('correoCont'))
                                            <i> {{ $errors->first('correoCont') }}</i>
                                            @endif
                                            {{Form::email('correoCont',($consulta->correoCont),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com', 'id'=>'correoCont'])}}
                                        </div>

                                        <div class="sub-title">WhatsApp:</div>
                                        <div>
                                            @if($errors->first('whatsapp'))
                                            <i> {{ $errors->first('whatsapp') }}</i>
                                            @endif
                                            {{Form::text('whatsapp',($consulta->whatsapp),['class' => 'form-control', 'placeholder' => 'Ejemplo: 7221234567'])}}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono:</div>
                                        <div>
                                            @if($errors->first('telefonoCont'))
                                            <i> {{ $errors->first('telefonoCont') }}</i>
                                            @endif
                                            {{Form::text('telefonoCont',($consulta->telefonoCont),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div>

                                        <div class="sub-title">Extensión:</div>
                                        <div>

                                            {{Form::text('extension',($consulta->extension),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>

                                        <div class="sub-title">Contacto adicional:</div>
                                        <div>

                                            {{Form::text('contactoAdicional',($consulta->contactoAdicional),['class' => 'form-control', 'placeholder' => 'Ejemplo: '])}}
                                        </div>
                                    </div>
                                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                    <center><input type="button" class="btn btn-success" id="guardarCont" value="Guardar contacto"></input></center>
                                    
                                <br>
                                </form>
                                <div id="reporteCont">
                                    @if($cuantos == 0)
                                        <div class="alert alert-warning" role="alert" align="center">
                                                <label for="">No se han agregado contactos.</label>
                                        </div>
                                    @else
                                    <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                <thead>
                                                <tr style="background-color: #E8E8E8;">
                                                        <th>
                                                            <font SIZE=2>Puesto</font>
                                                        </th>
                                                        <th>
                                                            <font SIZE=2>Contacto</font>
                                                        </th>
                                                        <th>
                                                            <font SIZE=2>Teléfono</font>
                                                        </th>
                                                        <th>
                                                            <font SIZE=2>Correo</font>
                                                        </th>
                                                        <th>
                                                            <font SIZE=2>Extensión</font>
                                                        </th>
                                                         <th>
                                                            <font SIZE=2>WhatsApp</font>
                                                        </th>
                                                        <th>
                                                            <font SIZE=2>Contacto Adicional</font>
                                                        </th>
                                                        <th>
                                                            <font SIZE=2>Opciones</font>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($contactos as $cont)
                                                    <tr>
                                                        <td>{{$cont->puesto}}</td>
                                                        <td>{{$cont->nombreCont}}</td>
                                                        <td>{{$cont->telefonoCont}}</td>
                                                        <td>{{$cont->correoCont}}</td>
                                                        <td>{{$cont->extension}}</td>
                                                        <td>{{$cont->whatsapp}}</td>
                                                        <td>{{$cont->contactoAdicional}}</td>
                                                        <td align="center">
                                                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                                                name='frmdo{{$cont->idContactoSuc}}' id='frmdo{{$cont->idContactoSuc}}' target='_self'>
                                                                <input type='hidden' value='{{$cont->idContactoSuc}}' name='idContactoSuc' id='idContactoSuc'>
                                                                <input type='hidden' value='{{$cont->idSucursal}}' name='idSucursal' id='idSucursal'>
                                                                <button type="button" class="btn btn-sm btn-danger borrarCont">
                                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                @endif
                                </div>
                                </div>
                                <br>
                                
                            </div>
                    

                    </div>
                    </div>

                    <!--Aqui inicia el div para Datos de venta-->
                    <!-- <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default"> -->
                                <!--Aqui inicia el para el encbezado que dira "Datos de venta"-->
                                <!-- <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Datos de Venta</div>
                                    </div>
                                </div> -->
                                <!--Aqui termina el encabezado que dira "Datos de venta"-->

                                <!--Aqui inicia el cuadro de textoque se vera en el formulario-->
                                <!-- <div class="panel-body">
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Contacto de ventas:</div>
                                        <div>
                                            @if($errors->first('contactoVentas'))
                                            <i> {{ $errors->first('contactoVentas') }}</i>
                                            @endif
                                            {{Form::text('contactoVentas',($consulta->contactoVentas),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera'])}}
                                        </div> -->
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
                                        <!-- <div class="sub-title">Correo de Ventas:</div>
                                        <div>
                                            @if($errors->first('correoVentas'))
                                            <i> {{ $errors->first('correoVentas') }}</i>
                                            @endif
                                            {{Form::email('correoVentas',($consulta->correoVentas),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                        </div>
                                    </div> -->

                                    <!-- <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono de Ventas:</div>
                                        <div>
                                            @if($errors->first('telVentas'))
                                            <i> {{ $errors->first('telVentas') }}</i>
                                            @endif
                                            {{Form::text('telVentas',($consulta->telVentas),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div>

                                        <div class="sub-title">Extensión de Ventas:</div>
                                        <div>

                                            {{Form::text('extenVentas',($consulta->extenVentas),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!--Aqui termina el div para Datos de venta-->


                    <!-- <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default"> -->
                                <!--Aqui inicia el para el encbezado que dira "Da"-->
                                <!-- <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Gerente de Planta</div>
                                    </div>
                                </div> -->
                                <!--Aqui termina el encabezado que dira "Datos de venta"-->

                                <!--Aqui inicia el cuadro de textoque se vera en el formulario-->
                                <!-- <div class="panel-body">
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Contacto del Gerente de Planta:</div>
                                        <div>
                                            @if($errors->first('contactoPlanta'))
                                            <i> {{ $errors->first('contactoPlanta') }}</i>
                                            @endif
                                            {{Form::text('contactoPlanta',($consulta->contactoPlanta),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera'])}}
                                        </div> -->
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
                                        <!-- <div class="sub-title">Correo del Gerente de Planta:</div>
                                        <div>
                                            @if($errors->first('correoPlanta'))
                                            <i> {{ $errors->first('correoPlanta') }}</i>
                                            @endif
                                            {{Form::email('correoPlanta',($consulta->correoPlanta),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                        </div>
                                    </div> -->
<!-- 
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono del Gerente de Planta:</div>
                                        <div>
                                            @if($errors->first('telPlanta'))
                                            <i> {{ $errors->first('telPlanta') }}</i>
                                            @endif
                                            {{Form::text('telPlanta',($consulta->telPlanta),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div>

                                        <div class="sub-title">Extensión del Gerente de Planta:</div>
                                        <div>

                                            {{Form::text('extenPlanta',($consulta->extenPlanta),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default"> -->
                                <!--Aqui inicia el para el encbezado que dira "Datos de venta"-->
                                <!-- <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Datos del Almacen</div>
                                    </div>
                                </div> -->
                                <!--Aqui termina el encabezado que dira "Datos de venta"-->

                                <!--Aqui inicia el cuadro de textoque se vera en el formulario-->
                                <!-- <div class="panel-body">
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Contacto del Almacen:</div>
                                        <div>
                                            @if($errors->first('contactoAlmacen'))
                                            <i> {{ $errors->first('contactoAlmacen') }}</i>
                                            @endif
                                            {{Form::text('contactoAlmacen',($consulta->contactoAlmacen),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera'])}}
                                        </div> -->
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
                                        <!-- <div class="sub-title">Correo del Almacen:</div>
                                        <div>
                                            @if($errors->first('correoAlmacen'))
                                            <i> {{ $errors->first('correoAlmacen') }}</i>
                                            @endif
                                            {{Form::email('correoAlmacen',($consulta->correoAlmacen),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                        </div>
                                    </div> -->

                                    <!-- <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono del Almacen:</div>
                                        <div>
                                            @if($errors->first('telAlmacen'))
                                            <i> {{ $errors->first('telAlmacen') }}</i>
                                            @endif
                                            {{Form::text('telAlmacen',($consulta->telAlmacen),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div>

                                        <div class="sub-title">Extensión del Almacen:</div>
                                        <div>

                                            {{Form::text('extenAlmacen',($consulta->extenAlmacen),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!--Aqui inicia el div para Datos de Gerente-->
                    <!-- <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default"> -->
                                <!--Aqui inicia el para el encbezado que dira "Datos del Gerente"-->
                                <!-- <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Datos del Gerente </div>
                                    </div>
                                </div> -->
                                <!--Aqui termina el encabezado que dira "Datos de venta"-->

                                <!--Aqui inicia el cuadro de texto que se vera en el formulario-->
                                <!-- <div class="panel-body">
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Contacto del Gerente:</div>
                                        <div>
                                            @if($errors->first('contactoGerente'))
                                            <i> {{ $errors->first('contactoGerente') }}</i>
                                            @endif
                                            {{Form::text('contactoGerente',($consulta->contactoGerente),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera'])}}
                                        </div> -->
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
                                        <!-- <div class="sub-title">Email del Gerente:</div>
                                        <div>
                                            @if($errors->first('correoGerente'))
                                            <i> {{ $errors->first('correoGerente') }}</i>
                                            @endif
                                            {{Form::email('correoGerente',($consulta->correoGerente),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                        </div>
                                    </div> -->

                                    <!-- <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono del Gerente:</div>
                                        <div>
                                            @if($errors->first('telGerente'))
                                            <i> {{ $errors->first('telGerente') }}</i>
                                            @endif
                                            {{Form::text('telGerente',($consulta->telGerente),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div>

                                        <div class="sub-title">Extensión del Gerente:</div>
                                        <div>

                                            {{Form::text('extenGerente',($consulta->extenGerente),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!--Aqui termina el div para Datos de Compras-->

                    <!--Aqui inicia el div para Datos de venta-->
                    <!-- <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default"> -->
                                <!--Aqui inicia el para el encbezado que dira "Datos de Compras"-->
                                <!-- <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Datos de Compras</div>
                                    </div>
                                </div> -->
                                <!--Aqui termina el encabezado que dira "Datos de Compras"-->

                                <!--Aqui inicia el cuadro de texto que se vera en el formulario-->
                                <!-- <div class="panel-body">
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Contacto de Compras:</div>
                                        <div>
                                            @if($errors->first('contactoCompras'))
                                            <i> {{ $errors->first('contactoCompras') }}</i>
                                            @endif
                                            {{Form::text('contactoCompras',($consulta->contactoCompras),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera'])}}
                                        </div> -->
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->

                                        <!-- <div class="sub-title">Email de Compras:</div>
                                        <div>
                                            @if($errors->first('correoCompras'))
                                            <i> {{ $errors->first('correoCompras') }}</i>
                                            @endif
                                            {{Form::email('correoCompras',($consulta->correoCompras),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono de Compras:</div>
                                        <div>
                                            @if($errors->first('telCompras'))
                                            <i> {{ $errors->first('telCompras') }}</i>
                                            @endif
                                            {{Form::text('telCompras',($consulta->telCompras),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div>


                                        <div class="sub-title">Extensión de Compras:</div>
                                        <div>

                                            {{Form::text('extenCompras',($consulta->extenCompras),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>
                                    </div> -->

                                <!-- </div>
                            </div>
                        </div>
                    </div> -->

                    <!--Aqui inicia el div para Datos de CUENTAS PP-->
                    <!-- <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default"> -->
                                <!--Aqui inicia el para el encbezado que dira "Datos de Compras"-->
                                <!-- <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Cuentas por pagar</div>
                                    </div>
                                </div> -->
                                <!--Aqui termina el encabezado que dira "Datos de Compras"-->

                                <!--Aqui inicia el cuadro de texto que se vera en el formulario-->
                                <!-- <div class="panel-body">
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Nombre:</div>
                                        <div> -->
                                            <!--@if($errors->first('contactoCompras'))
                                    <i> {{ $errors->first('contactoCompras') }}</i>
                                    @endif-->
                                            <!-- {{Form::text('nombreCuentasPP',($consulta->nombreCuentasPP),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera'])}} -->
                                        <!-- </div> -->
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
<!-- 
                                        <div class="sub-title">Correo:</div>
                                        <div> -->
                                            <!--@if($errors->first('correoCompras'))
                                    <i> {{ $errors->first('correoCompras') }}</i>
                                    @endif-->
                                            <!-- {{Form::email('correoCuentasPP',($consulta->correoCuentasPP),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono:</div>
                                        <div> -->
                                            <!--@if($errors->first('telCompras'))
                                    <i> {{ $errors->first('telCompras') }}</i>
                                    @endif-->
                                            <!-- {{Form::text('telefonoCuentasPP',($consulta->telefonoCuentasPP),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div>


                                        <div class="sub-title">Extensión:</div>
                                        <div>

                                            {{Form::text('extencionCuentasPP',($consulta->extencionCuentasPP),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> -->


                    <!--Aqui termina el div para Datos de CUENTASPP-->


                    <!-- <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default"> -->
                                <!--Aqui inicia el para el encbezado que dira "Datos de Compras"-->
                                <!-- <div class="panel-heading">
                                    <div class="card-title">
                                        <div class="title">Cuentas por cobrar</div>
                                    </div>
                                </div> -->
                                <!--Aqui termina el encabezado que dira "Datos de Compras"-->

                                <!--Aqui inicia el cuadro de texto que se vera en el formulario-->
                                <!-- <div class="panel-body">
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Nombre:</div>
                                        <div> -->
                                            <!--@if($errors->first('contactoCompras'))
                                    <i> {{ $errors->first('contactoCompras') }}</i>
                                    @endif-->
                                            <!-- {{Form::text('nombreCuentasPC',($consulta->nombreCuentasPC),['class' => 'form-control', 'placeholder' => 'Ejemplo: Joel Herrera'])}}
                                        </div> -->
                                        <!--Aqui termina el cuadro de texto que se vera en el formulario-->
<!-- 
                                        <div class="sub-title">Correo:</div>
                                        <div> -->
                                            <!--@if($errors->first('correoCompras'))
                                    <i> {{ $errors->first('correoCompras') }}</i>
                                    @endif-->
                                            <!-- {{Form::email('correoCuentasPC',($consulta->correoCuentasPC),['class' => 'form-control', 'placeholder' => 'Ejemplo: ejemplo@ejemplo.com'])}}
                                        </div>
                                    </div> -->
<!-- 
                                    <div class="form-group col-md-6">
                                        <div class="sub-title">Teléfono:</div>
                                        <div> -->
                                            <!--@if($errors->first('telCompras'))
                                    <i> {{ $errors->first('telCompras') }}</i>
                                    @endif-->
                                            <!-- {{Form::text('telefonoCuentasPC',($consulta->telefonoCuentasPC),['class' => 'form-control', 'placeholder' => 'Ejemplo: 5518273652'])}}
                                        </div> -->

                                        <!-- <div class="sub-title">Extensión:</div>
                                        <div>
                                            {{Form::text('extencionCuentasPC',($consulta->extencionCuentasPC),['class' => 'form-control', 'placeholder' => 'Ejemplo: 52'])}}
                                        </div>
                                    </div>
                                    <br> -->
                                    <center>
                                        {{Form::submit('Guardar',['class' => 'btn  btn-default','id'=>'guardar'])}}
                                        <a href="{{asset('reporteSucursales')}}"><button type="button"
                                                class="btn btn-default">Cancelar</button></a>
                                    </center>
                                    <br><br>
                </div><!--Termina primer tab-->

                <!-- Tab de reporte de seguimiento -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"><br>
                <div class="panel-body">
                    <h3>Reporte de Actividades</h3><br><br>
                    
                    @if($cuantosSegui==0)
                    <div align="center" class="alert alert-info" role="alert">¡No tiene Actividades!</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #ABEBC6">
                                        <th>Folio</th>
                                        <th>Fecha de creación</th>
                                        <th>Asunto</th>
                                        <th>Creado por</th>
                                        <th>Cliente / Sucursal</th>
                                        <th>Periódo de atención</th>
                                        <th>Nivel de atencion</th>
                                        <th>Área responsable</th>
                                        <th>Actividad</th>
                                        <th>Avance</th>
                                        <th>Atendido por</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($seguimiento as $seg)
                                            <tr>

                                                <td>{{$seg->folio}}</td>
                                                <td>{{$seg->fechaCreacionFormato}}</td>
                                                <td>{{$seg->asunto}}</td>
                                                <td>{{$seg->nombreUsuario}} {{$seg->aPaterno}} {{$seg->aMaterno}} </td>
                                                <td>{{$seg->clienteSucursal}}</td>
                                                <td>{{$seg->fechaInicioFormato}} - {{$seg->fechaTerminoFormato}} </td>
                                                <td>{{$seg->importanciaSeguimiento}}</td>
                                                <td>{{$seg->nombreArea}}</td>
                                                <td>{{$seg->nombreActividad}}</td>
                                                <?php 
                                                
                                                        $atendidoPor =\DB::select("SELECT CONCAT(SUM(r),' de ',COUNT(*)) AS atendidopor
                                                                    FROM (SELECT a.idsegactividad, a.idu,seguimiento($seg->idSegActividad, a.idu ) , IF(seguimiento($seg->idSegActividad, a.idu )>=1,1,0) AS r
                                                                    FROM asignacionseguimientos AS a
                                                                    INNER JOIN usuarios AS u ON u.idu = a.idu
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        
                                                        $porcentaje= \DB::select("SELECT t1.idsegactividad,TRUNCATE(AVG(t1.porcentaje),0) AS porcentaje
                                                                    FROM
                                                                    (SELECT idsegactividad, idu,IF(ultimoporcentaje($seg->idSegActividad,idu)IS NULL,0,ultimoporcentaje($seg->idSegActividad,idu)) AS porcentaje
                                                                    FROM asignacionseguimientos
                                                                    WHERE idsegactividad= $seg->idSegActividad) AS t1
                                                                    GROUP BY t1.idsegactividad");
                                                        $cuantoPor=count($porcentaje);
                                                        $cuantoAtendido=count($atendidoPor);
                                                ?>

                                                @if($cuantoPor=='0')
                                                    <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                    </td>
                                                @else
                                                    @if($porcentaje[0]->porcentaje == 0)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">0% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 1 && $porcentaje[0]->porcentaje < 11)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 10%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 11 && $porcentaje[0]->porcentaje < 21)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 20%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 21 && $porcentaje[0]->porcentaje < 31)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 30%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 31 && $porcentaje[0]->porcentaje < 41)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 40%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 41 && $porcentaje[0]->porcentaje < 51)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 50%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 51 && $porcentaje[0]->porcentaje < 61)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 60%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 61 && $porcentaje[0]->porcentaje < 71)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 70%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje > 71 && $porcentaje[0]->porcentaje < 81)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 80%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 81 && $porcentaje[0]->porcentaje < 91)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 90%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    
                                                    @if($porcentaje[0]->porcentaje > 91 && $porcentaje[0]->porcentaje < 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 95%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                    @if($porcentaje[0]->porcentaje == 100)
                                                        <td>
                                                        <div class="progress">
                                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                                            aria-valuemin="0" aria-valuemax="0"
                                                            style="width: 100%">{{$porcentaje[0]->porcentaje}}% completado
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                    @endif
                                                @endif
                                                @if($cuantoAtendido=='0')
                                                <td>0 de 0</td>
                                                @else
                                                <td>{{$atendidoPor[0]->atendidopor}}</td>
                                                @endif
                                                <td>
                                                <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"><i
                                                            class="ace-icon fa fa-trash-o bigger-120"> Detalle</i>
                                                <!-- @if($stipo == 'Administrador')
                                                    @if($seg->activo=='Si')
                                                    <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"><i
                                                            class="ace-icon fa fa-trash-o bigger-120"> Detalle</i>
                                                    </a> 
                                                    <br>
                                                    <a href="{{URL::action('seguimientoActController@eliminarSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-danger"><i
                                                            class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                                    </a> <br>

                                                    <a href="{{URL::action('seguimientoActController@modificarSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
                                                        <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                                    </a>
                                                    @else
                                                    <a href="{{URL::action('seguimientoActController@restaurarSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        class="btn btn-xs btn-warning" style="width:67px">
                                                        Restaurar</a>
                                                    @endif

                                                    @else
                                                    <a href="{{URL::action('seguimientoActController@detalleSeg',['idSegActividad'=>$seg->idSegActividad])}}"
                                                        type="submit" class="btn btn-xs btn-success"><i
                                                            class="ace-icon fa fa-trash-o bigger-120"> Detalle</i>
                                                    </a> 

                                                    
                                                @endif -->
                                                </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div><!-- Termina Tab de seguimiento-->
            </div>
            </div>
    </div>
</div>
@stop