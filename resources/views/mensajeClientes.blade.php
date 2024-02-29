@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaClientes')}}"><button type="button" class="btn btn-info">Agregar Otro Cliente</button></a></td>
      <td><a href="{{asset('consultaClientes')}}"><button type="button" class="btn btn-info">Reporte de Cliente</button></a></td>
  @stop
