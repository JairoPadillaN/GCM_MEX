@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('AltaTipoGastos')}}"><button type="button" class="btn btn-info">Alta de Tipos de gastos</button></a></td>
      <td><a href="{{asset('ReporteTipoGastos')}}"><button type="button" class="btn btn-info">Reporte de Tipos de gastos</button></a></td>
  @stop
