<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr style="background-color: #E8E8E8;">
                <th width="10%">
                    <font SIZE=2>Fecha</font>
                </th>
                <th width="15%">
                    <font SIZE=2>Nombre</font>
                </th>
                <th width="35%">
                    <font SIZE=2>Descripción</font>
                </th>
                <th width="30%">
                    <font SIZE=2>Agregado por:</font>
                </th>
                <th width="10%">
                    <font SIZE=2>Archivo</font>
                </th>
                <th width="10%">
                    <font SIZE=2>Opciones</font>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consultarchivos as $ca)
                @if ($ca->activo === 'Si')
                    <tr>
                        <td>{{ $ca->fecha }}</td>
                        <td>{{ $ca->nombre }}</td>
                        <td>{{ $ca->descripcion }}</td>
                        <td>{{ $ca->iduser }}</td>
                        <td align="center">
                            @if ($ca->archivo !== 'Sin archivo')
                                <div class="archivo-container">
                                    <a target="_blank"
                                        href="{{ asset('archivos/' . $ca->archivo) }}">
                                        <i class="fa fa-file"></i>
                                    </a>
                                </div>
                            @else
                                {{ $ca->archivo }}
                            @endif
                        </td>
                        <td align="center">
                            <form action='' method='POST' enctype='application/x-www-form-urlencoded'
                                name='frmdo{{ $ca->idac }}' id='frmdo{{ $ca->idac }}' target='_self'>
                                @csrf
                                <input type='hidden' value='{{ $ca->idacd }}' name='idacd' id='idacd'>
                                <input type='hidden' value='{{ $ca->idac }}' name='idac' id='idac'>
                                <button type="button" class="btn btn-sm btn-danger borrar">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>


<script type="text/javascript">
    $(function() {
        $('.borrarCo').click(
            function() {
                // alert("borrar");
                $("#reportearchivo").load('{{ url('borrarDetalle') }}' + '?' + $(this).closest('form')
                    .serialize());
            });
    });
</script>
