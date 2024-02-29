@extends('principal')
@section('contenido')

    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('ViewerJS/web/viewer.css') }}" />
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://kit.fontawesome.com/6832f025eb.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('ViewerJS/web/viewer.js') }}"></script>
    


    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1>Editar Archivo</h1>
            </div>
            <form action="{{ route('editarArchivoUnico') }}" method="POST" id="file-form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                      @foreach ($archivosUnicos as $u)
                        
                      <div class="row">
                        <div class="col-md-6 mb-4">
                      
                          <div class="mb-3">
                            <label for="" class="form-label">Nombre Del Archivo</label> 
                            <input type="text" class="form-control" name="nombreArchivo" id="nombreArchivo" aria-describedby="helpId" value="{{$u->nombreArchivo}}">
                          </div>
                      
                        </div>
                        <div class="col-md-6 mb-4">
                      
                          <div class="mb-3">
                            <label for="" class="form-label">Fecha</label> 
                            <input type="date" class="form-control" name="fecha" id="fecha" aria-describedby="helpId" value="{{$u->fecha}}">
                          </div>
                      
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-12 mb-4">
                      
                          <div class="mb-3">
                            <label for="" class="form-label">Descripción </label> 
                            <input type="text" class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" value="{{$u->descripcion}}">
                          </div>
                      
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="mb-3">
                                @if ($u->archivo)
                                    <p>Archivo actual: <a href="{{ url('archivos/' . $u->archivo) }}" target="_blank">{{ $u->archivo }}</a></p>
                                @else
                                    <p>No hay archivo cargado</p>
                                @endif
                            </div>
                        </div>
                    </div>
        {{-- este bloque de codigo es para agregar el input de modificacion de archivo --}}
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="mb-3">
                                <label for="" class="form-label">Nuevo Archivo</label>
                                <input type="file" class="form-control" name="archivo" id="archivo" aria-describedby="helpId">
                            </div>
                        </div>
                    </div><br>
                    <div class="row" style="display: none;">
                      <div class="col-md-12 mb-4">
                          <div class="mb-3">
                              <label for="" class="form-label">ID del Archivo</label>
                              <input type="text" class="form-control" name="id" id="id" aria-describedby="helpId" value="{{$u->id}}" readonly>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12 mb-4 text-center">
                        <div class="mb-3">
                            @if ($u->archivo && (
                              pathinfo($u->archivo, PATHINFO_EXTENSION) == 'JPG' || 
                              pathinfo($u->archivo, PATHINFO_EXTENSION) == 'jpg' || 
                              pathinfo($u->archivo, PATHINFO_EXTENSION) == 'PNG' || 
                              pathinfo($u->archivo, PATHINFO_EXTENSION) == 'png' || 
                              pathinfo($u->archivo, PATHINFO_EXTENSION) == 'JPEG'||
                              pathinfo($u->archivo, PATHINFO_EXTENSION) == 'jpeg'))
                                <h3>{{$u->nombreArchivo}}</h3><br>
                                <img src="{{ url('archivos/' . $u->archivo) }}" alt="Vista previa" style="max-width: 50%; height: auto; margin: 0 auto;">
                            @elseif ($u->archivo)
                              @php
                                $fileExtension = pathinfo($u->archivo, PATHINFO_EXTENSION);
                              @endphp
                              
                              @if ($fileExtension == 'pdf')
                                  <iframe style="float:center;" src = "{{asset('/ViewerJS/#../archivos/' . $u->archivo)}}" width='800' height='566' allowfullscreen webkitallowfullscreen></iframe>
                              @else
                                  <div class="alert alert-warning">Vista previa no disponible para este tipo de archivo</div>
                              @endif
                            @else
                                  <div class="alert alert-warning">Vista previa no disponible para este tipo de archivo</div>
                            @endif
                        </div>
                    </div>
                </div>

                    </div>
                    @endforeach
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCerrar">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                  </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      document.getElementById('btnCerrar').addEventListener('click', function() {
          // Regresar a la página anterior
          window.history.back();
      });
  </script>
@stop


