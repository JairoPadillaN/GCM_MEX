@extends('principal')
@section('contenido')
    <style>
        #filter-section{
            display: flex;
        }
        #filter-section table{
            margin: auto;
            width: 80%;
        }
        #filter-section table thead tr th{
            text-align: left;
        }
        #month-input-td, #year-input-td{
            width:15%;
        }
        #filters-input-td{
            width: 25%;
        }
    </style>
    <script nonce="undefined" src="https://cdn.zinggrid.com/zinggrid.min.js"></script>
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>Reporte de ventas por mes</h1>
            </div>
            <div class="panel-body">
                <hr>
                <section id="filter-section">
                    <table>
                        <thead>
                            <tr>
                                <th><label for="mes">Mes</label></th>
                                <th><label for="year">Año</label></th>
                                <th><label for="filter-by">Filtrar por:</label></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="month-input-td">
                                    <select class="form-control" name="month" id="month-input"  autocomplete="off">
                                        @if($month == '01')
                                            <option value="01"selected>Enero</option>
                                        @else
                                            <option value="01">Enero</option>
                                        @endif
                                        @if($month == '02')
                                        <option value="02" selected="selected">Febrero</option>
                                        @else
                                        <option value="02">Febrero</option>
                                        @endif
                                        @if($month == '03')
                                        <option value="03" selected="selected">Marzo</option>
                                        @else
                                        <option value="03">Marzo</option>
                                        @endif
                                        @if($month == '04')
                                        <option value="04" select="selected">Abril</option>
                                        @else
                                        <option value="04">Abril</option>
                                        @endif
                                        @if($month == '05')
                                        <option value="05" selected="selected">Mayo</option>
                                        @else
                                        <option value="05">Mayo</option>
                                        @endif
                                        @if($month=='06')
                                        <option value="06" selected="selected">Junio</option>
                                        @else
                                        <option value="06">Junio</option>
                                        @endif
                                        @if($month == '07')
                                        <option value="07" selected="selected">Julio</option>
                                        @else
                                        <option value="07">Julio</option>
                                        @endif
                                        @if($month == '08')
                                        <option value="08" selected="selected">Agosto</option>
                                        @else
                                        <option value="08">Agosto</option>
                                        @endif
                                        @if($month == '09')
                                        <option value="09" selected="selected">Septiembre</option>
                                        @else
                                        <option value="09">Septiembre</option>
                                        @endif
                                        @if($month == '10')
                                        <option value="10" selected="selected">Octubre</option>
                                        @else
                                        <option value="10">Octubre</option>
                                        @endif
                                        @if($month == '11')
                                        <option value="11" selected="selected">Noviembre</option>
                                        @else
                                        <option value="11">Noviembre</option>
                                        @endif
                                        @if($month == '12')
                                        <option value="12" selected="selected">Diciembre</option>
                                        @else
                                        <option value="12">Diciembre</option>
                                        @endif
                                    </select>
                                </td>
                                <td id="year-input-td"><input class="form-control" id="year-input" type="number" name="year" min="1900" max="2099" step="1" value="{{$year}}" /></td>
                                <td id="filters-input-td">
                                    <span>Empresa: </span>
                                    <input type="radio" name="filter-by" id="filters-by-empresa" value="empresa" checked>
                                    <span>Vendedor: </span>
                                    <input type="radio" name="filter-by" id="filters-by-vendedor" value="vendedor">
                                </td>
                                <td>
                                    <div id="empresas-section">
                                        <select class="form-control" name="" id="input-empresas">
                                            <option value="all">Todos</option>
                                            @foreach($empresas as $empresa)
                                                <option value="{{$empresa->idc}}">{{$empresa->razonSocial}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="vendedores-section">
                                        <select class="form-control" id="input-vendedores">
                                            <option value="all">Todos</option>
                                            @foreach($vendedores as $vendedor)
                                                <option value="{{ $vendedor->idu }}">{{ $vendedor->nombreUsuario }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-primary" id="filtrar-reporte-button">Buscar</button>
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="" id="export-reporte-button">Exportar</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                <hr>
                <section id="reporte-table">
                    
                </section>
                <section id="total-table" style="width: 100%">
                    <table style="width: 100%">
                        <tbody id="total-table-body"></tbody>
                    </table>
                </section>
                <hr>
                <section id="table-empresas">
                    <table class="table table-striped table-hover" style="width: 40%">
                        <thead><tr>
                            <th>Empresa</th>
                            <th>Cantidad</th>
                        </tr></thead>
                        <tbody id="t-body-empresas"></tbody>
                    </table>
                </section>
                <section id="table-vendedores">
                <table class="table table-striped table-hover" style="width: 40%">
                        <thead><tr>
                            <th>Vendedor</th>
                            <th>Cantidad</th>
                        </tr></thead>
                        <tbody id="t-body-vendedores"></tbody>
                    </table>
                </section> 
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/CosmoScript.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#input-empresas').select2();
        $('#input-vendedores').select2();

        let format = new Intl.NumberFormat('MXN');

        // * Variables de los filtros
        let month = document.querySelector('#month-input');
        let year = document.querySelector('#year-input');
        let filterEmpresa = document.querySelector('#filters-by-empresa');
        let filterVendedor = document.querySelector('#filters-by-vendedor');
        let btnFiltrar = document.querySelector('#filtrar-reporte-button');
        document.addEventListener('DOMContentLoaded', ()=>{
            if(filterEmpresa.checked){
                $('#empresas-section').attr('style', 'display: block;');
                $('#vendedores-section').attr('style', 'display: none;');
                document.querySelector('#table-empresas').setAttribute('style', 'display:block;');
                document.querySelector('#table-vendedores').setAttribute('style', 'display:none;');
            }
            if(filterVendedor.checked){
                $('#empresas-section').attr('style', 'display: none;');
                $('#vendedores-section').attr('style', 'display: block;');
                document.querySelector('#table-empresas').setAttribute('style', 'display:none;');
                document.querySelector('#table-vendedores').setAttribute('style', 'display:block;');
            }
        });

        // * Variables para exportar
        let btnExport = document.querySelector('#export-reporte-button');

        // * Eventlisteners de los filtros
        btnFiltrar.addEventListener('click', (e)=>{
            e.preventDefault();
            createTable();
            createTableEmpresas();
            createTotalTable()
        });
        filterEmpresa.addEventListener('click', (e)=>{
            $('#empresas-section').attr('style', 'display: block;');
            $('#vendedores-section').attr('style', 'display: none;');
        });
        filterVendedor.addEventListener('click', (e)=>{
            $('#empresas-section').attr('style', 'display: none;');
            $('#vendedores-section').attr('style', 'display: block;');
        });

        function createTotalTable(){
            document.querySelector('#cosmic-footer-suma').innerHTML = '';
            let filter = filterEmpresa.checked ? $('#input-empresas').val() : $('#input-vendedores').val();
            let params = {
                month: month.value,
                year: year.value,
                filterEmpresa: filterEmpresa.checked,
                filterVendedor: filterVendedor.checked,
                filter: filter
            }
            
            fetch(filterEmpresa.checked ?
                    `reporteventasempresatotal/${params.month}/${params.year}/${filter}` :
                    `reporteventasvendedortotal/${params.month}/${params.year}/${filter}`
            ).then((response)=>response.json())
            .then((response)=>{
                document.querySelector('#cosmic-footer-suma').innerHTML = `<td colspan=11></td>
                                                                           <td>$${response.subtotal}</td>
                                                                           <td>$${response.iva}</td>
                                                                           <td>$${response.suma}</td>`;
            });
        }

        // * Crear tabla de vendedores
        function createTableEmpresas(){
            document.querySelector('#t-body-empresas').innerHTML = '';
            document.querySelector('#t-body-vendedores').innerHTML = '';
            let filter = filterEmpresa.checked ? $('#input-empresas').val() : $('#input-vendedores').val();
            let params = {
                month: month.value,
                year: year.value,
                filterEmpresa: filterEmpresa.checked,
                filterVendedor: filterVendedor.checked,
                filter: filter
            }
            let container = filterEmpresa.checked ? document.querySelector('#t-body-empresas'): document.querySelector('#t-body-vendedores');
            fetch(filterEmpresa.checked ? 
                    `reporteventasempresa/${params.month}/${params.year}/${filter}` :
                    `reporteventasvendedor/${params.month}/${params.year}/${filter}`)
                .then((response)=>response.json())
                .then((response)=>{
                    response.response.forEach((element)=>{
                        let tableRow = document.createElement('tr');
                        tableRow.innerHTML = filterEmpresa.checked ? `<td>${element.razonSocial}</td> <td style="text-align: right;">${element.montoPesos}</td>`: `<td>${element.nombreUsuario}</td> <td style="text-align: right;">${(element.montoPesos)}</td>`;
                        container.appendChild(tableRow);
                    });
                    let tableRow = document.createElement('tr');
                    tableRow.innerHTML = `<td style="text-align: right;"><b>Total:</b></td><td style="text-align: right;"><b>${response.suma}</b></td>`;
                    container.appendChild(tableRow);
                });
                if(filterEmpresa.checked){
                    document.querySelector('#table-empresas').setAttribute('style', 'display:block;');
                    document.querySelector('#table-vendedores').setAttribute('style', 'display:none;');
                }
                if(filterVendedor.checked){
                    document.querySelector('#table-empresas').setAttribute('style', 'display:none;');
                    document.querySelector('#table-vendedores').setAttribute('style', 'display:block;');    
                }
        }

        // * Funcion para crear la tabla
        function createTable(){
            document.querySelector('#reporte-table').innerHTML = '';
            let filter = filterEmpresa.checked ? $('#input-empresas').val() : $('#input-vendedores').val();
            let params = {
                month: month.value,
                year: year.value,
                filterEmpresa: filterEmpresa.checked,
                filterVendedor: filterVendedor.checked,
                filter: filter
            }
            
            btnExport.removeAttribute('href');
            btnExport.setAttribute('href', `reporteventasdataexport/${params.month}/${params.year}/${params.filterEmpresa}/${params.filterVendedor}/${params.filter}`);

            Cosmic.table({
                container: '#reporte-table',
                url: `reporteventasdata/${params.month}/${params.year}/${params.filterEmpresa}/${params.filterVendedor}/${filter}`,
                tableElements: [
                        {name: "Fecha de facturacion", column: "fechaFactura", filter: false}, 
                        {name: "Fecha de vencimiento", column: "fechaVencimiento", filter: false}, 
                        {name: "Moneda facturada", column: "tipoMoneda", filter: false}, 
                        {name: "Estatus", column: "estatusPago", filter: true}, 
                        {name: "Plazo de pago", column: "periodoPago", filter: false}, 
                        {name: "Vendedor", column: "nombreUsuario", filter: true}, 
                        {name: "Numero de facura", column: "numeroFactura", filter: true}, 
                        {name: "No. Servicio German", column: "idServicios", filter: true}, 
                        {name: "Cliente", column: "razonSocial", filter: true}, 
                        {name: "Tipo de cambio", column: "cambioFactura", filter: false},
                        {name: "Saldo real", column: "saldo", filter: false},
                        {name: "Subtotal real", column: "subtotal"},
                        {name: "IVA real", column: "iva"},
                        {name: "Monto real", column: "montoPesos", filter: false, suma: true} 
                        ],
                classes: ["table", "table-striped", "table-hover"],
                paginate: 40,
            });
        }

        function filtros(){
            if(filterEmpresa.checked){
                console.log('Empresa');
            }
            if(filterVendedor.chequed){
                console.log('Vendedor');
            }
        }

        createTable();
        createTableEmpresas();
        createTotalTable();
    </script>
@endsection