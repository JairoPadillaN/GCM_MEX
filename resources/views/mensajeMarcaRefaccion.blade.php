@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaMarcasRefaccion')}}"><button type="button" class="btn btn-info">Alta marca refacciones</button></a></td>
      <td><a href="{{asset('reporteMarcasRefaccion')}}"><button type="button" class="btn btn-info">Reporte de marca refacciones</button></a></td>
  @stop
