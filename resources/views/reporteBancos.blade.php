@extends('principal')
@section('contenido')

		  <div class="header">
          <h1 class="page-header">Reporte de Bancos</h1>
			</div>
            <div id="page-inner">

            <div class="row">
            	<div class="panel-heading"><a href="{{asset('altaBancos')}}"><button type="button" class="btn btn-primary ">Agregar nuevo banco</button></a></div>
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
																			<tr>
																				
																				<th>Banco</th>
																				<th>Moneda</th>
																				<th>Opciones</th>
																			</tr>
                                    </thead>
																		<tbody>
																      @foreach($consulta as $b)
																    <tr>
																      
																      <td>{{$b->nombre}}</td>
																	  <td>{{$b->moneda}}</td>
																     
																	<td>@if($b->activo=='si')
																          <a href="{{URL::action('BancosController@eliminarBancos',['idb'=>$b->idb])}}"  
																			type="submit" class="btn btn-xs btn-danger"><i class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
																          </a> <br>
																		 
																          <a href="{{URL::action('BancosController@modificarBancos',['idb'=>$b->idb])}}" class="btn btn-xs btn-info" style="width:67px; margin-top:5px">
																		  <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
																          </a>
																         @else
																          <a href="{{URL::action('BancosController@restaurarBancos',['idb'=>$b->idb])}}" class="btn btn-xs btn-warning" style="width:67px">
																            Restaurar</a>
																         @endif
																      </td>
																    </tr>
																    @endforeach
																    </tbody>

                                </table>
								

                            </div>

                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
    </div>
	@stop
