<table>
    <thead>
        <tr>
            <th colspan='14'
                style="font-size: 20px; text-align:center; color:black;"
            >
                Reporte Equipos por Taller 
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
                Foto del equipo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Foto de parte
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                GCM ID parte
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Marca-Modelo
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Nombre de la parte
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Que se le realiza
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha de recepción
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha de entrada
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Estatus
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach( $data as $value )
            <tr>
                <td></td>
                <td></td>
                <td> {{ $value['c'] }} </td>
                <td> {{ $value['d'] }} </td>
                <td> {{ $value['e'] }} </td>
                <td> {{ $value['f'] }} </td>
                <td> {{ $value['g'] }} </td>
                <td> {{ $value['h'] }} </td>
                <td> {{ $value['i'] }} </td>
            </tr>
        @endforeach
    </tbody>
</table>
