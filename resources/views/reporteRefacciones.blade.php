@extends('principal')
@section('contenido')

        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default" style="margin-top:-45px">
                <div class="panel-body">
				<h1>Reporte de Equipos</h1> <br>
				<!-- <div class="panel-heading">
                <a href="{{asset('altaRefacciones')}}"><button type="button"
                    class="btn btn-primary ">Agregar nueva refacción</button></a></div> -->
                    <div id='filtroRefacciones'>
                        <div id="list" class="table-responsive"></div>
                    </div>

                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
</div>
<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    if(tipoSesion=='Administrador'){
    $(document).ready(function(){

        $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            
            columnas: [
                { leyenda: 'Foto placa', style:'width:80px;background:#C5EBFB;'},
                { leyenda: 'Foto principal', style:'width:80px;background:#C5EBFB;'},
                { leyenda: 'Fecha Compra', ordenable: true, columna: 'fecha', style:'width:80px;background:#C5EBFB;'},
                { leyenda: 'Número Factura', ordenable: true, columna: 'numeroFactura', filtro: true, style:'width:100px;background:#C5EBFB;'},
                { leyenda: 'Tipo de entrada', ordenable: true, columna: 'tipoEntrada', style:'width:100px;background:#C5EBFB;'},
                { leyenda: 'Nombre del equipo', ordenable: true, columna: 'nombreRefaccion', filtro: true, style:'width:150px;background:#C5EBFB;'},
                { leyenda: 'Número de parte', ordenable: true, columna: 'numeroParte', filtro: true, style:'width:100px;background:#C5EBFB;'},
                { leyenda: 'Precio venta', columna: 'precio', style:'width:120px;background:#C5EBFB;'},
                { leyenda: 'Ubicación', ordenable: true, columna: 'ubicacion', filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Tipo Producto', columna: 'tipoProducto', ordenable: true,  style:'background:#C5EBFB;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Refacciones Reparación', contenido: 'Reparacion' },                                       
                            { valor: 'Refacciones Venta', contenido: 'Venta' },                                           
                            { valor: 'Para cambio', contenido: 'Para cambio' },                                           
                        ]
                        });
                } },
                { leyenda: 'Disponible', style:'background:#C5EBFB;'},
                { leyenda: 'Opciones', style:'background:#C5EBFB;'},
            ],
            modelo: [
                { formato: function(tr, obj,celda){

                let imagen = '';
                    
                if (obj.fotoPlaca == 'Sin archivo' || obj.fotoPlaca == '' || obj.fotoPlaca === null) {
                        imagen += '<div style="color:red" align="center">Sin foto</div>';
                    }   else {                                    
                        imagen += '<a target="_blank" href="public/archivos/'+obj.fotoPlaca+'"><img src="public/archivos/'+obj.fotoPlaca+'" height=100 width=100/></a>';
                    }                                  
                    return imagen;                                
                }

                },
                { formato: function(tr, obj,celda){

                let imagen = '';
                    
                if (obj.fotoPrincipal == 'Sin archivo' || obj.fotoPrincipal == '' || obj.fotoPrincipal === null) {
                        imagen += '<div style="color:red" align="center">Sin foto</div>';
                    }   else {                                    
                        imagen += '<a target="_blank" href="public/archivos/'+obj.fotoPrincipal+'"><img src="public/archivos/'+obj.fotoPrincipal+'" height=100 width=100/></a>';
                    }                                  
                    return imagen;                                
                }

                },
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'numeroFactura' ,class:'text-center'},
                { propiedad: 'tipoEntrada' ,class:'text-center'},
                { propiedad: 'nombreRefaccion' ,class:'text-center'},
                { propiedad: 'numeroParte' ,class:'text-center'},
                { propiedad: 'precio' ,class:'text-center'},
                { propiedad: 'ubicacion' ,class:'text-center'},
                { propiedad: 'tipoProducto' ,class:'text-center'},
                {class:'text-center', formato: function(tr, obj,celda){

                    let disponible = '';
                    if(obj.GCMidParte){
                        disponible += '<b style="color:red">Ocupada en:'+obj.GCMidParte+' </b>';
                    }else{
                    if(obj.folioRecepcion){
                        disponible += '<b style="color:red">Ocupada en:'+obj.folioRecepcion+' </b>';
                    }else{
                        if(obj.total==0){
                            disponible += '<b style="color:red">Ocupada</b>';
                        }else{
                        disponible += '<b style="color:#36BF3D">Disponible '+obj.total+ ' de '+obj.cantidad+'</b>';
                        }
                            
                    }}
                                    return disponible;
                }
                },
                
            
                { class:'text-center', formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.activo=='Si') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-success',
                                contenido: 'Detalle Refacción',
                                href: 'modificarRefacciones/' + obj.idRefaccion
                            });

                        }
                    return botones;

                    },
                },
                
            ],
            url: 'filtroRefacciones',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idRefaccion',
            columna_orden: 'DESC'
        });
    })
    }else{
        $(document).ready(function(){

$("#list").anexGrid({
    class: 'table table-striped table-bordered table-hover',
    
    columnas: [
        { leyenda: 'Foto placa', style:'width:80px;background:#C5EBFB;'},
        { leyenda: 'Foto principal', style:'width:80px;background:#C5EBFB;'},
        { leyenda: 'Fecha Compra', ordenable: true, columna: 'fecha', style:'width:80px;background:#C5EBFB;'},
        // { leyenda: 'Número Factura', ordenable: true, columna: 'numeroFactura', filtro: true, style:'width:100px;background:#C5EBFB;'},
        { leyenda: 'Nombre de la refacción', ordenable: true, columna: 'nombreRefaccion', filtro: true, style:'width:150px;background:#C5EBFB;'},
        { leyenda: 'Número de parte', ordenable: true, columna: 'numeroParte', filtro: true, style:'width:100px;background:#C5EBFB;'},
        // { leyenda: 'Precio venta', columna: 'precio', style:'width:120px;background:#C5EBFB;'},
        { leyenda: 'Ubicación', ordenable: true, columna: 'ubicacion', filtro: true, style:'background:#C5EBFB;'},
        { leyenda: 'Tipo Producto', columna: 'tipoProducto', ordenable: true,  style:'background:#C5EBFB;', filtro: function(){
            return anexGrid_select({
                data: [
                    { valor: '', contenido: 'Todos' },
                    { valor: 'Refacciones Reparación', contenido: 'Reparacion' },                                       
                    { valor: 'Refacciones Venta', contenido: 'Venta' },                                           
                ]
                });
        } },
        { leyenda: 'Disponible', style:'background:#C5EBFB;'},
        { leyenda: 'Opciones', style:'background:#C5EBFB;'},
    ],
    modelo: [
        { formato: function(tr, obj,celda){

        let imagen = '';
            
        if (obj.fotoPlaca == 'Sin archivo' || obj.fotoPlaca == '' || obj.fotoPlaca === null) {
                imagen += '<div style="color:red" align="center">Sin foto</div>';
            }   else {                                    
                imagen += '<a target="_blank" href="public/archivos/'+obj.fotoPlaca+'"><img src="public/archivos/'+obj.fotoPlaca+'" height=100 width=100/></a>';
            }                                  
            return imagen;                                
        }

        },
        { formato: function(tr, obj,celda){

        let imagen = '';
            
        if (obj.fotoPrincipal == 'Sin archivo' || obj.fotoPrincipal == '' || obj.fotoPrincipal === null) {
                imagen += '<div style="color:red" align="center">Sin foto</div>';
            }   else {                                    
                imagen += '<a target="_blank" href="public/archivos/'+obj.fotoPrincipal+'"><img src="public/archivos/'+obj.fotoPrincipal+'" height=100 width=100/></a>';
            }                                  
            return imagen;                                
        }

        },
        { propiedad: 'fecha' ,class:'text-center'},
        // { propiedad: 'numeroFactura' ,class:'text-center'},
        { propiedad: 'nombreRefaccion' ,class:'text-center'},
        { propiedad: 'numeroParte' ,class:'text-center'},
        // { propiedad: 'precio' ,class:'text-center'},
        { propiedad: 'ubicacion' ,class:'text-center'},
        { propiedad: 'tipoProducto' ,class:'text-center'},
        // {class:'text-center', formato: function(tr, obj,celda){
        //     let producto = '';
        //     if(obj.tipoProducto=='refacciones'){
        //         producto += 'Refacción reparación';
        //     }else{
        //         producto += 'Refacción venta';
                    
        //     }
        //     return producto;
        // }
        // },
        // // { propiedad: 'GCMidParte' ,class:'text-center'},
        {class:'text-center', formato: function(tr, obj,celda){

            let disponible = '';
                    if(obj.GCMidParte){
                        disponible += '<b style="color:red">Ocupada en:'+obj.GCMidParte+' </b>';
                    }else{
                    if(obj.folioRecepcion){
                        disponible += '<b style="color:red">Ocupada en:'+obj.folioRecepcion+' </b>';
                    }else{
                        disponible += '<b style="color:#36BF3D">Disponible '+obj.total+ ' de '+obj.cantidad+'</b>';
                            
                    }}
                            return disponible;
        }
        },
        
    
        { class:'text-center', formato: function(tr, obj,celda){
                                
            let botones ='';

                if (obj.activo=='Si') {

                    botones += anexGrid_link({
                        class: 'btn btn-xs btn-success',
                        contenido: 'Detalle Refacción',
                        href: 'modificarRefacciones/' + obj.idRefaccion
                    });

                }
            return botones;

            },
        },
        
    ],
    url: 'filtroRefacciones',
    paginable: true,
    filtrable: true,
    limite: [10, 20, 50, 100],
    columna: 'idRefaccion',
    columna_orden: 'DESC'
});
})

        

    }
</script>
@stop