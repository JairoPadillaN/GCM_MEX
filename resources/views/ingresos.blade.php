<table>
    <thead>
        <tr>
            <th colspan='11' style="font-size: 20px; text-align:center; color:black;">REPORTE DE VENTAS
            </th>
        </tr>
        <tr>
            <th colspan='11' style="font-size: 15px; text-align:center; color:black;">
                Fecha de reporte:
                @php
                    date_default_timezone_set('America/Mexico_City');
                    echo date('d/m/Y h:i:s A');
                @endphp
            </th>
        </tr>
        <tr>
            <th colspan='11' style="font-size: 15px; text-align:center; color:black;">
                Período del reporte: {{ \Carbon\Carbon::parse($periodo[0])->format('d/m/Y') }} AL
                {{ \Carbon\Carbon::parse($periodo[1])->format('d/m/Y') }}
            </th>
        </tr>
        <tr>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Fecha de pago</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Servicio</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Cliente</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Número factura</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Tipo cambio pagado</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Saldo en cuenta</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Subtotal Final MXN</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                IVA final</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Monto real</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Diferencia cambiaria</th>
            <th style='background-color:#6495B9;; text-align:center; font-weight: bold; color: #ffffff'>
                Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ingresos as $i)
            <tr>
                <td>{{ \Carbon\Carbon::parse($i->fechaDatosPago)->format('d/m/Y') }}</td>
                <td>{{ $i->idservicios }}</td>
                <td>{{ $i->cliente }}</td>
                <td>{{ $i->numeroFacturaDP }}</td>
                <td>{{ $i->tipoCambioPagado }}</td>
                <td>{{ $i->saldoReal }}</td>
                <td>{{ $i->subtotalFinal }}</td>
                <td>{{ $i->ivaFinal }}</td>
                <td>{{ $i->montoReal }}</td>
                <td>{{ $i->difCambiaria }}</td>
                <td>{{ $i->observaciones }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style='background-color:#FFFF00;; font-weight: bold;'>{{ $resultados[0]->ventas }}
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
