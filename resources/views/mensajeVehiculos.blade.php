@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('AltaVehiculos')}}"><button type="button" class="btn btn-info">Alta de Vehículos</button></a></td>
      <td><a href="{{asset('ReporteVehiculos')}}"><button type="button" class="btn btn-info">Reporte de Vehículos</button></a></td>
  @stop
