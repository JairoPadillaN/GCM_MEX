@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaSucursales')}}"><button type="button" class="btn btn-info">Agregar sucursal</button></a></td>
      <td><a href="{{asset('reporteSucursales')}}"><button type="button" class="btn btn-info">Reporte de sucursales</button></a></td>
  @stop
