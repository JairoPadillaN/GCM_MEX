@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
      <td><a href="{{asset('altaEntradaAlmacen')}}"><button type="button" class="btn btn-info">Agregar otra compra</button></a></td>
      <td><a href="{{asset('reporteEntradaAlmacen')}}"><button type="button" class="btn btn-info">Reporte de Entrada Almacen</button></a></td>
  @stop
