<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal5" id="activar5"
    style="visibility:hidden">
</button>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal5" tabindex="-1" role="dialog"
    aria-labelledby="exampleModal5Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="modal-title" id="exampleModal5Label">Detalle</h5>

            </div>
            <div class="modal-body">
                <input type="hidden" name="nombreTab" value="{{ $nombreTab }}">
                @if ($cuantosPs == 0)
                <br>

                <div class="alert alert-warning" role="alert" align="center">
                    <label for="">Sin productos agregados</label>
                </div>
            @else
                    <table class="table table-striped table-bordered table-hover"
                        id="dataTables-example" style="width:100%">
                        <thead>
                            <tr style="background-color: #D6EAF8;">
                                <th class="fila">Existente</th>
                                <th class="fila">SKU</th>
                                <th class="fila">Categoría</th>
                                <th class="fila">Tipo</th>
                                <th class="fila">Modelo</th>
                                <th class="fila">Cantidad</th>
                                <th class="fila">Fotos / Archivos</th>
                                <th class="fila">Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($consultaPs as $c)
                                <tr>
                                    @if ($c->tipo == 'SKU')
                                        <td>Si</td>
                                    @else
                                        <td>No</td>
                                    @endif
                                    @if ($c->tipo == 'SKU')
                                        <td>{{ $c->sku }}</td>
                                    @else
                                        <td>{{ $c->skugenerico }}</td>
                                    @endif
                                    <td>{{ $c->tipoRefa }}</td>
                                    <td>{{ $c->tip }}</td>
                                    <td>{{ $c->modelo }}</td>
                                    <td>{{ $c->cantidad }}</td>
                                    @if (
                                        $c->foto1 == 'Sin foto' &&
                                            $c->foto2 == 'Sin foto' &&
                                            $c->foto3 == 'Sin foto' &&
                                            $c->foto4 == 'Sin foto' &&
                                            $c->foto5 == 'Sin foto')
                                        <td style="color:red" align="center">Sin fotos / archivos</td>
                                    @else
                                        <td align="center">

                                                <input type="hidden" value="{{ $c->idRfqDetalle }}"
                                                    name="idRfqDetalle">
                                                <input type="hidden" value="{{ $c->idRfq }}"
                                                                        name="idRfq">
                                            @php
                                            $imagen = [$c->foto1, $c->foto2, $c->foto3, $c->foto4, $c->foto5];
                                                                    $hasImages = false;
                                                    
                                                foreach ($imagen as $image) {
                                                    if ($image != 'Sin foto') {
                                                        echo '<a href="' . asset('archivos/' . $image) . '" class="verfotos" target="blank">' . /*$image*/'<i class="ace-icon fa fa-download bigger-120"></i>' . '</a><br>';
                                                            $hasImages = true;
                                                                                            }
                                                            }
                                             @endphp
                                             
                                        </td>
                                    @endif
                                    <td>{{ $c->notas }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
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
</style>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar5").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }

</script>
