@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaBancos')}}"><button type="button" class="btn btn-info">Agregar un nuevo banco</button></a></td>
      <td><a href="{{asset('reporteBancos')}}"><button type="button" class="btn btn-info">Reporte de bancos</button></a></td>
  @stop
