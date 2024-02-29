<h1>Costos de Envío</h1>
<br>
<form id="formularioCostoEnvio">
    @csrf
    <input type="hidden" value="{{ $consulta->idPartesVenta }}" name="idPartesVentaCE" id="idPartesVentaCE">
    <div class="form-group col-md-8">
        <div>
            <div class="row">
                <div class="sub-title">Compañía:</div>
                <div>
                    <select name="compañia" id="compañia" class="form-control" required>
                        <option value="">Seleccione Compañía
                        </option>
                        @foreach (['UPS', 'DHL', 'Paquete Express'] as $option)
                            <option value="{{ $option }}">{{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sub-title">Moneda:</div>
                <div>
                    <select name="monedaCE" id="monedaCE" class="form-control" required>
                        <option value="">Seleccione Moneda
                        </option>
                        @foreach (['MXN', 'USD', 'EUR'] as $option)
                            <option value="{{ $option }}">{{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sub-title">Monto:</div>
                <div>
                    {{ Form::text('montoCE', old('montoCE'), ['class' => 'form-control', 'required' => 'required', 'id' => 'montoCE']) }}
                    <span class="error-message" id="error-monto"></span>
                </div>

                <div class="sub-title">Servicio:</div>
                <div>
                    {{ Form::text('servicioCE', old('servicioCE'), ['class' => 'form-control', 'required' => 'required']) }}
                    <span class="error-message" id="error-servicio"></span>
                </div>

                <div class="sub-title">Fecha:</div>
                <div>
                    {{ Form::date('fechaCE', old('fechaCE'), ['class' => 'form-control', 'required' => 'required']) }}
                    <span class="error-message" id="error-fecha"></span>
                </div>
                <br><br>
                <div>
                    <button type="button" class="btn btn-primary" id="agregarCostoEnvio" disabled>
                        <span class="glyphicon glyphicon-plus-sign"></span>Agregar
                    </button>
                </div>
            </div>
            <br><br>
            <div class="form-group col-md-12">
                <div id="reporteCostosEnvio">
                    @if ($cuantosCE == 0)
                        <br>
                        <div class="alert alert-warning" role="alert" align="center">
                            <label for="">Sin archivos costos de envio agregados...</label>
                        </div>
                    @else
                        <br>
                        @include('reporteCostosDeEnvio')
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        var button = $('#agregarCostoEnvio');

        $('#formularioCostoEnvio').on('input change', function() {
            var form = $(this)[0];

            if (form.checkValidity()) {
                button.prop('disabled', false);
            } else {
                button.prop('disabled', true);
            }
        });
        
        $('#agregarCostoEnvio').click(function(e) {
            e.preventDefault();
            
            var montoCE = $('#montoCE').val();
            if (/^[0-9]*\.?[0-9]*$/.test(montoCE)) {
                var formData = $(this).closest('form').serialize();

                $("#reporteCostosEnvio").load('{{ url('guardarCostoEnvio') }}' + '?' + formData,
                    function() {
                        $("#formularioCostoEnvio")[0].reset();
                        button.prop('disabled', true);
                    });
            } else {
                alert('Por favor, ingrese un valor numérico en el campo Monto.');
            }
        });

        jQuery("#montoCE").on("input", function() {
        var montoValue = jQuery(this).val().replace(/[^0-9.]/g, '');
        jQuery(this).val(montoValue);
    });

    });
</script>
