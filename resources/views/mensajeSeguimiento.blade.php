@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaSeg')}}"><button type="button" class="btn btn-info">Alta de actividades</button></a></td>
      <td><a href="{{asset('reporteSeguimientoVista')}}"><button type="button" class="btn btn-info">Reporte de actividades</button></a></td>
  @stop
