<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<form>
{{Form::open(['route' => 'guardarPac','files'=>true])}}
{{Form::token()}}


<div class="container-fluid">
    <br>
    <div class="row">
        <div class="form-group col-md-12">
            <div class="form-group row">
                <div class="sub-title">*Marca:</div>
                <div>
                    @if($errors->first('idMarcaRefa'))
                    <i> {{ $errors->first('idMarcaRefa') }}</i>
                    @endif

                    <input type="hidden" name='idPaquete' id='idPaquete' class="form-control rounded-0"
                        value='{{$idPaquete}}'>
                    <select name='idMarcaRefa' class="form-control" id="idMarcaRefa">
                        <option value="">Seleccione una marca</option>
                        @foreach($marca as $mr)
                        @if($mr->activo=="Si")
                        <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                        @endif
                        @endforeach

                    </select>
                </div>
                <div class="sub-title">*Tipo SKU:</div>
                <div>
                    @if($errors->first('idTipoRefacciones'))
                    <i> {{ $errors->first('idTipoRefacciones') }}</i>
                    @endif
                    <select name='idTipoRefacciones' class="form-control" id="idTipoRefacciones">
                        <option value="">Seleccione un tipo de refacción</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">*Número SKU:</div>
                        <div>
                            @if($errors->first('numeroParte'))
                            <i> {{ $errors->first('numeroParte') }}</i>
                            @endif
                            <div id='parte'>
                                <select name='idPartesVenta' id="idPartesVenta" class="form-control mi-selector">
                                    <option value="">Seleccione un número de parte</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Código: </div>
                        <div id='codigoRefaccion'>
                            {{Form::text('codigo', old('codigo'),['class' => 'form-control', 'id' => 'codigo', 'readonly'])}}
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Número de serie:</div>
                        <div id="serie">
                            {{Form::text('serie',old ('serie'),['class' => 'form-control', 'id'=>'serie', 'readonly'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Modelo: </div>
                        <div id="modelo">
                            {{Form::text('modelo',old('modelo'),['class' => 'form-control', 'id'=>'modelo', 'readonly'])}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Precio de venta (Pesos):</div>
                        <div id="precioVentaPe">
                            {{Form::text('precioVentaPe',old ('precioVentaPe'),['class' => 'form-control', 'id'=>'precioVentaPe', 'readonly'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="sub-title">Precio de venta (Dolares): </div>
                        <div id="precioVentaDol">
                            {{Form::text('precioVentaDol',old('precioVentaDol'),['class' => 'form-control', 'id'=>'precioVentaDol', 'readonly'])}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="sub-title">Aparece SKU en cotización:</div>
                        <div>
                            Si {{Form::radio('apareceCoti','Si',true)}}&nbsp;&nbsp;&nbsp;
                            No {{Form::radio('apareceCoti','No')}}&nbsp;&nbsp;&nbsp;
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="" align="center">
            <button type="button" class="btn btn-success btn-sm" id="agregar">
                <span class="glyphicon glyphicon-plus-sign"></span> Agregar
            </button>
        </div>
    </div>

    <div id="reportePac">
       
    </div>

</div>
</form>

<script>
$("#idMarcaRefa").change(function() {
    $('#codigo').val('');
    $("#idTipoRefacciones").load('{{url('comboTipoRef')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
    $('.mi-selector').empty('');
});

$("#agregar").click(function() {
    // alert("boton");
    $("#reportePac").load('{{url('guardarPac')}}' + '?' + $(this).closest('form').serialize());
});


$("#idTipoRefacciones").change(function() {
    $("#idPartesVenta").load('{{url('comboNumeroSKU')}}' + '?r=' + Date.now() + '&idTipoRefacciones=' + this.options[this.selectedIndex].value);
    $('.mi-selector').select2();
});

$("#idPartesVenta").change(function() {
    $("#codigoRefaccion").load('{{url('codigo')}}' + '?' + $(this).closest('form').serialize());
    $("#serie").load('{{url('serie')}}' + '?' + $(this).closest('form').serialize());
    $("#modelo").load('{{url('modelo')}}' + '?' + $(this).closest('form').serialize());
    $("#precioVentaPe").load('{{url('precioVentaPe')}}' + '?' + $(this).closest('form').serialize());
    $("#precioVentaDol").load('{{url('precioVentaDol')}}' + '?' + $(this).closest('form').serialize());
});
</script>