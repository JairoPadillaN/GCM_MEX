@extends('principal')
@section('contenido')<br><br><br>
    @if(Session::get('sesiontipo')=="Administrador")
		    <h1 align="center">{{$proceso}}</h1>
		    <br>
		    <center>{{$mensaje}}<br><br>
		      <td><a href="{{asset('altaFacturas')}}"><button type="button" class="btn btn-info">Agregar nuevo Servicio</button></a></td>
			  <td><a href="{{asset('reporteFacturacion')}}"><button type="button" class="btn btn-info">Reporte de Servicios</button></a></td> 
			  <!--<td><a href="{{asset('reporteFacturas')}}"><button type="button" class="btn btn-info">Reporte de Servicios</button></a></td>-->
   	@endif
   	
   	@if(Session::get('sesiontipo')=="Vendedor")
		    <h1 align="center">{{$proceso}}</h1>
		    <br>
		    <center>{{$mensaje}}<br><br>
		      <!--<td><a href="{{asset('reporteFacturas')}}"><button type="button" class="btn btn-info">Reporte de Servicios</button></a></td>-->
			  <td><a href="{{asset('reporteFacturacion')}}"><button type="button" class="btn btn-info">Reporte de Servicios</button></a></td>
   	@endif
  
  @stop
