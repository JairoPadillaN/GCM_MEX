@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaCitas')}}"><button type="button" class="btn btn-info">Agregar otra cita</button></a></td>
      <td><a href="{{asset('reporteCitas')}}"><button type="button" class="btn btn-info">Reporte de citas</button></a></td>
  @stop
