<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal1" id="activar1"
    style="visibility:hidden">
</button>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal1" role="dialog"
    aria-labelledby="exampleModal1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="modal-title" id="exampleModal1Label">Estatus</h5>

            </div>
            <div class="modal-body">
                <form action="">
                    <div id="actualizandoEstatus">
                        <input type="hidden" name="idrfq" value="{{ $idrfq }}">
                        <input type="hidden" name="nombreTab" value="{{ $nombreTab }}">
                        <div class="form-group">
                            <label for="dni">Estatus:</label>
                            @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                                <select name='estatus' id='estatus' class="form-control">


                                    @if ($selectedEstatus == $estatus)
                                        <option hidden value=""> {{ $selectedEstatus }} </option>
                                    @else
                                        <!-- ← Ciclo para mandar el valor por defecto -->
                                        <option value="{{ $selectedEstatus }}"> Seleccione estatus </option>
                                    @endif


                                    <!-- ↓ Valores para la sesion de compras! ↓ -->

                                    <option value="Cotizando" {{ $estatus == 'Cotizando' ? 'selected' : '' }}>Cotizando
                                    </option>
                                    <option value="Revisar kardex" {{ $estatus == 'Revisar kardex' ? 'selected' : '' }}>
                                        Revisar kardex
                                    </option>
                                    <option value="Equipo Obsoleto"
                                        {{ $estatus == 'Equipo Obsoleto' ? 'selected' : '' }}>Equipo Obsoleto</option>
                                    <option value="En cálculos de importación"
                                        {{ $estatus == 'En cálculos de importación' ? 'selected' : '' }}>En cálculos de
                                        importación</option>
                                    <option value="Precio de lista en sistema"
                                        {{ $estatus == 'Precio de lista en sistema' ? 'selected' : '' }}>Precio de lista
                                        en
                                        sistema</option>
                                    <option value="PO enviada" {{ $estatus == 'PO enviada' ? 'selected' : '' }}>PO
                                        enviada
                                    </option>
                                    <option value="En tránsito a Laredo"
                                        {{ $estatus == 'En tránsito a Laredo' ? 'selected' : '' }}>En tránsito a Laredo
                                    </option>
                                    <option value="En tránsito a oficinas GCM"
                                        {{ $estatus == 'En tránsito a oficinas GCM' ? 'selected' : '' }}>En tránsito a
                                        oficinas GCM
                                    </option>
                                    <option value="Equipo protegido"
                                        {{ $estatus == 'Equipo protegido' ? 'selected' : '' }}>Equipo protegido
                                    </option>
                                    <option value="Cancelado" {{ $estatus == 'Cancelado' ? 'selected' : '' }}>Cancelado
                                    </option>
                                </select>
                                <!-- ↓ Valores para la sesion de Vendedor! ↓ -->
                            @elseif (Session::get('sesiontipo') == 'Vendedor')
                                <select name='estatus' id='estatus' class="form-control">

                                    @if ($selectedEstatus == $estatus)
                                        <option hidden value="{{ $selectedEstatus }}"> {{ $selectedEstatus }}
                                        </option>
                                    @else
                                        <!-- ← Ciclo para mandar el valor por defecto -->
                                        <option value="{{ $selectedEstatus }}"> Seleccione estatus </option>
                                    @endif

                                    <option value="Cotización enviada al cliente"
                                        {{ $estatus == 'Cotización enviada al cliente' ? 'selected' : '' }}>Cotización
                                        enviada al cliente</option>
                                    <option value="OC Recibida" {{ $estatus == 'OC Recibida' ? 'selected' : '' }}>OC
                                        Recibida</option>
                                    <option value="En tránsito al cliente"
                                        {{ $estatus == 'En tránsito al cliente' ? 'selected' : '' }}>En tránsito
                                        al cliente</option>
                                    <option value="Entregado" {{ $estatus == 'Entregado' ? 'selected' : '' }}>
                                        Entregado</option>
                                    <option value="En Pausa" {{ $estatus == 'En Pausa' ? 'selected' : '' }}>
                                        En pausa</option>
                                    <!-- Campo de fecha cotizacion aparece y desaparece segun la opcion se selccione↓ -->
                                    <select class="form-control" onchange="habilitarFecha()">
                                        <div id="campo-fecha" class="form-group">
                                            <label for="di">Fecha de entrega de cotización:</label>
                                            <div>
                                                <input type="date" name="fechaentrega" 
                                                id="fechaentrega" value="{{ $fechas ? $fechas : date('Y-m-d') }}" 
                                                class="form-control" min="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                            </div>
                                        </div>
                                    </select><!-- ← Termina el objeto select de el campo de fecha -->
                                </select>
                            @endif <!-- Terminan valores de Vendedor! -->

                        </div>
                        <div class="modal-footer">
                            <button type="button" id = 'guardastatus' class="btn btn-success btn-sm rounded-0 guardarDatosEstatus" disabled>
                                <span class="glyphicon glyphicon-plus-sign"></span> Guardar estatus
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
                    <div id="actualizacionEstatusOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Estatus modificado correctamente.
                            </center>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .sinBorder {
        border-style: none;
    }

    .modal-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .vertical-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #campo-fecha {
  display: none;
  display: visible;
 
}
</style>

<script>
    $(document).ready(function() {
		var statusanterior;
		statusanterior = $('#estatus').val();
		console.log(statusanterior);
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar1").click();
		
		
         $('#estatus').on('change', function() {
			 if( $('#estatus').val()!== statusanterior)
			 {
            $('#guardastatus').removeAttr("disabled");
			 }
			 else
			 {
			$('#guardastatus').attr("disabled","disabled");	 
			 }
        });
		
        // Función de ver campo de calendario según se seleccione la opción
        $('#estatus').on('change', function() {
            if ($(this).val() === 'Cotización enviada al cliente') {
                $('#campo-fecha').show();
            } else {
                $('#campo-fecha').hide();
            }
        });
    });
    // ↓ La funcion de aqui hace que no se muestre el campo de las fechas cuando se despliegue el modal  
    function habilitarFecha() {
        var estatusSelect = document.getElementById('estatus');
        var campoFecha = document.getElementById('campo-fecha');

        if (estatusSelect.value == 'Cotización enviada al cliente') {
            campoFecha.style.display = 'visible';
        } else {
            campoFecha.style.display = 'none';
        }
    }

    // ↓ Esta funcion es cuando este selccionado Cotizacion cliente se vea el select de fecha!
    var estatusSelect = document.getElementById('estatus');
    var campoFecha = document.getElementById('campo-fecha');

    // Verificar el valor seleccionado al abrir el modal
    if (estatusSelect.value === 'Cotización enviada al cliente') {
        campoFecha.style.display = 'block';
        campoFecha.querySelector('input').disabled = false;
    }

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }

    $(".guardarDatosEstatus").click(function(e) {
        e.preventDefault();
        // Obtener el nombre del Tab
        var nombreTab = $(this).closest('form').find('input[name="nombreTab"]').val();
        $.ajax({
            url: '{{ url('guardarDatosEstatus') }}',
            type: 'GET',
            data: $(this).closest('form').serialize(),
            success: function(response) {
                // Si todo sale bien disparar la funcion cargarTabla con su Tab correspondiente
                $(document).trigger('cargarTabla', nombreTab);

                $('#actualizandoEstatus').css('display', 'none');
                $('#actualizacionEstatusOk').css('display', 'block');
                $(".modal-open").css('padding-right', '');
                $(".modal-open").removeClass("modal-open");
                $(".modal-backdrop").removeClass("modal-backdrop fade in");
            },
            error: function(error) {
                // Maneja error
            }
        });
    });
</script>
