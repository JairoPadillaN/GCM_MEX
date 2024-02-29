@extends('principal')
@section('contenido')

<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#idc").select2({width:'100%'});
});
</script>

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Cotizaciones<small> *Campos obligatorios</small></h1>
        </div>

        <div class="panel-body">
            <br>
            <form>
                <div class="row">
                    <div class="col-sm-6">
                        * Cliente:
                        <select name='idc' id='idc' class="form-control">
                            <option value="">Seleccionar cliente</option>
                            <!-- <option value="todos">Todos</option> -->
                            @foreach($cliente as $cliente)
                            @if($cliente->activo=="si")
                            <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        Fecha de Inicio:<input type='date' name='fechaInicio' id='fechaInicio' class="form-control rounded-0">
                    </div>

                    <div class="col-md-3">
                        Fecha de Fin:<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>                    
                </div>
                <br>
                <div class="" style="text-align:right;">                    
                    <button type="button" class="btn btn-default" id="filtrar">
                        Aplicar filtro
                    </button>
                    <button type="button" class="btn btn-default" id="limpiarFiltro">
                        Limpiar Filtro
                    </button>
                    <button type="button" class="btn btn-success btn-default" id="agregarCotizacion">
                        <span class="glyphicon glyphicon-plus-sign"></span> Agregar cotización
                    </button>
                </div>
            </form>
            <br>
            
            <div id='reporteVersiones'>
                <ul class="nav nav-tabs">
                    <li class="active" id='' ><a href="#11" data-toggle="tab"><label for="">Activas</label></a></li>
                    <li class="" id='' ><a href="#22" data-toggle="tab"><label for="">Canceladas</label></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="11">
                        <div id="list" class="table-responsive"></div>                                        
                    </div>

                    <div class="tab-pane fade" id="22">
                        <div id="listCanceladas" class="table-responsive"></div>
                    </div>  
                </div>
            </div>
            <div calss="row" style="" id='altaVersion'>            
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var tipo = '<?php echo $stipo?>';
    $(document).ready(function(){
        var tablita = $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',            
            columnas: [
                { leyenda: 'Número cotización', ordenable: true, filtro: true, columna: 'numeroCotizacion',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Fecha', ordenable: true, columna:  'fechaCotizacion',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Cliente / Sucursal', ordenable: true, filtro: true, columna: 'clienteSucursal',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Creada por', ordenable: true,filtro: true, columna: 'nombreUsuario',style:'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'ITEMS', ordenable: true, columna: 'noEquipos',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Monto', columna: 'montoMoneda', ordenable: true,style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Estatus ', columna: 'estatus',style:'background-color: #C5EBFB;text-align:center', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'En revisión', contenido: 'En revisión' },
                            { valor: 'Pendiente de autorización', contenido: 'Pendiente de autorización' },                                        
                            { valor: 'Aceptada con orden de compra', contenido: 'Aceptada con orden de compra' },                                        
                        ]
                        });
                } },                
                { leyenda: 'Opciones',style: 'width:240px;background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'numeroCotizacion' ,style:'text-align:center'},
                { propiedad: 'fechaCotizacion' ,style:'text-align:center'},
                { propiedad: 'clienteSucursal' ,style:'text-align:center'},                     
                { propiedad: 'usuario' ,style:'text-align:center'},
                { propiedad: 'noEquipos' ,style:'text-align:center'},      
                { propiedad: 'montoMoneda' ,style:'text-align:center', formato: function(tr, obj,celda){
                        let sinMonto ='';
                            // if (obj.montoMoneda=='MXN $0.00' || obj.montoMoneda=='USD $0.00') {
                            //     if (obj.noEquipos=='0') {
                            //         sinMonto +='$0.00';
                            //     }else{
                            //         sinMonto +='Sin monto por equipo repetido';
                            //     }
                            // }else{
                            //     sinMonto += obj.montoMoneda;                                
                            // }

                            if (obj.repetidos =='si') {
                                sinMonto +='Contiene equipos repetidos <br>'+obj.montoMoneda;
                            }else{
                                sinMonto += obj.montoMoneda;
                            }
                            
                        return sinMonto;
                    }                    
                },    
                { propiedad: 'estatus' ,style:'text-align:center'},
                { propiedad: 'idCotizacion',style:'text-align:center', formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.estatus=='Aceptada con orden de compra') {
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion                                
                            });

                            botones += anexGrid_boton({
                                class: 'btn btn-sm btn-info editar',
                                contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                value: obj.idCotizacion,
                                style: 'margin-right:3px;height:30px',

                            });
                            
                            botones += anexGrid_boton({
                                class: 'btn btn-warning btn-sm actualizar',
                                contenido: '<i class="fa fa-refresh"></i> Actualizar cotización',
                                value: obj.idCotizacion
                            });                                                    

                        }else if (obj.estatus=='Facturada') {
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion                                                            
                            });
                        
                        
                            if(tipo == "Administrador"){                                    
                                botones += anexGrid_boton({
                                    style: 'color: #fff;background-color:#4A235A;border-color:#4A235A;',
                                    class: 'btn btn-default btn-sm reactivar',
                                    contenido: 'Reactivar Cotización',
                                    value: obj.idCotizacion
                                });
                            }
                        
                        }else if(obj.clonActual=='Si'){
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion                                
                            });

                            botones += anexGrid_boton({
                                class: 'btn btn-sm btn-info editar',
                                contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                value: obj.idCotizacion,
                                style: 'margin-right:3px;height:30px',

                            });
                            
                            botones += anexGrid_boton({
                                class: 'btn btn-warning btn-sm actualizar',
                                contenido: '<i class="fa fa-refresh"></i> Actualizar cotización',
                                value: obj.idCotizacion
                            });

                            if (obj.numCotizacion > 1 && obj.estatus=='En revisión') {
                                botones += anexGrid_boton({
                                    style: 'color: #fff;background-color: #CEBBE1;border-color: #CEBBE1; margin-top: 2px;',
                                    class: 'btn btn-warning btn-sm rollback',
                                    contenido: '<span class="glyphicon glyphicon-share-alt"></span> Revertir cotización',
                                    value: obj.idCotizacion
                                });
                            }

                        }else{
                            
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px; height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion
                            });

                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #E17272;border-color: #E17272;width:185px',
                                class: 'btn btn-default btn-sm',
                                contenido: '<span class="glyphicon glyphicon-remove"></span> Cotización cancelada',
                                attr: ['disabled'],                                
                            });
                        }                      

                        return botones;

                    }
                },                
            ],
            url: 'anexgridVersionesCotizacion',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'created_at',
            columna_orden: 'DESC'
        });

        tablita.tabla().on('click', '.editar', function(){
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);    
            $('.ver').attr("disabled", true);    
            $("#altaVersion").empty();
            $("#altaVersion").load('{{url('editarCotizacion')}}' + '?' +'idCotizacion='+ $(this).val());            
        });

        tablita.tabla().on('click', '.actualizar', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $('.ver').attr("disabled", true);    
            $("#altaVersion").load('{{url('actualizarCotizacion')}}' + '?' +'idCotizacion='+ $(this).val()); 
        });

        tablita.tabla().on('click', '.ver', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $("#altaVersion").load('{{url('verCotizacion')}}' + '?' +'idCotizacion='+ $(this).val()); 
        });

        tablita.tabla().on('click', '.reactivar', function(){
            $("#altaVersion").empty();            
            $("#altaVersion").load('{{url('reactivarCotizacion')}}' + '?' +'idCotizacion='+ $(this).val()); 
            location.reload();
        });

        tablita.tabla().on('click', '.rollback', function(){
            if(confirm("¿Desea revertir la versión de cotización?")){
                $("#altaVersion").empty();            
                $("#altaVersion").load('{{url('rollbackCotizacion')}}' + '?' +'idCotizacion='+ $(this).val()); 
                location.reload();
                alert("Operacion exitosa");
            }else{
                alert("Operación cancelada");
            }            
        });
        
        ////////////////////////////////////////////////////////////////////

        var tablitaCanceladas = $("#listCanceladas").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',            
            columnas: [
                { leyenda: 'Número cotización', ordenable: true, filtro: true, columna: 'numeroCotizacion',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Fecha', ordenable: true, columna:  'fechaCotizacion',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Cliente / Sucursal', ordenable: true, filtro: true, columna: 'clienteSucursal',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Creada por', ordenable: true,filtro: true, columna: 'nombreUsuario',style:'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'ITEMS', ordenable: true, columna: 'noEquipos',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Monto', columna: 'montoMoneda', ordenable: true,style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Estatus ', columna: 'estatus',style:'background-color: #C5EBFB;text-align:center', ordenable: true},                
                { leyenda: 'Opciones',style: 'width:240px;background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'numeroCotizacion' ,style:'text-align:center'},
                { propiedad: 'fechaCotizacion' ,style:'text-align:center'},                     
                { propiedad: 'clienteSucursal' ,style:'text-align:center'},                     
                { propiedad: 'usuario' ,style:'text-align:center'},
                { propiedad: 'noEquipos' ,style:'text-align:center'},      
                { propiedad: 'montoMoneda' ,style:'text-align:center', formato: function(tr, obj,celda){
                        let sinMonto ='';
                            // if (obj.montoMoneda=='MXN $0.00' || obj.montoMoneda=='USD $0.00') {
                            //     if (obj.noEquipos=='0') {
                            //         sinMonto +='$0.00';
                            //     }else{
                            //         sinMonto +='Sin monto por equipo repetido';
                            //     }
                            // }else{
                            //     sinMonto += obj.montoMoneda;                                
                            // }

                            if (obj.repetidos =='si') {
                                sinMonto +='Contiene equipos repetidos <br>'+obj.montoMoneda;
                            }else{
                                sinMonto += obj.montoMoneda;
                            }

                        return sinMonto;
                    }                    
                },
                { propiedad: 'estatus' ,style:'text-align:center'},
                { propiedad: 'idCotizacion',style:'text-align:center', formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.estatus=='Aceptada con orden de compra') {
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion
                            });

                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #E172CC;border-color: #E172CC;width:185px',
                                class: 'btn btn-default btn-sm',
                                contenido: '<i class="fa fa-handshake-o" aria-hidden="true"></i> Cotización cerrada',
                                attr: ['disabled'],                                
                            });                            
                        }else if (obj.estatus=='Facturada') {
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion                                
                            });                            
                        }else if(obj.clonActual=='Si'){
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion                                
                            });

                            botones += anexGrid_boton({
                                class: 'btn btn-sm btn-info editar',
                                contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                value: obj.idCotizacion,
                                style: 'margin-right:3px;height:30px',

                            });
                            
                            botones += anexGrid_boton({
                                class: 'btn btn-warning btn-sm actualizar',
                                contenido: '<i class="fa fa-refresh"></i> Actualizar cotización',
                                value: obj.idCotizacion
                            });

                        }else{
                            
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px; height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacion
                            });

                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #E17272;border-color: #E17272;width:185px',
                                class: 'btn btn-default btn-sm',
                                contenido: '<span class="glyphicon glyphicon-remove"></span> Cotización cancelada',
                                attr: ['disabled'],                                
                            });
                        }                      

                        return botones;

                    }
                },                
            ],
            url: 'anexgridVersionesCanceladas',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'created_at',
            columna_orden: 'DESC'
        });

        tablitaCanceladas.tabla().on('click', '.editar', function(){
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);    
            $('.ver').attr("disabled", true);    
            $("#altaVersion").empty();
            $("#altaVersion").load('{{url('editarCotizacion')}}' + '?' +'idCotizacion='+ $(this).val());            
        });

        tablitaCanceladas.tabla().on('click', '.actualizar', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $('.ver').attr("disabled", true);    
            $("#altaVersion").load('{{url('actualizarCotizacion')}}' + '?' +'idCotizacion='+ $(this).val()); 
        });

        tablitaCanceladas.tabla().on('click', '.ver', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $("#altaVersion").load('{{url('verCotizacion')}}' + '?' +'idCotizacion='+ $(this).val()); 
        });

    });
</script>
<script type="text/javascript">
    $("#agregarCotizacion").click(function(e) {
        var comboClientes = $("#idc").val();

        if (comboClientes == '' || comboClientes == 'todos' ){
            alert("El campo *Cliente es obligatorio.");
            e.preventDefault();
        }else{    
        $("#altaVersion").load('{{url('altaCotizacion')}}' + '?' + $(this).closest('form').serialize());
        }
    });

    // $("#idc").change(function() {
    //     $("#altaVersion").empty();
    //     $("#reporteVersiones").empty();
    //     $("#reporteVersiones").load('{{url('reporteVersionesCotizacion')}}' + '?r=' + Date.now() + '&idc=' + this.options[this.selectedIndex].value);
    // });

    $("#filtrar").click(function() {
        $("#altaVersion").empty();
        $("#reporteVersiones").empty();
        $("#reporteVersiones").load('{{url('reporteVersionesCotizacion')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#limpiarFiltro").click(function() {
        location.reload();
    });
</script>
@stop