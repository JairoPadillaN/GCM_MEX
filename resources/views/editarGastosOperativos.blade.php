@extends('principal')
@section('contenido')

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top: -45px">
        <header class="panel-heading">
            <h1>Modificar gasto</h1>
        </header>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Error!</strong> Revise los campos obligatorios.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li class="error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
           
            <div class="panel-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="cargar" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                            role="tab" aria-controls="pills-home">Información del gasto</a>
                    </li>
                    <li class="nav-item">
                        <a href="#pills-servicios" class="nav-link" id="pills-servicios-tab" data-toggle="pill" 
                        role="tab" aria-controls="pills-contact">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link comprobantes" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Comprobantes</a>
                    </li>
                    <!--
                    <li class="nav-item">
                        <a class="nav-link empresa" id="pills-profile-tab" data-toggle="pill" href="#pills-empresa" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Empresa</a>
                    </li>
                    -->
                </ul>

                @if(isset($mensaje))
                    <div class="mensaje bg-info" 
                         style="width: 100%;
                                padding: 5px; 
                                margin: 10px;
                                margin-bottom: 10px;
                                text-align: center;
                                border-radius: 5px">
                        <h3>{{ $mensaje }}</h3>
                    </div>
                @endif
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <form method="POST" action="{{ route('ActualizarGastoOperativo',$gasto->id) }}"  role="form" enctype="multipart/form-data">
                            <input type="hidden" data-url="{{url('public/archivos')}}" class="csrf-token" name="csrf-token" value="{{ csrf_token() }}">
                            {{ csrf_field() }}

            <div class="form-group col-md-6">
                
                
                <div class="sub-title">Nombre del gasto</div>
                <div>
                <input type="text" name="nombreGasto" id="nombreGasto" class="form-control" value="{{$gasto->nombreGasto}}">
                <input type="hidden"  id="idGasto" class="form-control" value="{{$gasto->id}}">  
                </div>
                <div class="sub-title">Beneficiario</div>
                <div>
                <input type="text" name="beneficiario" id="beneficiario" class="form-control" value="{{$gasto->beneficiario}}">    
                </div>
                <div class="sub-title">Empresa:</div>
                <div>
                    <select name="empresa" id="empresa" class="form-control" aria-label="Empresa">
                        @foreach(['cym', 'gcm', 'surja', 'yaneth', 'pedro'] as $empresa)
                            <option value="{{ $empresa }}" {{ $gasto->empresa == $empresa ? 'selected' : '' }}>
                                {{ strtoupper($empresa) }}
                            </option>
                        @endforeach
                    </select>  
                </div>
                <div class="sub-title">Cuenta</div>
                <select class="form-control" name="idCuenta" id="cuentas"></select>
                <div class="sub-title">Banco</div>
                @foreach($bancos as $banco)
                    @if($gasto->BANCO == $banco->idb)
                        <input type="text" class="form-control" name="name_banco" id="nombre_banco" value="{{$banco->nombre}}" readonly>
                    @endif
                @endforeach
                <div class="sub-title">Cambiar banco</div>
                <input type="hidden" name="banco" value="{{$gasto->BANCO}}" id="banco_value">
                <div id="banco_input">
                    <select name="BANCO" id="change_banco" class="form-control" aria-label="Banco">
                        <option selected disabled value="">Elige una opción</option>
                        @foreach ($bancos as $banco)
                            @if($gasto->BANCO == $banco->idb)
                                <option value="{{ $banco->idb }}" selected>{{ $banco->nombre }}</option>
                            @else
                                <option value="{{ $banco->idb }}">{{ $banco->nombre }}</option>
                            @endif
                        @endforeach
                    </select> 
                </div>
                <div class="sub-title">Fecha de pago</div>
                <div>
                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" value="{{ date('Y-m-d', strtotime($gasto->fecha_pago)) }}">     
                </div>
                <div class="sub-title">Etiquetas</div>
                <div>
                    <select name="etiquetas" id="etiquetas" class="form-control">
                        <option value="">Seleccione etiqueta</option>
                        @foreach(['COSTO DE VENTA', 'GASTO ADMINISTRATIVO', 'GASTO FINANCIERO', 'GASTO OPERATIVO', 'GASTO DE VENTA', 'IMPUESTOS', 'INTERES'] as $etiqueta)
                            <option value="{{ $etiqueta }}" {{ $gasto->etiquetas == $etiqueta ? 'selected' : '' }}>
                                {{ $etiqueta }}
                            </option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="form-group col-md-6">
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <div class="sub-title">*Moneda</div>
                    </td>
                    <td>
                        <div class="sub-title">Tipo de cambio</div>
                    </td>
                </tr>
                <tr>
                    <td>@if($gasto->moneda == 'MXN')<input type="radio" name="moneda" id="mxn" value="MXN" checked>@else<input type="radio" name="moneda" id="mxn" value="MXN">@endif MXN</td>
                    <td>@if($gasto->moneda == 'USD')<input type="radio" name="moneda" id="usd" value="USD" checked>@else<input type="radio" name="moneda" id="usd" value="USD">@endif USD</td>
                    <td id="cambio_dolar_input" width="60%">
                        @if($gasto->moneda == 'MXN')
                            <input type="text" name="cambioDolar" id="cambio_dolar" class="form-control" value="1.00" readonly>
                        @else
                            <input type="text" name="cambioDolar" id="cambio_dolar" class="form-control" value="{{$gasto->cambioDolar}}">
                        @endif
                        
                    </td>
                </tr>
            </table>
            <div class="sub-title">Metodo de pago</div>
                <div>
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td>PPD - Pago en parcialidades o diferido 
                                    @if($gasto->metodo_pago == 'PPD')
                                        <input type="radio" name="metodo_pago" id="formaPago_credito" value="PPD" checked>
                                    @else
                                        <input type="radio" name="metodo_pago" id="formaPago_credito" value="PPD">
                                    @endif
                                </td>
                                <td>PUE - Pago en una sola exhibicion
                                @if($gasto->metodo_pago == 'PUE')
                                    <input type="radio" name="metodo_pago" id="formaPago_efectivo" value="PUE" checked>
                                @else
                                    <input type="radio" name="metodo_pago" id="formaPago_efectivo" value="PUE">
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="sub-title">Periodo de pago</div>
                <div class="credito_dias_input">
                    @if($gasto->credito_dias == 'No aplica')
                        <input type="text" name="credito_dias" id="credito_dias" class="form-control" value="No aplica" readonly>
                    @else
                        <input type="text" class="form-control" name="credito_dias" id="credito_dias" value="{{($gasto->credito_dias)}} dias">
                    @endif
                </div>
                <div class="sub-title">Forma de pago</div>
                <div>
                    <select data-selected="{{$gasto->forma_pago}}" name="forma_pago" id="forma_pago" class="form-control">
                        <option value="01 Efectivo">Efectivo</option>
                        <option value="02 Cheque nominativo">Cheque nominativo</option>
                        <option value="03 Transferencia electronica">Transferencia electronica</option>
                        <option value="04 Tarjeta de credito">Tarjeta de credito</option>
                        <option value="05 Monedero electronico">Monedero electronico</option>
                        <option value="06 Dinero electronico">Dinero electronico</option>
                        <option value="08 Vales de despensa">Vales de despensa</option>
                        <option value="28 Tarjeta de debito">Tarjeta de debito</option>
                        <option value="29 Tarjeta de servicios">Tarjeta de servicios</option>
                        <option value="99 No definir">No definir</option>
                        <option value="NA No aplica">No aplica</option>
                        <option value="30 Aplicacion de anticipos">Aplicacion de anticipos</option>
                        <option value="14 Pago por consignacion">Pago por consignacion</option>
                        <option value="15 Condonacion">Condonacion</option>
                        <option value="17 Compensacion">Compensacion</option>
                        <option value="27 Satisfaccion del acreedor">Satisfaccion del acreedor</option>
                        <option value="25 Remision de deuda">Remision de deuda</option>
                    </select>
                </div>
                <div class="sub-title">Subtotal</div>
                <div>
                 <input type="text" name="factura" id="subtotal" class="form-control" value="{{$gasto->factura}}">    
                </div>
                <div class="row">
                    <div class="col-md-6">
                <div class="sub-title">IVA %</div>
                <div>
                 <input type="text" name="iva" id="iva" class="form-control" value="{{$gasto->iva}}">    
                </div></div>
                 <div class="col-md-6">
                <div class="sub-title">IVA $</div>
                <div>
                 <input type="text" name="total_iva" id="total_iva" class="form-control" readonly value="{{$gasto->total_iva}}">    
                </div>
                </div>
               
                <div class="col-md-6">
                <div class="sub-title">Retención de ISR %</div>
                <div>
                 <input type="text" name="isr" id="isr" class="form-control" value="{{$gasto->isr}}">    
                </div></div>
                <div class="col-md-6">
                <div class="sub-title">Retención de ISR $</div>
                <div>
                 <input type="text" name="total_isr" id="total_isr" readonly class="form-control" value="{{$gasto->total_isr}}">    
                </div></div>
                 <div class="col-md-12">
                <div class="sub-title">Total</div>
                <div>
                <input type="text" name="total" id="total" readonly class="form-control" value="{{$gasto->total}}">  
                </div>
                <div class="sub-title">Referencia</div>
                <div>
                 <input type="text" name="referencia" id="referencia" class="form-control" value="{{$gasto->referencia}}">  
                </div>
            </div>  </div> </div> 
            <div class="col-md-12">
               
                <div class="sub-title">Descripción</div>
                <div>
                    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5">{{$gasto->descripcion}}</textarea>
                </div>
               
                <!--div class="sub-title">Evidencias</div>
                <div>
                    <input type="file" name="evidencias" id="evidencias" multiple>
                </div-->
                <center style="margin-top: 10px">
                   
                  <button type="submit" class="btn btn-primary save1">Guardar</button>
                </center>
            </div>
            </form>
        </div>

        <!-- * Panel para relacionar gastos operativos con los rervicios -->
        <div class="tab-pane fade" id="pills-servicios" role="tabpanel" aria|-labelledby="pills-profile-tab">
            <div class="col-12">
                <div class="row align-items-center">
                    <div style="margin: auto; width: 80%">
                        <h3 style="text-align: center;">Asignacion de gastos a servicios</h3>
                        <form action="#" method="post">
                            <label for="porcentaje" class="sub-title">Porcentaje: </label>
                            <input class="form-control" id="porcentajeInput" type="number" name="porcentaje" style="width: 10%; display: inline-block; text-align: right;" value="100">%
                            <label for="monto" style="padding-left: 10px">Monto: $</label>
                            <input class="form-control" id="montoInput" type="text" name="monto" style="width: 10%; display: inline-block; text-align: right;" value="{{$gasto->total}}">
                            <label for="servicio" class="sub-title" style="padding-left: 10px">Servicio</label>
                            <select class="form-control" name="servicio" id="servicios" style="width: 45%; display: inline-block">
                                <option value="">Cargando elementos</option>
                            </select>
                            <button class="btn btn-success" id="btnServicios" style="width: 10%;"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
                        </form>
                        <hr>
                        <div id="cargando" style="width: 100%; text-align: center;"></div>
                        <table class="table table-stripped table-hover" style="100%">
                            <thead>
                                <th style="width: 10%">Folio de servicio</th>
                                <th>Cliente</th>
                                <th>Sucursal</th>
                                <th style="width: 10%">Monto</th>
                                <th style="width: 10%">Opciones</th>
                            </thead>
                            <tbody id="table-body">

                            </tbody>
                            <tfoot id="table-footer"></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            document.querySelector('#pills-servicios-tab').addEventListener('click', ()=>{
                var serviciosSelect = document.querySelector("#servicios");
                let idGasto = document.querySelector('#idGasto').value;
                axios.get('../serviciosList/'+idGasto).then((response)=>{
                    let servicios = response.data;
                    
                    serviciosSelect.innerHTML = '<option value="">Selecciona una opcion</option>';
                    servicios.forEach((e)=>{
                        let option = document.createElement('option');
                        option.value = e.idServicios;
                        option.innerHTML = `${e.idServicios} - ${e.cliente} - ${e.sucursal}`;
                        serviciosSelect.appendChild(option);
                    })
                }).catch((e)=>{
                    console.log(e);
                });

                cargarTabla("");
            });
            document.querySelector('#btnServicios').addEventListener('click',(e)=>{
                e.preventDefault();
                if(document.querySelector("#servicios").value == ""){
                    alert('Error: Se debe seleccionar un servicio.');
                }else{
                    let sumaTotal = parseFloat(document.querySelector('#suma-total').innerHTML);
                    let porcentajeTotal = parseFloat(document.querySelector('#montoInput').value)
                    let total = parseFloat(document.querySelector('#total').value);
                    if(sumaTotal + porcentajeTotal > total){
                        alert('¡La suma de los porcentajes supera el monto total!');
                    }else{
                        axios.post('../asignarServicioGasto',{
                            idServicio: document.querySelector("#servicios").value,
                            idGastoOperativo: document.querySelector('#idGasto').value,
                            total: document.querySelector('#montoInput').value,
                        }).then((response)=>{
                            console.log(response);
                            if(response.data == "registro_repetido"){
                                document.querySelector('#cargando').innerHTML = "<div class='bg-danger' style='padding-bottom: 5px; border-radius: 10px;'><h4>¡El servicio que intentas asignar ya ha sido registrado antes!</h4></div>";
                            }else{
                                cargarTabla("Cargando...");
                            }
                        }).catch((e)=>{
                            console.log(e);
                        });
                    }
                }
            });

            function cargarTabla(mensaje){
                document.querySelector('#cargando').innerHTML = mensaje;
                var tableBody = document.querySelector('#table-body');
                axios.get('../gastoList/'+document.querySelector('#idGasto').value).then((response)=>{
                    tableBody.innerHTML = "";
                    document.querySelector('#cargando').innerHTML = "";
                    let gastos = response.data;
                    let total = 0
                    gastos.forEach((e)=>{
                        let row = document.createElement('tr');
                        let button = `<label class="btn btn-sm btn-danger" rel="tooltip" title="Borrar" id="delete-${e.id}">
                                            <i class="fa fa-trash"></i>
                                     </label>`;
                        row.innerHTML = `<td>${e.idServicios}</td><td>${e.cliente}</td><td>${e.sucursal}</td><td>$${e.total}</td><td>${button}</td>`;
                        tableBody.appendChild(row);
                        document.querySelector(`#delete-${e.id}`).addEventListener('click', ()=>{
                            axios.get('../deleteGastoServicio/'+e.id).then(()=>{
                                tableBody.removeChild(row);
                                document.querySelector('#suma-total').innerHTML = (parseFloat(document.querySelector('#suma-total').innerHTML)-parseFloat(e.total)).toFixed(2);
                                alert("Registro eliminado correctamente");
                            });
                        });
                        total += parseFloat(e.total);
                    });
                    let tfooter = document.querySelector('#table-footer');
                    tfooter.innerHTML = `<tr><th colspan=3 style="text-align:right;">Total: </th><td>$<span id="suma-total">${total.toFixed(2)}</span></td></tr>`;
                });
            }
        </script>

        <!-- * Panel para el seguimiento de archivos -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                    <form action="{{ route('SubirComprobanteOperativo', $gasto->id) }}" method="POST" class="form_files" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group col-md-12">
                            <div class="sub-title">Agregar comprobante:</div>
                            <div><br>
                                <input name="file" type="file">
                            </div>
                        </div>
                    
                        <div class="form-group col-md-6">
                            <div class="sub-title">Descripción:</div><br>
                            <div><textarea name="descripcion_file" rows="3" cols="80"></textarea></div>
                        </div>

                        <div class="form-group col-md-6">
                            <br><br> <br><br><br>
                            <button type="submit" class="btn btn-success agregar_comprobante">
                                <span class="glyphicon glyphicon-plus-sign"></span> &nbsp;Agregar
                            </button>
                        </div>
                    </form>
                    <div class="form-group col-md-8">
                        <div id="reporteUsuarios">
                            @if(Session::has('success'))
           
            <div class="alert alert-info alert1">
                {{Session::get('success')}}
            </div>
            @endif
                            {{--@if($cuantos == 0)--}
                            <br>
                            
                            <div class="alert alert-warning" role="alert" align="center">
                                <label for="">Sin usuarios asignados</label>
                            </div> 
                            {{--@else--}}
                            <br>
                            <!-- TIENE USUARIOS -->
                            <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr style="background-color: #E8E8E8;">
                                        <th width = "">
                                            <font SIZE=2>Nombre de archivo</font>
                                        </th>
                                        <th width = "">
                                            <font SIZE=2>Descripción</font>
                                        </th>
                                        <th width = "">
                                            <font SIZE=2>Activo</font>
                                        </th>
                                        <th width = "">
                                            <font SIZE=2>Fecha de subida</font>
                                        </th>
                                        
                                        <th width = "">
                                            <font SIZE=2>Acciones</font>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($archivos as $archivo)
                                    <tr>
                                       
                                    <td align="center"> {{$archivo->nombre_archivo}}</td>
                                    <td align="center"> {{$archivo->descripcion}}</td>
                                    <td align="center"> {{ $archivo->activo }}</td>
                                     <td align="center"> {{ date('d-m-Y', strtotime($archivo->created_at)) }}</td>
                                    <td align="center">
                                        @if($archivo->activo=='Si')
                                       
                                      
                                                                                <a target="_blank"
                                                                                    href="{{ asset('archivos/' . $archivo->nombre_archivo) }}">
                                                                                    <i class="fa fa-file"></i>
                                                                                </a>
                                                                            @endif
                                        <a href="{{route('bajaLogicaComprobanteOperativo',$archivo->id)}}" 
                                           data-id="{{$archivo->id}}" 
                                           target="" 
                                           class="btn btn-sm btn-danger baja_logica_file"><i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                    
                                    </tr>
                                    @endforeach
                                   <div id="fileModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Contenido del modal -->
    <div style="width:fit-content;" class="">
      <div style="border-bottom: 0px solid #e5e5e5 !important;" class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
         <iframe src="" style="width: 700px;height: 800px;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
                                               
                                         
                                   
                                </tbody>
                            </table>
                        </div>
                            {{--@endif--}}
                        </div>
                    </div>
                </div>
    </div>
</div>
<style>
    .swal2-popup {
   font-size : 1.6rem !important ;
}
</style>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $('#servicios').select2();
    document.addEventListener('DOMContentLoaded', ()=>{
        let montoInput = document.querySelector('#montoInput');
        let porcentajeInput = document.querySelector('#porcentajeInput');

        let total = parseFloat(JSON.parse({!!json_encode($gasto->total)!!}));

        montoInput.addEventListener('change', ()=>{
            if(parseFloat(montoInput.value) < 1 || parseFloat(montoInput.value) > total || montoInput.value == ""){
                alert('Ingresa un monto valido');
            }else{
                porcentajeInput.value = ((parseFloat(montoInput.value) * 100) / total).toFixed(2);
            }
        });

        porcentajeInput.addEventListener('change', ()=>{
            if(parseFloat(porcentajeInput.value) < 1 || parseFloat(porcentajeInput.value) >100 || porcentajeInput.value == ''){
                alert('Ingresa un porcentaje valido');
            }else{
                montoInput.value = ((parseFloat(porcentajeInput.value) * total) / 100).toFixed(2);
            }
        });
    });

    function cargarServicios(){
        
    }
</script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(){
        do{
            cuentasEmpresa();
        }while(document.querySelector('#cuentas').value != "");

        document.querySelector('#empresa').addEventListener('change', ()=>{
            document.querySelector('#cuentas').innerHTML = "";
            cuentasEmpresa();
        });;

        document.querySelector("#formaPago_efectivo").addEventListener("change",(e)=>{
            if(e.target.checked){
                document.querySelector(".credito_dias_input").innerHTML=`<input type="text" name="credito_dias" id="credito_dias" class="form-control" readonly>`;
                document.querySelector("#credito_dias").value="No aplica";
            }
        });
        document.querySelector("#formaPago_credito").addEventListener("change",(e)=>{
            if(e.target.checked){
                document.querySelector(".credito_dias_input").innerHTML=`<select class="form-control" name="credito_dias" id="credito_dias">
                        <option value="7" name="credito_dias">7 dias</option>
                        <option value="10" name="credito_dias">10 dias</option>
                        <option value="14" name="credito_dias">14 dias</option>
                        <option value="30" name="credito_dias">30 dias</option>
                        <option value="45" name="credito_dias">45 dias</option>
                        <option value="60" name="credito_dias">60 dias</option>
                    </select>`;
            }
        });

        var cuentas = JSON.parse('{!!json_encode($cuentas)!!}');
        var archivos = JSON.parse('{!!json_encode($archivos)!!}');

        calcTotal();

        document.querySelector("#mxn").addEventListener("change", (e=>{
            if(e.target.checked){
                document.querySelector("#cambio_dolar_input").innerHTML = `<input type="text" name="cambioDolar" id="cambio_dolar" class="form-control" value="1.00" readonly>`;
            }
        }));
        document.querySelector("#usd").addEventListener("change", (e=>{
            if(e.target.checked){
                document.querySelector("#cambio_dolar_input").innerHTML = `<input type="text" name="cambioDolar" id="cambio_dolar" class="form-control" value="${gasto.cambioDolar}">`;
            }
        }));

        document.querySelector('#change_banco').addEventListener('change', ()=>{
            console.log(bancos);
            bancos.forEach(e=>{
                if(e.idb == document.querySelector("#change_banco").value){
                    console.log('si');
                    document.querySelector("#nombre_banco").value = e.nombre;
                }else{
                    console.log(document.querySelector("#change_banco").value);
                }
            });
        });
        
        document.querySelector("#formaPago_efectivo").addEventListener("change",(e)=>{
            if(e.target.checked){
                document.querySelector(".credito_dias_input").innerHTML=`<input type="text" name="credito_dias" id="credito_dias" class="form-control" readonly>`;
                document.querySelector("#credito_dias").value="No aplica";
            }
        });
        
    });

    function cuentasEmpresa(){
        
        let empresa = document.querySelector('#empresa').value;
        axios.get('../cuentasporEmpresa/'+empresa).then((response)=>{
            
            let cuentas = response.data;
            cuentas.forEach((e)=>{
                let tableOption = document.createElement('option');
                tableOption.value = e.id;
                if(e.id == JSON.parse({!!json_encode($gasto->idCuenta)!!})){
                    tableOption.setAttribute('selected', 'true');
                }
                tableOption.innerHTML = `${e.numeroCu} - ${e.nombre}`;
                document.querySelector('#cuentas').appendChild(tableOption);
            });
        }).catch((e)=>{
            console.log(e);
        });
    }

    function calcTotal(){
        let subtotal = document.querySelector("#subtotal");
        let iva = document.querySelector("#iva");
        let isr = document.querySelector("#isr");
        var total_iva = 0;
        var total_isr = 0;
        let numberFormat = Intl.NumberFormat("es-MX",{
                                style: "currency",
                                currency: "MXN"
                            });
        if(subtotal.value != "" && iva.value != ""){
            total_iva = parseFloat(subtotal.value)*(parseFloat(iva.value)/100);
            document.querySelector("#total_iva").value=total_iva.toFixed(2);
        }
        if(subtotal.value != "" && isr.value != ""){
            total_isr = parseFloat(subtotal.value)*(parseFloat(isr.value)/100);
            document.querySelector("#total_isr").value=total_isr.toFixed(2);
        }
        if(subtotal.value  != "" && iva.value != "" && isr.value != ""){
            total = parseFloat(subtotal.value) + total_iva+total_isr;
            document.querySelector("#total").value = total.toFixed(2);
        }

        subtotal.addEventListener("change", ()=>{
            if(iva.value != ""){
                total_iva = parseFloat(subtotal.value)*(parseFloat(iva.value)/100);
                document.querySelector("#total_iva").value=total_iva.toFixed(2);
            }
            if(isr.value != ""){
                total_isr = parseFloat(subtotal.value)*(parseFloat(isr.value)/100);
                document.querySelector("#total_isr").value=total_isr.toFixed(2);
            }
            if(iva.value != "" && isr.value != ""){
                total = parseFloat(subtotal.value) + total_iva+total_isr;
                document.querySelector("#total").value = total.toFixed(2);
            }
        });
        iva.addEventListener("change", ()=> {
            if(subtotal.value != ""){
                total_iva = parseFloat(subtotal.value)*(parseFloat(iva.value)/100);
                document.querySelector("#total_iva").value=total_iva.toFixed(2);
            }
            if(isr.value != ""){
                total_isr = parseFloat(subtotal.value)*(parseFloat(isr.value)/100);
                document.querySelector("#total_isr").value=total_isr.toFixed(2);
            }
            if(iva.value != "" && isr.value != ""){
                total = parseFloat(subtotal.value) + total_iva+total_isr;
                document.querySelector("#total").value = total.toFixed(2);
            }
        });
        isr.addEventListener("change", ()=> {
            if(subtotal.value != ""){
                total_iva = parseFloat(subtotal.value)*(parseFloat(iva.value)/100);
                document.querySelector("#total_iva").value=total_iva.toFixed(2);
            }
            if(subtotal.value != ""){
                total_isr = parseFloat(subtotal.value)*(parseFloat(isr.value)/100);
                document.querySelector("#total_isr").value=total_isr.toFixed(2);
            }
            if(iva.value != "" && isr.value != ""){
                total = parseFloat(subtotal.value) + total_iva+total_isr;
                document.querySelector("#total").value = total.toFixed(2);
            }
        });
    }
    $(document).ready(function() {
        $('#cuentas').select2({
            width: '100%'
        });
        $('#change_banco').select2({
            width: '100%'
        });

       var forma_pago = $('#forma_pago').data('selected');
       $('#forma_pago option[value="'+forma_pago+'"]').attr("selected", "selected");

        $('#metodo_pago').change(function(){
        if($(this).val()=='PPD'){
            $('.label_credito').css("display","block");
            $('.div_credito').css("display","block");
            
        }else {
            $('.label_credito').css("display","none");
            $('.div_credito').css("display","none");
        }
        })
        $('.view_file').click(function(e){
            e.preventDefault();

            nfile = $.trim($(this).closest('tr').find('td:eq(0)').text());
            url = $('.csrf-token').data('url')+'/'+nfile;
            //console.log(url);
        if(nfile.indexOf("jpg") > -1 || nfile.indexOf("jpeg") > -1 || nfile.indexOf("png") > -1){
             
             //var url = 'http://www.pruebas.germancontrolmotion.com/public/archivos/'+nfile; 
             window.open(url, '_blank');
            //$('#fileModal').find('iframe').attr('src','http://www.pruebas.germancontrolmotion.com/public/archivos/'+nfile);
        }else{
           
            $('#fileModal').find('iframe').attr('src','https://docs.google.com/gview?url='+url+'&embedded=true');
            //http://www.pruebas.germancontrolmotion.com/public/archivos/'+nfile+'
            $('#fileModal').modal('show');
        }
          
        })
         
        var str = $(".error").text();
     if ( $(".alert1").length > 0 || str.includes('file') || str.includes('decripcion_file')) {
  $(".comprobantes").click();
}else{
    $(".cargar").click();
}
    $('a').click(function(){

   $('.alert-danger').remove();
    })

   $('.baja_logica_file').click(function(e){
    e.preventDefault();
    activo = $.trim($(this).closest('tr').find('td:eq(2)').text());
    archivo = $.trim($(this).closest('tr').find('td:eq(0)').text());
    title = ( activo == 'No') ? '¿Quieres eliminar completamente este comprobante?' : '¿Quieres desactivar este comprobante?';  
    Swal.fire({
        title: title,
        //showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar',
        //denyButtonText: `Cancelar`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
        baja_fisica($(this).data('id'),activo,archivo);
        //console.log($('input[name="csrf-token"]').val());
    } /*else if (result.isCanceled) {
        Swal.fire('Changes are not saved', '', 'info')
    }*/
})
     
    })
        function baja_fisica(id,activo,archivo) {
            url_temp = '{{route('bajaLogicaComprobanteOperativo','temp')}}'
            url = url_temp.replace('temp', id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="csrf-token"]').val()
                }
            });

            $.ajax({
                type: "POST",
                dataType : "json",
                url: url ,
                data:  {'activo':activo,'file':archivo},
                success: function (data) {
                    if(data.includes('eliminado')){
                       $( "a[data-id='"+id+"']" ).closest('tr').remove() ; 
                    } else if (data.includes('desactivado')){
                      $( "a[data-id='"+id+"']" ).parent().find('.download_file').remove();
                      $( "a[data-id='"+id+"']" ).parent().find('.view_file').remove();
                      $( "a[data-id='"+id+"']" ).closest('tr').find('td:eq(2)').text('No');
                    }
                    
                   Swal.fire('Correcto!', data, 'success');
                  
        
                }
            })
        }
    
     $('#fecha_pago').change(function() {
    //console.log($(this).val());
     })
     $('#subtotal').change(function() {
     
     subtotal = $(this).val();
     iva = ($('#iva').val()/100)*subtotal;
     isr = ($('#isr').val()/100)*subtotal;
     calcular_total(subtotal,iva,isr);
     })
     $('#iva').change(function() {
     
     subtotal = ($('#subtotal').val());
     iva = ($(this).val());
     isr = $('#total_isr').val();
     //console.log(subtotal,iva1);
     calcular_total(subtotal,iva,isr);
     })
     $('#isr').change(function() {
     
     subtotal = $('#subtotal').val();
     iva = $('#iva').val();
     isr = ($(this).val()/100)*subtotal;
     calcular_total(subtotal,iva,isr);
     })
      function calcular_total(subtotal,iva,isr){
         s = subtotal=='' ? subtotal : parseFloat(subtotal);
         i = iva=='' ? iva : parseFloat(iva);
         is = isr=='' ? isr : parseFloat(isr);
         total = s + i + is;
         //total_iva = s*i/100
         $('#total_iva').val(i.toFixed(2));
          $('#total_isr').val(is.toFixed(2));
          if(total=='00'){
            total = 0;
          }
         $('#total').val(total.toFixed(2));
        //console.log(i);

     }
 })
</script>
@stop
