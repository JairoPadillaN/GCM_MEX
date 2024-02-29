@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        //  alert ("hola");
        $("#filtroEquipos").load('{{url('reporteEquiposAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#GCM").click(function() {
        $("#filtroEquipos").load('{{url('reporteGCM')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#CYM").click(function() {
        $("#filtroEquipos").load('{{url('reporteCYM')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#GCMyCYM").click(function() {
        $("#filtroEquipos").load('{{url('reporteGCMyCYM')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#limpiar").click(function() {
        location.reload();
    });

    var table = $('#example').DataTable( {
        order : [[3,"desc"]],
        columnDefs: [ {
            "targets": [0,1,2,10,13],
            "orderable": false
        } ],
        dom: 'fBtlip',
        buttons: [
           { 
            titleAttr: 'Exportar a Excel',
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel-o"></i>',
            className: 'btn btn-success',
            exportOptions: {
                stripHtml: false,
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ]
                },
            filename: 'Reporte Equipos',
            sheetName: 'Equipos'
           }
        ],
        language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
		initComplete: function () {
            this.api().columns('.select-filter').every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
  
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                    $(select).click(function(e) {
                    e.stopPropagation();
                });
  
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
                
            } );
			this.api().columns('.input-filter').every( function () {
				var that = this;
                /*$( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );*/
				var input = $('<input type="text" placeholder="Buscar" />')
                    .appendTo( $(this.footer()).empty() )
                    .on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                    } );
                    $(input).click(function(e) {
                    e.stopPropagation();
                });
            } );
        }
    } );
});

</script>

<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Recepción de entradas al taller</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3"> Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">Cliente:
                        <select name='idc' id='idc' class="form-control rounded-0">
                            <option value="">Seleccione un cliente</option>
                            <option value='Todos'>Todos</option>
                            @foreach($clientes as $c) <option value='{{$c->idc}}'>
                            {{$c->razonSocial}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-3">Estatus:
                        <select name="estatus" class="form-control" id='status'>
                            <option value="" selected>Seleccione un estatus</option>
                            <option value="Todos">Todos</option>
                            <option value="En revisión">En revisión</option>
                            <option value="Reparación interna">Reparación interna</option>
                            <option value="Reparación externa">Reparación externa</option>
                            <option value="Reparado">Reparado</option>
                            <option value="Entregado">Entregado</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-6">
                    <button type="button" class="btn  btn-default" name="agrega" id="agrega">Aplicar filtro</button>
                    <button type="button" class="btn  btn-default" name="agrega" id="limpiar">Limpiar filtro</button>
                        <a href="{{asset('altaEquipos')}}">
                            <button type="button" class="btn btn-primary ">Agregar nuevo equipo
                            </button>
                        </a>
                    </div>
                    @if(Session::get('sesiontipo')=="Administrador")
                        <div class="col-lg-6" align="right">
                            <b>Mostrar solo:</b>&nbsp;&nbsp;&nbsp;
                            GCM <input type="radio" name="empresa" id="GCM" value="GCM">&nbsp;&nbsp;&nbsp;
                            CYM <input type="radio" name="empresa" id="CYM" value="CYM">&nbsp;&nbsp;&nbsp;
                            GCM y CYM <input type="radio" name="empresa" id="GCMyCYM" value="GCMyCYM">                                                            
                        </div>                
                    @endif
                </div>
                <br>
                <div id='filtroEquipos'>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="example" >
                            <thead>
                                <tr>
                                    <th>Foto principal</th>
                                    <th>Foto vista-frontal</th>
                                    <th>Foto placa 1</th>
                                    <th class="input-filter">Folio de recepción</th>
                                    <th class="input-filter">GCM ID</th>
                                    <th class="input-filter">Fecha de entrada</th>
                                    <th class="input-filter">Cliente - Sucursal</th>
                                    <th class="input-filter">Datos para certificado</th>
                                    <th class="input-filter">Equipo</th>
                                    <th class="input-filter">Marca - Modelo</th>
                                    <th>Importancia</th>
                                    <th class="select-filter">Estatus Reparación</th>
                                    <th class="select-filter">Estatus Entrega</th>
                                    <th class="input-filter">Piezas divididas</th>
                                    <th>Opciones</th>
                                </tr>
                                
                            </thead>
                            <tfoot><tr>
                                    <th>Foto principal</th>
                                    <th>Foto vista-frontal</th>
                                    <th>Foto placa 1</th>
                                    <th class="input-filter">Folio de recepción</th>
                                    <th class="input-filter">GCM ID</th>
                                    <th class="input-filter">Fecha de entrada</th>
                                    <th class="input-filter">Cliente - Sucursal</th>
                                    <th class="input-filter">Datos para certificado</th>
                                    <th class="input-filter">Equipo</th>
                                    <th class="input-filter">Marca - Modelo</th>
                                    <th>Importancia</th>
                                    <th class="select-filter">Estatus Reparación</th>
                                    <th class="select-filter">Estatus Entrega</th>
                                    <th class="input-filter">Piezas divididas</th>
                                    <th>Opciones</th>
                                </tr></tfoot>
                            
                            <tbody>
                                @foreach($consulta as $e)
                                <tr>
                                <!-- vista superior -->
                                    @if($e->vistaSuperior =="" || $e->vistaSuperior =="Sin archivo")
                                    <td style="color:red" align="center" >Pendiente por subir</td>
                                    @else 
                                    <td><a target="_blank" align="center"
                                            href="{{asset ('public/archivos/'.$e->vistaSuperior)}}"><img
                                                src="{{asset ('public/archivos/'.$e->vistaSuperior)}}" height=50
                                                width=100></a></td>
                                    @endif

                                <!-- vista Frente -->
                                    @if($e->vistaFrente =="Sin archivo" || $e->vistaFrente =="")
                                    <td style="color:red" align="center" >Pendiente por subir</td>
                                    @else 
                                    <td><a target="_blank" align="center"
                                            href="{{asset ('public/archivos/'.$e->vistaFrente)}}"><img
                                                src="{{asset ('public/archivos/'.$e->vistaFrente)}}" height=50
                                                width=100></a></td>
                                    @endif
                                
                                <!-- placa_1 -->
                                    @if($e->placa_1 =="" || $e->placa_1 =="Sin archivo")
                                    <td style="color:red" align="center" >Pendiente por subir</td>
                                    @else
                                    <td><a target="_blank" align="center"
                                            href="{{asset ('public/archivos/'.$e->placa_1)}}"><img
                                                src="{{asset ('public/archivos/'.$e->placa_1)}}" height=50
                                                width=100></a></td>
                                    @endif
                                    <td>{{$e->folioRecepcion }}</td>
                                    <td>{{$e->gcmid }}</td>
                                    <td>{{$e->fecha }}</td>
                                    <td>{{$e->cliSuc }}</td>
                                    <td>{{$e->datos}}</td>
                                    <td>{{$e->equipo }}</td>
                                    <td>{{$e->marcaModelo }}</td>
                                    @if($e->importancia=='Alta')
                                    <td align="center"><br><img src="{{asset ('public/archivos/circulo-rojo-png-4.png')}}" height=50 width=50></td>
                                    @endif

                                    @if($e->importancia=='Media')
                                    <td align="center"><br><img src="{{asset ('public/archivos/circulo-amarillo.png')}}" height=45 width=45></td>
                                    @endif

                                    @if($e->importancia=='Baja')
                                    <td align="center"><br><img src="{{asset ('public/archivos/circulo-verde.png')}}" height=50 width=65></td>
                                    @endif
                                    <td>{{$e->estatus }}</td>
                                    <td>{{$e->estatusEntrega }}</td>
                                    <td>{{$e->canti }}</td>
                                    <td>@if($e->activo=='Si')
                                        <a href="{{URL::action('EquiposController@dividirEquipos',['idEquipos'=>$e->idEquipos])}}"
                                            type="submit" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-wrench"></span> Dividir en partes
                                        </a> <br>
                                        <a href="{{URL::action('EquiposController@eliminarEquipos',['idEquipos'=>$e->idEquipos])}}"
                                            type="submit" class="btn btn-xs btn-danger"><i
                                                class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                        </a> <br>                                        
                                        <a href="{{URL::action('EquiposController@modificarEquipos',['idEquipos'=>$e->idEquipos])}}"
                                            type="submit" class="btn btn-xs btn-info" style="width:67px;">
                                            <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                        </a>                                        
                                        @else
                                        <a href="{{URL::action('EquiposController@restaurarEquipos',['idEquipos'=>$e->idEquipos])}}"
                                            class="btn btn-xs btn-warning">
                                            Restaurar</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    th {
    background: #ABEBC6;
    text-align: center;
    vertical-align: center;
    }
</style>
@stop