@extends('principal')
@section('contenido')
<script type="text/javascript">
$(document).ready(function() {
    
    $("#guardar").click(function(){
        
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
                
			},
			messages: 
			{
                idc: {required: 'Elige un cliente'},
                sucursal: {required: 'Ingresa la Sucursal'},
                gcmid: {required: 'La clave GCMid es obligatorio'},
                colonia: {required: 'La colonia es obligatorio'},
                calle: {required: 'La calle es obligatorio'},
                num: {required: 'El número es obligatorio'},                
            },
            errorPlacement: function (error, element) {
            alert(error.text());
        }
		});
       
    });
    
});
</script>

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
});
</script>

{{Form::open(['route' => 'GuardarSucursales','files'=>true, 'id' => 'formularioSucursal'])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Registro de nueva Sucursal<small> *Campos obligatorios</small></h1>
        </div>
        <div class="panel-body">
            <div class="card-title">
                <div class="title">Datos de la sucursal</div>
            </div>
            <div class="row" style="">
                <div class="col-lg-4">
                    <div class="sub-title">* Cliente:</div>
                    <div>
                        @if($errors->first('idc'))
                        <i> {{ $errors->first('idc') }}</i>
                        @endif
                        <select name='idc' id='oo' class="form-control">
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
                <div class="col-lg-4">
                    <div class="sub-title">* Sucursal:</div>
                    <div>
                        @if($errors->first('sucursal'))
                        <i> {{ $errors->first('sucursal') }}</i>
                        @endif
                        {{Form::text('sucursal',old ('sucursal'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Toluca'])}}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sub-title">* Clave para GCMid:</div>
                    <div>
                        @if($errors->first('gcmid'))
                        <i> {{ $errors->first('gcmid') }}</i>
                        @endif
                        @if($cuantosHay ==1 )
                        <label style="color:red"> El GCMid {{$gcmid}} ya existe</label>
                        @endif
                        {{Form::text('gcmid',old ('gcmid'),['class' => 'form-control', 'placeholder' => 'Ejemplo: CCI','onkeyup'=>'javascript:this.value=this.value.toUpperCase();','maxlength'=>'5'])}}
                    </div>
                </div>
            </div>
            <div class="row" style="">
                <div class="col-lg-4">
                    <div class="sub-title">* Colonia:</div>
                    <div>
                        @if($errors->first('colonia'))
                        <i> {{ $errors->first('colonia') }}</i>
                        @endif
                        {{Form::text('colonia',old ('colonia'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Guadalupe'])}}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sub-title">* Calle:</div>
                    <div>
                        @if($errors->first('calle'))
                        <i> {{ $errors->first('calle') }}</i>
                        @endif
                        {{Form::text('calle',old ('calle'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Guadalupe Victoria'])}}
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sub-title">* Número:</div>
                    <div>
                        @if($errors->first('num'))
                        <i> {{ $errors->first('num') }}</i>
                        @endif
                        {{Form::text('num',old ('num'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 892'])}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="sub-title">Zona Geográfica:</div>
                    <div>
                        <select name="zonaGeografica" class="form-control">
                            <option value="">Seleccione una zona geográfica</option>
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
                        <div class="col-lg-4"> {{Form::radio('tipoSucursal', 'prospecto')}} Prospecto</div>
                        <div class="col-lg-3"> {{Form::radio('tipoSucursal', 'activo', 'true')}}    Activo</div>
                        <div class="col-lg-4"> {{Form::radio('tipoSucursal', 'noActivo')}} No Activo</div>
                    </div>
                
                </div>
                
            </div>
            <div class="row" style="">
                <div class="col-lg-5">
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
                </div>
                <div class="col-lg-7"><div class="sub-title">*Marcas:</div>
                    <div class="row"> 
                        <div class="col-lg-4">
                        <div class=""><strong>HUSKY: </strong></div>
                            {{Form::checkbox('marcas[]', 'AMK',false,['id'=>'marca33'])}} AMK<br>
                            {{Form::checkbox('marcas[]', 'BAUMULLER',false,['id'=>'marca1'])}} BAUMULLER<br>
                            {{Form::checkbox('marcas[]', 'BECKHOFF',false,['id'=>'marca2'])}} BECKHOFF<br>
                            {{Form::checkbox('marcas[]', 'BOSCH_REXROTH',false,['id'=>'marca3'])}} BOSCH REXROTH<br>
                            {{Form::checkbox('marcas[]', 'FANUC',false,['id'=>'marca4'])}} FANUC<br>
                            {{Form::checkbox('marcas[]', 'HUSKY',false,['id'=>'marca5'])}} HUSKY<br>
                            {{Form::checkbox('marcas[]', 'MOOG',false,['id'=>'marca6'])}} MOOG<br>
                            {{Form::checkbox('marcas[]', 'SIEMENS',false,['id'=>'marca7'])}} SIEMENS<br>
                            
                            
                        
                        </div>
                        <div class="col-lg-4">
                        <div class=""><strong>AUTOMATIZACIÓN Y CNC / ROBÓTICA:</strong></div>       
                            {{Form::checkbox('marcas[]', 'ABB',false,['id'=>'marca8'])}} ABB <br>
                            {{Form::checkbox('marcas[]', 'ALLEN-BRADLEY',false,['id'=>'marca9'])}} ALLEN-BRADLEY <br>
                            {{Form::checkbox('marcas[]', 'DELTA',false,['id'=>'marca10'])}} DELTA <br>
                            {{Form::checkbox('marcas[]', 'FANUC',false,['id'=>'marca11'])}} FANUC <br>
                            {{Form::checkbox('marcas[]', 'KOLLMORGEN',false,['id'=>'marca12'])}} KOLLMORGEN <br>
                            {{Form::checkbox('marcas[]', 'LENZE',false,['id'=>'marca13'])}} LENZE <br>
                            {{Form::checkbox('marcas[]', 'PILZ',false,['id'=>'marca14'])}} PILZ <br>
                            {{Form::checkbox('marcas[]', 'REXROTH_INDRAMAT',false,['id'=>'marca15'])}} REXROTH INDRAMAT <br>
                            {{Form::checkbox('marcas[]', 'SCHNEIDER',false,['id'=>'marca16'])}} SCHNEIDER <br>
                            {{Form::checkbox('marcas[]', 'SICK',false,['id'=>'marca17'])}} SICK <br>
                            {{Form::checkbox('marcas[]', 'SIEMENS',false,['id'=>'marca18'])}} SIEMENS <br>
                            {{Form::checkbox('marcas[]', 'TOSHIBA',false,['id'=>'marca19'])}} TOSHIBA<br>                            
                            {{Form::checkbox('marcas[]', 'YASKAWA',false,['id'=>'marca20'])}} YASKAWA <br>
                        </div>
                        <div class="col-lg-4">
                        <div class=""><strong>HIDRÁULICA:</strong></div>
                            {{Form::checkbox('marcas[]', 'ATOS',false,['id'=>'marca21'])}} ATOS<br>
                            {{Form::checkbox('marcas[]', 'DANFOSS',false,['id'=>'marca22'])}} DANFOSS<br>
                            {{Form::checkbox('marcas[]', 'DENISON',false,['id'=>'marca23'])}} DENISON<br>
                            {{Form::checkbox('marcas[]', 'EATON_VICKERS',false,['id'=>'marca24'])}} EATON VICKERS<br>
                            {{Form::checkbox('marcas[]', 'KOMATSU',false,['id'=>'marca25'])}} KOMATSU<br>
                            {{Form::checkbox('marcas[]', 'MOOG_HIDRA',false,['id'=>'marca26'])}} MOOG<br>
                            {{Form::checkbox('marcas[]', 'CALZONI',false,['id'=>'marca27'])}} CALZONI<br>
                            {{Form::checkbox('marcas[]', 'PARKER',false,['id'=>'marca28'])}} PARKER<br>
                            {{Form::checkbox('marcas[]', 'REXROTH',false,['id'=>'marca29'])}} REXROTH<br>
                            {{Form::checkbox('marcas[]', 'SAUER_DANFOOS',false,['id'=>'marca30'])}} SAUER DANFOOS<br>
                            {{Form::checkbox('marcas[]', 'TOKYO_KEIKI',false,['id'=>'marca31'])}} TOKYO KEIKI<br>
                            {{Form::checkbox('marcas[]', 'VOLVO',false,['id'=>'marca32'])}} VOLVO<br>
                            {{Form::checkbox('marcas[]', 'YUKEN',false,['id'=>'marca33'])}} YUKEN<br>                       
                        </div>
                    </div>
                </div></div>
                
            </div>              

                    <center>
                        {{Form::submit('Guardar',['class' => 'btn  btn-success','id'=>'guardar'])}}
                    </center>
                    <br><br>
        </div>
    </div>
    <!--Aqui termina el div para Datos de Cliente-->                   
   

<style>
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

@stop