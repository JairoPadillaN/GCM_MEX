@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaServicios')}}"><button type="button" class="btn btn-info">Alta de Servicios</button></a></td>
      <td><a href="{{asset('reporteServicios')}}"><button type="button" class="btn btn-info">Reporte de Servicios</button></a></td>
  @stop
