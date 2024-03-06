<table>
    <thead>
        <tr>
            <th colspan='15' style="font-size: 16px; text-align:center; color:black; font-weight: bold;">REPORTE DE VENTAS POR EMPRESA
            </th>
        </tr>
        <tr>            
        </tr>        
        <tr>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Servicio</th>            
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Vendedores</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Empresa</th>
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Enero</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Febrero</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Marzo</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Abril</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Mayo</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Junio</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Julio</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Agosto</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Septiembre</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Octubre</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Noviembre</th>                   
            <th style='background-color:#D9E1F2;; text-align:center; font-weight: bold; color: #000'>
                Diciembre</th>                                               
        </tr>
    </thead>
    <tbody>
        @foreach ($ventasempresa as $ve)
            <tr>
                <td>{{ $ve->razonsocial }}</td>                
                <td>{{ $ve->vendedores }}</td>
                <td>{{ $ve->nombreEmpresa }}</td>
                <td>{{ $ve->Enero }}</td>                              
                <td>{{ $ve->Febrero }}</td>                              
                <td>{{ $ve->Marzo }}</td>                              
                <td>{{ $ve->Abril }}</td>                              
                <td>{{ $ve->Mayo }}</td>                              
                <td>{{ $ve->Junio }}</td>                              
                <td>{{ $ve->Julio }}</td>                              
                <td>{{ $ve->Agosto }}</td>                              
                <td>{{ $ve->Septiembre }}</td>                              
                <td>{{ $ve->Octubre }}</td>                              
                <td>{{ $ve->Noviembre }}</td>                              
                <td>{{ $ve->Diciembre }}</td>                              
            </tr>
        @endforeach        
    </tbody>
</table>
