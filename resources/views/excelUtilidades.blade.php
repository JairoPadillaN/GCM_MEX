<table>
    <thead>
        <tr>
            <th colspan='14'
                style="font-size: 20px; text-align:center; color:black;"
            >
                Reporte de Utilidades
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
                Folio de Servicio Asignado
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Folio de Factura
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha de Servicio
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha de Pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Cliente
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Sucursal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Monto de Cotización
            </th>
         
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total de Gastos MXN
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total de Compras MXN
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Utilidad del Servicios MXN
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
            <td style='text-align:right; background-color:#ffdab9;'>Total: </td>
            <td style='text-align:right; background-color:#ffdab9;'>{{$suma[0]->totalgastos}}</td>
            <td style='text-align:right; background-color:#ffdab9;'>{{$suma[0]->totaloc}}</td>
            <td style='text-align:right; background-color:#ffdab9;'>{{$suma[0]->utilidad}}</td>
        </tr>
    </tbody>
</table>
