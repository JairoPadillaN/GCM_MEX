@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('AltaUsuarios')}}"><button type="button" class="btn btn-info">Alta de Usuarios</button></a></td>
      <td><a href="{{asset('ReporteUsuarios')}}"><button type="button" class="btn btn-info">Reporte de Usuarios</button></a></td>
  @stop
