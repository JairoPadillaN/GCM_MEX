<table>
    <thead>
        <tr>
            <th colspan='14'
                style="font-size: 20px; text-align:center; color:black;"
            >
                Reporte de Gastos
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
                Fecha de pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Beneficiario
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Forma de Pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Referencia
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Etiqueta
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Descripción
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Subtotal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                IVA
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                IVA $
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                ISR
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                ISR $
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                TotalMXN
            </th>

        </tr>

    </thead>
    <tbody>
        @foreach( $data as $value )
            <tr>
                <td style='text-align:right;'> {{ $value['a'] }} </td>
                <td style='text-align:right;'> {{ $value['b'] }} </td>
                <td style='text-align:right;'> {{ $value['c'] }} </td>
                <td style='text-align:right;'> {{ $value['d'] }} </td>
                <td style='text-align:right;'> {{ $value['e'] }} </td>
                <td style='text-align:right;'> {{ $value['f'] }} </td>
                <td style='text-align:right;'> {{ $value['g'] }} </td>
                <td style='text-align:right;'> {{ $value['h'] }} </td>
                <td style='text-align:right;'> {{ $value['i'] }} </td>
                <td style='text-align:right;'> {{ $value['j'] }} </td>
                <td style='text-align:right;'> {{ $value['k'] }} </td>
				<td style='text-align:right;'> {{ $value['l'] }} </td>
				<td style='text-align:right;'> {{ $value['m'] }} </td>
                
               
            </tr>
        @endforeach
        <!-- <tr>
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
            <td style='text-align:right; background-color:#ffdab9;'></td>
        </tr> -->
    </tbody>
</table>