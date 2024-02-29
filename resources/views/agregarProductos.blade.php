@extends('principal')
@section('contenido')

{{Form::open(['route' => 'agregarOrden','id' => 'formularioOrden','files'=>true])}}
{{Form::token()}}

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Agregar productos a la orden
                <!-- <small> *Campos Requeridos</small> -->
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="sub-title">Producto: </div>
                    <div>
                        <select name="idPartesVenta" id="idPartesVenta" class="form-control">
                            <option value="">Selecciona un producto</option>
                            @foreach($consultaOrden as $orden)
                            <option value="{{$orden->idPartesVenta}}">{{$orden->nombreRefaccion}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="infoSku">
                        <div class="sub-title">SKU</div>
                        <div>
                            <input type="text" class="form-control" readonly>
                        </div>
                        <div class="sub-title">Descripción: </div>
                        <div>
                            <input type="text" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    
                    <div class="sub-title">Precio</div>
                    <div>
                        {{Form::text('precioOrden', old('precioOrden'),['class'=>'form-control','id'=>'precioOrden'])}}
                    </div>
                    <div class="sub-title">Cantidad: </div>
                    <div>
                        {{Form::text('cantidadOrden', old('cantidadOrden'),['class'=>'form-control','id'=>'cantidadOrden'])}}
                    </div>
                    <!-- <div class="sub-title">Importe: </div>
                    <div>
                        {{Form::text('importedOrden', old('importedOrden'),['class'=>'form-control','id'=>'importedOrden', 'readonly'])}}
                    </div> -->
                </div>
                <div class="col-md-4">
                    <div class="sub-title">IVA (%): </div>
                    <div>
                        {{Form::text('ivaOrden', $consulta->ivaOrden,['class'=>'form-control', 'id'=>'ivaOrden'])}}
                    </div>
                    <div class="sub-title">ISR (%): </div>
                    <div>
                        {{Form::text('isrOrden', $consulta->isrOrden,['class'=>'form-control', 'id'=>'isrOrden'])}}
                    </div>             
                    <!-- <div class="sub-title">TOTAL: </div>
                    <div>
                        {{Form::text('totalOrden', old('totalOrden'),['class'=>'form-control','id'=>'totalOrden','readonly'])}}
                    </div> -->
                </div>
            </div>

            <div><br><br>
                <center> <button type="button" class="btn btn-info" id="agregarOrden">Agregar</button></center>
            </div><br><br>

            <div id="reporteOrden">
                @if($cuantasOrden==0)
                    <div class="alert alert-warning" role="alert">
                    No tiene productos agregados
                    </div>
                @else
                    @include('reporteOrdenAgregada')
                @endif
            </div>
        </div>
    </div>
</div>
@stop
