@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{URL::action('cotizacionServiciosController@cotizacionServicios')}}"><button type="button" class="btn btn-info">Aceptar</button></a></td>
  @stop
