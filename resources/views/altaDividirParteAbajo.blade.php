<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<ul class="nav nav-tabs">
    <li class="active"><a href="#1" data-toggle="tab"><label for="">Información de la parte</label></a></li>
</ul>

{{Form::open(['route' => 'guardarParteCreada','files'=>true])}}
{{Form::token()}}
<div class="tab-content">
    <div class="tab-pane active" id="1">
        <div class="container-fluid">
            <br>
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-3">GCM ID Parte:</div>
                        <div class="col-sm-9">
                            {{Form::hidden('numParte',$idParteSiguiente,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('idEquipos',$idEquipos,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::hidden('GCMid',$gcmid,['class' => 'form-control rounded-0', 'readonly'])}}
                            {{Form::text('GCMidParte',$GCMidParte,['class' => 'form-control rounded-0', 'readonly'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">*Nombre:</div>
                        <div class="col-sm-9">
                            @if($errors->first('nombreParte'))
                            <i> {{ $errors->first('nombreParte') }}</i>
                            @endif
                            {{Form::text('nombreParte',old ('nombreParte'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Flecha 234252','id'=>'req1'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">*Fecha de salida:</div>
                        <div class="col-sm-8">
                            @if($errors->first('fechaSalida'))
                            <i> {{ $errors->first('fechaSalida') }}</i>
                            @endif
                            <input type='date' name='fechaSalida' class="form-control rounded-0" id='req2'>
                        </div>
                    </div>
                    ¿Qué se realizará?:<textarea name="queReparacion" rows="2" class="form-control rounded-0"
                        placeholder="Ejemplo: Reparar Flecha que esta rota de la punta">{!! old('queReparacion') !!}</textarea>
                    <br>
                    <div class="form-group row">
                        <div class="col-sm-4">Fecha de recepción:</div>
                        <div class="col-sm-8">
                            <input type='date' name='fechaRecepcion' class="form-control rounded-0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">Fecha de entrega:</div>
                        <div class="col-sm-8">
                            <input type='date' name='fechaEntrega' class="form-control rounded-0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">Orden decompra:</div>
                        <div class="col-sm-8">
                            {{Form::text('ordenCompra',old ('ordenCompra'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: OC-000043'])}}
                        </div>
                    </div>
                    Archivo de la Ordem de compra:{{Form::file('archivoOrdenCompra',['class' => 'form-control rounded-0'])}}
                      <br>
                    <div class="form-group row">
                        <div class="col-sm-6">Monto:
                            {{Form::text('montoReparacion',old ('montoReparacion'),['id'=>'monto','class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 50000'])}}
                        </div>
                        <div class="col-sm-6">Tipo de moneda: <br>
                            MXN {{Form::radio('tipoMoneda','MXN')}}&nbsp;&nbsp;&nbsp;&nbsp;
                            USD {{Form::radio('tipoMoneda','USD')}}&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-2">*Taller:</div>
                        <div class="col-sm-10">
                            @if($errors->first('idTaller'))
                            <i> {{ $errors->first('idTaller') }}</i>
                            @endif
                            <select name="idTaller" id='req3' class="form-control">
                                <option value="">Seleccione un taller</option>
                                @foreach($comboTaller as $talleres)
                                @if($talleres->activo=="Si")
                                <option value='{{$talleres->idTaller}}'>{{$talleres->nombreTaller}} -
                                    {{$talleres->tipoTaller}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">¿Quién llevó?:</div>
                        <div class="col-sm-9">
                            {{Form::text('quienllevo',old ('quienllevo'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Pedro Perez Lopez'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">¿Quién recibió?:</div>
                        <div class="col-sm-8">
                            {{Form::text('quienRecibio',old ('quienRecibio'),['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: Carlos Robles Carmona'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">Prioridad:</div>
                        <div class="col-sm-10">
                            Urgente {{Form::radio('prioridadRep','Urgente')}}&nbsp;&nbsp;&nbsp;&nbsp;
                            Normal {{Form::radio('prioridadRep','Normal')}}&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">*Foto:</div>
                        <div class="col-sm-10">
                            {{Form::file('fotoParte',['class' => 'form-control rounded-0','id'=>'req4','onchange'=>'ValidarImagen(this);'])}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">*Estatus:</div>
                        <div class="col-sm-10">
                            <select name="idEstatus" id="idEstatus" class="form-control">
                            <option value="">Seleccione un estatus</option>
                                @foreach($comboEstatus as $estatus)
                                @if($estatus->activo=="Si")
                                <option value='{{$estatus->idEstatus}}'>{{$estatus->nombreEstatus}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-9">Seguimiento de entrega de técnico:</div>
                        <div class="col-sm-10">
                        {{Form::textarea('seguimientoTecnico',old ('seguimientoTecnico'),['class' => 'form-control rounded-0'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="" align="center">
                <button type="submit" class="btn btn-success btn-sm" id="guardarParte">
                    <span class="glyphicon glyphicon-plus-sign"></span> Crear parte
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#guardarParte").click(function(e) {
        let datos1 = $("#req1").val();
        let datos2 = $("#req2").val();
        let datos3 = $("#req3").val();
        let datos4 = $("#req4").val();
        let datos5 = $("#idEstatus").val();
        if (datos1 == '' || datos2 == '' || datos3 == '' || datos4 == '' || datos5 == '' ) {
            if (datos1 == '') {
            alert("El campo *Nombre es requerido");
            }
            if (datos2 == '') {
            alert("El campo *Fecha de salida es requerido");
            }
            if (datos3 == '') {
            alert("El campo *Taller es requerido");
            }
            if (datos4 == '') {
            alert("El campo *Foto es requerido");
            }
            if (datos5 == '') {
            alert("El campo *Estatus es requerido");
            }            
            
            e.preventDefault();
        }
    });
});
</script>
<script>
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
function ValidarImagen(obj){
    var uploadFile = obj.files[0];
    let datos4 = $("#req4").val();
    if (!(/\.(jpeg|png|gif|jpg)$/i).test(uploadFile.name)) {
        alert('El archivo seleccionado no es una imagen');
        $('#req4').val('');
    }                  
}
</script>