@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaEquipos')}}"><button type="button" class="btn btn-info">Alta de equipos</button></a></td>
      <td><a href="{{asset('reporteEquipos')}}"><button type="button" class="btn btn-info">Reporte entradas al almacen</button></a></td>
  @stop
