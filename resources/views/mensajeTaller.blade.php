@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaTaller')}}"><button type="button" class="btn btn-info">Alta de taller</button></a></td>
      <td><a href="{{asset('reporteTaller')}}"><button type="button" class="btn btn-info">Reporte de taller</button></a></td>
  @stop
