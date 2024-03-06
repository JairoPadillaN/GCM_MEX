@extends('principal')
@section('contenido')

    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://kit.fontawesome.com/6832f025eb.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    


    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1>Archivos Únicos asd</h1>
            </div>

            <div class="panel-body" style="overflow-x: auto">
            @if (Session::get('sesiontipo') == 'Contador')
                    
                    @else
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{url('altaArchivoUnico')}}" type="button" class="btn btn-success"><i class="fa-solid fa-plus"></i> Nuevo</a>
                        </div>
                    </div>
                    @endif
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{url('altaArchivoUnico')}}" type="button" class="btn btn-success"><i class="fa-solid fa-plus"></i> Nuevo</a>
                    </div>
                </div>
                <div class="row">

                </div><br>
                <div id="tablaUnicos">
                    <table class="display" id="archivosUnicos" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="no-display">id</th>
                                <th>Nombre del Archivo</th>
                                {{-- <th>Descripción</th> --}}
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Archivo</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivosUnicos as $U)
                            @php
                             $fecha = date_create($U->fecha);
                            @endphp
                            <tr>
                                <td>{{$U->id}}</td>
                                <td>{{$U->nombreArchivo}}</td>
                                {{-- <td>{{$U->descripcion}}</td> --}}                                
                                <td>{{$U->id_usuario}}</td>
                                <td>{{date_format($fecha, 'd/m/Y')}}</td>
                                {{-- <td>{{$U->archivo}}</td> --}}
                                <td>
{{--                                     <form action="" method="POST" enctype="application/x-www-form-urlencoded">
                                        @csrf
                                        <input type="hidden" value="{{$U->id}}" name="id">
                                        <a href="{{route('reporteArchivoUnico')}}" class="verarchivos">Ver Archivo
                                        </a>
                                    </form> --}}
 {{--                                    <form action="" method="POST" enctype="application/x-www-form-urlencoded" class="archivoForm" data-modal="modalArchivos">
                                        @csrf
                                        <input type="hidden" value="{{$U->id}}" name="id">
                                        <a href="{{route('reporteArchivoUnico')}}" class="verarchivos">Ver Archivo</a>
                                    </form> --}}
{{--                                     <form action="" method="POST" enctype="application/x-www-form-urlencoded" class="archivoForm" data-modal="modalArchivos" data-id="{{$U->id}}">
                                        @csrf
                                        <a href="#" class="verarchivos">Ver Archivo</a>
                                    </form> --}}
                                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                        @csrf
                                        <input type="hidden" value="{{ $U->id }}" name="id">
                                        <a href="{{ route('reporteArchivoUnico') }}" class="verarchivos">Archivos
                                        </a>
                                    </form>
                                </td>
                                <td>
      {{--                               <form action="" method="POST" enctype="application/x-www-form-urlencoded">
                                        @csrf
                                        <input type="hidden" value="{{$U->id}}" name="id">
                                        <a 
                                        class="btn btn-info editarArchivo" 
                                        href="{{route('verArchivoUnico')}}">
                                        <i class="fa-regular fa-pen-to-square"></i></a>
                                    </form> --}}
{{--                                     <form action="" method="POST" enctype="application/x-www-form-urlencoded" class="archivoForm" data-modal="modalEditar">
                                        @csrf
                                        <input type="hidden" value="{{$U->id}}" name="id">
                                        <a class="btn btn-info editarArchivo" href="{{route('verArchivoUnico')}}">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                    </form> --}}
{{--                                     <form action="" method="POST" enctype="application/x-www-form-urlencoded" class="archivoForm" data-modal="modalEditar" data-id="{{$U->id}}">
                                        @csrf /*Este es el chido*/
                                        <a class="btn btn-info editarArchivo" href="{{route('verArchivoUnico')}}">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                    </form> --}}
                                    <a 
                                    class="btn btn-info editarArchivo" 
                                    href="{{route('obtenerArchivoUnico',$U->id)}}">
                                    <i class="fa-regular fa-pen-to-square"></i></a>
                                    <a 
                                    class="btn btn-danger eliminarArchivo" 
                                    href="{{route('eliminarArchivoUnico', $U->id)}}">
                                    <i class="fa-regular fa-trash-can"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div id="modalEditar"></div> --}}
    <div id="modalArchivos"></div>
    
    


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>

    
    <script>
        function mostrarToastArchivoGuardado() {
            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
        }


        function mostrarToastArchivoErrorGuardado() {
            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: "error",
            title: "{{ session('error') }}"
        });
        }

        function mostrarToastArchivoEliminado() {
            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: "warning",
            title: "{{ session('status') }}"
        });
        }

        @if(session('success'))
            mostrarToastArchivoGuardado();
        @elseif(session('error'))
            mostrarToastArchivoErrorGuardado();
        @elseif(session('status'))
        mostrarToastArchivoEliminado();
        @endif
    </script>
    

    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#archivosUnicos thead tr');
            var table = $('#archivosUnicos').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                columnDefs: [{
                    targets: 'no-display',
                    orderable: true,
                    visible: false
                }, ],
                "order": [
                    [2, "desc"]
                ],
            });

/*             $('.editarArchivo').on('click', function (ev) {
        ev.preventDefault();
        console.log('Editar Archivo clicado');
        var form = $(this).closest('form');
        var modalId = form.data('modal');
        var fileId = form.data('id');
        console.log('File ID:', fileId);
        var url = '{{ route('verArchivoUnico') }}' + '?id=' + fileId;
        $('#' + modalId).load(url);
    }); */

/*     $('.verarchivos').on('click', function (e) {
        e.preventDefault();
        console.log('Ver Archivos clicado');
        var form = $(this).closest('form');
        var modalId = form.data('modal');
        var fileId = form.data('id');
        console.log('File ID:', fileId);
        var url = '{{ route('reporteArchivoUnico') }}' + '?id=' + fileId;
        $('#' + modalId).load(url);
    }); */

    $('#tablaUnicos').on('click', '.verarchivos', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var url = '{{ route('reporteArchivoUnico') }}' + '?' + form.serialize();
                $('#modalArchivos').load(url);
            });



            /*----------------------Eliminar archivo-------------------------*/

            $(document).on('click', '.eliminarArchivo', function(event) {
                event.preventDefault();

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                title: "¿Está seguro de eliminar este archivo?",
                text: "¡Está acción no se puede revertir!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Eliminar!",
                cancelButtonText: "No, Cancelar!",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                    title: "¡Eliminado!",
                    text: "Su archivo ha sido eliminado.",
                    icon: "success"
                    });

                    // Redirect to the link's href attribute after the user confirms the deletion
                    window.location.href = $(this).attr('href');
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                    title: "Cancelado",
                    text: "Aún se conserva el archivo",
                    icon: "error"
                    });
                }
                });
            });
        });

    </script>
    
@stop


{{-- CREATE TABLE `archivosUnicos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombreArchivo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT,
  `fecha` DATE NOT NULL,
  `archivo` VARCHAR(255),
  `id_usuario` VARCHAR(255) NOT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb3 --}}