@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaServTaller')}}"><button type="button" class="btn btn-info">Alta de servicios de taller</button></a></td>
      <td><a href="{{asset('reporteServTaller')}}"><button type="button" class="btn btn-info">Reporte de servicios de taller</button></a></td>
  @stop
