@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaRefacciones')}}"><button type="button" class="btn btn-info">Alta refacciones</button></a></td>
      <td><a href="{{asset('reporteRefacciones')}}"><button type="button" class="btn btn-info">Reporte de refacciones</button></a></td>
  @stop
