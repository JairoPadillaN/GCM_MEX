<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
table{
    table-layout: fixed;
    width: 80%;
    align: center;
}

th, td {
    text-align: center;
 
    word-wrap: break-word;
    border: 1px solid;
}
th {
    background-color: #07A0E3;
}
</style>
</head>

<body>
    <h2 align="center">Cita asignada</h2>
    <h3>{{$nombre}} {{$paterno}} {{$materno}} tienes una cita asignada por {{$registradoPor}}. 
    A continuación se muestran los datos de la cita</h3>
    <table align="center">
        <tr>
            <th>Fecha alta</th>
            <th>Empresa</th>
            <th>Registro por</th>
            <th>Cliente</th>
        </tr>
        <tr>
            <td>{{$fechaAlta}}</td>
            <td>{{$empresaSeguimiento}}</td>
            <td>{{$registradoPor}}</td>
            <td>{{$nombreCliente}}</td>
        </tr>
        </table><br>
        <table align="center">
        <tr>
            <th>Sucursal</th>
            <th>Contacto</th>
            <th>Fecha de cita</th>
            <th>Hora</th>
        </tr>
        <tr>
            <td>{{$nombreSucursal}}</td>
            <td>{{$contacto}}</td>
            <td>{{$fechaCita}}</td>
            <td>{{$hora}}</td>
        </tr>
    </table>

    </table><br>
        <table align="center">
        <tr>
            <th>Lugar cita</th>
            <th>Responsable atender cita</th>
            <th colspan="2">Observacion previa cita</th>
            
            
            
        </tr>
        <tr>
            <td>{{$lugar}}</td>
            <td>{{$nombre}} {{$paterno}} {{$materno}}</td>
            <td colspan="2">{{$observacionCita}}</td>
            
            
            
        </tr>
    </table>


</body>

</html>
