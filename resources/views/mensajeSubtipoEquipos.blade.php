@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaSubtipoEquipos')}}"><button type="button" class="btn btn-info">Alta de Subtipos de equipos</button></a></td>
      <td><a href="{{asset('reporteSubtipoEquipos')}}"><button type="button" class="btn btn-info">Reporte de Subtipos de equipos</button></a></td>
  @stop
