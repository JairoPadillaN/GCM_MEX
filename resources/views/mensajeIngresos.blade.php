@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaIngresos')}}"><button type="button" class="btn btn-info">Alta de Ingresos</button></a></td>
      <td><a href="{{asset('reporteOtrosing')}}"><button type="button" class="btn btn-info">Reporte de Otros Ingresos</button></a></td>
  @stop
