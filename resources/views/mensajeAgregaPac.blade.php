@extends('principal')
@section('contenido')<br><br><br>
    <h3 align="center">{{$proceso}}</h3>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{URL::action('paquetesController@agregarPartePaquete',[$idPaquete])}}"><button type="button" class="btn btn-info">Aceptar</button></a></td>
  @stop
