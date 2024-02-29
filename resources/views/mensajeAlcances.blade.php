@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaAlcances')}}"><button type="button" class="btn btn-info">Alta de Alcances</button></a></td>
      <td><a href="{{asset('reporteAlcances')}}"><button type="button" class="btn btn-info">Reporte de Alcances</button></a></td>
  @stop
