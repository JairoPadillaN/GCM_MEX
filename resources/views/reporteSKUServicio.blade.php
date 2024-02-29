@extends('principal')
@section('contenido')
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de SKU's asignados a Servicios</h1>
            </div><br>
            <div class="panel-body"> 
                
              
                <table id="TablaSKU" class="table table-striped table-bordered display">
                    <thead class="bg-gray-dark color-palette">
                        <tr style="background-color: #E0E0E0" >
                            <th>Servicio</th>
                            <th>Factura</th>
                            <th>Fecha de la factura</th>
                            <th>Fecha de pago</th>
                            <th>No.Cotizacion</th>
                            <th>Razón social</th>
                            <th>Sucursal</th>
                            <th>Nombre de la refacción</th>
                            <th>No.Piezas</th>
                            <th>No.de Parte</th>
                            <th>No.de Serie</th>
                            <th>Modelo</th>
                            <th>SKU</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Tipo de cotizacion</th> 
                            <th>Notas</th><!-- 17 -->
                        </tr>
                    </thead>
                    </table>
                                 
                </div>
            </div>
        </div>
    

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script>
//Comienza script de DataTable
window.addEventListener('DOMContentLoaded', (event) => {

// Setup - add a text input to each footer cell
$('#TablaSKU thead tr')
.clone(true) 
.addClass('filters')
.appendTo('#TablaSKU thead');


// Creando el cuerpo de la tabla con dataTable y ajax
var  tabla = $("#TablaSKU").dataTable({
    // Apartado para filtro por columna
    orderCellsTop: true,
    fixedHeader: true,
    
    initComplete: function () {
    var api = this.api();
    
        // For each column
        api
        .columns()
        .eq(0)
        .each(function (colIdx) {
            // Set the header cell to contain the input element
            var cell = $('.filters th').eq(
                $(api.column(colIdx).header()).index()
            );
            var title = $(cell).text();
            $(cell).html('<input type="text" placeholder="' + title + '" class=""/>');

            // On every keypress in this input
            $(
                'input',
                $('.filters th').eq($(api.column(colIdx).header()).index())
            )
            .off('keyup change')
            .on('change', function (e) {
                // Get the search value
                $(this).attr('title', $(this).val());
                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                var cursorPosition = this.selectionStart;
                // Search the column for that value
                api
                    .column(colIdx)
                    .search(
                        this.value != ''
                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                            : '',
                        this.value != '',
                        this.value == ''
                    )
                    .draw();
            })
            .on('keyup', function (e) {
                e.stopPropagation();

                $(this).trigger('change');
                $(this)
                    .focus()[0]
                    .setSelectionRange(cursorPosition, cursorPosition);
            });
        });
    },
    
    // Petición para llenar la tabla
    "ajax": {
        url: 'peticionSKU',
        dataSrc: ''
    },
    "columns": [
       {data: 'idservicios', render: function(data, type, row) {
            //Condicion del tipo de usuario
            var tipoUsuario = '{{Session::get('sesiontipo')}}';
            if (tipoUsuario === 'Vendedor' || tipoUsuario === 18) {
            return `<a href="modificarFacturas/${data}" target="_blank">${data}</a>`;
            }else{
                return data;
            }
        }},
        {data: 'factura', visible: true},//0
        {data: 'fechafactura',visible: true},//1
        {data: 'fechapago', visible:true},
        {data: 'numerocotizacion', render: function(data, type, row) {
            return `<a href="pdfCotizacion?idCotizacion=${row.idcotizacion}&verTotal=Si&pld=1&cD=Si" target="_blank">${data}</a>`;
        }},
        {data: 'razonsocial',visible: true},
        {data: 'sucursal',visible: true},
        {data: 'nombrerefaccion',visible: true},
        {data: 'piezas',visible: true},
        {data: 'numeroparte',visible: true},
        {data: 'serie',visible: true},
        {data: 'modelo',visible: true},
        {data: 'sku',visible: true},
        {data: 'nombreTipoRefaccion',visible: true},
        {data: 'marcarefaccion',visible: true},
        {data: 'comosecotizo',visible: true},
        {data: 'notas', visible: true},
    ],
    // Definir tamaño de columnas segun su index
    columnDefs: [
        {targets: 16,
        width: "500px",
        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
        $(td).css('text-align', 'justify'); // centrar el contenido de la celda
        }},
         {targets: 5,
        width: "300px",
        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
        $(td).css('text-align', 'justify'); // centrar el contenido de la celda
        }},
        {targets: 7,
        width: "250px",
        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
        $(td).css('text-align', 'justify'); // centrar el contenido de la celda
        }},
        {targets: 15,
        width: "200px",
        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
        $(td).css('text-align', 'justify'); // centrar el contenido de la celda
        }},
        {targets: 8,
        width: "50px",
        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
        $(td).css('text-align', 'center'); // centrar el contenido de la celda
        }},
        {targets: 4,
        width: "50px",
        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
        $(td).css('text-align', 'center'); // centrar el contenido de la celda
        }},
        {targets: 0,
        width: "50px",
        createdCell: function(td, cellData, rowData, rowIndex, colIndex) {
        $(td).css('text-align', 'center'); // centrar el contenido de la celda
        }},
                ],
    //idioma de los mensajes
    language: {
        info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty:      "Mostrando 0 a 0 de 0 registros",
        lengthMenu:     "Mostrar _MENU_ registros",
        search:         "Buscar:",
        loadingRecords: "Loading...",
        processing:     "Procesando...",
        zeroRecords:    "No hay registros aún",
        paginate: {
            // previous: "Anterior",
            // next: "Siguiente"
            next: '→',
            previous: '←',
            first:'Inicio',
            last:'Ultimo'
        },
    },
    // Mostrar los botones de paginacion
    pagingType: 'full_numbers',
    // Numero de registros por pagina
    lengthMenu: [10,15,25,50,100],
    // Botones para exportar información de la tabla 
    dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>rt<"row"<"col-sm-3"l><"col-sm-3"i><"col-sm-6"p>>',
        buttons: [
            {
                extend: 'excel',
                text: 'Excel',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13]
                },
                styles: {
                    tableHeader: {
                        alignment: 'center'
                    },
                }
            }
        ],
   
    scrollX: true, //Scroll horizontal
})

});
</script>

<style>
   thead input {
            width: 100%;
        }

        button:focus {
            outline: none;
        }

        .btn:focus {
            outline: none;
        }

        .dataTables_wrapper table {
            text-align:justify;
        }

        .dataTables_wrapper table thead th {
            text-align: center;
        }
        
</style>

@stop