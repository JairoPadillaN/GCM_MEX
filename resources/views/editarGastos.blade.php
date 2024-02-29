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
                    <a class="nav-link comprobantes" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Comprobantes</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <form method="POST" action="{{ route('ActualizarGasto',$gasto->id) }}"  role="form" enctype="multipart/form-data">
                            <input type="hidden" data-url="{{url('public/archivos')}}" class="csrf-token" name="csrf-token" value="{{ csrf_token() }}">
                            {{ csrf_field() }}
                             <input type="hidden" name="idGasto" id="idGasto" class="form-control" value="{{$gasto->id}}">  

            <div class="form-group col-md-6">
                
                <div class="sub-title">Número de servicio</div>
                <div><input type="text" class="form-control" value="{{$idServicio}}" disabled>
                    
                                    </div>
                <div class="sub-title">Nombre del gasto</div>
                <div>
                <input type="text" name="nombreGasto" id="nombreGasto" class="form-control" value="{{$gasto->nombreGasto}}">  
                </div>
                <div class="sub-title">Beneficiario</div>
                <div>
                <input type="text" name="beneficiario" id="beneficiario" class="form-control" value="{{$gasto->beneficiario}}">     
                </div>
                <div class="sub-title">Banco</div>
                <div>
                <select name="banco" id="banco" class="form-control" aria-label="Banco">
                    <option selected disabled>Elige una opción</option>
                     @foreach ($bancos as $banco)
                    <option value="{{ $banco->idb }}" {{ ( $gasto->banco == $banco->idb) ? 'selected' : '' }}>{{ $banco->nombre }}</option>
                    @endforeach
                    
                </select>  
                  
                </div>
                <div class="sub-title">Empresa</div>
                <div>
                <select name="empresa" id="empresa" class="form-control" aria-label="Empresa">
                    @if($gasto->empresa == 'cym')
                        <option value="cym">CYM</option>
                        <option value="gcm">GCM</option>
                        <option value="surja">SURJA</option>
                        <option value="yaneth">YANETH</option>
                        <option value="pedro">PEDRO</option>
                    @endif
                    @if($gasto->empresa == 'gcm')
                        <option value="gcm">GCM</option>
                        <option value="cym">CYM</option>
                        <option value="surja">SURJA</option>
                        <option value="yaneth">YANETH</option>
                        <option value="pedro">PEDRO</option>
                    @endif
                    @if($gasto->empresa == 'surja')
                        <option value="surja">SURJA</option>
                        <option value="cym">CYM</option>
                        <option value="gcm">GCM</option>
                        <option value="yaneth">YANETH</option>
                        <option value="pedro">PEDRO</option>
                    @endif
                    @if($gasto->empresa == 'yaneth')
                        <option value="yaneth">YANETH</option>
                        <option value="cym">CYM</option>
                        <option value="gcm">GCM</option>
                        <option value="surja">SURJA</option>
                        <option value="pedro">PEDRO</option>
                    @endif
                    @if($gasto->empresa == 'pedro')
                        <option value="pedro">PEDRO</option>
                        <option value="cym">CYM</option>
                        <option value="gcm">GCM</option>
                        <option value="surja">SURJA</option>
                        <option value="yaneth">YANETH</option>
                    @endif
                </select>  
                  
                </div>
                <div class="sub-title">Cuenta</div>
                <div>
                <select name="cuenta" id="cuentas" class="form-control" aria-label="Cuenta">
                    
                </select>  
                  
                </div>
                <div class="sub-title">Fecha de pago</div>
                <div>
                <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" value="{{ date('Y-m-d', strtotime($gasto->fecha_pago)) }}">     
                </div>
                
                <div class="sub-title">Referencia</div>
                <div>
                 <input type="text" name="referencia" id="referencia" class="form-control" value="{{$gasto->referencia}}">  
                </div>
                
                
            </div>
            <div class="form-group col-md-6">
                
                <div class="sub-title">Etiquetas</div>
                <div>
                 <input type="text" name="etiquetas" id="etiquetas" class="form-control" value="{{$gasto->etiquetas}}">     
                </div>
                <div class="sub-title">Método de pago</div>
                <div>
                    <select name="metodo_pago" id="metodo_pago" class="form-control" aria-label="Método de pago">
                      <option selected disabled="">Elige una opción</option>
                        @foreach ($metodo_pago as $m)
                    <option value="{{ $m }}" {{ ( $m == $gasto->metodo_pago) ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                      
                      
                    
                    </select>
               
                </div>
                 <div class="sub-title">Forma de pago</div>
                <div>
                <select data-selected="{{$gasto->forma_pago}}" name="forma_pago" id="forma_pago" class="form-control">
                        <option value="1 Efectivo">Efectivo</option>
                        <option value="2 Cheque nominativo">Cheque nominativo</option>
                        <option value="3 Transferencia electronica">Transferencia electronica</option>
                        <option value="4 Tarjeta de credito">Tarjeta de credito</option>
                        <option value="5 Monedero electronico">Monedero electronico</option>
                        <option value="6 Dinero electronico">Dinero electronico</option>
                        <option value="8 Vales de despensa">Vales de despensa</option>
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
               </select>
                </div>
                <div style="{{ ($gasto->metodo_pago == 'PPD') ? 'display: block;' : 'display: none;' }}" class="sub-title label_credito">Periodo de pago</div>
                <div style="{{ ($gasto->metodo_pago == 'PPD') ? 'display: block;' : 'display: none;' }}" class="div_credito">
                    <select name="credito_dias" id="credito_dias" class="form-control" aria-label="Periodo de pago">
                      <option selected disabled="">Elige una opción</option>
                      
                       @foreach ($credito_dias as $dias)
                    <option value="{{ $dias }}" {{( $dias == $gasto->credito_dias) ? 'selected' : '' }}>{{ $dias }} días</option>
                    @endforeach
                      
                    
                    </select>
               
                </div>
                 <div class="row">
                <div class="form-group col-md-6">
                <div class="sub-title">Subtotal</div>
                <div>
                 <input type="text" step=".01" name="subtotal" id="subtotal" class="form-control" value="{{$gasto->factura}}">    
                
                </div></div>
                <div class="form-group col-md-3">
                <div class="sub-title">Moneda</div>
                <select name="moneda" id="moneda" class="form-control" aria-label="Moneda">
                      <option selected disabled="">Elige una opción</option>  
                      <option {{( $gasto->moneda=='MXN') ? 'selected' : '' }} value="MXN">MXN</option>
                      <option {{( $gasto->moneda=='USD') ? 'selected' : '' }} value="USD">USD</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                <div class="sub-title">Cambio Dólar</div>
                <input type="number" step=".01" name="cambio_dolar" id="cambio_dolar" class="form-control" value="{{$gasto->cambioDolar}}" required>    
                </div>
                </div>   
                <div style="position: relative;top:-20px;" class="row">
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
                <input type="number" name="total" id="total" readonly class="form-control" value="{{$gasto->total}}">  
                </div>
            </div>  </div> </div> 
            <div class="col-md-12">
               
                <div class="sub-title">Descripción</div>
                <div>
                    <textarea class="form-control" name="descripcion" id="" cols="30" rows="5">{{$gasto->descripcion}}</textarea>
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
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                    <div class="form-group col-md-12">
                        <div class="sub-title">Agregar comprobante:</div>
                        <div><br>
                            <form method="POST" class="form_files" action="{{ route('SubirComprobante', $gasto->id) }}"  role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
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
									@endif<a href="#{{--route('bajaLogicaComprobante',$archivo->id)--}}" data-id="{{$archivo->id}}" target="" class="btn btn-sm btn-danger baja_logica_file"><i class="fa fa-trash-o"></i></a></td>
                                    
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
.select2-container{
    width: 100% !important;
   } 
</style>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
        do{
            cuentasEmpresa();
        }while(document.querySelector('#cuentas').value != "");

        document.querySelector('#empresa').addEventListener('change', ()=>{
            document.querySelector('#cuentas').innerHTML = '';
            cuentasEmpresa();
        });
        calcTotal();
    });
    function cuentasEmpresa(){
        let empresa = document.querySelector('#empresa').value;
        axios.get('../../cuentasporEmpresa/'+empresa).then((response)=>{
            let cuentas = response.data;
            cuentas.forEach((e)=>{
                let tableOption = document.createElement('option');
                tableOption.value = e.id;
                if(e.id == JSON.parse({!!json_encode($gasto->idCuenta)!!})){
                    tableOption.setAttribute('selected', 'selected');
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
</script>
<script type="text/javascript">

    $(document).ready(function() {
      
	    calcTotal();
       $('#cuentas').select2({
            width: '100%'
        });
       $('#banco').select2({
            width: '100%'
        });
       var forma_pago = $('#forma_pago').data('selected');
       $('#forma_pago option[value="'+forma_pago+'"]').attr("selected", "selected");
       

       
       var id = $('#idGasto').val();
         function cuenta(id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="csrf-token"]').val()
                }
            });

            $.ajax({
                type: "POST",
                dataType : "json",
                url: '{{route('cuentaGasto')}}' ,
                data:  {'id':id},
                success: function (data) {
                    //console.log(data);
                    $('#cuenta').find('option:selected').removeAttr('selected');
                    var cuenta = $("#cuenta option[value='"+data[0].idCuenta+"']").text();
                    $("#cuenta option[value='"+data[0].idCuenta+"']").remove();
                     $("#cuenta").prepend('<option selected value="'+data[0].idCuenta+'">'+cuenta+'</option>');
                    //$("#cuenta option[value='"+data[0].idCuenta+"']").attr("selected", true); 
                   
                      
                }
            })
        }
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
            url_temp = '{{route('bajaLogicaComprobante','temp')}}'
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
 })
</script>
@stop
