<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#exampleModal" id="activar"
    style="visibility:hidden">
</button>
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div style="text-align:right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row" style="background-color:#F3FAF9">
                    <div>
                        <center>
                            <h5 class="modal-title">Fotos</h5>
                        </center>
                    </div>
                </div>
                <div class="contenedor-fotos">
                    @foreach ($consulta as $key => $c)
                        @if ($c->foto1 != 'Sin foto')
                            <div>
                                <a target="_blank" href="{{ asset('archivos/' . $c->foto1) }}">
                                    @if (in_array(strtolower(pathinfo($c->foto1, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="contenedor">
                                            <img src="{{ asset('archivos/' . $c->foto1) }}" alt="{{ $c->foto1 }}"
                                                class="img-thumbnail" width="150px">
                                        </div>
                                    @else
                                        <div class="contenedor">
                                            <img src="{{ asset('img/archivosi.png') }}" alt="{{ $c->foto1 }}"
                                                class="img-thumbnail" width="130px">
                                            <p>{{ $c->foto1 }}</p>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endif

                        @if ($c->foto2 != 'Sin foto')
                            <div>
                                <a target="_blank" href="{{ asset('archivos/' . $c->foto2) }}">
                                    @if (in_array(strtolower(pathinfo($c->foto2, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="contenedor">
                                            <img src="{{ asset('archivos/' . $c->foto2) }}" alt="{{ $c->foto2 }}"
                                                class="img-thumbnail" width="150px">
                                        </div>
                                    @else
                                        <div class="contenedor">
                                            <img src="{{ asset('img/archivosi.png') }}" alt="{{ $c->foto2 }}"
                                                class="img-thumbnail" width="130px">
                                            <p>{{ $c->foto2 }}</p>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endif

                        @if ($c->foto3 != 'Sin foto')
                            <div>
                                <a target="_blank" href="{{ asset('archivos/' . $c->foto3) }}">
                                    @if (in_array(strtolower(pathinfo($c->foto3, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="contenedor">
                                            <img src="{{ asset('archivos/' . $c->foto3) }}" alt="{{ $c->foto3 }}"
                                                class="img-thumbnail" width="150px">
                                        </div>
                                    @else
                                        <div class="contenedor">
                                            <img src="{{ asset('img/archivosi.png') }}" alt="{{ $c->foto3 }}"
                                                class="img-thumbnail" width="130px">
                                            <p>{{ $c->foto3 }}</p>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endif

                        @if ($c->foto4 != 'Sin foto')
                            <div>
                                <a target="_blank" href="{{ asset('archivos/' . $c->foto4) }}">
                                    @if (in_array(strtolower(pathinfo($c->foto4, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="contenedor">
                                            <img src="{{ asset('archivos/' . $c->foto4) }}" alt="{{ $c->foto4 }}"
                                                class="img-thumbnail" width="150px">
                                        </div>
                                    @else
                                        <div class="contenedor">
                                            <img src="{{ asset('img/archivosi.png') }}" alt="{{ $c->foto4 }}"
                                                class="img-thumbnail" width="130px">
                                            <p>{{ $c->foto4 }}</p>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endif

                        @if ($c->foto5 != 'Sin foto')
                            <div>
                                <a target="_blank" href="{{ asset('archivos/' . $c->foto5) }}">
                                    @if (in_array(strtolower(pathinfo($c->foto5, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="contenedor">
                                            <img src="{{ asset('archivos/' . $c->foto5) }}" alt="{{ $c->foto5 }}"
                                                class="img-thumbnail" width="150px">
                                        </div>
                                    @else
                                        <div class="contenedor">
                                            <img src="{{ asset('img/archivosi.png') }}" alt="{{ $c->foto5 }}"
                                                class="img-thumbnail" width="130px">
                                            <p>{{ $c->foto5 }}</p>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endif
                    @endforeach



                </div>
            </div>
            <div class="modal-footer">
                <div style="text-align:center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"
                        onclick="cerrarM()">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
        $("#activar").click();
    });

    function cerrarM() {
        $(".modal-backdrop").removeClass("modal-backdrop fade in");
    }

    $('.carousel').carousel()
</script>

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
