<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal1" id="activar1"
    style="visibility:hidden"></button>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal1" tabindex="-1" role="dialog"
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
                <h5 class="modal-title" id="exampleModal1Label">Editar producto</h5>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" id="formEditarProductoOrden">
                    @csrf
                    <div id="actualizandoOrden">
                        <input type="hidden" name="idProductoM" value="{{ $idProducto }}">
                        <input type="hidden" name="idOrdenM" value="{{ $idOrden }}">
                        <input type="hidden" id="idPartesVentaM" name="idPartesVentaM" value="{{ $idPartesVenta }}">
                        <div class="form-group">
                            <div class="sub-title">Seleccionar tipo de entrada: </div>
                            <div>
                                No. De solicitud
                                {{ Form::radio('tipoEntradaM', '1', false, ['disabled' => 'disabled']) }}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Compra única {{ Form::radio('tipoEntradaM', '2', false, ['disabled' => 'disabled']) }}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Equipos {{ Form::radio('tipoEntradaM', '3', true) }}
                            </div>

                            <input type="hidden" class="form-control" id="facturaValue" name="facturaValue" readonly
                                value="{{ $idFactura ? $idFactura : null }}">
                            <div>
                                <div class="sub-title">SKU:</div>
                                <div>
                                    <input type="text" class="form-control" id="skuM" name="skuM" readonly
                                        value="{{ $consulta->codigo }}">
                                </div>
                            </div>
                            <div>
                                <div class="sub-title">Descripción:</div>
                                <div>
                                    <input type="text" class="form-control" id="descripcionM" name="descripcionM"
                                        readonly value="{{ $consulta->nombreRefaccion }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Precio:</div>
                                    <div>
                                        <input type="text" class="form-control" id="precioOrdenM" name="precioOrdenM"
                                            onkeyup="calcularTotalM()" onblur="calcularTotalM()"
                                            value="{{ $consulta->precioOrden }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">Cantidad:</div>
                                    <div>
                                        <input type="text" class="form-control" id="cantidadOrdenM"
                                            name="cantidadOrdenM" onkeyup="calcularTotalM()" onblur="calcularTotalM()"
                                            value="{{ $consulta->cantidadOrden }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">IVA (%):</div>
                                    <div>
                                        <input type="text" class="form-control" id="ivaOrdenM" name="ivaOrdenM"
                                            onkeyup="calcularTotalM()" onblur="calcularTotalM()"
                                            value="{{ $consulta->ivaOrden == '' ? 0 : $consulta->ivaOrden }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">ISR (%):</div>
                                    <div>
                                        <input type="text" class="form-control" id="isrOrdenM" name="isrOrdenM"
                                            onkeyup="calcularTotalM()" onblur="calcularTotalM()"
                                            value="{{ $consulta->isrOrden == '' ? 0 : $consulta->isrOrden }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sub-title">Retención (%):</div>
                                    <div>
                                        <input type="text" class="form-control" id="retencionM" name="retencionM"
                                            onkeyup="calcularTotalM()" onblur="calcularTotalM()"
                                            value="{{ $consulta->retencion == '' ? 0 : $consulta->retencion }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-title">TOTAL:</div>
                                    <div>
                                        <input type="text" class="form-control" id="totalCalculoM"
                                            name="totalCalculoM" readonly value="{{ $consulta->precioProducto }}">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="sub-title">*Vendor part number:</div>
                                <div>
                                    <input type="text" class="form-control" id="vendorPartNumberM"
                                        name="vendorPartNumberM" value="{{ $consulta->vendorPartNumber }}">
                                </div>
                            </div>
                            <div>
                                <div class="sub-title">Notas del producto:</div>
                                <textarea id="notasProductoM" name="notasProductoM" rows="5" class="form-control">{{ $consulta->notasProducto }}</textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-sm rounded-0 guardarProd">
                                <span class="glyphicon glyphicon-plus-sign"></span> Guardar
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                                <span aria-hidden="true">Cerrar</span>
                            </button>
                        </div>
                    </div>
                    <div id="actualizacionOk" style="display:none;">
                        <br>
                        <div class="alert alert-success" role="alert">
                            <center>
                                Producto modificado correctamente.
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

    .custom-row {
        display: flex;
        justify-content: space-around;
    }

    .file-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .file-input-wrapper {
        display: flex;
        align-items: center;
    }

    .file-input {
        display: flex;
        align-items: center;
    }

    .file-input>input {
        margin-right: 5px;
    }
</style>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar1").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }

    function calcularTotalM() {
        var cantidadOrdenM = $("#cantidadOrdenM").val();
        var precioOrdenM = $("#precioOrdenM").val();
        var ivaOrdenM = $("#ivaOrdenM").val();
        var isrOrdenM = $("#isrOrdenM").val();
        var retencionM = $("#retencionM").val();
        var subtotalM = precioOrdenM * cantidadOrdenM;
        var ivaM = ivaOrdenM / 100 * subtotalM;
        var isrM = isrOrdenM / 100 * subtotalM;
        retencionM = retencionM / 100 * (subtotalM + ivaM + isrM);
        var totalM = (subtotalM + ivaM + isrM) - retencionM;
        totalFormatoM = totalM.toFixed(2);
        if (cantidadOrdenM > 0 && precioOrdenM > 0) {
            // alert("El total es: " + total);
            document.getElementById("totalCalculoM").value = totalFormatoM;
        }
    }

    $("#totalCalculoM, #vendorPartNumberM, #precioOrdenM, #cantidadOrdenM" ).on("input", function() {
        if ($("#totalCalculoM").val() === '' || $("#vendorPartNumberM").val() === '' || $("#precioOrdenM")
            .val() === '' || $("#cantidadOrdenM").val() === '') {
            $(".guardarProd").prop("disabled", true);
        } else {
            $(".guardarProd").prop("disabled", false);
        }
    });

    $(".guardarProd").click(function(e) {
        e.preventDefault();

        var form = $("#formEditarProductoOrden")[0];
        var formData = new FormData(form);

        $.ajax({
            url: '{{ route('guardarModifProductoOrden') }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#reporteProductos').html(response);
                $('#actualizandoOrden').css('display', 'none');
                $('#actualizacionOk').css('display', 'block');
                $(".modal-open").css('padding-right', '');
                $(".modal-open").removeClass("modal-open");
                $(".modal-backdrop").removeClass("modal-backdrop fade in");                
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });


    // jQuery("#cantidadM").on("input", function() {
    //     jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
    // })
</script>
