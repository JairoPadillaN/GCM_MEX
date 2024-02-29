<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#modalEntradaAlmacen" id="activar">
</button>

<!-- Modal -->
<form action='editarRefaccion' method='POST' enctype='multipart/form-data' id="formularioEditarRefaccion">
    @csrf
    <div class="modal fade bd-example-modal-lg" id="modalEntradaAlmacen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title" id="exampleModalLongTitle" align="center">Modificar Refacciones</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{Form::hidden('idRefaccion',($consultaRefacciones->idRefaccion),['class' => 'form-control'])}}
                {{Form::hidden('idProveedor',($consultaRefacciones->idProveedor),['class' => 'form-control'])}}
                {{Form::hidden('idEntrada',($consultaRefacciones->idEntrada),['class' => 'form-control'])}}
                {{Form::hidden('moneda',($consultaRefacciones->moneda ),['class' => 'form-control'])}}
                {{Form::hidden('idPartesVenta',($consultaRefacciones->idPartesVenta ),['class' => 'form-control'])}}
                
                <div class="panel-body">            
                    <div class="form-group col-md-6">
                        <div class="sub-title">Marca:</div>
                        <div>
                            <?php $content = DB::table('marcasRefaccion')->select('marcaRefaccion')
                            ->where('idMarcaRefa','=',$consultaRefacciones->idMarcaRefa)
                            ->get(); ?>
                            <select name='idMarcaRefa' class="form-control idMarcaRefa">
                                <option value="{{($consultaRefacciones->idMarcaRefa)}}">{{$content[0]->marcaRefaccion}}</option>
                                @foreach($marca as $mr)
                                @if($mr->activo=="Si")
                                <option value='{{$mr->idMarcaRefa}}'>{{$mr->marcaRefaccion}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="sub-title">*Tipo de refacción:</div>
                        <div>
                            <!-- consulta para tipo refacciones -->
                            <?php $content = DB::table('tiporefacciones')->select('nombreTipoRefaccion')
                            ->where('idTipoRefacciones','=',$consultaRefacciones->idTipoRefacciones)
                            ->get(); ?>
                            <!-- Termina consulta -->
                            <select name='idTipoRefacciones' class="form-control idTipoRefacciones">                        
                                <option value='{{($consultaRefacciones->idTipoRefacciones)}}'>{{$content[0]->nombreTipoRefaccion}}</option>
                            </select>
                        </div>

                        <div class="sub-title">Nombre de SKU:</div>
                            <select name="" id="comboRef" class="form-control mi-selector comboRef">
                                <option value="{{($consultaRefacciones->idRefaccion)}}">{{$consultaRefacciones->nombreRefaccion}}</option>
                            </select>
                        
                        <div id="datosSKU" class="datosSKU">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="sub-title">Número de parte:</div>
                                        <div>
                                            @if($errors->first('numeroParte'))
                                            <i> {{ $errors->first('numeroParte') }}</i>
                                            @endif
                                            {{Form::text('numeroParte',$consultaRefacciones->numeroParte,['class' => 'form-control', 'readonly','id'=>'numParte'])}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="sub-title">Número de serie:</div>
                                        <div>
                                            @if($errors->first('serie'))
                                            <i> {{ $errors->first('serie') }}</i>
                                            @endif
                                            {{Form::text('serie',$consultaRefacciones->serie,['class' => 'form-control', 'readonly','id'=>'numSerie'])}}
                                        </div>
                                    </div>
                                </div>  
                                <div class="sub-title">Código:</div>
                                <div id='codigoRefaccion'>
                                    <!-- <input class="form-control" type='text' name='codigoRefaccion' id='codigoRefaccion' readonly='readonly'> -->
                                    {{Form::text('codigoRefaccion',$consultaRefacciones->codigoRefaccion,['class' => 'form-control', 'readonly','id'=>'numCodigo'])}}
                                </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="sub-title">Presentación:</div>
                                <div>
                                
                                    @if ($consultaRefacciones->presentacion == 'Pieza')
                                    Pieza {{Form::radio('presentacion','Pieza',true)}}&nbsp;&nbsp;&nbsp;
                                    Caja {{Form::radio('presentacion','Caja')}}&nbsp;&nbsp;&nbsp;
                                    Equipo {{Form::radio('presentacion','Equipo')}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                    @if ($consultaRefacciones->presentacion == 'Caja')
                                    Pieza {{Form::radio('presentacion','Pieza')}}&nbsp;&nbsp;&nbsp;
                                    Caja {{Form::radio('presentacion','Caja',true)}}&nbsp;&nbsp;&nbsp;
                                    Equipo {{Form::radio('presentacion','Equipo')}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                    @if ($consultaRefacciones->presentacion == 'Equipo')
                                    Pieza {{Form::radio('presentacion','Pieza')}}&nbsp;&nbsp;&nbsp;
                                    Caja {{Form::radio('presentacion','Caja')}}&nbsp;&nbsp;&nbsp;
                                    Equipo {{Form::radio('presentacion','Equipo', true)}}&nbsp;&nbsp;&nbsp;
                                    @endif
                                    
                                </div>
                                
                            </div>
                            <div class="col-sm-6">
                                <div class="sub-title">Unidades presentación:</div>
                                <div>
                                    @if($errors->first('unidades'))
                                    <i> {{ $errors->first('unidades') }}</i>
                                    @endif
                                    {{Form::text('unidades',($consultaRefacciones->unidades),['class' => 'form-control', 'placeholder' => 'Ejemplo: 12'])}}
                                </div>
                            </div>
                        </div>

                        <div class="sub-title">Ubicación:</div>
                            <div>
                            @if($errors->first('ubicacion'))
                            <i> {{ $errors->first('ubicacion') }}</i>
                            @endif
                            {{Form::text('ubicacion',($consultaRefacciones->ubicacion),['class' => 'form-control', 'placeholder' => 'Ejemplo: Bodega'])}}
                            </div>
                    </div>
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio lista:</div>
                                    <div>
                                        @if($errors->first('precioLista'))
                                        <i> {{ $errors->first('precioLista') }}</i>
                                        @endif
                                        {{Form::text('precioLista',($consultaRefacciones->precioLista),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio último:</div>
                                    <div>
                                        @if($errors->first('precioUltimo'))
                                        <i> {{ $errors->first('precioUltimo') }}</i>
                                        @endif
                                        {{Form::text('precioUltimo',($consultaRefacciones->precioUltimo),['class' => 'form-control', 'placeholder' => 'Ejemplo: 405.00'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Tipo producto:</div>
                            <div>
                                @if($errors->first('tipoProducto'))
                                <i> {{ $errors->first('tipoProducto') }}</i>
                                @endif
                                @if($consultaRefacciones->tipoProducto == 'Refacciones Reparación')
                                Refacciones para reparación {{Form::radio('tipoProducto','Refacciones Reparación',true)}}&nbsp;&nbsp;&nbsp;
                                Refacciones Venta {{Form::radio('tipoProducto','Refacciones Venta')}}&nbsp;&nbsp;&nbsp;
                                Para cambio {{Form::radio('tipoProducto','Para cambio')}}&nbsp;&nbsp;&nbsp;
                                @else
                                @if($consultaRefacciones->tipoProducto == 'Refacciones Venta')
                                Refacciones para reparación {{Form::radio('tipoProducto','Refacciones Reparación')}}&nbsp;&nbsp;&nbsp;
                                Refacciones Venta {{Form::radio('tipoProducto','Refacciones Venta', true)}}&nbsp;&nbsp;&nbsp;
                                Para cambio {{Form::radio('tipoProducto','Para cambio')}}&nbsp;&nbsp;&nbsp;
                                @else
                                Refacciones para reparación{{Form::radio('tipoProducto','Refacciones Reparación')}}&nbsp;&nbsp;&nbsp;
                                Refacciones Venta {{Form::radio('tipoProducto','Refacciones Venta')}}&nbsp;&nbsp;&nbsp;
                                Para cambio {{Form::radio('tipoProducto','Para cambio', true)}}&nbsp;&nbsp;&nbsp;
                                @endif
                                @endif
                            </div>
                            <div class="sub-title">Cantidad:</div>
                                <div>
                                    @if($errors->first('cantidad'))
                                    <i> {{ $errors->first('cantidad') }}</i>
                                    @endif
                                    {{Form::text('cantidad',($consultaRefacciones->cantidad),['class' => 'form-control', 'placeholder' => 'Ejemplo: 3'])}}
                            </div>
                            <div class="sub-title">Foto Placa:</div>
                                <div>
                                <center>
                                @if($consultaRefacciones->fotoPlaca =="" || $consultaRefacciones->fotoPlaca =="Sin archivo")
                                
                                    <div style="color:red" >Sin foto</div>
                                
                                @else
                                    <div><a target="_blank" href="{{asset ('archivos/'.$consultaRefacciones->fotoPlaca)}}">
                                    <img src="{{asset ('archivos/'.$consultaRefacciones->fotoPlaca)}}" height=70 width=70></a></div>
                                                
                                @endif
                                </center>
                            </div>
                            <div>Editar foto placa: {{Form::file('fotoPlaca',['class' => 'form-control'])}}</div>
                            <div class="sub-title">Foto Principal:</div>
                                <div>
                                <center>
                                @if($consultaRefacciones->fotoPrincipal =="" || $consultaRefacciones->fotoPrincipal =="Sin archivo")
                                
                                    <div style="color:red" >Sin foto</div>
                                
                                @else
                                    <div><a target="_blank" href="{{asset ('archivos/'.$consultaRefacciones->fotoPrincipal)}}">
                                    <img src="{{asset ('archivos/'.$consultaRefacciones->fotoPrincipal)}}" height=70 width=70></a></div>
                                                
                                @endif
                                </center>
                                <div>Editar foto principal: {{Form::file('fotoPrincipal',['class' => 'form-control'])}}</div>

                            </div>
                            <div class="sub-title">Observaciones:</div>
                                <div>
                                    {{Form::text('observaciones',($consultaRefacciones->observaciones),['class' => 'form-control', 'placeholder' => 'Ejemplo: 3'])}}
                            </div><br>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="submit" class="btn btn-secondary" data-dismiss="modal">Guardar cambios</button> -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="guardar" class="btn btn-primary ver" data-dismiss="modal">Guardar Cambios</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>





<script>
$("#activar").click();
    $(document).ready(function() {
        
        // $(".ver").click(function(){
        //     $("#reporteRefacciones").load('{{url('editarRefaccion')}}' + '?r=' + Date.now() + $(this).closest('form').serialize());
        // });
        $(".idMarcaRefa").change(function(){
            //  alert("hola");
            $(".idTipoRefacciones").load('{{url('comboTipoRefaccion')}}' + '?r=' + Date.now() + '&idMarcaRefa=' + this.options[this.selectedIndex].value);
            $("#comboRef").load('{{url('comboSKU')}}' + '?r=' + Date.now() + '&idTipoRefacciones=' + this.options[this.selectedIndex].value);
            $("#numParte, #numSerie, #numCodigo").val("");
        });
        $(".idTipoRefacciones").change(function(){
            //  alert("hola");
            $("#comboRef").load('{{url('comboSKU')}}' + '?r=' + Date.now() + '&idTipoRefacciones=' + this.options[this.selectedIndex].value);
        });
        $(".comboRef").change(function(){
            // alert("hola");
            $(".datosSKU").load('{{url('datosSKUEntrada')}}' + '?r=' + Date.now() + '&idPartesVenta=' + this.options[this.selectedIndex].value);
        });

        $(".parte").change(function(){
            // alert("asf");
            $(".codigoRefaccion").load('{{url('generarCodigo')}}' + '?' + $(this).closest('form').serialize());
        });

    });
</script>
<script>
    $(function(){
            $(".ver").click(function(e){
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formularioEditarRefaccion"));
            //formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "{{ route('editarRefaccion') }}",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
	            processData: false,
                enctype: 'multipart/form-data'

            })
                .done(function(res){
                    $("#reporteRefacciones").html(res);
                });
        });
    });
</script>
