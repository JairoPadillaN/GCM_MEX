@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaProveedores')}}"><button type="button" class="btn btn-info">Alta de proveedores</button></a></td>
      <td><a href="{{asset('reporteProveedores')}}"><button type="button" class="btn btn-info">Reporte de proveedores</button></a></td>
  @stop
