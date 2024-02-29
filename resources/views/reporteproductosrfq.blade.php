<script src="{!! asset('assets/js/jquery-1.10.2.js') !!}"></script>
<br>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:90%" align="center">
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
                <th class="fila">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consulta as $c)
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
                        <td style="color:red" align="center">Sin fotos / archivos
                        </td>
                    @else
                        <td align="center">

                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idRfqDetalle }}" name="idRfqDetalle">
                                <input type="hidden" value="{{ $c->idRfq }}" name="idRfq">
                                <a href="{{ route('verFotosRfq') }}" class="verfotos">Ver</a>
                            </form>


                        </td>
                    @endif

                    <td>{{ $c->notas }}</td>
                    <td>
                        <div id="botonesProds" style="display: flex; justify-content: center;">
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idRfqDetalle }}" name="idRfqDetalle">
                                <input type="hidden" value="{{ $c->idmarca }}" name="idmarca">
                                <input type="hidden" value="{{ $c->idRfq }}" name="idRfq">
                                <button type="button" class="btn btn-sm btn-info editarProductoRfq"
                                    style="width:40px; height:35px; margin-right: 5px;">
                                    <i class="ace-icon fa fa-pencil bigger" aria-hidden="true"></i>
                                </button>
                            </form>
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idRfqDetalle }}" name="idRfqDetalle">
                                <input type="hidden" value="{{ $c->idRfq }}" name="idRfq">
                                <button type="button" class="btn btn-sm btn-danger borrarProductoRfq"
                                    style="width:40px; height:35px; margin-left: 5px;">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<script type="text/javascript">
    $(".borrarProductoRfq1").click(function() {
        $("#reporteProductos").load('{{ url('borrarProductosRfq') }}' + '?r=' + Date.now() + $(this).closest(
            'form').serialize());
    });

    $(".editarProductoRfq").click(function(e) {
        $('#modalEditar').load('{{ url('editarProductoRfq') }}' + '?' + $(this).closest(
            'form').serialize());
        e.preventDefault();
    });
</script>

<style>
    .fila {
        width: 80px;
        text-align: center;
    }

    td {
        text-align: center;
    }
</style>
