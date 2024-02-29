@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaPartesVenta')}}"><button type="button" class="btn btn-info">Agregar otra parte</button></a></td>
      <td><a href="{{asset('reportePartesVenta')}}"><button type="button" class="btn btn-info">Reporte de partes</button></a></td>
  @stop
