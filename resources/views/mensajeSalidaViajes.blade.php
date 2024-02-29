@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaSalidaViajes')}}"><button type="button" class="btn btn-info">Nueva Salida </button></a></td>
      <td><a href="{{asset('reporteViajes')}}"><button type="button" class="btn btn-info">Reporte Salidas</button></a></td>
  @stop
