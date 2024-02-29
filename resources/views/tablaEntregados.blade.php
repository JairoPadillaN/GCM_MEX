@if ($cuantosrfq == 0)
    <br>
    <div class="alert alert-warning" role="alert" align="center">
        <label for="">Sin RFQs entregados</label>
    </div>
@else
    <table id="entregados" class="display nowrap" style="width: 100%">
        <thead>
            <tr>
                <th class="no-display hidden">idrfq</th>
                <th style="background-color: #C5EBFB">RFQ</th>
                <th style="background-color: #C5EBFB">Fecha de solicitud</th>
                <th style="background-color: #C5EBFB">Vendedor</th>
                <th style="background-color: #C5EBFB">Nivel de solicitud</th>
                <th style="background-color: #C5EBFB">Cliente</th>
                <th style="background-color: #C5EBFB">Estatus</th>
                <th style="background-color: #C5EBFB">Fecha entrega
                    <br>cotización a cliente</th>
                <th style="background-color: #C5EBFB">Fecha estimada
                    <br>de entrega</th>
                <th style="background-color: #C5EBFB">Observaciones</th>
                <th style="background-color: #C5EBFB">Opciones</th>
            </tr>
            <tr class="filters">
                <th class="no-display hidden">idrfq</th>
                <th>RFQ</th>
                <th>Fecha de solicitud</th>
                <th>Vendedor</th>
                <th>Nivel de solicitud</th>
                <th>Cliente</th>
                <th>Estatus</th>
                <th>Fecha entrega cotización a cliente</th>
                <th>Fecha estimada de entrega</th>
                <th>Observaciones</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consulta as $c)
                <tr>
                    <td class="hidden">{{ $c->idrfq }}</td>
                    <td>{{ $c->cmgrfq }}</td>
                    <td>{{ date('d-m-Y', strtotime($c->fechasolicitud)) }}
                    </td>
                    <td>{{ $c->nombre }}</td>
                    <td>{{ $c->nivelsolicitud }}</td>
                    <td>{{ $c->razonSocial }}</td>
                    <td>
                        {{ $c->estatus }}
                        @if (Session::get('sesiontipo') == 'Compras')
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                                <input type="hidden" value="entregados" name="nombreTab">
                                <a href="#" class="editarInfoEstatus">Cambiar</a>
                            </form>
                        @elseif (Session::get('sesiontipo') == 'Vendedor')
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                                <input type="hidden" value="entregados" name="nombreTab">
                                <a href="#" class="editarInfoEstatus">
                                    Cambiar
                                </a>
                            </form>
                        @elseif (Session::get('sesiontipo') == 'Administrador')
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                                @csrf
                                <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                                <input type="hidden" value="entregados" name="nombreTab">
                                <a href="#" class="editarInfoEstatus">
                                    Cambiar
                                </a>
                            </form>
                        @endif
                    </td>
                    <td>
                        @if ($c->fechaentrega == '1900-01-01')
                            Sin fecha asignada
                        @else
                            {{ date('d-m-Y', strtotime($c->fechaentrega)) }}
                    </td>
            @endif
            <td>
                @if ($c->fechaestimadaentrega == '')
                    Sin fecha asignada
                    @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                            @csrf
                            <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                            <input type="hidden" value="entregados" name="nombreTab">
                            <a href="#" class="editarInfoFecha">Agregar</a>
                        </form>
                    @endif
                @elseif($c->fechaestimadaentrega != '')
                    {{ date('d-m-Y', strtotime($c->fechaestimadaentrega)) }}
                    @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                        <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                            @csrf
                            <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                            <input type="hidden" value="entregados" name="nombreTab">
                            <a href="#" class="editarInfoFecha">Cambiar</a>
                        </form>
                    @endif
                @endif
            </td>
            <td>
                @if ($c->observaciones == '')
                    Sin observaciones

                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                        @csrf
                        <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                        <input type="hidden" value="entregados" name="nombreTab">
                        <a href="#" class="editarInfoObservacion">Agregar</a>
                    </form>
                @elseif($c->observaciones != '')
                    Con observaciones

                    <form action='' method='POST' enctype='application/x-www-form-urlencoded'>
                        @csrf
                        <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                        <input type="hidden" value="entregados" name="nombreTab">
                        <a href="#" class="editarInfoObservacion">Ver / Cambiar</a>
                    </form>
                @endif

            </td>
            <td>
                @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                    <a href="{{ route('detalleRfq', ['idrfq' => $c->idrfq]) }}" target="blank">
                        <button type="button" class="btn btn-success">
                            <i class="ace-icon fa fa-eye bigger-120"></i>
                        </button>
                    </a>
                @endif
                @if (Session::get('sesiontipo') == 'Compras' || Session::get('sesiontipo') == 'Administrador')
                    <a href="{{ route('reporteCardex', ['idrfq' => $c->idrfq]) }}" target="blank"> <!-- Cardex -->
                        <button type="button" class="btn btn-warning">
                            <i class="ace-icon fa fa-bar-chart-o bigger-120"></i>
                        </button></a>
                @endif
                @if (Session::get('sesiontipo') == 'Vendedor')
                    {{-- @if ($c->deleted_at)
                            <a href="{{ route('activarRfq', ['idrfq' => $c->idrfq]) }}">
                                <button type="button" class="btn btn-warning">Activar</button>
                            </a>
                        @else --}}
                    <a href="{{ route('modificarRfq', ['idrfq' => $c->idrfq]) }}">
                        <button type="button" class="btn btn-info">
                            <i class="ace-icon fa fa-pencil bigger"></i>
                        </button>
                    </a>

                    {{-- <a href="{{ route('desactivarRfq', ['idrfq' => $c->idrfq]) }}">
                                <button type="button" class="btn btn-danger">
                                    <i class="ace-icon fa fa-trash-o bigger-150"></i>
                                </button>
                            </a>
                        @endif --}}
                @endif

                <div id="Detalle" class="xd"> 
                    <form action="" method="POST" enctype="application/x-www-form-urlencoded">
                        @csrf
                    <input type="hidden" value="{{ $c->idrfq }}" name="idrfq">
                    <a href="{{route('modalDetalle')}}" class="btn btn-primary mDetalle">
                        <i class="ace-icon fa fa-binoculars bigger-120"></i>
                    </a>
                    </form>
                </div>

            </td>
            </tr>
@endforeach
</tbody>
</table>
@endif

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

    .xd{
            width: 50px;
            margin: auto;
            text-align: center;
        }
</style>
