<table>
    <thead>
        <tr>
            <th colspan='14'
                style="font-size: 20px; text-align:center; color:black;"
            >
                Reporte de ordenes de compra 
            </th>
        </tr>
        <tr>
            <th colspan='14'
                style="font-size: 15px; text-align:center; color:black;"
            >
                Fecha de reporte: 
                @php
                    date_default_timezone_set( 'America/Mexico_City' );
                    echo date( 'd/m/Y h:i:s A' );
                @endphp
            </th>
        </tr>
        <tr>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Folio de servicio
            </th>
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
                Sucursal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Tipo de servicio
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Empresa
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Cuenta
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Moneda
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Cambio dólar 
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
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total MXN
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Estatus
            </th>
        </tr>

    </thead>
    <tbody>
        @foreach( $data as $value )
            <tr>
                <td style='text-align:right;'> {{ $value['a'] }} </td>
                <td> {{ $value['b'] }} </td>
                <td> {{ $value['c'] }} </td>
                <td> {{ $value['d'] }} </td>
                <td> {{ $value['e'] }} </td>
                <td> {{ $value['p'] }} </td>
                <td> {{ $value['f'] }} </td>
                <td> {{ $value['g'] }} </td>
                <td> {{ $value['q'] }} </td>
                <td> {{ $value['r'] }} </td>
                <td style='text-align:right;'> {{ $value['h'] }} </td>
                <td style='text-align:right;'> {{ $value['i'] }} </td>
                <td style='text-align:right;'> {{ $value['j'] }} </td>
                <td style='text-align:right;'> {{ $value['k'] }} </td>
                <td style='text-align:right;'> {{ $value['m'] }} </td>
                <td> {{ $value['l'] }} </td>
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style='text-align:right; background-color:#ffdab9;'>Total: </td>
            <td style='text-align:right; background-color:#ffdab9;'>{{$suma[0]->total}}</td>
        </tr>
    </tbody>
</table>
