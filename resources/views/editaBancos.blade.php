@extends('principal')
@section('contenido')

      <div class="header">
          <h1 class="page-header">Modifica de Banco <small>Modifique los datos del Banco </small></h1>
      </div>

    {{Form::open(['route' => 'editarBancos','files'=>true])}}
    {{Form::token()}}

<div id="page-inner">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">

            <div class="panel-heading">
                                  <div class="card-title">
                                      <div class="title">Datos del Banco </div>
                                  </div>
                        </div>

                <div class="panel-body">

          <div class="form-group col-md-6">
                                    <div class="sub-title">Nombre del Banco :</div>
                                    <div>
                                      @if($errors->first('nombre'))
                                      <p><i> {{ $errors->first('nombre') }}</i></p>
                                      @endif
                                        {{Form::hidden('idb',$consulta->idb)}}
                                        {{Form::text('nombre',($consulta->nombre),['class' => 'form-control'])}}
                                    </div>


                                    <div class="sub-title">Número de Cuenta:</div>
                                    <div>
                                      @if($errors->first('numeroDeCuenta'))
                                      <p><i> {{ $errors->first('numeroDeCuenta') }}</i></p>
                                      @endif
                                        {{Form::text('numeroDeCuenta',($consulta->numeroDeCuenta),['class' => 'form-control'])}}
                                   
                  </div>

                  <div class="sub-title">Tipo de Moneda: </div>
                                    <div>
                                      <select class="form-control" name="moneda">
                                        <option selected="selected" value=""><?php echo ($consulta->moneda) ?></option>
                                        <option value="Dolares (USD)">Dolares (USD)</option>
                                        <option value="Pesos (MXN)">Pesos (MXN)</option>
                                      </select>
                                    </div>

                                  
                               
                               
          </div>

                                
        </div>
         <br><center>{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}</center>
                                
            </div>
        </div>
    </div>
</div>
  @stop
