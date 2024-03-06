@extends('principal')
@section('contenido')

    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1>Reporte global de ventas</h1>
            </div>
            <form action="{{ route('reporteGVentas') }}" method="GET" id="reporteExcel">
                @csrf
                <div class="panel-body" style="overflow-x: auto;">

                    <div class="row">
                    </div>
                    <br><br>
                    <div>
                        <input type="hidden" name="cadena" class="form-control" id='cadena' value = 'xxe3' required>
                    </div>                    

                    <div class="sub-title">Empresa:</div>
                    <select name="empresa" id="empresa" class="form-control" required>
                        <option value="">Seleccione empresa</option>
                        <option value="gcm">GCM</option>
                        {{-- <option value="cym">CYM</option>
                        <option value="surja">SURJA</option>
                        <option value="yaneth">YANETH</option>
                        <option value="pedro">PEDRO</option> --}}
                    </select>
                    
                    <div class="sub-title">Año:</div>
                    <select name="fecha" id="fecha" class="form-control" required>
                        <option value="">Seleccione año</option>                        
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        {{-- <option value="cym">CYM</option>
                        <option value="surja">SURJA</option>
                        <option value="yaneth">YANETH</option>
                        <option value="pedro">PEDRO</option> --}}
                    </select>

                    <br>
                    <button type="submit" name="excel" id="excel" class="btn btn-success" disabled>Generar reporte
                        Excel</button>
                    {{-- <button name="limpiar" id="limpiar" class="btn btn-primary" disabled>Limpiar</button> --}}
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
			$("#cadena").val(Date.now());
            const formulario = document.getElementById("reporteExcel");
            // const fechaInicio = $("#fechaInicio");
            // const fechaFinal = $("#fechaFinal");
            // const empresa = $("#empresa");
            // const limpiarButton = $("#limpiar");

            formulario.addEventListener("input", function() {
                if (formulario.checkValidity()) {
                    $("#excel").prop("disabled",
                        false);
                } else {
                    $("#excel").prop("disabled",
                        true);
                }
                // const hayDatos = fechaInicio.val() !== "" || fechaFinal.val() !== "" || empresa.val() !==
                //     "";
                // limpiarButton.prop("disabled", !hayDatos);
            });

            // limpiarButton.click(function() {
            //     $('#fechaInicio').val('dd/mm/aaaa');
            //     $('#fechaFinal').val('dd/mm/aaaa');
            //     $('#empresa').val('');
            //     $("#excel").prop("disabled", true);
            //     limpiarButton.prop("disabled", true);
            // });

        });
    </script>
@stop