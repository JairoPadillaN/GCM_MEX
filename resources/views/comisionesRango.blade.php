@extends('principal')
@section('contenido')

		  
            <div id="page-inner">

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                    <div class="panel-heading">
                <h1 class="">Rango de Comisiones</h1> <a href="{!! asset('createrango')!!}" style="float:right;" type="button" class="btn btn-primary" >
  Agregar un nuevo rango
                </a>
            </div><br>
                        
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
																			<tr>
																				
																				<th>#</th>
																				<th>Rango Inicial</th>
                                                                                <th>Rango Final</th>
                                                                                <th>Porcentaje de Comisión</th>
                                                                                <th>Vigente</th>
                                                                                <th>Opciones</th>
																			</tr>
                                    </thead>
																		<tbody align="center">
                                      <?php 
                                      $i = 0;
                                      ?>
                                      @foreach($comisiones as $comision)
                                      <?php 
                                      $i++;
                                      ?>
                                      <tr>
                                        <td>
                                          {{$i}}
                                        </td>
                                        <td>
                                          ${{$comision->rangoInicial}}
                                        </td>
                                        <td>
                                          ${{$comision->rangoFinal}}
                                        </td>
                                        <td>
                                          %{{$comision->porcentajeComision}}
                                        </td>
                                        <td>
                                          @if($comision->vigente==1)
                                          Si
                                          @else
                                          No
                                          @endif
                                        </td>
                                        <td>
                                          <a href="{{asset('updaterango/')}}/{{$comision->id}}"><button class="btn-sm btn-primary">
                                          <i class="ace-icon fa fa-pencil bigger-120 " ></i> Editar
                                          </button></a>
                                          <form action="{{ route('deleterango') }}" method ="PUT">
                                          @csrf
                                          <button class="btn-sm btn-danger">
                                          <input type="hidden" name="id" value="{{$comision->id}}">
                                          <i class="ace-icon fa fa-trash-o bigger-120"></i> Eliminar
                                          </button>
                                         </form>
                                         
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
    @if(Session::has('error'))
    <script>
      alert("{{Session::get('error')}}"); 
    </script>
    @endif
<script type="text/javascript">

$(document).ready(function () {
        $("#porcentaje").keyup(function () {
            var value = $(this).val();
            $("#porcentaje2").val(value);
        });
});


</script>
	@stop
