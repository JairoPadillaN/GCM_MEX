{{Form::hidden('idCotizacion',$idCotizacion,['id' => 'idCotizacion'])}}
<center>
<div style='text-align'>
    @foreach($archivo as $archi)
    @if($archi->archivoCotizacion=='Sin archivo')
    <img src="{{asset('img/archivono.png')}}" height="50" width="50">
    <h6>Sin archivo de cotización</h6>
    @else
    <a target="_blank" href="{{asset('archivos/'.$archi->archivoCotizacion)}}">
        <img src="{{asset('img/archivosi.png')}}" height=50 width=50>
        <h6>Descargar cotización</h6>
    </a>
    @endif
    @endforeach
</div>