@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaMovimientos')}}"><button type="button" class="btn btn-info">Alta de Movimientos</button></a></td>
      <td><a href="{{asset('reporteMovimientos')}}"><button type="button" class="btn btn-info">Reporte de Movimientos</button></a></td>
  @stop
