
@section('contenido')<br><br><br>
    <h3 align="center">{{$proceso}}</h3>
    <br>
      <td><a href="{{URL::action('EquiposController@editarParteAbajo')}}"><button type="button" class="btn btn-info">Aceptar</button></a></td>
@stop
