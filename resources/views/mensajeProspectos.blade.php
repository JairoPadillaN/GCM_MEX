@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaProspectos')}}"><button type="button" class="btn btn-info">Alta de prospectos</button></a></td>
      <td><a href="{{asset('reporteProspectos')}}"><button type="button" class="btn btn-info">Reporte de prospectos</button></a></td>
  @stop
