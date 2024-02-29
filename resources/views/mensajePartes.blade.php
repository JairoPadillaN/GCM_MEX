@extends('principal')
@section('contenido')<br><br><br>
    <h3 align="center">{{$proceso}}</h3>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{URL::action('EquiposController@dividirEquipos',[$idEquipos])}}"><button type="button" class="btn btn-info">Aceptar</button></a></td>
  @stop
