@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaAreas')}}"><button type="button" class="btn btn-info">Alta de áreas</button></a></td>
      <td><a href="{{asset('reporteAreas')}}"><button type="button" class="btn btn-info">Reporte de áreas</button></a></td>
  @stop
