{{ Form::hidden('idCotizacion',$idCotizacion,['id'=>'idCotizacion','readonly'])}}
@if($idCotizacion == 0)
<div class="alert alert-danger" role="alert">
    <center>
        <div>
            <b>Número de cotización ya existente</b>
        </div>
        
        <div>
            <button type="button" class="btn btn-warning" id="corregirNumCot">
                <span class="glyphicon glyphicon-arrow-left"></span> Generar nuevo número de cotización
            </button>
        </div>
    </center>
</div>
@else
    <div class="form-group col-md-12">
        <div class="form-group row">                            
            <div class="col-sm-3">
                <b>*¿Que tipo de equipo cotizas?:</b>
            </div>
            <div class="col-sm-3" >
                <select name='tipoCotizacion' id='tipoCotizacion' class="form-control">
                    <option value="">Seleccionar opción</option>                                       
                    <option value="reparacion">Reparación</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="refurbished">Refurbished y/o Repair</option>
                </select>
            </div>
        </div>
    </div>
@endif


<script>
$(document).ready(function(){
    $("#comboEquipos").select2({width:'100%'});
})

$("#corregirNumCot").click(function(e) {
    $("#comboSucursal").css('border-color', 'red');
    $("#comboSucursal").css('color', 'red');
    
    $("#comboSucursal").val('');
    $("#numCot").val('');    

    $('#tab2').css('visibility', 'hidden');
    $("#tab2").removeClass("active");
    $("#2").removeClass("tab-pane active");
    $("#2").toggleClass("tab-pane fade");
    $("#reparaciones").empty();

    $("#tab1").toggleClass("active");
    $("#1").removeClass("tab-pane fade");
    $("#1").toggleClass("tab-pane active");
    $('#tab1').css('visibility', 'visible');

    $('#botonParaGuardar').css('visibility', 'hidden');

});

$("#tipoCotizacion").change(function(e) {
        var opcionTipo = $('#tipoCotizacion').val();
        let comboSucursal = $("#comboSucursal").val();
        switch (opcionTipo) {
            case 'reparacion':                    
                $("#reporteReparaciones").empty();                    
                $('.seleccionarEquipo').css('display', 'block');                    
                $('.seleccionarEquipo').css('visibility', 'visible');
            break;                   
            case 'nuevo':                
                // $("#comboEquipos").val('');
                // $('#comboEquipos').select2('');
                $("#reporteReparaciones").empty();
                $('.seleccionarEquipo').css('display', 'none');
                $("#reporteReparaciones").load('{{url('cotizacionEquipoNuevo')}}' + '?' + $(this).closest('form').serialize(),
                    function() {
                        setTimeout(() => {
                            $("#marcas").select2({width:'100%'});
                            $("#partes").select2({width:'100%'});
                        }, 600);

                    }
                );
            break;
            case 'refurbished':
                // $("#comboEquipos").val('');
                // $('#comboEquipos').select2('');
                $("#reporteReparaciones").empty();
                $('.seleccionarEquipo').css('display', 'none');
                $("#reporteReparaciones").load('{{url('cotizacionRefurbished')}}' + '?' + $(this).closest('form').serialize(),
                    function() {
                        setTimeout(() => {
                            $("#marcas").select2({width:'100%'});
                            $("#partes").select2({width:'100%'});
                        }, 600);
                    }
                );
            break;
        }
    });
</script>