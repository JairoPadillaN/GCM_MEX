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
    <h1 align="center">Nuevo RFQ</h1>
    <p><h2>RFQ creado: {{ $cmgrfq }}</h2></p>
    <p><h3>El usuario {{ $nombreUsuario }} a creado un RFQ para que se de seguimiento </h3></p>
</body>

</html>
