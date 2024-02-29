@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaCuentas')}}"><button type="button" class="btn btn-info">Alta de cuentas</button></a></td>
      <td><a href="{{asset('reporteCuentas')}}"><button type="button" class="btn btn-info">Reporte de cuentas</button></a></td>
  @stop
