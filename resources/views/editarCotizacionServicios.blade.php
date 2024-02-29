<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>

{{Form::open(['route' => 'guardarEditarCotizacionServicios','files'=>true])}}
{{Form::token()}}

<ul class="nav nav-tabs">
    <li class="active" id='tab1' style="visibility:visible;"><a href="#1" data-toggle="tab"><label for="">Cotización</label></a></li>
</ul>

    <div class="tab-content">
        <div class="tab-pane active" id="1">
            <div id='a'></div>
            <div class="container-fluid">
                <br>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-4"><b>Registrado por:</b></div>
                            <div class="col-sm-8">
                                {{Form::hidden('idCotizacionServicios',$cotizacionEditar->idCotizacionServicios,['class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('idc',$cotizacionEditar->idc,['class' => 'form-control rounded-0', 'readonly'])}}                                
                                <select name="idu" class="form-control rounded-0" id="idu">
                                    <option value="{{$cotizacionEditar->idu}}">{{$cotizacionEditar->usuario}}</option>
                                    <option style="background-color: #dddddde0;" disabled>Administradores</option>
                                        @foreach($comboUsuariosAdmon as $comboUsuariosAdmon)
                                            <option value="{{$comboUsuariosAdmon->idu}}">{{$comboUsuariosAdmon->nombreUsuario}} {{$comboUsuariosAdmon->aPaterno}} {{$comboUsuariosAdmon->aMaterno}}</option>
                                        @endforeach
                                    <option style="background-color: #dddddde0;" disabled>Vendedores</option>
                                        @foreach($comboUsuariosVendedor as $comboUsuariosVendedor)
                                            <option value="{{$comboUsuariosVendedor->idu}}">{{$comboUsuariosVendedor->nombreUsuario}} {{$comboUsuariosVendedor->aPaterno}} {{$comboUsuariosVendedor->aMaterno}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="nombreUsuario" id="nombreUsuario" value="{{$cotizacionEditar->nombreUsuario}}">
                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Fecha:</b></div>
                            <div class="col-sm-9">
                                {{Form::date('fechaCotizacion',$cotizacionEditar->fechaCotizacion,['class' => 'form-control','id'=>'fechaCotizacion'])}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><b>*Sucursal:</b></div>
                            <div class="col-sm-9">
                                <select name="idSucursal" id='comboSucursal' class="form-control" readonly>
                                    <option value="{{$cotizacionEditar->idSucursal}}">{{$cotizacionEditar->sucursal}}</option>                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-5"><b>Número de cotización:</b></div>
                            <div class="col-sm-7 numeroCotizacionGenerado">
                                {{Form::text('numeroCotizacion',$cotizacionEditar->numeroCotizacion,['class' => 'form-control rounded-0', 'readonly'])}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><b>Dirigido a:</b></div>
                            <div class="col-sm-9">
                                <select name='dirigidoA' class="form-control" id="comboContacto">
                                    <option value="{{$cotizacionEditar->dirigidoA}}">{{$cotizacionEditar->dirigidoA}}</option>
                                        @foreach($consulta as $s)
                                            <option value='{{$s->nombreCont}}'>{{$s->nombreCont}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2"><b>Cc:</b></div>
                            <div class="col-sm-10">
                                <select name='conCopia' class="form-control">
                                    <option value="{{$cotizacionEditar->conCopia}}">{{$cotizacionEditar->conCopia}}</option>
                                        @foreach($comboConCopia as $comboConCopia)
                                            <option value='{{$comboConCopia->nombreCont}}'>{{$comboConCopia->nombreCont}} - {{$comboConCopia->sucursal}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3" align="center">archivo:
                                @foreach($fotoCotizacionEditar as $cotizacionFoto)
                                    @if($cotizacionFoto->archivoCotizacion =='Sin archivo')
                                        <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                        <input type="hidden" value="0" id="Asubido">
                                    @else
                                        <input type="hidden" value="1" id="Asubido">
                                        <a target="_blank" href="{{asset ('archivos/'.$cotizacionFoto->archivoCotizacion)}}">
                                            @if(strpos($cotizacionFoto->archivoCotizacion,'pdf'))
                                                <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                            @else
                                                <img src="{{asset ('archivos/'.$cotizacionFoto->archivoCotizacion)}}" height=80 width=80>
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-sm-9">
                                <b>Archivo de cotización:</b>{{Form::file('archivoCotizacion',['class' => 'form-control rounded-0','id'=>'archivoCotizacion','onchange'=>'ValidarImagen(this);'])}}
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="form-group row">
                            <div class="col-sm-6"><b>Monto:</b>
                                <?php $a= number_format ($cotizacionEditar->montoTotal,2)?>
                                {{Form::text('montoTotalVista',$a,['id'=>'monto','class' => 'form-control rounded-0', 'readonly'])}}
                                {{Form::hidden('montoTotal',$cotizacionEditar->montoTotal,['id'=>'monto','class' => 'form-control rounded-0', 'readonly'])}}                                    
                            </div>
                            <div class="col-sm-6" id="validarTipo"><b>*Tipo de moneda:</b> <br>
                                @if ($cotizacionEditar->tipoMoneda == 'MXN')
                                    MXN <input type="radio" name="tipoMoneda" id="MXN" class="tip" value="MXN" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD <input type="radio" name="tipoMoneda" id="USD" class="tip" value="USD" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div id="cambio" style="display:none;"><br>
                                        <b>Cambio del dolar:</b><br> 
                                        {{ Form::text('cambio',$cotizacionEditar->cambio,['id'=>'monto','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
                                    </div>
                                @elseif ($cotizacionEditar->tipoMoneda == 'USD')
                                    MXN <input type="radio" name="tipoMoneda" id="MXN" class="tip" value="MXN" >&nbsp;&nbsp;&nbsp;&nbsp;
                                    USD <input type="radio" name="tipoMoneda" id="USD" class="tip" value="USD" checked>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div id="cambio" style="display:block;"><br>
                                        <b>Cambio del dolar:</b><br> 
                                        {{ Form::text('cambio',$cotizacionEditar->cambio,['id'=>'monto','class' => 'form-control rounded-0','placeholder'=>'Ejemplo: 20.04'])}}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class='form-group row'id='validarNotas'>
                            <div class='col-md-12'><b>*Notas y condiciones comerciales:</b></div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar->check1 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check1','si',true,['id'=>'check1'])}}</div>                                    
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check1','si',false,['id'=>'check1'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck1',$cotizacionEditar->textoCheck1,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check2 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check2','si',true,['id'=>'check2'])}}</div>                                   
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check2','si',false,['id'=>'check2'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck2',$cotizacionEditar->textoCheck2,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check3 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check3','si',true,['id'=>'check3'])}}</div>                                    
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check3','si',false,['id'=>'check3'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck3',$cotizacionEditar->textoCheck3,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check4 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check4','si',true,['id'=>'check4'])}}</div>                                    
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check4','si',false,['id'=>'check4'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck4',$cotizacionEditar->textoCheck4,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    @if($cotizacionEditar->check5 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check5','si',true,['id'=>'check5'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check5','si',false,['id'=>'check5'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck5',$cotizacionEditar->textoCheck5,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check6 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check6','si',true,['id'=>'check6'])}}</div>                                    
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check6','si',false,['id'=>'check6'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck6',$cotizacionEditar->textoCheck6,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check7 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check7','si',true,['id'=>'check7'])}}</div>
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check7','si',false,['id'=>'check7'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck7',$cotizacionEditar->textoCheck7,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>                                    
                                </div>
                                <div class="row">
                                    @if($cotizacionEditar->check8 == "si")
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check8','si',true,['id'=>'check8'])}}</div>                                    
                                    @else
                                    <div class="col-md-1" style="padding-top:8px;">{{ Form::checkbox('check8','si',false,['id'=>'check8'])}}</div>
                                    @endif
                                    <div class="col-md-10">
                                        {{ Form::text('textoCheck8',$cotizacionEditar->textoCheck8,['class' => 'form-control rounded-0 notas','style'=>'margin: 2px;'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='' id="validarEstatus">
                            <div class=''><b>*Estatus de cotización:</b></div>
                            <div class="row">
                                @if($cotizacionEditar->estatus == "Pendiente de autorización")
                                <div class="form-group col-md-7">
                                    <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización',['checked' => 'checked'])}} Pendiente de autorización</div>
                                    <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                </div>
                                <div class="form-group col-md-5">
                                    <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                </div>
                                @elseif($cotizacionEditar->estatus == "En revisión")
                                <div class="form-group col-md-7">
                                    <div>{{Form::radio('estatus','En revisión',['checked' => 'checked'])}} En revisión
                                    </div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                    </div>
                                    <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                </div>
                                <div class="form-group col-md-5">
                                    <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>
                                </div>
                                @elseif($cotizacionEditar->estatus == "Cancelada")
                                <div class="form-group col-md-7">
                                    <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                    </div>
                                    <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra"> Aceptada con orden de compra</div>
                                </div>
                                <div class="form-group col-md-5">
                                    <div>{{Form::radio('estatus','Cancelada',['checked' => 'checked'])}} Cancelada</div>
                                </div>
                                @else
                                <div class="form-group col-md-7">
                                    <div>{{Form::radio('estatus','En revisión')}} En revisión</div>
                                    <div>{{Form::radio('estatus','Pendiente de autorización')}} Pendiente de autorización
                                    </div>
                                    <div><input type="radio" name="estatus" id="aceptada" value="Aceptada con orden de compra" checked> Aceptada con orden de compra</div>

                                </div>
                                <div class="form-group col-md-5">
                                    <div>{{Form::radio('estatus','Cancelada')}} Cancelada</div>                                    
                                </div>
                                @endif
                            </div>
                            <div class=""style="visibility:hidden;" id="divOrdenCompra">
                                <b>*Orden de compra:</b><input class="form-control" placeholder="Ejemplo: AC286639" name="ordenCompra" type="text">
                                <b>*Orden de compra:</b>{{ Form::file('archivo',['class' => 'form-control rounded-0','id'=>'archivoOrden','onchange'=>'ValidarImagen2(this);'])}}
                            </div>
                        </div>
                    </div>                     
                </div>
                <div id='botonParaGuardar' style="text-align:center;" >
                    {{ Form::submit('Guardar',['class' => 'btn  btn-success','id'=>'guardar'])}}
                    <button type="button" class="btn btn-danger" id='cancelar'>
                        Cancelar
                    </button>                    
                </div>
            </div>
        </div>
    </div>

<script>

    $("#idu").change(function(e) {
        var comboUsuario = document.getElementById("idu");
        var selectedUsuario = comboUsuario.options[comboUsuario.selectedIndex].text;
        document.getElementById("nombreUsuario").value = selectedUsuario;        
    });

    $("#monto").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "keyup": function(event) {
            $(event.target).val(function(index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });
</script>

<script>
    function ValidarImagen(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivoCotizacion").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivoCotizacion').val('');
        }
    }
</script>
<script>
    function ValidarImagen2(obj) {
        var uploadFile = obj.files[0];
        let datos4 = $("#archivoOrden").val();
        if (!(/\.(jpeg|png|gif|jpg|pdf)$/i).test(uploadFile.name)) {
            alert('El archivo seleccionado no es valido.');
            $('#archivoOrden').val('');
        }
    }
</script>

<script>
    // $("#fechaCotizacion").change(function(e) {
    //     let fechaCotizacion = $("#fechaCotizacion").val();
    //     let comboSucursal = $("#comboSucursal").val();

    //     if (fechaCotizacion != '' && comboSucursal != '') {
    //         $(".numeroCotizacionGenerado").load('{{url('generarNumeroCotizacionEditar')}}' + '?' + $(this).closest('form').serialize());
    //     }
    // });
    $("#MXN").click(function(){ 
        $('#cambio').css('display', 'none');        
    })
    
    $("#USD").click(function(){
        $('#cambio').css('display', 'block');  
    })

    $("#cancelar").click(function(){
        $("#altaVersion").empty();
        $('.actualizar').attr("disabled", false);
        $('.editar').attr("disabled", false);
        location.reload();
    });
    $("#guardar").click(function(e){

        let fechaCotizacion = $("#fechaCotizacion").val();
        let comboSucursal = $("#comboSucursal").val();
        if (fechaCotizacion == '' || comboSucursal == ''){
            if(fechaCotizacion == ''){
                $("#fechaCotizacion").css('border-color', 'red');
                $("#fechaCotizacion").css('color', 'red');
                alert('El campo *Fecha es obligatorio.');
                e.preventDefault();    
            }
            if(comboSucursal == ''){
                $("#comboSucursal").css('border-color', 'red');
                $("#comboSucursal").css('color', 'red');
                alert('El campo *Sucursal es obligatorio.');
                e.preventDefault();    
            }            
        }else if($("[name='tipoMoneda']:checked").val()==undefined){
            $("#validarTipo").css("color", "red");
            alert('El campo *Tipo de moneda es obligatorio.');
            e.preventDefault();    
        }else if($("[type='checkbox']:checked").val()==undefined){
            $("#validarNotas").css("color", "red");
            $(".notas").css('border-color', 'red');
            alert('El campo *Notas y condiciones comerciales es obligatorio.');
            e.preventDefault();    
        }else if($("[name='estatus']:checked").val()==undefined){
            $("#validarEstatus").css("color", "red");
            alert('El campo *Estatus es obligatorio.');                                            
            e.preventDefault();    
        }                           
            
               
    });
    $("#aceptada").click(function(e) {
        // let archivoCotizacion = $("#archivoCotizacion").val();
        // let archivoCotizacion2 = $("#Asubido").val();
        // if (archivoCotizacion == '' && archivoCotizacion2 == 0){
        //     $("#archivoCotizacion").css('border-color', 'red');
        //     $("#archivoCotizacion").css('color', 'red');            
        //     alert('El campo *Archivo de cotización es obligatorio.');
        //     e.preventDefault(); 
        // }else{
            $('#divOrdenCompra').css('visibility', 'visible');
        // }
    });
</script>

