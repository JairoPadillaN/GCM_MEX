
@extends('principal')
@section('contenido')
<style>
    li.paginate_button {
        padding: 0em !important;
    }
    thead input {
        width: 100%;
    }

    /* Ensure that the demo table scrolls */
    .noWrap { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
 
    div.container {
        width: 80%;
    }
    /* #tablaProductosNota tr {
        text-align: center;
    } */
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Rastreo de asignaciones</h1>
        </div>
        
        <div class="panel-body">
            <div class="">
                
                <div class="row col-sm-4" style="margin-bottom:20px;">
                    <label for="" class="">Seleccionar servicio:</label>
                    <div class="input-group">
                        <select class="form-control" name="idFactura" id="idFactura">
                            <option value="">Seleccionar</option>
                            @foreach($servicios as $servicio)
                                <option value='{{$servicio->idFactura}}'>{{$servicio->idServicios}}</option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-sm" type="button" id="btn_consultar_servicio">Consultar</button>
                        </span>
                    </div>
                </div>

                <div>
                    <table id="" class="table table-striped table-bordered display" style="width:100%;">
                        <thead class="">
                            <tr>
                                <th class="" style="background-color: #C5EBFB;text-align:center">Proveedores</th>
                                <th class="" style="background-color: #C5EBFB;text-align:center">Asignación</th>
                                <th class="" style="background-color: #C5EBFB;text-align:center">Almacén</th>
                                <th class="" style="background-color: #C5EBFB;text-align:center">Detalle</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo_tabla_rastreo"></tbody>
                    </table>
                </div>


                <!-- <table id="tabla_ratsreo_asignaciones" class="table table-striped table-bordered display" style="width:100%;">
                    <thead class="">
                        <tr>
                            <th class="" style="background-color: #C5EBFB;text-align:center">SKU</th>
                            <th class="" style="background-color: #C5EBFB;text-align:center">Descripción</th>
                            <th class="" style="background-color: #C5EBFB;text-align:center">Serie/Modelo</th>
                            <th class="" style="background-color: #C5EBFB;text-align:center">Cantidad</th>
                            <th class="" style="background-color: #C5EBFB;text-align:center">Servicio referencia</th>
                            <th class="" style="background-color: #C5EBFB;text-align:center">Detalle</th>
                        </tr>
                    </thead>
                </table> -->
            </div>
            


            <!-- Modal -->
            <div class="modal fade" id="modal_detalle_asignacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="row">
                                <div class="col-md-11">
                                    <h3 class="modal-title" id="exampleModalLabel">Detalles</h3>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body" id="contenidoModal_detalle_asignacion">
                            <table id="" class="table table-striped table-bordered display" style="width:100%;">
                                <thead class="">
                                    <tr>
                                        <th class="" style="background-color: #C5EBFB;text-align:center">Origen</th>
                                        <th class="" style="background-color: #C5EBFB;text-align:center">Sku</th>
                                        <th class="" style="background-color: #C5EBFB;text-align:center">Cantidad</th>
                                        <th class="" style="background-color: #C5EBFB;text-align:center">Almacén destino</th>
                                        <th class="" style="background-color: #C5EBFB;text-align:center">Fecha asignación</th>
                                        <!-- <th class="" style="background-color: #C5EBFB;text-align:center">Costo</th>
                                        <th class="" style="background-color: #C5EBFB;text-align:center">Subtotal</th> -->
                                    </tr>
                                </thead>
                                <tbody id="contenido_tabla_detalle_rastreo"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    var tablaNotasEntrada;

    $(document).ready(function(){
        $("#idFactura").select2({width:'100%'});
        crear_tabla_rastreo(0)
        cargar_listeners()
    });

    function cargar_listeners(){
        
        document.querySelector("#btn_consultar_servicio").addEventListener('click', () =>{
            let servicio = document.querySelector("#idFactura").value;
            crear_tabla_rastreo(servicio);
        });

        document.querySelector("#cuerpo_tabla_rastreo").addEventListener('click', (event) =>{
            if(event.target.classList.contains("btn_detalle_asignacion")){
                
                let id_orden_compra = event.target.value;
                let contenido_tabla_detalle_rastreo = document.querySelector("#contenido_tabla_detalle_rastreo");
                
                contenido_tabla_detalle_rastreo.innerHTML = ""

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: `/consulta_detalle_rastreo_asignacion/${id_orden_compra}`,
                    type: "get",
                    success: function (res) {
                        console.log(res)
                        console.log(res.length)

                        res.forEach(element => {
                            contenido_tabla_detalle_rastreo.innerHTML += `
                                <tr>
                                    <td style="text-align:center">${element.codigoOrden}</td>
                                    <td style="text-align:center">${element.sku}</td>
                                    <td style="text-align:center">${element.cantidadAservicio}</td>
                                    <td style="text-align:center">${element.almacen}</td>
                                    <td style="text-align:center">${element.fechaAsignacion}</td>
                                </tr>`
                            ;
                        });
                        
                        // <td style="text-align:right">${element.moneda} ${element.costo}</td>
                        // <td style="text-align:right">${element.moneda} ${element.subtotal}</td>
                        
                        $("#modal_detalle_asignacion").modal('show');
                    },
                    error: function (err) {
                        console.log(err)
                    }
                });
            }
        });
    }

    function crear_tabla_rastreo(id_servicio){
        console.log(id_servicio)
        let contenido_tabla_rastreo = document.querySelector("#cuerpo_tabla_rastreo")
        contenido_tabla_rastreo.innerHTML = "";

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `/consulta_rastreo_asignaciones/${id_servicio}`,
            type: "get",
            success: function (res) {
                console.log(res)
                if(res.length > 0){
                    res.forEach(element => {
                        contenido_tabla_rastreo.innerHTML += `
                            <tr>
                                <td style="text-align:left">${element.proveedor}</td>
                                <td style="text-align:center">${element.asignacion}</td>
                                <td style="text-align:center">${element.almacen}</td>
                                <td style="text-align:center">
                                    <button type="button" class="btn btn-info btn-sm btn_detalle_asignacion" value="${element.idOrden}">Ver</button>
                                </td>
                            </tr>`
                        ;
                    });
                }else{
                    contenido_tabla_rastreo.innerHTML += `
                        <tr>
                            <td style="text-align:center" colspan="4">Sin registros</td>
                        </tr>`
                    ;
                }

            },
            error: function (err) {
                console.log(err)
                contenido_tabla_rastreo.innerHTML += `
                    <tr>
                        <td style="text-align:center" colspan="4">Sin registros</td>
                    </tr>`
                ;
            }

        })
    }

</script>
@stop