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
                <h1>Subir Archivo</h1>
            </div>
            <form action="{{ route('guardarArchivoUnico') }}" method="POST" id="file-form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                      
                      <div class="row">
                        <div class="col-md-6 mb-4">
                      
                          <div class="mb-3">
                            <label for="" class="form-label">Nombre Del Archivo</label> 
                            <input type="text" class="form-control" name="nombreArchivo" id="nombreArchivo" aria-describedby="helpId" placeholder="" required>
                          </div>
                      
                        </div>
                        <div class="col-md-6 mb-4">
                      
                          <div class="mb-3">
                            <label for="" class="form-label">Fecha</label> 
                            <input type="date" class="form-control" name="fecha" id="fecha" aria-describedby="helpId" placeholder="" required>
                          </div>
                      
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-12 mb-4">
                      
                          <div class="mb-3">
                            <label for="" class="form-label">Descripción </label> 
                            <textarea type="text" class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" placeholder="" required></textarea>
                          </div>
                      
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-12 mb-4">
                          <div class="mb-3">
                            <label for="" class="form-label">Archivo </label> 
                            <input type="file" class="form-control" name="archivo" id="archivo" aria-describedby="helpId" placeholder="" required>
                          </div>
                        </div>
                      </div>

                    </div>
                  
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


