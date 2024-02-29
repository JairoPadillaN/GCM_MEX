<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;">
    <style>
    @page {
        margin-top: 100px;
    }

    #header {
        position: fixed;
        left: 0px;
        top: -105px;
        right: 0px;
        background-color: orange;
        text-align: center;
    }

    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

</head>

<body>
    <div id="header">
        <img src="{!! asset('archivos/membrete.jpeg')!!}" style="height:103px; width:100%;">
    </div>

    <div id="content">
        <div style="text-align:center">
            <strong>Taller:</strong>&nbsp;&nbsp;&nbsp;{{$taller->nombreTaller}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Fecha:</strong>&nbsp;&nbsp;&nbsp;{{$fechaInicio}}
            &nbsp;&nbsp;&nbsp;<strong>a</strong>&nbsp;&nbsp;&nbsp;
            {{$fechaFin}}
        </div>
        <br><br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr style=" background-color: #4997D9;">
                        <th style="text-align:center;">
                            <font SIZE=2>Foto del equipo</font>
                        </th><th style="text-align:center;">
                            <font SIZE=2>Foto de parte</font>
                        </th>
                        <th style="text-align:center;">
                            <font SIZE=2>GCM ID PARTE</font>
                        </th>
                        <th style="text-align:center;">
                            <font SIZE=2>Marca - Modelo</font>
                        </th>
                        <th style="text-align:center;">
                            <font SIZE=2>Nombre de la parte</font>
                        </th>
                        <th style="text-align:center;">
                            <font SIZE=2>Que se le realiza</font>
                        </th>
                        <th style="text-align:center;">
                            <font SIZE=2>Fecha de recepción</font>
                        </th>
                        <th style="text-align:center;">
                            <font SIZE=2>Fecha de entrega</font>
                        </th>
                        <th style="text-align:center;">
                            <font SIZE=2>Estatus</font>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reporte as $t)
                    <tr>
                        <td style="text-align:center; color:red">
                            @if($t->vistaSuperior=='')
                                <font SIZE=2>Sin foto</font>
                            @else
                                <a target="_blank" href="{{asset ('public/archivos/'.$t->vistaSuperior)}}">
                                    <img src="{{asset ('public/archivos/'.$t->vistaSuperior)}}" height=80 width=80>
                                </a>
                            @endif
                        </td>
                        <td style="text-align:center; color:red">
                            @if($t->fotoParte=='Sin archivo')
                                <font SIZE=2>Sin foto</font>
                            @else
                                <a target="_blank" href="{{asset ('public/archivos/'.$t->fotoParte)}}">
                                    <img src="{{asset ('public/archivos/'.$t->fotoParte)}}" height=80 width=80>
                                </a>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <font SIZE=1>{{$t->GCMidParte}}</font>
                        </td>
                        <td style="text-align:center">
                            <font SIZE=1>{{$t->marca}} - {{$t->modelo}}</font>
                        </td>
                        <td style="text-align:center">
                            <font SIZE=2>{{$t->nombreParte}}</font>
                        </td>
                        <td style="text-align:center" style="width:10%">
                            <font SIZE=1>{{$t->queReparacion}}</font>
                        </td>

                        @if($t->fecha=='')
                        <td style="text-align:center; color:red">
                            <font SIZE=2>Sin fecha</font>
                        </td>
                        @else
                        <td style="text-align:center">
                            <font SIZE=2>{{$t->fecha}}</font>
                        </td>
                        @endif

                        @if($t->fechaE=='')
                        <td style="text-align:center; color:red">
                            <font SIZE=2>Sin fecha</font>
                        </td>
                        @else
                        <td style="text-align:center">
                            <font SIZE=2>{{$t->fechaE}}</font>
                        </td>
                        @endif
                        <td style="text-align:center">
                            <font SIZE=2>{{$t->nombreEstatus}}</font>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>