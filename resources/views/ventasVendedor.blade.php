<table>
    <thead>
        <tr>
            <th colspan='14' style="font-size: 16px; text-align:center; color:black; font-weight: bold;">REPORTE DE VENTAS POR VENDEDOR
            </th>
        </tr>
        <tr>            
        </tr>        
        <tr>                        
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
        @foreach ($ventasvendedor as $vv)
            <tr>                              
                <td>{{ $vv->vendedores }}</td>
                <td>{{ $vv->nombreEmpresa }}</td>
                <td>{{ $vv->Enero }}</td>                              
                <td>{{ $vv->Febrero }}</td>                              
                <td>{{ $vv->Marzo }}</td>                              
                <td>{{ $vv->Abril }}</td>                              
                <td>{{ $vv->Mayo }}</td>                              
                <td>{{ $vv->Junio }}</td>                              
                <td>{{ $vv->Julio }}</td>                              
                <td>{{ $vv->Agosto }}</td>                              
                <td>{{ $vv->Septiembre }}</td>                              
                <td>{{ $vv->Octubre }}</td>                              
                <td>{{ $vv->Noviembre }}</td>                              
                <td>{{ $vv->Diciembre }}</td>                              
            </tr>
        @endforeach        
    </tbody>
</table>
