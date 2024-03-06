<table>
    <thead>
        <tr>      
            <th colspan='3' style="font-size: 20px; text-align:center; color:black;">REPORTE ESTADO DE RESULTADOS {{$nombreEmpresa}}
            </th>
        </tr>
        <tr>
            <th colspan='3' style="font-size: 15px; text-align:center; color:black;">
                Fecha de reporte:
                @php
                    date_default_timezone_set('America/Mexico_City');
                    echo date('d/m/Y h:i:s A');
                @endphp
            </th>
        </tr>
        <tr>
            <th colspan='3' style="font-size: 15px; text-align:center; color:black;">
                Período del reporte: {{ \Carbon\Carbon::parse($periodo[0])->format('d/m/Y') }} AL
                {{ \Carbon\Carbon::parse($periodo[1])->format('d/m/Y') }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold;">ESTADO DE RESULTADO INTEGRAL MENSUAL</th>
            <th style="font-weight: bold; text-indent: 7px;">MENSUALES</th>
            <th style="font-weight: bold; text-indent: 7px;">%</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>VENTAS</td>
            <td style="font-weight: bold;">{{ $resultados[0]->ventas }}</td>
            <td style="font-weight: bold;">{{ $porcentajes[0]->porventas / 100 }}</td>
        </tr>
        <tr>
            <td>COSTO DE VENTAS</td>
            <td>{{ $resultados[0]->costodeventa }}</td>
            <td>{{ $porcentajes[0]->porcostoventa / 100 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">UTILIDAD BRUTA</td>
            <td style="font-weight: bold;">{{ $resultados[0]->utilidadbruta }}</td>
            <td style="font-weight: bold;">{{ $porcentajes[0]->portutilidadbruta / 100 }}</td>
        </tr>
        <tr>
            <td>GASTOS DE OPERACIÓN</td>
            <td>{{ $resultados[0]->gastosdeoperacion }}</td>
            <td>{{ $porcentajes[0]->porgastosdeoperacion / 100 }}</td>
        </tr>
        <tr>
            <td>*GASTOS DE VENTA</td>
            <td>{{ $resultados[0]->gastodeventa }}</td>
            <td>{{ $porcentajes[0]->porgastodeventa / 100 }}</td>
        </tr>
        <tr>
            <td>*GASTOS DE ADMINISTRACION</td>
            <td>{{ $resultados[0]->gastoadministrativo }}</td>
            <td>{{ $porcentajes[0]->porgastoadministrativo / 100 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">UTILIDAD DE OPERACIÓN</td>
            <td style="font-weight: bold;">{{ $resultados[0]->utilidaddeoperacion }}</td>
            <td style="font-weight: bold;">{{ $porcentajes[0]->porutilidadoperacion / 100 }}</td>
        </tr>
        <tr>
            <td>CIF (COSTO INTEGRAL DE FINANCIAMIENTO)</td>
            <td>{{ $resultados[0]->CIF }}</td>
            <td>{{ $porcentajes[0]->porcif / 100 }}</td>
        </tr>
        <tr>
            <td>Neto: Gastos Financieros y productos financieros</td>
            <td></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">UTILIDAD ANTES DE IMPUESTOS</td>
            <td style="font-weight: bold;">{{ $resultados[0]->utilidadantesdeimpuestos }}</td>
            <td style="font-weight: bold;">{{ $porcentajes[0]->porutilantesimpuestos / 100 }}</td>
        </tr>
        <tr>
            <td>ISR 30%</td>
            <td>{{ $resultados[0]->ISR30 }}</td>
            <td>{{ $porcentajes[0]->porISR30 / 100 }}</td>
        </tr>
        <tr>
            <td>PTU 10%</td>
            <td>{{ $resultados[0]->PTU10 }}</td>
            <td>{{ $porcentajes[0]->porPTU10 / 100 }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Utilidad neta</td>
            <td style="font-weight: bold;">{{ $resultados[0]->utilidadneta }}</td>
            <td style="font-weight: bold;">{{ $porcentajes[0]->porutilidadneta / 100 }}</td>
        </tr>
    </tbody>
</table>
