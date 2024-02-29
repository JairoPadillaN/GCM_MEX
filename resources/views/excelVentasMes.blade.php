<table>
    <thead>
        <tr>
            <th colspan="13" style="font-size: 20px; text-align:center; color:black;">
                Reporte de ventas por mes: {{$month}}/{{$year}}
            </th>
        </tr>
        <tr>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Fecha de facturacion</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Fecha de vencimiento</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Moneda de factura</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Estatus</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Plazo de pago</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Vendedor</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Factura</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>No. Servicio German</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Empresa</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Tipo de cambio</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Saldo real</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Subtotal real</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Iva real</th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Monto real</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($data))
        @foreach($data as $columna)
            <tr>
                <td style='text-align:right;'> {{ $columna['b'] }} </td>
                <td style='text-align:right;'> {{ $columna['c'] }} </td>
                <td style='text-align:right;'> {{ $columna['d'] }} </td>
                <td style='text-align:right;'> {{ $columna['e'] }} </td>
                <td style='text-align:right;'> {{ $columna['f'] }} </td>
                <td style='text-align:right;'> {{ $columna['g'] }} </td>
                <td style='text-align:right;'> {{ $columna['h'] }} </td>
                <td style='text-align:right;'> {{ $columna['i'] }} </td>
                <td style='text-align:right;'> {{ $columna['j'] }} </td>
                <td style='text-align:right;'> {{ $columna['k'] }} </td>
                <td style='text-align:right;'> {{ $columna['l'] }} </td>
                <td style='text-align:right;'> {{ $columna['m'] }} </td>
                <td style='text-align:right;'> {{ $columna['n'] }} </td>
                <td style='text-align:right;'> {{ $columna['o'] }} </td>
            </tr>
        @endforeach
        @endif
        <tr>
            <td colspan=11></td>
            <td style="background-color:#6495B9; color:white; text-align: right;">{{ $subreport[count($subreport)-1]['b'] }}</td>
            <td style="background-color:#6495B9; color:white; text-align: right;">{{ $subreport[count($subreport)-1]['c'] }}</td>
            <td style="background-color:#6495B9; color:white; text-align: right;">{{ $subreport[count($subreport)-1]['d'] }}</td>
        </tr>
        <tr>
            <td colspan="14"></td>
        </tr>
        @if($filter == true)
            <tr>
                <td colspan="12"></td>
                <th colspan="2" style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Total por empresa</th>
            </tr>
            <tr>
                <td colspan="12"></td>
                <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Empresa</th>
                <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Monto total</th>
            </tr>
        @else
            <tr>
                <td colspan="12"></td>
                <th colspan="2" style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Total por vendedor</th>
            </tr>
            <tr>
                <td colspan="12"></td>
                <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Vendedor</th>
                <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>Monto total</th>
            </tr>
        @endif
        @foreach($subreport as $sub)
            @if(str_contains($sub['b'], '$'))
            @else
            <tr>
                <td colspan="12"></td>
                <td style="text-align: right;">{{ $sub['b'] }}</td>
                <td style="text-align: right;">{{ $sub['c'] }} MXN</td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>