@extends('principal')
@section('contenido')


    <div id="page-inner">

        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class=""> Actualizacion de Rango Comisiones </h1> 
                    </div><br>

                    <div class="panel-body">
                        <form action="{{ route('editrango') }}" method="PUT">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Rango Inicial</label>
                                    <input type="hidden" name="id" value="{{$rangocomision->id}}">
                                    <input type="number" value="{{$rangocomision->rangoInicial}}" name="rangoInicial" class="form-control rangoinicial" id="inputEmail4"
                                        placeholder="Rango Inicial">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Rango Final</label>
                                    <input type="number" value="{{$rangocomision->rangoFinal}}" name="rangoFinal" class="form-control rangofinal" id="inputPassword4"
                                        placeholder="Rango Final">
                                </div>

                            </div>
                            <br>
                            <hr>
                            <label for="inputAddress">Porcentaje de Comision</label>
                            <div class="form-group ">

                                <label for="colFormLabel" class="col-sm-2 col-form-label">
                                    <h3 style="font-weight: bold;">%</h3>
                                </label>
                                <div class="col-sm-10">
                                    <input name="porcentajeComision" value="{{$rangocomision->porcentajeComision}}" step="1" type="number"
                                        class="form-control porcentaje" id="porcentaje" placeholder="Porcentaje">
                                </div>
                            </div><br><br><br>
                            

                            <br>
                            <hr>
                            <input name="vigente" type="hidden" value="1">

                            <!-- <div class="form-group col-md-4">
                          <label for="inputState">Vigente</label>
                          <select id="inputState" class="form-control">
                            <option selected>Seleccione...</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                          </select>
                        </div> -->

                    </div>
                    <div>
                      <center><button type="submit" class="btn-lg btn-primary">Actualizar Rango</button></center><br>


                    </div>
                        
                    
                    </form>


                </div>

            </div>

        </div>

    </div>

    </div>
    @if(Session::has('error'))
    <script>
      alert("{{Session::get('error')}}"); 
    </script>
    @endif
    <script type="text/javascript">

        $(document).ready(function() {
            $("#porcentaje").keyup(function() {
                var value = $(this).val();
                $("#porcentaje2").val(value);
            });
        
        $(".rangofinal").keyup(function () {
          var rangouno = parseFloat($(".rangofinal").val())
          var rangodos = parseFloat($(".rangoinicial").val())
            if (rangodos >= rangouno){
              $(".rangofinal").css("border", "red solid 1px");
              console.log("Funcionando")
            }else{
              $(".rangofinal").css("border", "green solid 1px");
            }
        });
        $(".rangoinicial").keyup(function () {
          var rangouno = parseFloat($(".rangofinal").val())
          var rangodos = parseFloat($(".rangoinicial").val())
            if (rangodos >= rangouno){
              $(".rangoinicial").css("border", "red solid 1px");
              console.log("Funcionando")
            }else{
              $(".rangoinicial").css("border", "green solid 1px");
            }
        });
      });
    </script>
@stop
