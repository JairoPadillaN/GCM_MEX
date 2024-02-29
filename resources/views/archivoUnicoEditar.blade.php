<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal2" id="activar2"
    style="visibility:hidden">
</button>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h5 class="modal-title" id="exampleModal2Label">Editar Archivo</h5>

            </div>
            <div class="modal-body">
                <form action="{{ route('editarArchivoUnico')}}" method="POST" id="file-form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @foreach($consultarArchivoUnico as  $U)
                        <div class="mb-3">
                            <label for="" class="form-label">ID Del Archivo</label> 
                            <input type="text" class="form-control" name="id" id="id" aria-describedby="helpId" value="{{$U->id}}" required>
                          </div>
                      <div class="mb-3">
                        <label for="" class="form-label">Nombre Del Archivo</label> 
                        <input type="text" class="form-control" name="nombreArchivo" id="nombreArchivo" aria-describedby="helpId" value="{{$U->nombreArchivo}}" required>
                      </div>
                      <div class="mb-3">
                        <label for="" class="form-label">Descripción </label> 
                        <input type="text" class="form-control" name="descripcion" id="descripcion" aria-describedby="helpId" value="{{$U->descripcion}}" required>
                      </div>
                      <div class="mb-3">
                        <label for="" class="form-label">Fecha</label> 
                        <input type="date" class="form-control" name="fecha" id="fecha" aria-describedby="helpId" value="{{$U->fecha}}" required>
                      </div>
                      <div class="mb-3">
                        <label for="" class="form-label">Archivo </label> 
                        <input type="file" class="form-control" name="archivo" id="archivo" aria-describedby="helpId" value="{{$U->archivo}}" required>
                      </div>
                      
                      {{$U->id}} {{$U->nombreArchivo}}{{$U->fecha}}
                      @endforeach
                    </div>
                  
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cerrarM()">
                        <span aria-hidden="true">Cerrar</span>
                    </button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                  </div>
            </form>
            </div>
        </div>
    </div>
</div>

<style>
    .sinBorder {
        border-style: none;
    }

    .modal-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .vertical-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .contenedor-fotos {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 30px;
    }

    .contenedor {
        text-align: center;
    }

    .contenedor img {
        display: block;
        margin: 0 auto;
    }

    .contenedor p {
        text-align: center;
        font-size: 11px;
    }
</style>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar2").click();});

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }
</script>
