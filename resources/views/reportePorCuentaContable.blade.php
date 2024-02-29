<table>
    <thead>
        <tr>
            <th colspan='5' style="font-size: 20px; text-align:center; color:black;">REPORTE DE GASTOS POR CUENTA CONTABLE
            </th>
        </tr>
        <tr>
            <th colspan='5' style="font-size: 15px; text-align:center; color:black;">
                Fecha de reporte:
                @php
                    date_default_timezone_set('America/Mexico_City');
                    echo date('d/m/Y h:i:s A');
                @endphp
            </th>
        </tr>
        <tr>
            <th colspan='5' style="font-size: 15px; text-align:center; color:black;">
                Período del reporte: {{ \Carbon\Carbon::parse($periodo[0])->format('d/m/Y') }} AL
                {{ \Carbon\Carbon::parse($periodo[1])->format('d/m/Y') }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                CUENTA CONTABLE
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                SUBTOTAL
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                TOTAL IVA
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                TOTAL ISR
            </th>
            <th style='background-color:#6495B9; color:white; text-align:center; font-weight: bold;'>
                TOTAL EN PESOS
            </th>
        </tr>        
    </thead>
    <tbody>
        @foreach ($reporteGastos as $rG)
            <tr>
                <td>{{ $rG->cuentacontable }}</td>
                <td>{{ $rG->subtotal }}</td>
                <td>{{ $rG->total_iva }}</td>
                <td>{{ $rG->total_isr }}</td>
                <td>{{ $rG->totalenpesos }}</td>
            </tr>
        @endforeach
        <tr>
            <td>TOTAL</td>
            <td>{{ $subtotal[0]->subtotal }}</td>
        </tr>
    </tbody>
</table>