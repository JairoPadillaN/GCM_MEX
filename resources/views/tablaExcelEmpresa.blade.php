<table>
    <thead>
        <tr>
            <th colspan='14'
                style="font-size: 20px; text-align:center; color:black;"
            >
                Reporte Equipos Empresa
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
                Foto principal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Foto vista-frontal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Foto placa 1
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Folio de recepción
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                GCM ID
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha de entrada
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Cliente - Sucursal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Quien entrego
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Equipo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Marca - Modelo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Importancia
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Estatus de reparación
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Estatus de entrega
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Piezas divididas
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach( $data as $value )
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td> {{ $value['d'] }} </td>
                <td> {{ $value['e'] }} </td>
                <td> {{ $value['f'] }} </td>
                <td> {{ $value['g'] }} </td>
                <td> {{ $value['p'] }} </td>
                <td> {{ $value['i'] }} </td>
                <td> {{ $value['j'] }} </td>
                <td></td>
                <td> {{ $value['l'] }} </td>
                <td> {{ $value['m'] }} </td>
                <td> {{ $value['n'] }} </td>
            </tr>
        @endforeach
    </tbody>
</table>
