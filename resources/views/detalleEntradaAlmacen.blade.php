@extends('principal')
@section('contenido')

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
                <h1>Detalle Entrada Almacen</h1>
        </div>
            <div class="tab-content">
                <div class="tab-pane active" id="registro" role="tabpanel" aria-labelledby="home-tab">
                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <div class="sub-title">Fecha de compra:</div>
                                <div>
                                        @if($errors->first('fechaCompra'))
                                        <i> {{ $errors->first('fechaCompra') }}</i>
                                        @endif
                                        {{Form::date('fechaCompra',($consulta->fechaCompra),['class' => 'form-control', 'readonly' => 'true'])}}
                                </div>
                                <div class="sub-title">Registrado por:</div>
                                <div>
                                    <input type='hidden' name='idu' id='idu' value="{{$consulta->idu}}">
                                    <input type="text" name="usuarioEquipos" class="form-control" readonly="true" value="{{$usuario->nombreUsuario}} {{$usuario->aPaterno}} {{$usuario->aMaterno}}">
                                </div>              
                                <div class="sub-title">Proveedor:</div>
                                <div>
                                        @if($errors->first('idProveedor'))
                                            <i> {{ $errors->first('idProveedor') }}</i>
                                        @endif
                                        <select name='idProveedor' class="form-control" disabled>
                                            <option value='{{$idProvSel}}'>{{$nombreProveedor}}</option>
                                            @foreach($proveedor as $prov)
                                            @if($prov->activo=="Si")
                                            <option value='{{$prov->idProveedor}}'>{{$prov->razonSocialProv}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                </div>
                                
                            </div>

                            <div class="form-group col-md-6">
                                <div class="sub-title">Número de factura:</div>
                                <div>
                                        @if($errors->first('numeroFactura'))
                                        <i> {{ $errors->first('numeroFactura') }}</i>
                                        @endif
                                        {{Form::text('numeroFactura', ($consulta->numeroFactura),['class' => 'form-control', 'readonly' => 'true'])}}
                                </div>
                                <!-- <div class="sub-title">Monto factura:</div>
                                <div>
                                        @if($errors->first('montoFactura'))
                                        <i> {{ $errors->first('montoFactura') }}</i>
                                        @endif
                                        {{Form::text('montoFactura',($consulta->montoFactura),['class' => 'form-control', 'placeholder'=>'Ejemplo: 405.00'])}}
                                </div> -->
                                <div class="sub-title">Tipo de moneda:</div>
                                <div>
                                {{Form::text('moneda', ($consulta->moneda),['class' => 'form-control', 'readonly' => 'true'])}}
                                </div>
                                <div class="sub-title">Recibido en almacen por:</div>
                                <div>
                                        @if($errors->first('recibidoPor'))
                                        <i> {{ $errors->first('recibidoPor') }}</i>
                                        @endif
                                        {{Form::text('recibidoPor',($consulta->recibidoPor),['class' => 'form-control', 'readonly' => 'true'])}}
                                </div>
                                <!-- <div class="sub-title">Archivo de factura: </div>
                                <div>
                                @foreach($archivoEditar as $archivo)
                                @if($archivo->archivoFactura =='Sin archivo')
                                                        <h5 align="center"><span class="label label-warning">Sin archivo</span></h5>
                                                    @else
                                        <a target="_blank" href="{{asset ('public/archivos/'.$archivo->archivoFactura)}}">
                                            @if(strpos($archivo->archivoFactura,'pdf'))
                                                <i class="fa fa-file-pdf-o fa-5x" aria-hidden="true" style="color: #750404;background-color: #fff;border-color: #4d42ff59;"></i>
                                            @else
                                                <img src="{{asset ('public/archivos/'.$archivo->archivoFactura)}}" height=80 width=80>
                                            @endif
                                            
                                        </a>
                                        @endif
                                        @endforeach
                                </div> -->

                            </div>
                        </div>
                </div>
                
            </div> 
            
            
            <div>
                @include('reporteEntradaAgregada')
            </div>
            </div>
    </div>
</div>


@stop