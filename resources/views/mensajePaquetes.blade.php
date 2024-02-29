@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaPaquetes')}}"><button type="button" class="btn btn-info">Agregar otro paquete</button></a></td>
      <td><a href="{{asset('reportePaquetes')}}"><button type="button" class="btn btn-info">Reporte de paquetes</button></a></td>
  @stop
