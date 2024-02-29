@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaEstatusReparaciones')}}"><button type="button" class="btn btn-info">Alta de estatus de reparación</button></a></td>
      <td><a href="{{asset('reporteEstatusReparaciones')}}"><button type="button" class="btn btn-info">Reporte de estatus</button></a></td>
  @stop
