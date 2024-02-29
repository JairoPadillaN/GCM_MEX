<table>
    <thead>
        <tr>
            <th colspan='14'
                style="font-size: 20px; text-align:center; color:black;"
            >
              Reporte equipos servicios
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
                Servicio
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Factura
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Cotizacion
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha Pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Razon Social
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Sucursal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                GCMID
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Descripcion
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Tipo Equipo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Subtipo Equipo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Serie
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Marca
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Modelo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Ubicacion
            </th>
            
        </tr>

    </thead>
    <tbody>
        @foreach( $data as $value )
            <tr>
                <td style='text-align:right;'> {{ $value['id'] }} </td>
                <td style='text-align:right;'> {{ $value['a'] }} </td>
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
                <td style='text-align:right;'> {{ $value['n'] }} </td>
                <td style='text-align:right;'> {{ $value['o'] }} </td>
               
            </tr>
        @endforeach
        
    </tbody>
</table>
