@extends('principal')
@section('contenido')

<script>
    $(document).ready(function(){
        $(".cargar").click();
    });
</script>
<!-- vista de Gastos Operativos                       -->
<div class="col-xs-12">
    <div class="panel panel-default" style="margin-top:-55px">
        <div class="panel-heading">
            <h1>Gastos operativos</h1>
        </div>
        <div class="panel-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="cargar" class="nav-link-active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                        role="tab" aria-controls="pills-home" aria-selected="true">Información del gasto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-agregar"
                        role="tab" aria-controls="pills-contact">Agregar gasto</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
            <div class="tab-content" id="pills-tabContent" style="margin-top: 10px">

            <!-- * Tabla de gastos operativos -->
<div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
    @if(Session::has('success'))           
        <div class="alert alert-info gastos1">
        {{Session::get('success')}}
        </div>
    @endif
    <div class="row" style="width: 100%">
        <form action="{{route('GastosOperativosFecha')}}" method="get">
            <div class="col-md-4">
                <span>Fecha de inicio</span>
                @if(isset($fechaInicio))
                    <input type="date" name="fechaInicio" id="fechaInicio" class="form-control" value="{{$fechaInicio}}">
                @else
                    <input type="date" name="fechaInicio" id="fechaFin" class="form-control">
                @endif
            </div> 
            <div class="col-md-4">
                <span>Fecha final</span>
                @if(isset($fechaFin))
                    <input type="date" name="fechaFin" id="fechaFin" class="form-control" value="{{$fechaFin}}">
                @else
                    <input type="date" name="fechaFin" id="fechaFin" class="form-control">
                @endif
            </div>
            <div class="col-md-2">
                <br>
                <input type="submit" value="Aplicar filtro" class="btn btn-success form-control">
            </div>
        </form>
        <div class="col-md-1">
            <br>
            <a href="{{route('GastosOperativos')}}">Limpiar filtros</a>
        </div>
        <div class="col-md-1" id="excel-gastos-operativos">
            <a id="export" href="{{route('excelGastosOperativos', ['null', 'null', 'null', 'null'])}}">
                <img src="{{asset('img/excelico.png')}}" height=50 width=50>
            </a>
        </div>
    </div>
    <div id="table-gastos-operativos" class="table-responsive">
             <table id="tGastos"></table>
    </div>
</div>
<br>

        <!-- * Formulario para nuevos gastos operativos -->

            <div class="tab-pane fade" id="pills-agregar" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="col-md-12">
                <div class="sub-title">
                    <small>*Campos obligatorios</small>
                </div>
            </div>
                <div class="form-group col-md-6">
                    {{Form::open(['route' => "guardarGastoOperativo", 'id' => 'formAltaGastos', 'files' => true])}}
                    {{Form::token()}}
                    <div class="sub-title">*Nombre del gasto</div>
                    <div>
                        {{Form::text(('nombreGasto'), old('nombreGasto'),['class' => 'form-control', 'id' => 'nombreGasto', 'placeholder' => 'Ejemplo: Vuelo'])}}
                    </div>
                    <div class="sub-title">*Beneficiario</div>
                    <div>
                        {{Form::text('beneficiario', old('beneficiario'), ['class' => 'form-control', 'id' => 'beneficiario', 'placeholder' => 'Ejemplo: #'])}}
                    </div>
                    <div class="sub-title">*Empresa</div>
                    <div>
                        <select name="empresa" id="empresa" class="form-control" aria-label="Empresa">
                            <option value="cym">CYM</option>
                            <option value="gcm">GCM</option>
                            <option value="pedro">PEDRO</option>
                            <option value="surja">SURJA</option>
                            <option value="yaneth">YANETH</option>
                        </select>
                    </div>  
                    <div class="sub-title">*Cuenta</div>
                    <div>
                        <select class="form-control" id="cuentas" name ="idCuenta"></select>
                    </div>
                    <div class="sub-title">*Banco</div>
                    <div id="banco_input">
                        <select name="BANCO" id="banco" class="form-control" aria-label="Banco">
                            <option selected disabled>Elige una opción</option>
                            @foreach ($bancos as $banco)
                                <option value="{{ $banco->idb }}">{{ $banco->nombre }}</option>
                            @endforeach
                        </select> 
                    </div>
                    <div class="sub-title">*Fecha de pago</div>
                    <div>
                        {{Form::date('fecha_pago', old('fecha_pago'), ['class' => 'form-control', 'id' => 'fecha_pago'])}}
                    </div>
                    <div class="sub-title">*Etiqueta</div>
                    <div>
                        <select name="etiquetas" id="etiquetas" class="form-control">
                            <option value="">Seleccione etiqueta</option>
                            <option value="COSTO DE VENTA"
                                @if (old('etiquetas') == 'COSTO DE VENTA') {{ 'selected' }} @endif>COSTO DE VENTA
                            </option>
                            <option value="GASTO ADMINISTRATIVO"
                                @if (old('etiquetas') == 'GASTO ADMINISTRATIVO') {{ 'selected' }} @endif>GASTO
                                ADMINISTRATIVO</option>
                            <option value="GASTO FINANCIERO"
                                @if (old('etiquetas') == 'GASTO FINANCIERO') {{ 'selected' }} @endif>GASTO FINANCIERO
                            </option>                                        
                            <option value="GASTO DE VENTA"
                                @if (old('etiquetas') == 'GASTO DE VENTA') {{ 'selected' }} @endif>GASTO DE VENTA
                            </option>
                            <option value="IMPUESTOS"
                                @if (old('etiquetas') == 'IMPUESTOS') {{ 'selected' }} @endif>IMPUESTOS</option>
                            <option value="INTERES"
                                @if (old('etiquetas') == 'INTERES') {{ 'selected' }} @endif>INTERES</option>
                        </select>
                    </div>
                    <div class="sub-title">Referencias</div>
                    <div>
                        {{Form::text('referencia', old('referencia'), ['class' => 'form-control', 'id' => 'referencia'])}}
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
                            <td><input type="radio" name="moneda" id="mxn" value="MXN" checked>MXN</td>
                            <td><input type="radio" name="moneda" id="usd" value="USD">USD</td>
                            <td id="cambio_dolar_input" width="60%">
                                <input type="text" name="cambioDolar" id="cambio_dolar" class="form-control" value="1.00" readonly>
                            </td>
                        </tr>
                    </table>
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
                    <div class="sub-title">Total</div>
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
            </div>
        </div>
    </div>
</div>
<!-- La etiqueta meta es necesaria si la peticion es de tipo POST -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/CosmoScript.js') }}"></script>
<script>
    $('#cuentas').select2({
            width: '100%'
        });
    $('#banco').select2({
            width: '100%'
        });

    /*
    Cosmic.table({
        container: '#table-gastos-operativos',
        url: 'get-data-gastos-operativos',
        tableElements: [
            {name: "Fecha de pago", column: "fecha_pago", filter: false}, 
            {name: "Beneficiario", column: "beneficiario", filter: true}, 
            {name: "Forma de pago", column: "forma_pago", filter: false}, 
            {name: "Cuenta contable", column: "cuenta", filter: true},
            {name: "Empresa", column: "empresa", filter: true}, 
            {name: "Referencia", column: "referencia", filter: true},
            {name: "Etiqueta", column: "etiquetas", filter: true},
            {name: "Descripcion", column: "descripcion", filter: true},

        ],
        classes: ["table", "table-striped"],
        paginate: 10
    });
    */

    
    $(document).ready(function(){
        $("#tGastos").anexGrid({
            class: 'table table-striped',
            //Columnas crea los th de la tabla
            columnas: [
                {leyenda: 'Fecha de pago', ordenable: true, columna: 'fecha_pago'},
                {leyenda: 'Beneficiario', ordenable: true, columna: 'beneficiario', filtro: true},
                {leyenda: 'Forma de pago', ordenable: true, columna: 'forma_pago'},
                {leyenda: 'Cuenta contable', ordenable: true, columna: 'cuenta', filtro: true},
                {leyenda: 'Empresa', ordenable: true, columna: 'empresa', filtro: true},
                {leyenda: 'Referencia', ordenable: true, columna: 'referencia', filtro: true},
                {leyenda: 'Etiqueta', columna: 'etiquetas', ordenable: true, filtro: true},
                {leyenda: 'Descripcion', columna: 'descripcion', ordenable: true, filtro: true},
                {leyenda: 'Subtotal', columna: 'factura', ordenable: true},
                {leyenda: 'IVA %', columna: 'iva', ordenable: true},
                {leyenda: 'Total iva', columna: 'total_iva', ordenable: true},
                {leyenda: 'ISR %', columna: 'isr', ordenable: true},
                {leyenda: 'Total isr', columna: 'total_isr', ordenable: true},
                {leyenda: 'Total', columna: 'total', ordenable: true},
                {leyenda: 'Total en pesos', ordenable: true},
                {leyenda: 'Opciones'},
            ],
            //Modelo crea los td segun cada registro
             modelo: [
                 {propiedad:'fecha_pago'},
                 {propiedad:'beneficiario'},
                 {propiedad:'forma_pago'},
                 {propiedad:'cuenta'},
                 {propiedad:'empresa'},
                 {propiedad:'referencia'},
                 {propiedad:'etiquetas'},
                 {propiedad:'descripcion'},
                 //El formato se define como una funcion con un return que muestra el formato de texto que le quieras dar
                 {propiedad:'factura', formato: function(tr, obj, celda){
                     return obj.moneda + ' $'+ obj.factura;
                 }},
                 {propiedad:'iva', formato: function(tr, obj, celda){
                     return obj.iva+'%';
                 }},
                 {propiedad:'total_iva', formato: function(tr, obj, celda){
                     return obj.moneda + ' $' + (obj.total_iva);
                 }},
                 {propiedad:'isr', formato: function(tr, obj, celda){
                     return obj.isr+'%';
                 }},
                 {propiedad:'total_isr', formato: function(tr, obj, celda){
                     return obj.moneda + ' $' + (obj.total_isr);
                 }},
                 {propiedad:'total', formato: function(tr, obj, celda){
                     return obj.moneda + ' $' + (obj.total);
                 }},
                 {formato: function(tr, obj, celda){
                     if(obj.moneda == 'USD'){
                         return 'MXN $'+ (obj.cambioDolar*obj.total).toFixed(2);
                     }else{
                         return obj.moneda + ' $' + (obj.total);
                     }
                 }},
                 { class:'text-center', formato: function(tr, obj,celda){               
                    let botones ='';

                        if (obj.activo=='Si') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: '<i class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>',
                                href: 'baja_logica_go/' + obj.id
                            });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info',
                                contenido: '<i class="ace-icon fa fa-pencil bigger-120"> Editar</i>',
                                href: 'editarGastosOperativos/' + obj.id
                            });

                        }else{

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: '<span>Restaurar</span>',
                                href: 'baja_logica_go/' + obj.id
                            });

                        }
                    return botones;

                    },
                },

             ],
            fechaInicio: $('#fechaInicio').val(),
            fechaFin: $('#fechaFin').val(),
            //url es la direccion y no el nombre de la ruta
            url: $('#fechaInicio').val() == '' || $('#fechaFin').val() == '' ? 'get-data-gastos-operativos' : `get-data-gastos-operativos/${$('#fechaInicio').val()}/${$('#fechaFin').val()}`,
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'id',
            columna_orden: 'DESC'
        });
        
    })
</script>

<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">

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
        document.querySelector("#formAltaGastos").addEventListener("submit", validacion);
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

    function initialValues(){
        document.querySelector("#iva").value = 16;
        document.querySelector("#isr").value = 20;
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
        let cambio_dolar = document.querySelector('#cambio_dolar');
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

        if(cambio_dolar.value != 'No aplica'){
            if(parseInt(cambio_dolar.value)){
                console.log(typeof(parseInt(subtotal.value)));
            }else{
            alert("El subtotal no es valido");
            return;
            }
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
<script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
<script>
    $(document).ready(function(){
        setFiltros();
    });
    function setFiltros(){
        let arreglo = {
            fechaInicio: inic = $('#fechaInicio').val() != null ? $('#fechaInicio').val() : 'null',
            fechaFin: fin = $('#fechaFin').val() != '' ? $('#fechaFin').val() : 'null',
            filtro: 'null',
            value: 'null'
        };
        setURL(arreglo);
        let filtroBeneficiario = $("input[data-columna = 'beneficiario']");
        filtroBeneficiario.keyup(function(e){
            if(e.key === 'Enter'){
                arreglo = {
                    fechaInicio: inic = $('#fechaInicio').val() != null ? $('#fechaInicio').val() : 'null',
                    fechaFin: fin = $('#fechaFin').val() != '' ? $('#fechaFin').val() : 'null',
                    filtro: 'beneficiario',
                    value: filtroBeneficiario.val()
                }
                console.log(arreglo);
                setURL(arreglo);
            }
        });
        let filtroReferencia = $("input[data-columna = 'referencia']");
        filtroReferencia.keyup(function(e){
            if(e.key === 'Enter'){
                arreglo = {
                    fechaInicio: inic = $('#fechaInicio').val() != null ? $('#fechaInicio').val() : 'null',
                    fechaFin: fin = $('#fechaFin').val() != '' ? $('#fechaFin').val() : 'null',
                    filtro: 'referencia',
                    value: filtroReferencia.val()
                }
                console.log(arreglo);
                setURL(arreglo);
            }
        });
        let filtroEtiqueta = $("input[data-columna = 'etiquetas']");
        filtroEtiqueta.keyup(function(e){
            if(e.key === 'Enter'){
                arreglo = {
                    fechaInicio: inic = $('#fechaInicio').val() != null ? $('#fechaInicio').val() : 'null',
                    fechaFin: fin = $('#fechaFin').val() != '' ? $('#fechaFin').val() : 'null',
                    filtro: 'etiquetas',
                    value: filtroEtiqueta.val()
                }
                setURL(arreglo);
            }
        });
        let filtroDescripcion = $("input[data-columna = 'descripcion']");
        filtroDescripcion.keyup(function(e){
            if(e.key === 'Enter'){
                arreglo = {
                    fechaInicio: inic = $('#fechaInicio').val() != null ? $('#fechaInicio').val() : 'null',
                    fechaFin: fin = $('#fechaFin').val() != '' ? $('#fechaFin').val() : 'null',
                    filtro: 'descripcion',
                    value: filtroDescripcion.val()
                }
                setURL(arreglo);
            }
        });
        $('.anexgrid-tGastos-filtro-limpiar').click(function(){
            let arreglo = {
                fechaInicio: inic = $('#fechaInicio').val() != null ? $('#fechaInicio').val() : 'null',
                fechaFin: fin = $('#fechaFin').val() != '' ? $('#fechaFin').val() : 'null',
                filtro: 'null',
                value: 'null'
            }
            setURL(arreglo);
        });
    }
    function setURL(arreglo){
        url_temp = "{{route('excelGastosOperativos',['temp', 'temp1', 'temp2', 'temp3'])}}";

        url = url_temp.replace('temp', arreglo.fechaInicio)
                        .replace('temp1', arreglo.fechaFin)
                        .replace('temp2', arreglo.filtro)
                        .replace('temp3', arreglo.value);

        $('#export').attr('href',url);
    }
</script>
<!-- termina vista gastos                         -->
@stop
