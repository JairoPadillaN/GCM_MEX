@if ($diferenciaDias >= 30)
    <div class="alert" style="color: #fff;
background-color: #f39c12;">
        <center>Equipo actualizado en catálogo el día
            {{ \Carbon\Carbon::parse($fechaActualizacionParte->fechaActualizacion)->format('d/m/Y') }}, verifica stock y
            tiempo de entrega</center>
    </div>
@endif
