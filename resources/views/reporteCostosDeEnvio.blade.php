<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr style="background-color: #E8E8E8;">
                <th width="10%">
                    <font SIZE=2>Fecha</font>
                </th>
                <th width="20%">
                    <font SIZE=2>Compañia</font>
                </th>
                <th width="15%">
                    <font SIZE=2>Moneda</font>
                </th>
                <th width="15%">
                    <font SIZE=2>Monto</font>
                </th>
                <th width="30%">
                    <font SIZE=2>Servicio</font>
                </th>
                <th width="10%">
                    <font SIZE=2>Opciones</font>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consultaCED as $c)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $c->compañia }}</td>
                    <td>{{ $c->moneda }}</td>
                    <td style="text-align: right;">$ {{ number_format($c->monto, 2, '.', ',') }}</$>
                    <td>{{ $c->servicio }}</td>

                    <td align="center">
                        <form action=''>
                            @csrf
                            <input type='hidden' value='{{ $c->idced }}' name='idced' id='idced'>
                            <input type='hidden' value='{{ $c->idPartesVenta }}' name='idPartesVentaCE'
                                id='idPartesVentaCE'>
                            <button type="button" class="btn btn-sm btn-danger borrarCE">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(".borrarCE").click(function() {
        $("#reporteCostosEnvio").load('{{ url('borrarCostoEnvio') }}' + '?r=' + Date.now() + $(this).closest(
            'form').serialize());
    });
</script>
