@extends('principal')
@section('contenido')<br><br><br>
    <h3 align="center">{{$proceso}}</h3>
    <br>
    <center>{{$mensaje}}<br><br>
    <td><a href="{{URL::action('servPublicidadController@altaServPublicidad')}}"><button type="button" class="btn btn-info">Agregar un nuevo registro</button></a></td>
    <td><a href="{{URL::action('servPublicidadController@reporteServPublicidad')}}"><button type="button" class="btn btn-info">Reporte servicios de publicidad</button></a></td>
  @stop
