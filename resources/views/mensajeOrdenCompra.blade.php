@extends('principal')
@section('contenido')<br><br><br>
    <h1 align="center">{{$proceso}}</h1>
    <br>
    <center>{{$mensaje}}<br><br>
    <td><a href="{{asset('altaOrdenCompra')}}"><button type="button" class="btn btn-info">Alta de orden de compra</button></a></td>
    <td><a href="{{asset('reporteOrdenesCompra')}}"><button type="button" class="btn btn-info">Reporte de ordenes de compra</button></a></td> 
@stop
