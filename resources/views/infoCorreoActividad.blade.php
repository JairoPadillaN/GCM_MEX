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
    background-color: #FDF2E9
}
</style>
</head>

<body>
    <h2 align="center">Actividad asignada</h2>
    <h3>Tienes una actividad asignada por {{$consulta->nombreUsuario}}. 
    A continuación se muestran los datos de la actividad</h3>
    <div class="table-responsive">
        <table align="center">
            <tr>
                <th>Fecha creación</th>
                <th>Registro por</th>
                <th>Cliente</th>
                <th>Sucursal</th>
            </tr>
            <tr>
                <td>{{$consulta->fechaCreacion}}</td>
                <td>{{$consulta->nombreUsuario}}</td>
                <td>{{$consulta->razonSocial}}</td>
                <td>{{$consulta->sucursal}}</td>
            </tr>
        </table><br>
        <table align="center">
            <tr>
                <th>Asunto</th>
                <th>Nivel de atención</th>
                <th>Area responsable</th>
                <th>Actividad</th>
            </tr>
            <tr>
                <td>{{$consulta->asunto}}</td>
                <td>{{$consulta->importanciaSeguimiento}}</td>
                <td>{{$consulta->nombreArea}}</td>
                <td>{{$consulta->nombreActividad}}</td>
            </tr>
        </table><br>
        <table align="center">
            <tr>
                <th colspan="2">Periodo de atención</th>
                <th colspan="2">¿Qué se realiza?</th>
            </tr>
            <tr>
                <td colspan="2">{{$consulta->periodo}}</td>
                <td colspan="2">{{$queRealiza}}</td>
                
            </tr>
        </table>
    </div>
</body>

</html>
