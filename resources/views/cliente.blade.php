@extends('principal')
@section('contenido')

          <div class="header">
          <h1 class="page-header">Alta de Usuarios <small>Registrar nuevos clientes</small></h1>
            </div>

        {{Form::open(['route' => 'GuardarClientes','files'=>true])}}
        {{Form::token()}}

                <div id="page-inner">
                       <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                  <div class="card-title">
                                      <div class="title">Datos de cliente</div>
                                  </div>
                              </div>
                                <div class="panel-body">
                                    <div class="sub-title">Razon social:</div>
                                    <div>
                                            @if($errors->first('razonSocial'))
                                <p><i> {{ $errors->first('razonSocial') }}</i></p>
                                            @endif
                                {{Form::text('razonSocial',old ('razonSocial'),['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">contacto:</div>
                                    <div>
                                                                            @if($errors->first('contacto'))
                                                                            <p><i> {{ $errors->first('contacto') }}</i></p>
                                                                            @endif
                                                                                {{Form::text('contacto',old ('contacto'),['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">Dias de pago:</div>
                                    <div>
                                                                            @if($errors->first('diasDePago'))
                                                                            <p><i> {{ $errors->first('diasDePago') }}</i></p>
                                                                            @endif
                                                                            {{Form::text('diasDePago',old ('diasDePago'),['class' => 'form-control'])}}
                                    </div>

                                    <div class="sub-title">Fecha de pago:</div>
                                    <div>
                                                                            @if($errors->first('fechaDePago'))
                                                                            <p><i> {{ $errors->first('fechaDePago') }}</i></p>
                                                                            @endif
                                                                            {{Form::text('fechaDePago',old ('fechaDePago'),['class' => 'form-control'])}}
                                    </div>

                                                                             <div class="form-group">

                                                                {{ Form::select('estado', $estados, null, array('class'=>'form-control', 'placeholder'=>'Please select ...')) }}
                                                                            </div>






                                <!--                                        <div class="sub-title">Contacto de Ventas:</div>
                                    <div>
                                                                            @if($errors->first('contactoVentas'))
                                                                            <p><i> {{ $errors->first('contactoVentas') }}</i></p>
                                                                            @endif
                                                                            {{Form::text('contactoVentas',old ('contactoVentas'),['class' => 'form-control'])}}
                                    </div> -->

                                    <!--                                    <div class="sub-title">Foto de perfil:</div>
                                    <div>
                                                                            @if($errors->first('imagen'))
                                                                            <p><i> {{ $errors->first('imagen') }}</i></p>
                                                                            @endif
                                                                            {{Form::file('imagen')}}
                                                                        </div>

                                    <div class="sub-title">Tipo</div>
                                    <div>
                                        <select class="selectbox" name="tipo">
                                                                                <option selected="selected" value="">Seleccione el tipo de empleado</option>
                                        <option value="Administrador">Administrador</option>
                                        <option value="Chofer">Chofer</option>
                                        <option value="Tecnico">Tecnico</option>
                                        <option value="Vendedor">Vendedor</option>
                                      </select>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                     <div class="col-xs-12">
                         <div class="panel panel-default">
                           <div class="panel-heading">
                               <div class="card-title">
                                   <div class="title">Datos de venta</div>
                               </div>
                           </div>
                             <div class="panel-body">
                                 <div class="sub-title">Contacto de ventas:</div>
                                 <div>
                                                                     @if($errors->first('contactoVentas'))
                                                                     <p><i> {{ $errors->first('contactoVentas') }}</i></p>
                                                                     @endif
                                                                     {{Form::text('contactoVentas',old ('contactoVentas'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Correo de Ventas:</div>
                                 <div>
                                                                     @if($errors->first('correoVentas'))
                                                                     <p><i> {{ $errors->first('correoVentas') }}</i></p>
                                                                     @endif
                                                                     {{Form::email('correoVentas',old ('correoVentas'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Telefono de Ventas:</div>
                                 <div>
                                                                     @if($errors->first('telVentas'))
                                                                     <p><i> {{ $errors->first('telVentas') }}</i></p>
                                                                     @endif
                                                                     {{Form::text('telVentas',old ('telVentas'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Extención de Ventas:</div>
                                 <div>

                                                                     {{Form::text('extenVentas',old ('extenVentas'),['class' => 'form-control'])}}
                                 </div>



                            </div>
                     </div>
                  </div>


                   <div class="row">
                     <div class="col-xs-12">
                         <div class="panel panel-default">
                           <div class="panel-heading">
                               <div class="card-title">
                                   <div class="title">Datos de Grente</div>
                               </div>
                           </div>
                             <div class="panel-body">
                                 <div class="sub-title">Contacto de Grente:</div>
                                 <div>
                                                                     @if($errors->first('contactoGerente'))
                                                                     <p><i> {{ $errors->first('contactoGerente') }}</i></p>
                                                                     @endif
                                                                     {{Form::text('contactoGerente',old ('contactoGerente'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Correo del Gerente:</div>
                                 <div>
                                                                     @if($errors->first('correoGerente'))
                                                                     <p><i> {{ $errors->first('correoGerente') }}</i></p>
                                                                     @endif
                                                                     {{Form::email('correoGerente',old ('correoGerente'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Telefono del Gerente:</div>
                                 <div>
                                                                     @if($errors->first('telGerente'))
                                                                     <p><i> {{ $errors->first('telGerente') }}</i></p>
                                                                     @endif
                                                                     {{Form::text('telGerente',old ('telGerente'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Extención del Gerente:</div>
                                 <div>

                                                                     {{Form::text('extenGerente',old ('extenGerente'),['class' => 'form-control'])}}
                                 </div>



                                                                </div>
                            </div>
                     </div>
                  </div>


                  <div class="row">
                     <div class="col-xs-12">
                         <div class="panel panel-default">
                           <div class="panel-heading">
                               <div class="card-title">
                                   <div class="title">Datos de Compras</div>
                               </div>
                           </div>
                             <div class="panel-body">
                                 <div class="sub-title">Contacto de Compras:</div>
                                 <div>
                                                                     @if($errors->first('contactoCompras'))
                                                                     <p><i> {{ $errors->first('contactoCompras') }}</i></p>
                                                                     @endif
                                                                     {{Form::text('contactoCompras',old ('contactoCompras'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Correo de Compras:</div>
                                 <div>
                                                                     @if($errors->first('correoCompras'))
                                                                     <p><i> {{ $errors->first('correoCompras') }}</i></p>
                                                                     @endif
                                                                     {{Form::email('correoCompras',old ('correoCompras'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Telefono de Compras:</div>
                                 <div>
                                                                     @if($errors->first('telCompras'))
                                                                     <p><i> {{ $errors->first('telCompras') }}</i></p>
                                                                     @endif
                                                                     {{Form::text('telCompras',old ('telCompras'),['class' => 'form-control'])}}
                                 </div>

                                 <div class="sub-title">Extención de Compras:</div>
                                 <div>

                                                                     {{Form::text('extenCompras',old ('extenCompras'),['class' => 'form-control'])}}
                                 </div>
                                 <br><center>
                                                                {{Form::submit('Guardar',['class' => 'btn  btn-default'])}}



                                                                </div>
                            </div>
                     </div>
                  </div>
                            </div>

    @stop
