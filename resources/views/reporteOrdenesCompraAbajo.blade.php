
<ul class="nav nav-tabs">
    <li class="active" id='tab1' ><a href="#1" data-toggle="tab"><label for="">Activas</label></a></li>
    <li class="" id='tab2' ><a href="#2" data-toggle="tab"><label for="">Canceladas</label></a></li>
</ul>
<div class="tab-content">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="tab-pane active" id="1">
        <div class="table-responsive">
            <br>
            <button id='getData' class='btn btn-sm btn-success'>Generar Excel</button>
            <zing-grid 
                    id='tabla'
                    filter
                    search
                    theme='android'
                    context-menu
                    columns-controls
                >
                <zg-colgroup>
                    <!-- <zg-column 
                        index='a'  
                        type='text'
                        header='Folio de servicio'
                    ></zg-column> -->
                    <zg-column 
                        index='b'  
                        type='text' 
                        header='Folio orden'
                    ></zg-column>
                    <zg-column 
                        index='c'  
                        type='text'
                        header='Fecha'
                    ></zg-column>
                    <zg-column 
                        index='d'  
                        type='text' 
                        header='Proveedor'
                    ></zg-column>
                    <!-- <zg-column 
                        index='e'  
                        type='text' 
                        header='Sucursal'
                    ></zg-column> -->
                    <zg-column 
                        index='f'  
                        type='text' 
                        header='Empresa'
                    ></zg-column>
                    <!-- <zg-column
                        index='g'
                        type='text'
                        header='Cuenta'
                        
                    ></zg-column> -->
                    <zg-column 
                        index='h'
                        type='currency'
                        type-currency='MXN'
                        header='Importe'
                        
                    ></zg-column>
                    <zg-column
                        index='i'
                        type='currency'
                        type-currency='MXN'
                        header='IVA'
                        
                    ></zg-column>
                    <zg-column
                        index='j'
                        type='currency'
                        type-currency='MXN'
                        header='ISR'
                        
                    ></zg-column>
                    <zg-column
                        index='K'
                        type='currency'
                        type-currency='MXN'
                        header='Retención'
                        
                    ></zg-column>
                    <zg-column
                        index='o'
                        type='currency'
                        type-currency='MXN'
                        header='Total'
                        foot-cell='TOTAL'
                    ></zg-column>
                    <zg-column
                        index='l'
                        type='currency'
                        type-currency='MXN'
                        header='Total MXN'
                        foot-cell='total'
                    ></zg-column>
                    <zg-column 
                        index='m'  
                        type='text' 
                        header='Estatus'
                    ></zg-column>
                    <zg-column 
                        index='n'  
                        type='text' 
                        header='Opciones'
                    ></zg-column>
                </zg-colgroup>
            </zing-grid> 
        </div>
    </div>
    <div class="tab-pane fade" id="2">
        <div class="table-responsive">
            
                <zing-grid 
                        id='tablaCanceladas'
                        filter
                        search
                        theme='android'
                        context-menu
                        columns-controls
                        lang="es"
                    >
                    <zg-colgroup>
                        <zg-column
                            index='id'
                            type='text'
                            header='Clave'
                            filter='disabled'
                            hidden='true'
                        ></zg-column>
                        <!-- <zg-column 
                            index='a'  
                            type='text'
                            header='Folio de servicio'
                        ></zg-column> -->
                        <zg-column 
                            index='b'  
                            type='text' 
                            header='Folio orden'
                        ></zg-column>
                        <zg-column 
                            index='c'  
                            type='text'
                            header='Fecha'
                        ></zg-column>
                        <zg-column 
                            index='d'  
                            type='text' 
                            header='Proveedor'
                        ></zg-column>
                        <!-- <zg-column 
                            index='e'  
                            type='text' 
                            header='Sucursal'
                        ></zg-column> -->
                        <zg-column 
                            index='f'  
                            type='text' 
                            header='Empresa'
                        ></zg-column>
                        <!-- <zg-column 
                            index='g'  
                            type='text' 
                            header='Cuenta'
                        ></zg-column> -->
                        <zg-column 
                            index='h'  
                            type='text' 
                            header='Importe'
                        ></zg-column>
                        <zg-column 
                            index='i'  
                            type='text' 
                            header='IVA'
                        ></zg-column>
                        <zg-column 
                            index='j'  
                            type='text' 
                            header='ISR'
                        ></zg-column>
                        <zg-column 
                            index='k'  
                            type='text' 
                            header='Retención'
                        ></zg-column>
                        <zg-column 
                            index='l'  
                            type='text' 
                            header='Total'
                        ></zg-column>
                        <zg-column 
                            index='m'  
                            type='text' 
                            header='Estatus'
                        ></zg-column>
                        <zg-column 
                            index='n'  
                            type='text' 
                            header='Opciones'
                        ></zg-column>
                    </zg-colgroup>
                </zing-grid> 
        </div>
    </div>
</div>

<script src='https://cdn.zinggrid.com/zinggrid.min.js' defer></script>
<script>
function totalIva(columnData) {
    console.log(arguments);
    console.log(" ColumnDATAar:"+ columnData.length);

    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }

    console.log("total " + total);
    totalFormato=(new Intl.NumberFormat("en-IN",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}

function totalImporte(columnData) {
    console.log(arguments);
    var total = 0;

    for (const value of columnData) {
        
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-IN",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}
function totalIsr(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-IN",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}
function total(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-IN",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}
$( document ).ready( () => {
    // window.onload = function() {
        document.querySelector('#tabla').data = @json($consulta);
        document.querySelector('#tablaCanceladas').data = @json($consultaCanceladas);
    // }

    $("#getData").on('click', () => {

        let clv = [];

        const zgRef = document.querySelector( '#tabla' );

        const gridData = zgRef.getData( {
                                            'rows' : 'visible',
                                        } );

        for ( let val of gridData )
        {
            clv.push( val['id'] );
        }        

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type:"POST",
            url: "{{ route('excelOrdenesCompra') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv},
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporteOrden.xlsx";
                link.click();

            },
        });

        } );
} );
</script>
<style>
zg-foot{text-align:right;font-weight:bold;}
</style>