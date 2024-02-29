<table>
    <thead>
        <tr>
            <th colspan='14'
                style="font-size: 20px; text-align:center; color:black;"
            >
                Reporte cuentas contables
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
                Folio 
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Empresa
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Cuenta
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Tipo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Beneficiario
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Forma de pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Divisa
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Importe
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                IVA
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                ISR
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total MXN
            </th>
        </tr>

    </thead>
    <tbody>
        @foreach( $data as $value )
            <tr>
                <td style='text-align:right;'> {{ $value['a'] }} </td>
                <td style='text-align:right;'> {{ $value['m'] }} </td>
                <td style='text-align:right;'> {{ $value['b'] }} </td>
                <td style='text-align:right;'> {{ $value['c'] }} </td>
                <td style='text-align:right;'> {{ $value['d'] }} </td>
                <td style='text-align:right;'> {{ $value['e'] }} </td>
                <td style='text-align:right;'> {{ $value['f'] }} </td>
                <td style='text-align:right;'> {{ $value['l'] }} </td>
                <td style='text-align:right;'> {{ $value['g'] }} </td>
                <td style='text-align:right;'> {{ $value['h'] }} </td>
                <td style='text-align:right;'> {{ $value['i'] }} </td>
                <td style='text-align:right;'> {{ $value['j'] }} </td>
                <td style='text-align:right;'> {{ $value['k'] }} </td>
               
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
            <td style='text-align:right; background-color:#ffdab9;'>Total: </td>
            <td style='text-align:right; background-color:#ffdab9;'>{{$suma[0]->total}}</td>
        </tr>
    </tbody>
</table>
