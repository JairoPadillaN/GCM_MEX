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
    <!-- <h2 align="center"></h2> -->
    <h3>Los archivos para la orden de compra {{$consultaOrd->codigoOrden}} han sido cargados correctamente por el proveedor  {{$consultaProv->razonSocialProv}}.
        En espera de programación de pago.</h3>
</body>

</html>
