@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaMarcas')}}"><button type="button" class="btn btn-info">Alta de Marcas</button></a></td>
      <td><a href="{{asset('reporteMarcas')}}"><button type="button" class="btn btn-info">Reporte de Marcas</button></a></td>
  @stop
