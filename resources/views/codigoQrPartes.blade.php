<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Reporte subtipos de equipos PDF</title>
</head>

<body>
<div style="width:40%">
<table>
    <tr>
        <td align="justify">  
        <font SIZE=2>GCM ID:<br>{{$consultaQr1->GCMid}}</font>
        </td>
        <td align="justify">
            <font SIZE=2>GCM ID Parte:<br>{{$consultaQr1->GCMidParte}}</font> 
        </td>
    </tr>
    <tr>
        <td align="justify">  
            <font SIZE=2>Tipo de equipo:<br>{{$consultaQr2->tipoEquipo}}</font> 
        </td>
        <td align="justify">
            <font SIZE=2>Subclasificación:<br>{{$consultaQr2->subtipoEquipo}}</font> 
        </td>
    </tr>
    <tr>
        <td align="justify">  
            <font SIZE=2>Nombre de la pieza:<br>{{$consultaQr1->nombreParte}}</font>        
        </td>
        <td rowspan="2">
           <!-- <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('GCM ID: '.$consultaQr1->GCMid."\n".'GCM ID parte: '.$consultaQr1->GCMidParte."\n".' Tipo de equipo: '.$consultaQr2->tipoEquipo."\n".'Subclasificación: '.$consultaQr2->subtipoEquipo."\n".' Nombre de lapieza: '.$consultaQr1->nombreParte."\n".' Servicios de reparacion: '.$consultaQr1->queReparacion)) !!} "> -->
           <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('GCM ID parte: '.$consultaQr1->GCMidParte)) !!} ">
        </td>
    </tr>
    <tr>
        <td align="justify" style="padding-right:7px">  
            <font SIZE=2>Servicios de reparacion: {{$consultaQr1->queReparacion}}</font>        
        </td>        
    </tr>
</table> 

</div>


           
</body>
</html>