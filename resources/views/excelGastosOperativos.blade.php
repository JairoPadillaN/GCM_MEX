<table>
    <thead>
        <tr>
            <th colspan='14' style="font-size: 20px; text-align:center; color:black;">Reporte de los Gastos Operativos</th>
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
                Fecha de pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Gasto
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Beneficiario
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Cuenta contable
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Forma de pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Referencia
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Etiquetas
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Descripcion
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Subtotal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                IVA %
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total IVA
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                ISR %
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                'Total ISR',
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total en pesos
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $columna)
            <tr>
                <td style='text-align:right;'> {{ $columna['a'] }} </td>
                <td style='text-align:right;'> {{ $columna['b'] }} </td>
                <td style='text-align:right;'> {{ $columna['c'] }} </td>
                <td style='text-align:right;'> {{ $columna['o'] }} </td>
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
            </tr>
        @endforeach
        <tr><td colspan=14 style="background-color:#6495B9;"></td></tr>
            <tr>
                <td colspan=13 style ="text-align:right; font-weight:bold;">Suma total en pesos</td>
                <td style ="text-align:right; font-weight:bold;">MXN ${{$suma}}</td>
            </tr>
    </tbody>
</table>