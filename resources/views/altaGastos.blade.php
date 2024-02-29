@extends('principal')
@section('contenido')

<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top: -45px">
        <header class="panel-heading">
            <input type="hidden" class="csrf-token" name="csrf-token" value="{{ csrf_token() }}">
                            {{ csrf_field() }}
            <h1>Alta de gastos <small> *Campos obligatorios</small></h1>
        </header>
        <section class="panel-body">
            <div class="form-group col-md-6">
                {{Form::open(['route' => "guardarGasto", 'id' => 'formAltaGastos', 'files' => true])}}
                {{Form::token()}}
                <div class="sub-title">Numero de servicio</div>
		<input type="hidden" name="moneda" value="MXN">
                <div>
                    {{Form::text(('id_servicio'), old('id_servico'),['class' => 'form-control', 'id' => 'id_servicios', 'readonly'])}}
		    <input type="hidden" name="id_factura" id="id_factura">
                </div>
                <div class="sub-title">*Nombre del gasto</div>
                <div>
                    {{Form::text(('nombreGasto'), old('nombreGasto'),['class' => 'form-control', 'id' => 'nombreGasto', 'placeholder' => 'Ejemplo: Vuelo'])}}
                </div>
                <div class="sub-title">*Beneficiario</div>
                <div>
                    {{Form::text('beneficiario', old('beneficiario'), ['class' => 'form-control', 'id' => 'beneficiario', 'placeholder' => 'Ejemplo: #'])}}
                </div>
                <div class="sub-title">*Banco</div>
                <div>
                <select name="BANCO" id="banco" class="form-control" aria-label="Banco">
                    <option selected disabled>Elige una opción</option>
                     @foreach ($bancos as $banco)
                    <option value="{{ $banco->idb }}">{{ $banco->nombre }}</option>
                    @endforeach
                    
                </select>  
                  
                </div>
                <div class="sub-title">*Empresa</div>
                <div>
                <select name="empresa" id="empresa" class="form-control" aria-label="Empresa">
                    <option selected disabled>Elige una opción</option>
                    <option value="cym">CYM</option>
                    <option value="gcm">GCM</option>
                    <option value="surja">SURJA</option>
                    <option value="yaneth">YANETH</option>
                    <option value="pedro">PEDRO</option>
                </select>  
                  
                </div>
                
               <div class="sub-title">*Cuenta</div>
                <div>
                    <select name="idCuenta" id="cuentas" class="form-control">
                       <option selected disabled>Elige una opción</option>  
                    </select>
                </div>
                <div class="sub-title">*Fecha de pago</div>
                <div>
                    {{Form::date('fecha_pago', old('fecha_pago'), ['class' => 'form-control', 'id' => 'fecha_pago'])}}
                </div>
                
                <div class="sub-title">Referencias</div>
                <div>
                    {{Form::text('referencia', old('referencia'), ['class' => 'form-control', 'id' => 'referencia'])}}
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="sub-title">*Etiqueta</div>
                <div>
                    {{Form::text('etiquetas', old('etiquetas'), ['class' => 'form-control', 'id' => 'etiquetas'])}}
                </div>
                <div class="sub-title">*Metodo de pago</div>
                <div>
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td>PPD - Pago en parcialidades o diferido<input type="radio" name="metodo_pago" id="formaPago_credito" value="PPD" checked></td>
                                <td>PUE - Pago en una sola exhibicion<input type="radio" name="metodo_pago" id="formaPago_efectivo" value="PUE"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="sub-title">*Periodo de pago</div>
                <div class="credito_dias_input">
                    <select class="form-control" name="credito_dias" id="credito_dias">
                        <option value="7" name="credito_dias">7 dias</option>
                        <option value="10" name="credito_dias">10 dias</option>
                        <option value="14" name="credito_dias">14 dias</option>
                        <option value="30" name="credito_dias">30 dias</option>
                        <option value="45" name="credito_dias">45 dias</option>
                        <option value="60" name="credito_dias">60 dias</option>
                    </select>
                </div>
                <div class="sub-title">*Forma de pago</div>
                <div class="forma_pago">
                    <select name="forma_pago" id="forma_pago" class="form-control">
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
                            <td><input type="radio" name="moneda" id="mxn" value="MXN" checked>MXN</td>
                            <td><input type="radio" name="moneda" id="usd" value="USD">USD</td>
                            <td id="cambio_dolar_input" width="60%">
                                <input type="text" name="cambioDolar" id="cambio_dolar" class="form-control" value="1.00" readonly>
                            </td>
                        </tr>
                    </table>
                <div class="sub-title">*Subtotal</div>
                <div>
                    {{Form::text('factura', old('factura'), ['class' => 'form-control', 'id' => 'subtotal'])}}
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-title">*IVA</div>
                        <div>
                            {{Form::text('iva', old('iva'), ['class' => 'form-control', 'id' => 'iva'])}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-title">Total IVA</div>
                        <div>
                            {{Form::text('total_iva', old('total_iva'), ['class' => 'form-control', 'id' => 'total_iva', 'readonly'])}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-title">*Retencion de ISR</div>
                        <div>
                            {{Form::text('isr', old('isr'), ['class' => 'form-control', 'id' => 'isr'])}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-title">Total ISR</div>
                        <div>
                            {{Form::text('total_isr', old('total_isr'), ['class' => 'form-control', 'id' => 'total_isr', 'readonly'])}}
                        </div>
                    </div>
                </div>
                <div class="sub-title">*Total</div>
                <div>
                    {{Form::text('total', old('total'), ['class' => 'form-control', 'id' => 'total', 'readonly'])}}
                </div>
            </div>
            <div class="col-md-12">
                <div class="sub-title">*Descripcion</div>
                <div>
                    <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5"></textarea>
                </div>
                <center style="margin-top: 10px">
                    {{Form::submit('Guardar', ['class' => 'btn btn-default', 'id' => 'btn_guardar', 'style' => 'width: 15%'])}}
                </center>
            </div>
        </section>
    </div>
</div>

<style>
    .mostar{display:block;}
    .ocultar{display:none;}
</style>

<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">

    var id_servicio=JSON.parse({!!json_encode($_GET['id_servicio'])!!});
    var id_factura=JSON.parse({!!json_encode($_GET['id_factura'])!!});

    let arrayCuentas = [];
    $('#cuentas').select2();
    $('#banco').select2();

    document.addEventListener("DOMContentLoaded", ()=>{
        cuentasEmpresa();

        document.querySelector('#empresa').addEventListener('change', ()=>{
            document.querySelector('#cuentas').innerHTML = '';
            cuentasEmpresa();
        });

        if(document.querySelector("#subtotal").value != 0){
            calcTotal();
        }
        
        initialValues();
        calcTotal();

        document.querySelector("#mxn").addEventListener("change", (e=>{
            if(e.target.checked){
                document.querySelector("#cambio_dolar").setAttribute("readonly", "");
                document.querySelector("#cambio_dolar").value="1.0";
            }
        }));
        document.querySelector("#usd").addEventListener("change", (e=>{
            if(e.target.checked){
                document.querySelector("#cambio_dolar").removeAttribute("readonly");
                document.querySelector("#cambio_dolar").value="";
            }
        }));

        document.querySelector("#formAltaGastos").addEventListener("submit", validacion);
        document.querySelector("#formaPago_efectivo").addEventListener("change",(e)=>{
            if(e.target.checked){
                document.querySelector(".credito_dias_input").innerHTML=`<input type="text" name="credito_dias" id="credito_dias" class="form-control" readonly>`;
                document.querySelector("#credito_dias").value="No aplica";
            }
        });
        document.querySelector("#formaPago_credito").addEventListener("change",(e)=>{
            if(e.target.checked){
                document.querySelector(".credito_dias_input").innerHTML=`<select class="form-control" name="credito_dias" id="credito_dias">
                        <option value="7_dias" name="credito_dias">7 dias</option>
                        <option value="10_dias" name="credito_dias">10 dias</option>
                        <option value="14_dias" name="credito_dias">14 dias</option>
                        <option value="30_dias" name="credito_dias">30 dias</option>
                        <option value="45_dias" name="credito_dias">45 dias</option>
                        <option value="60_dias" name="credito_dias">60 dias</option>
                    </select>`;
            }
        });
    });

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
            total = parseFloat(subtotal.value) + total_iva-total_isr;
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
                total = parseFloat(subtotal.value) + total_iva-total_isr;
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
                total = parseFloat(subtotal.value) + total_iva-total_isr;
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
                total = parseFloat(subtotal.value) + total_iva-total_isr;
                document.querySelector("#total").value = total.toFixed(2);
            }
        });
    }

    function cuentasEmpresa(){
        let empresa = document.querySelector('#empresa').value;
        axios.get('cuentasporEmpresa/'+empresa).then((response)=>{
            let cuentas = response.data;
            cuentas.forEach((e)=>{
                let tableOption = document.createElement('option');
                tableOption.value = e.id;
                tableOption.innerHTML = `${e.numeroCu} - ${e.nombre}`;
                document.querySelector('#cuentas').appendChild(tableOption);
            });
        });
    }

    function initialValues(){
        document.querySelector("#id_servicios").value=id_servicio;
        document.querySelector("#id_factura").value=id_factura;
        document.querySelector("#iva").value = 16;
        document.querySelector("#isr").value = 0;
    }

    function validacion(e){
        e.preventDefault();
        
        //Variables para la validacion
        let nombreGasto = document.querySelector("#nombreGasto");
        let beneficiario = document.querySelector("#beneficiario");
        let banco = document.querySelector("#banco");
        let empresa = document.querySelector("#empresa");
        let cuenta = document.querySelector("#cuentas");
        let formaPago_credito = document.querySelector("#formaPago_credito");
        let formaPago_efectivo = document.querySelector("#formaPago_efectivo");
        let referencia = document.querySelector("#referencia");
        let etiquetas = document.querySelector("#etiquetas");
        let subtotal = document.querySelector("#subtotal");
        let total = document.querySelector("#total");
        let descripcion = document.querySelector("#descripcion");
        let btn_guardar = document.querySelector("#btn_guardar");
        
        //Validacion del nombre
        if(nombreGasto.value.length == 0){
            alert("No se asigno un nombre al gasto");
            return;
        }
        //Validacion del beneficiario
        if(beneficiario.value.length == 0){
            alert("No se asigno un beneficiario");
            return;
        }
        //Validacion del banco
        if(banco.value.length == 0){
            alert("No se asigno un banco");
            return;
        }
        //Validacion de la empresa
        if(empresa.value.length == 0){
            alert("No se asigno una empresa");
            return;
        }
        //Validacion de la cuenta
        if(cuenta.value.length == 0){
            alert("No se asigno una cuenta");
            return;
        }

        //Validar la fecha
        if(fecha_pago.value.length == 0){
            alert("No se adjunto una fecha");
            return;
        }else{
            let today = new Date();
            let fecha = fecha_pago.value;
            let anio = fecha.substr(0,4);
            let mes = fecha.substr(5,2);
            let dia = fecha.substr(8,2);

            if(today.getFullYear() < anio){
                alert("Fecha invalida");
                return;
            }else{
                if(today.getFullYear() == anio){
                    if(today.getMonth()+1 < mes){
                        alert("Fecha invalida");
                        return;
                    }else {
                        if(today.getMonth()+1 == mes){
                            if(today.getDate() < dia){
                                alert("Fecha invalida");
                                return;
                            }
                        }
                    }
                }
            }
        }

        //Validar las etiquetas
        if(etiquetas.value.length == 0){
            alert("No se asigno una etiqueta");
            return;
        }

        //Validar la referencia
        /*
        if(parseInt(referencia.value)){
            console.log(typeof(parseInt(referencia.value)));
        }else{
            alert("El numero de referencia no es valido");
            return;
        }
        
        */
        //Validar el subtotal
        if(parseInt(subtotal.value)){
            console.log(typeof(parseInt(subtotal.value)));
        }else{
            alert("El subtotal no es valido");
            return;
        }
        /*

        //Validar el subtotal
        if(parseInt(total.value)){
            console.log(typeof(parseInt(total.value)));
        }else{
            alert("El total no es valido");
            return;
        }
        */
        //Validar el impuesto
        //if(parseInt(impuesto.value)){
        //    console.log(typeof(parseInt(impuesto.value)));
        //}else{
        //    alert("Valor del impuesto no es valido");
        //    return;
        //}

        this.submit();
    }
</script>
@stop