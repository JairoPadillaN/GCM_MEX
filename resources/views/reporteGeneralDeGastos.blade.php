<table>
    <thead>
        <tr>
            <th colspan='14' style="font-size: 20px; text-align:center; color:black;">REPORTE GENERAL DE GASTOS
            </th>
        </tr>
        <tr>
            <th colspan='14' style="font-size: 15px; text-align:center; color:black;">
                Fecha de reporte:
                @php
                    date_default_timezone_set('America/Mexico_City');
                    echo date('d/m/Y h:i:s A');
                @endphp
            </th>
        </tr>
        <tr>
            <th colspan='14' style="font-size: 15px; text-align:center; color:black;">
                Período del reporte: {{ \Carbon\Carbon::parse($periodo[0])->format('d/m/Y') }} AL
                {{ \Carbon\Carbon::parse($periodo[1])->format('d/m/Y') }}
            </th>
        </tr>
        <tr>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Fecha de pago
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Nombre gasto
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
                Etiqueta
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Moneda
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Subtotal
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                IVA
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total IVA
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                ISR
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total ISR
            </th>
            {{-- <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total
            </th> --}}
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Total en pesos
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                Descripción
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporteGastosGral as $rGG)
            <tr>
                <td>{{ \Carbon\Carbon::parse($rGG->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $rGG->nombreGasto }}</td>
                <td>{{ $rGG->beneficiario }}</td>
                <td>{{ $rGG->cuentacontable }}</td>
                <td>{{ $rGG->forma_pago }}</td>
                <td>{{ $rGG->referencia }}</td>
                <td>{{ $rGG->etiquetas }}</td>
                <td>{{ $rGG->moneda }}</td>
                <td>{{ $rGG->subtotal }}</td>
                <td>{{ $rGG->iva / 100}}</td>
                <td>{{ $rGG->total_iva }}</td>
                <td>{{ $rGG->isr / 100 }}</td>
                <td>{{ $rGG->total_isr }}</td>
                <td>{{ $rGG->totalenpesos }}</td>
                <td>{{ $rGG->descripcion }}</td>
            </tr>
        @endforeach        
    </tbody>
</table>
