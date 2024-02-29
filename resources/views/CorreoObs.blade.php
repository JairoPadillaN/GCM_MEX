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
    <h1 align="center">Cambio de Observaciones del RFQ</h1>

    <p><h3>Las observaciones del RFQ ha sido modificado por: {{ $usuarioCambios }}</h3></p>
    <p>RFQ: {{ $cmgr }}</p>
    <p>Estatus: {{ $estatusAnterior }}</p>
    @if($observacionesCardex == '')
    <p>Observaciones:  Sin observaciones</p>
    @else
    <p>Observaciones:  {{ $observacionesCardex }}</p>
    @endif
</body>

</html>
