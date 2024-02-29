<table>
    <thead>
        <tr>
            <th colspan='14' style="font-size: 20px; text-align:center; color:black;">REPORTE DE LOS GASTOS OPERATIVOS
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
                Moneda
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
        @foreach ($costoVenta as $cV)
            <tr>
                <td>{{ \Carbon\Carbon::parse($cV->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $cV->nombreGasto }}</td>
                <td>{{ $cV->beneficiario }}</td>
                <td>{{ $cV->cuentacontable }}</td>
                <td>{{ $cV->forma_pago }}</td>
                <td>{{ $cV->referencia }}</td>
                <td>{{ $cV->etiquetas }}</td>
                <td>{{ $cV->moneda }}</td>
                <td>{{ $cV->subtotal }}</td>
                <td>{{ $cV->iva }}</td>
                <td>{{ $cV->total_iva }}</td>
                <td>{{ $cV->isr }}</td>
                <td>{{ $cV->total_isr }}</td>
                <td>{{ $cV->totalenpesos }}</td>
                <td>{{ $cV->descripcion }}</td>
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
            <td style='background-color:#FFFF00; font-weight: bold;'>{{ $resultados[0]->costodeventa }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>



<table>
    <thead>
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
                Moneda
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
        @foreach ($gastoAdmin as $gA)
            <tr>
                <td>{{ \Carbon\Carbon::parse($gA->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $gA->nombreGasto }}</td>
                <td>{{ $gA->beneficiario }}</td>
                <td>{{ $gA->cuentacontable }}</td>
                <td>{{ $gA->forma_pago }}</td>
                <td>{{ $gA->referencia }}</td>
                <td>{{ $gA->etiquetas }}</td>
                <td>{{ $gA->moneda }}</td>
                <td>{{ $gA->subtotal }}</td>
                <td>{{ $gA->iva }}</td>
                <td>{{ $gA->total_iva }}</td>
                <td>{{ $gA->isr }}</td>
                <td>{{ $gA->total_isr }}</td>
                <td>{{ $gA->totalenpesos }}</td>
                <td>{{ $gA->descripcion }}</td>
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
            <td style='background-color:#FFFF00; font-weight: bold;'>{{ $resultados[0]->gastoadministrativo }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>


<table>
    <thead>
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
                Moneda
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
        @foreach ($gastoVenta as $gV)
            <tr>
                <td>{{ \Carbon\Carbon::parse($gV->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $gV->nombreGasto }}</td>
                <td>{{ $gV->beneficiario }}</td>
                <td>{{ $gV->cuentacontable }}</td>
                <td>{{ $gV->forma_pago }}</td>
                <td>{{ $gV->referencia }}</td>
                <td>{{ $gV->etiquetas }}</td>
                <td>{{ $gV->moneda }}</td>
                <td>{{ $gV->subtotal }}</td>
                <td>{{ $gV->iva }}</td>
                <td>{{ $gV->total_iva }}</td>
                <td>{{ $gV->isr }}</td>
                <td>{{ $gV->total_isr }}</td>
                <td>{{ $gV->totalenpesos }}</td>
                <td>{{ $gV->descripcion }}</td>
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
            <td style='background-color:#FFFF00; font-weight: bold;'>{{ $resultados[0]->gastodeventa }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>



<table>
    <thead>
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
                Moneda
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
        @foreach ($gastoFinan as $gF)
            <tr>
                <td>{{ \Carbon\Carbon::parse($gF->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $gF->nombreGasto }}</td>
                <td>{{ $gF->beneficiario }}</td>
                <td>{{ $gF->cuentacontable }}</td>
                <td>{{ $gF->forma_pago }}</td>
                <td>{{ $gF->referencia }}</td>
                <td>{{ $gF->etiquetas }}</td>
                <td>{{ $gF->moneda }}</td>
                <td>{{ $gF->subtotal }}</td>
                <td>{{ $gF->iva }}</td>
                <td>{{ $gF->total_iva }}</td>
                <td>{{ $gF->isr }}</td>
                <td>{{ $gF->total_isr }}</td>
                <td>{{ $gF->totalenpesos }}</td>
                <td>{{ $gF->descripcion }}</td>
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
            <td style='background-color:#FFFF00; font-weight: bold;'>{{ $resultados[0]->GASTOFINANCIERO }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>



<table>
    <thead>
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
                Moneda
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
        @foreach ($impuestos as $im)
            <tr>
                <td>{{ \Carbon\Carbon::parse($im->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $im->nombreGasto }}</td>
                <td>{{ $im->beneficiario }}</td>
                <td>{{ $im->cuentacontable }}</td>
                <td>{{ $im->forma_pago }}</td>
                <td>{{ $im->referencia }}</td>
                <td>{{ $im->etiquetas }}</td>
                <td>{{ $im->moneda }}</td>
                <td>{{ $im->subtotal }}</td>
                <td>{{ $im->iva }}</td>
                <td>{{ $im->total_iva }}</td>
                <td>{{ $im->isr }}</td>
                <td>{{ $im->total_isr }}</td>
                <td>{{ $im->totalenpesos }}</td>
                <td>{{ $im->descripcion }}</td>
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
            <td style='background-color:#FFFF00; font-weight: bold;'>{{ $resultados[0]->IMPUESTOS }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>



<table>
    <thead>
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
                Moneda
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
        @foreach ($interes as $in)
            <tr>
                <td>{{ \Carbon\Carbon::parse($in->fecha_pago)->format('d/m/Y') }}</td>
                <td>{{ $in->nombreGasto }}</td>
                <td>{{ $in->beneficiario }}</td>
                <td>{{ $in->cuentacontable }}</td>
                <td>{{ $in->forma_pago }}</td>
                <td>{{ $in->referencia }}</td>
                <td>{{ $in->etiquetas }}</td>
                <td>{{ $in->moneda }}</td>
                <td>{{ $in->subtotal }}</td>
                <td>{{ $in->iva }}</td>
                <td>{{ $in->total_iva }}</td>
                <td>{{ $in->isr }}</td>
                <td>{{ $in->total_isr }}</td>
                <td>{{ $in->totalenpesos }}</td>
                <td>{{ $in->descripcion }}</td>
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
            <td style='background-color:#FFFF00; font-weight: bold;'>{{ $resultados[0]->INTERES }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
