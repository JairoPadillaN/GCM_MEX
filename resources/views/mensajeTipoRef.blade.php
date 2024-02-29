@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaTipoRef')}}"><button type="button" class="btn btn-info">Alta de Tipo Refacciones</button></a></td>
      <td><a href="{{asset('reporteTipoRef')}}"><button type="button" class="btn btn-info">Reporte de Tipo Refacciones</button></a></td>
  @stop
