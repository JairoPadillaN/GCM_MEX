<!-- Este boton se le da clic automaticamente con el script de abajo para que abra el modal-->
<button type="button" class="" data-toggle="modal" data-target="#modalEntradaAlmacen" id="activar">
</button>

<!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="modalEntradaAlmacen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    
                    <h3 class="modal-title" id="exampleModalLongTitle" align="center">Detalle Refacción</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="panel-body">            
                    <div class="form-group col-md-6">
                        @if($consultaRefacciones->idEntradaOrden=='')
                        <div class="sub-title">Marca:</div>
                        <div>
                            <?php $content = DB::table('marcasRefaccion')->select('marcaRefaccion')
                        ->where('idMarcaRefa','=',$consultaRefacciones->idMarcaRefa)
                        ->get(); ?>
                        {{Form::text('idMarcaRefa',($content[0]->marcaRefaccion),['class' => 'form-control', 'readonly' => 'true'])}}
                        
                        </div>
                        <div class="sub-title">Tipo de refacción:</div>
                        <div>
                            <!-- consulta para tipo refacciones -->
                            <?php $content = DB::table('tiporefacciones')->select('nombreTipoRefaccion')
                            ->where('idTipoRefacciones','=',$consultaRefacciones->idTipoRefacciones)
                            ->get(); ?>
                            <!-- Termina consulta -->
                            {{Form::text('idTipoRefacciones',($content[0]->nombreTipoRefaccion),['class' => 'form-control', 'readonly' => 'true'])}}
                            
                        </div>
                        @endif
                        <div class="sub-title">Nombre de SKU:</div>
                            {{Form::text('nombreRefaccion',($consultaRefacciones->nombreRefaccion),['class' => 'form-control', 'placeholder' => 'Ejemplo: Electrico','readonly' => 'true'])}}
                        
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
                                {{Form::text('presentacion',($consultaRefacciones->presentacion),['class' => 'form-control', 'readonly' => 'true'])}}
                                </div>
                                
                            </div>
                            <div class="col-sm-6">
                                <div class="sub-title">Unidades presentación:</div>
                                <div>
                                    {{Form::text('unidades',($consultaRefacciones->unidades),['class' => 'form-control','readonly' => 'true'])}}
                                </div>
                            </div>
                        </div>

                        <div class="sub-title">Ubicación:</div>
                            <div>
                            {{Form::text('ubicacion',($consultaRefacciones->ubicacion),['class' => 'form-control', 'readonly' => 'true'])}}
                            </div>
                    </div>
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio lista:</div>
                                    <div>
                                        {{Form::text('precioLista',($consultaRefacciones->precioLista),['class' => 'form-control', 'readonly' => 'true'])}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="sub-title">Precio último:</div>
                                    <div>
                                        {{Form::text('precioUltimo',($consultaRefacciones->precioUltimo),['class' => 'form-control','readonly' => 'true'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="sub-title">Tipo producto:</div>
                            <div>
                                {{Form::text('precioUltimo',($consultaRefacciones->tipoProducto),['class' => 'form-control','readonly' => 'true'])}}
                            </div>
                            <div class="sub-title">Cantidad:</div>
                                <div>
                                    {{Form::text('cantidad',($consultaRefacciones->cantidad),['class' => 'form-control','readonly' => 'true'])}}
                                </div>
                            <div class="sub-title">Foto Placa:</div>
                                <div>
                                <center>
                                @if($consultaRefacciones->fotoPlaca =="" || $consultaRefacciones->fotoPlaca =="Sin archivo")
                                
                                    <div style="color:red" >Sin foto</div>
                                
                                @else
                                    <div><a target="_blank" href="{{asset ('public/archivos/'.$consultaRefacciones->fotoPlaca)}}">
                                    <img src="{{asset ('public/archivos/'.$consultaRefacciones->fotoPlaca)}}" height=70 width=70></a></div>
                                                
                                @endif
                                </center>
                            </div>
                            <div class="sub-title">Foto Principal:</div>
                                <div>
                                <center>
                                @if($consultaRefacciones->fotoPrincipal =="" || $consultaRefacciones->fotoPrincipal =="Sin archivo")
                                
                                    <div style="color:red" >Sin foto</div>
                                
                                @else
                                    <div><a target="_blank" href="{{asset ('public/archivos/'.$consultaRefacciones->fotoPrincipal)}}">
                                    <img src="{{asset ('public/archivos/'.$consultaRefacciones->fotoPrincipal)}}" height=70 width=70></a></div>
                                                
                                @endif
                                </center>

                            </div>
                            <div class="sub-title">Observaciones:</div>
                                <div>
                                    {{Form::text('observaciones',($consultaRefacciones->observaciones),['class' => 'form-control','readonly' => 'true'])}}
                            </div><br>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="submit" class="btn btn-secondary" data-dismiss="modal">Guardar cambios</button> -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    $("#activar").click();
    </script>
