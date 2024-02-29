<table>
    <thead>
        <tr>
            <th colspan='14' style="font-size: 20px; text-align:center; color:black;">
                Reporte Ordenes
            </th>
        </tr>
        <tr>
            <th colspan='14' style="font-size: 15px; text-align:center; color:black;">
                Fecha de reporte:
                @php
                    date_default_timezone_set( 'America/Mexico_City' );
                    echo date( 'd/m/Y h:i:s A' );
                @endphp
            </th>
        </tr>
        <tr>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Folio orden
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Proveedor
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Empresa
            </th>
            <th style='background-color:#6495B9; color:white; text-align:right; font-weight: bold;'>
                Importe
            </th>
            <th style='background-color:#6495B9; color:white; text-align:right; font-weight: bold;'>
                IVA
            </th>
            <th style='background-color:#6495B9; color:white; text-align:right; font-weight: bold;'>
                ISR
            </th>
            <th style='background-color:#6495B9; color:white; text-align:right; font-weight: bold;'>
                Retención
            </th>
            <th style='background-color:#6495B9; color:white; text-align:right; font-weight: bold;'>
                Total
            </th>
            <th style='background-color:#6495B9; color:white; text-align:right; font-weight: bold;'>
                Total MXN
            </th>
            <th style='background-color:#6495B9; color:white; text-align:right; font-weight: bold;'>
                Pago Final
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Estatus
            </th>
        </tr>

    </thead>
    <tbody>
        @foreach( $datos as $value )
        <tr>
            <td> {{ $value['codigoOrden'] }} </td>
            <td> {{ $value['fecha_formato'] }} </td>
            <td> {{ $value['razonSocialProv'] }} </td>
            <td> {{ $value['nombreEmpresa'] }} </td>
            <td style='text-align:right;'> {{ $value['importeOrden'] }} </td>
            <td style='text-align:right;'> {{ $value['iva'] }} </td>
            <td style='text-align:right;'> {{ $value['isr'] }} </td>
            <td style='text-align:right;'> {{ $value['retencion'] }} </td>
            <td style='text-align:right;'> {{ $value['totalOrden'] }} </td>
            <td style='text-align:right;'> {{ $value['totalMXN'] }} </td>
            <td style='text-align:right;'> {{ $value['totalFinalPagado'] }} </td>
            <td> {{ $value['estatus'] }} </td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style='text-align:right; background-color:#ffdab9;'>Total: </td>
            <td style='text-align:right; background-color:#ffdab9;'>{{$sumaTotales}}</td>
        </tr>
    </tbody>
</table>