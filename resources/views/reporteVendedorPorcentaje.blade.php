<script src="{!! asset('public/assets/js/jquery-1.10.2.js') !!}"></script>
<br>
@if($cuantosVas != 0)
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:90%" align="center">
        <thead>
            <tr style=" background-color: #78e08f;">
                <th style="width: 80px;">Vendedor</th>
                <th style="width: 80px;">Porcentaje</th>
                <th style="width: 80px;">
                    <center>Opciones</center>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consultaVas as $vas)
                <tr>
                    <td style="text-align: center;"> {{ $vas->vendedor }}</td>
                    <td style="text-align: center;"> {{ $vas->porcentaje }}%</td>
                    <td align="center">
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                            @csrf
                            <input type="hidden" value="{{ $vas->idser }}" name="idFactura">
                            <input type="hidden" value="{{ $vas->idvas }}" name="idvas">
                            <button type="button" class="btn btn-sm btn-danger borrarVendedorPorcentaje1"
                                style='width:40px; height: 35px;'>
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>

                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
@else
<br>
<div class="alert alert-warning" role="alert" align="center">
    <label for="">Sin vendedores ni porcentajes asignados</label>
</div>
@endif

<script type="text/javascript">
    $(function() {
        $('.borrarVendedorPorcentaje1').click(
            function() {
                $("#reporteVendedoresPorcentaje").load('{{ url('borrarVendedorPorcentaje') }}' + '?' + $(
                    this).closest('form').serialize());
            });

    });
</script>
