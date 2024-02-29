    @if ($cuantos == 0)
        <br>
        <div class="alert alert-warning" role="alert" align="center" class="date-table">
            <label for="">Sin ordenes de compra activas</label>
        </div>
    @else
        <table border="0" cellspacing="5" cellpadding="5" class="date-table">
            <tbody>
                <tr>
                    <td class="label-cell" style="font-size: 14px; font-weight:bold;">Fecha Inicial:</td>
                    <td><input class="date-input" type="text" id="min" name="min"></td>
                    <td class="label-cell" style="font-size: 14px; font-weight:bold;">Fecha Final:</td>
                    <td><input class="date-input" type="text" id="max" name="max"></td>
                    <td>
                        <div id="botonExcel"></div>
                    </td>
                    <td class="search-controls" style="font-size: 14px; font-weight:bold;">
                        Buscar:
                    </td>
                    <td>
                        <div id="searchContainer"></div>
                    </td>

                </tr>
            </tbody>
        </table>
        <table id="activastabla" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    {{-- <td style="display: none;">fechaoculta</td> --}}
                    <th style="background-color: #C5EBFB;">Folio de Orden</th>
                    <th style="background-color: #C5EBFB;">Fecha</th>
                    <th style="background-color: #C5EBFB;">Proveedor</th>
                    <th style="background-color: #C5EBFB;">Empresa</th>
                    <th style="background-color: #C5EBFB;">Importe</th>
                    <th style="background-color: #C5EBFB;">IVA</th>
                    <th style="background-color: #C5EBFB;">ISR</th>
                    <th style="background-color: #C5EBFB;">Retención</th>
                    <th style="background-color: #C5EBFB;">Total</th>
                    <th style="background-color: #C5EBFB;">Total MXN $
                        {{ number_format($totalMXN[0]->sumaTotalMXN, 2, '.', ',') }}</th>
                    <th style="background-color: #C5EBFB;">Estatus</th>
                    <th style="background-color: #C5EBFB;">Pago Final</th>
                    <th style="background-color: #C5EBFB;">Opciones</th>
                </tr>
                <tr class="filters">
                    <th>Folio de Orden</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Empresa</th>
                    <th>Importe</th>
                    <th>IVA</th>
                    <th>ISR</th>
                    <th>Retención</th>
                    <th>Total</th>
                    <th>Total MXN</th>
                    <th>Estatus</th>
                    <th>Pago Final</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consulta as $c)
                    <tr>
                        <td>{{ $c->codigoorden }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($c->fechaorden)->format('d/m/Y') }}</td> --}}
                        <td>{{ $c->fechaorden }}</td>
                        <td>{{ $c->razonSocialProv }}</td>
                        <td>{{ $c->nombreempresa }}</td>
                        <td>{{ $c->importeorden }}</td>
                        <td>{{ $c->ivacompra }}</td>
                        <td>{{ $c->isrcompra }}</td>
                        <td>{{ $c->retencioncompra }}</td>
                        <td>{{ $c->total }}</td>
                        <td>{{ $c->totalMXN }}</td>
                        <td>{{ $c->estatus }}</td>
                        <td>{{ $c->PagoFinal }}</td>
                        <td>
                            @if ($c->iconoPDF != '0')
                                <form class="pdf-form" method="POST" id="pdfForm" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="idOrden" class="idOrden" value="{{ $c->idorden }}">
                                    <button type="button" class="btn btn-default btn-xs pdf margin">
                                        <i class="fa fa-file-pdf-o" style="font-size:13px;color:#CC5035"></i>
                                    </button>
                                </form>
                                <a href="{{ 'notasEntrada/' . $c->idorden }}" title="Notas de entrada"
                                    style="display: inline-block; text-decoration: none;">
                                    <button type="button" class="btn btn-default btn-xs notasEntrada margin"
                                        style="color: #000000;">
                                        <i class="fa fa-file-text-o"></i>
                                    </button>
                                </a>
                                <br>
                                <a href="{{ 'modificarOrden/' . $c->idorden }}" title="Editar OC"
                                    style="display: inline-block; text-decoration: none;">
                                    <button type="button" class="btn btn-info btn-xs editar margin">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>

                                @if ($c->estatus === 'Sin programar pago')
                                    <a href="#" class="btn btn-danger btn-xs eliminar margin"
                                        data-id="{{ $c->idorden }}" title="Cancelar OC">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @else
                                    <div></div>
                                @endif
                            @else
                                <a href="{{ 'notasEntrada/' . $c->idorden }}" title="Notas de entrada"
                                    style="display: inline-block; text-decoration: none;">
                                    <button type="button" class="btn btn-default btn-xs notasEntrada margin"
                                        style="color: #000000;">
                                        <i class="fa fa-file-text-o"></i>
                                    </button>
                                </a>
                                <a href="{{ 'modificarOrden/' . $c->idorden }}" title="Editar OC"
                                    style="display: inline-block; text-decoration: none;">
                                    <button type="button" class="btn btn-info btn-xs editar margin">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>
                                <br>
                                @if ($c->estatus === 'Sin programar pago')
                                    <a href="#" class="btn btn-danger btn-xs eliminar margin"
                                        data-id="{{ $c->idorden }}" title="Cancelar OC">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @else
                                    <div></div>
                                @endif
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <script>
        $(document).ready(function() {

            // $('#pdfForm').on('submit', function(e) {
            //     e.preventDefault(); // Evita el envío predeterminado del formulario
            //     var idOrden = $(this).find('input[name="idOrden"]').val();

            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         url: $(this).attr('action'),
            //         type: 'POST',
            //         data: {
            //             idOrden: idOrden
            //         }
            //     }).done(function(response) {
            //         // Redirige al usuario a la URL del PDF en una nueva ventana
            //         var pdfUrl = '{{ url('pdfOrdenCompras') }}' + '/' + response.encryptedId;
            //         window.open(pdfUrl);
            //     }).fail(function(xhr, status, error) {
            //         console.error(error);
            //     });
            // });

            $('#activastabla').on('click', '.pdf', function(e) {
                e.preventDefault(); // Evita el comportamiento predeterminado del botón
                var $form = $(this).closest('form');
                var idOrden = $form.find('.idOrden').val();
                $.ajax({
                    url: "{{ route('encriptarIdPdf') }}",
                    type: "post",
                    data: {
                        idOrden: idOrden,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        window.open('{{ url('pdfOrdenCompras') }}' + '/' + res);
                    },
                    error: function(xhr, status, error) {}
                });
            });


            $('#activastabla').on('click', '.eliminar', function(e) {
                e.preventDefault(); // Evita que el enlace se abra
                var idOrden = $(this).data('id');
                Swal.fire({
                    title: 'Cancelar Orden de compra',
                    text: "La orden de compra pasará a la pestaña de Canceladas, ¿Desea continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `eliminarOrden/${idOrden}`,
                            type: "GET",
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            success: function(res) {},
                            error: function(err) {}
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1200)
                    }
                });
            });

            // function encriptarIdPdf(idOrden) {
            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         url: "{{ route('encriptarIdPdf') }}",
            //         type: "post",
            //         data: {
            //             idOrden: idOrden
            //         }
            //     }).done(function(res) {
            //         window.open('{{ url('pdfOrdenCompras') }}' + '/' + res);
            //     });
            // }

            DataTable.ext.search.push(function(settings, data, dataIndex) {
                let minDateStr = minDate.val();
                let maxDateStr = maxDate.val();
                let dateStr = data[1];
                let minDateMoment = moment(minDateStr, 'DD/MM/YYYY', true);
                let maxDateMoment = moment(maxDateStr, 'DD/MM/YYYY', true);
                let dateMoment = moment(dateStr, 'DD/MM/YYYY', true);

                if (
                    (minDateStr === null && maxDateStr === null) ||
                    (minDateStr === null && dateMoment.isSameOrBefore(maxDateMoment)) ||
                    (minDateMoment.isSameOrBefore(dateMoment) && maxDateStr === null) ||
                    (minDateMoment.isSameOrBefore(dateMoment) && dateMoment.isSameOrBefore(maxDateMoment))
                ) {
                    return true;
                }

                return false;
            });

            minDate = new DateTime('#min', {
                format: 'DD/MM/YYYY'
            });
            maxDate = new DateTime('#max', {
                format: 'DD/MM/YYYY'
            });


            let table = $('#activastabla').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel'],
                scrollX: true,
                orderCellsTop: true,
                fixedHeader: true,
                responsive: false,
                columnDefs: [{
                        "render": function(data, type, row) {
                            if (type === "sort") {
                                return data; // Mantener el formato
                            }
                            return moment(data).format("DD/MM/YYYY"); // Cambiar a formato deseado
                        },
                        "targets": [1] // Indice de la columna que contiene las fechas
                    },
                    {
                        targets: 12,
                        width: "20px",
                        createdCell: function(td, cellData, rowData, rowIndex,
                            colIndex) {
                            $(td).css('text-align',
                                'left'
                            );
                        }
                    },
                ],
                order: [
                    [1, "desc"] // Ordena la columna de fecha de manera descendente
                ],
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "datetime": {
                        "previous": "Anterior",
                        "next": "Próximo",
                        "hours": "Horas",
                        "minutes": "Minutos",
                        "seconds": "Segundos",
                        "unknown": "-",
                        "amPm": [
                            "AM",
                            "PM"
                        ],
                        "months": {
                            "0": "Enero",
                            "1": "Febrero",
                            "2": "Marzo",
                            "3": "Abril",
                            "4": "Mayo",
                            "5": "Junio",
                            "6": "Julio",
                            "7": "Agosto",
                            "8": "Septiembre",
                            "9": "Octubre",
                            "10": "Noviembre",
                            "11": "Diciembre"
                        },
                        "weekdays": [
                            "Dom",
                            "Lun",
                            "Mar",
                            "Mié",
                            "Jue",
                            "Vie",
                            "Sáb"
                        ]
                    }
                },
                // pageLength: 100,
                initComplete: function() {
                    var api = this.api();
                    api.columns().eq(0).each(function(colIdx) {
                        var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
                        var title = $(cell).text();
                        $(cell).html('<input type="text" placeholder="' + title + '" />');
                        $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                            .off('keyup change')
                            .on('keyup change', function(e) {
                                e.stopPropagation();
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})';
                                var cursorPosition = this.selectionStart;
                                api
                                    .column(colIdx)
                                    .search((this.value != "") ?
                                        regexr.replace('{search}', '(((' + this.value +
                                            ')))') : "", this.value != "", this.value == "")
                                    .draw();
                                $(this).focus()[0].setSelectionRange(cursorPosition,
                                    cursorPosition);
                            });
                    });
                }
            });


            document.querySelectorAll('#min, #max').forEach((el) => {
                el.addEventListener('change', () => $('#activastabla').DataTable().draw());
            });

            $('.dt-buttons').appendTo($('#botonExcel').parent());

            $('#exportarExcel').on('click', function() {
                table.button(0).trigger();
            });

            var searchInput = $('.dataTables_filter input').clone(true);

            $('#searchContainer').append(searchInput);
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
            text-align: center;
        }

        .dataTables_wrapper table thead th {
            text-align: center;
        }

        .hidden {
            display: none;
        }

        .date-table {
            width: auto;
            margin-bottom: 20px;
        }

        .date-table td {
            padding: 5px;
            vertical-align: middle;
        }

        .date-input {
            width: 200px;
            padding: 5px;
            border: 1px solid #ddd;
            background-color: #fff;
            font-size: 14px;
            border-radius: 5px;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .search-controls {
            width: 650px;
            text-align: right;
        }
    </style>
