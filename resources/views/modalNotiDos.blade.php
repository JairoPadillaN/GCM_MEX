<div class="table-responsive">
    <table class="table table-striped table-hover" id="Not" style="width:100%">
        <thead >
            <tr style="background-color: #B0B0B0">

                <th>Acción</th>
                <th>Mensaje</th>
                <th>Fecha/Hora</th>
                <th>Visto</th>
                </tr>
        </thead>
        <tbody>

            @if($cuantos>=1)
            @foreach ($consulta as $consu)
                <tr>

                    <td>{{$consu->accion}}</td>
                    <td>{{$consu->mensaje}}</td>
                    <td>{{$consu->fecha_hora}} hrs</td>
                    <td>
                        @if ($consu->visto === 1)
                        <center>
                        <button type="button" data-backdrop="static" data-keyboard="false"
                        class="btn btn-success btn-circle btn-sm visto" disabled>
                    <i class="fa fa-check-square-o fa-lg" aria-hidden="true" ></i>
                        </button>
                    </center>
                        @else
                        <center>
                        <form action="" method="POST" enctype="application/x-www-form-urlencoded"
                        name="frmdo{{$consu->idnot}}"
                        id="frmdo{{$consu->idnot}}" target="_self">

                        <input type="hidden"
                        value="{{ $consu->idnot }}"
                        name="idnot" id="idnot">

                        <button type="button" value="1" name="visto" data-backdrop="static" data-keyboard="false"
                        class="btn btn-primary btn-circle btn-sm visto">
                    <i class="fa fa-square-o fa-lg" aria-hidden="true"></i>
                    </button>
                </center>
                        </form>
                    @endif
                    </td>
                </tr>
            @endforeach
            @else
            <tr>

                <td colspan= 4><center>No hay notificaciones</center></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>


<script>
     $(document).ready(function() {


        $("#exampleModal2").modal({
            backdrop: 'static',
            keyboard: false
        });



        //↓ Refrescar seccion del div y realizar la accion
        $("#notiVista").on("click", ".visto", function() {
                $("#notiVista").load('{{ url('visto') }}' + '?' + $(this).closest('form').serialize());
            });

         // ↓ Recargar la página cuando se da clic al boton cerrar
         $("#cerrarModal").click(function() {
            window.location.reload();
            $("#notiVista").load('{{ url('cerrar') }}' + '?' + $(this).closest('form').serialize());
        });


    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }
</script>
