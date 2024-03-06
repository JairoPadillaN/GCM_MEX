<table>
    <thead>
        <tr>
            <th colspan='7' style="font-size: 16px; text-align:center; color:black; font-weight: bold;">DETALLE DE
                FACTURAS ASIGNADAS A VENDEDORES
            </th>
        </tr>
        <tr>
        </tr>
        <tr>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Servicio</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Razón Social</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Vendedores</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Empresa</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Subtotal</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                IVA</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Monto</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Número de factura</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Fecha de la factura</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detallefacturas as $df)
            <tr>
                <td>{{ $df->idservicios }}</td>
                <td>{{ $df->razonsocial }}</td>
                <td>{{ $df->vendedores }}</td>
                <td>{{ $df->nombreEmpresa }}</td>
                <td>{{ $df->subtotalfac }}</td>
                <td>{{ $df->ivafac }}</td>                
                <td>{{ $df->montopesosDP }}</td>
                <td>{{ $df->numeroFacturaDP }}</td>
                <td style="text-align: right">{{ \Carbon\Carbon::parse($df->fechafactura)->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
