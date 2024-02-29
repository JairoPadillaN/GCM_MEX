<script>
$("#pdf").click(function() {
    window.open('{{url('descargarPDF')}}' + '?' + $(this).closest('form').serialize());
});
</script>

@if($cuantos>=1)

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th>Foto del equipo</th>
                <th>Foto de parte</th>
                <th>GCM ID PARTE</th>
                <th>Marca-Modelo</th>
                <th>Nombre de la parte</th>
                <th>Que se le realiza</th>
                <th>Fecha de recepción</th>
                <th>Fecha de entrega</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporte as $t)
            <tr>
                <td style="text-align:center; color:red">
                    @if($t->vistaSuperior=='')
                    <font SIZE=2>Sin foto</font>
                    @else
                    <a target="_blank" href="{{asset ('public/archivos/'.$t->vistaSuperior)}}">
                        <img src="{{asset ('public/archivos/'.$t->vistaSuperior)}}" height=80 width=80>
                    </a>
                    @endif
                </td>
                <td style="text-align:center; color:red">
                    @if($t->fotoParte=='Sin archivo')
                    <font SIZE=2>Sin foto</font>
                    @else
                    <a target="_blank" href="{{asset ('public/archivos/'.$t->fotoParte)}}">
                        <img src="{{asset ('public/archivos/'.$t->fotoParte)}}" height=80 width=80>
                    </a>
                    @endif
                </td>
                <td class="sorting_1">{{$t->GCMidParte}}</td>
                <td class="sorting_1">{{$t->marca}} - {{$t->modelo}}</td>
                <td class="sorting_1">{{$t->nombreParte}}</td>
                <td class="sorting_1">{{$t->queReparacion}}</td>

                @if($t->fecha=='')
                <td class="sorting_1" style="color:red" align="center">Sin fecha</td>
                @else
                <td class="sorting_1">{{$t->fecha}}</td>
                @endif
                @if($t->fechaE=='')
                <td class="sorting_1" style="color:red" align="center">Sin fecha</td>
                @else
                <td class="sorting_1">{{$t->fechaE}}</td>
                @endif
                <td class="sorting_1">{{$t->nombreEstatus}}</td>


            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<form action="">
    {{Form::hidden('idTaller',$idTaller)}}
    {{Form::hidden('fechaInicio',$fechaInicio)}}
    {{Form::hidden('fechaFin',$fechaFin)}}
    {{Form::hidden('idEquipos',$idEquipos)}}
    <button type="button" class="btn  btn-warning" name="agrega" id="pdf">Descargar PDF</button>
</form>
@else
<center>
    <div class="alert alert-danger" role="alert">No existen equipos para el taller seleccionado</div>
    @endif