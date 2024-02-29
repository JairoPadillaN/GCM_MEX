
                <div id='filtroUtilidades'>
                    <div>
                        <div class="tab-content">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                            <button id='getData' class='btn btn-sm btn-success'>Generar Excel</button>
                            <zing-grid 
                                    id='tabla'
                                    filter
                                    search
                                    pager
                                    page-size=10
                                    page-size-options='1,5,10,15,20'
                                    theme='android'
                                    context-menu
                                    columns-controls
                                    lang="es"
                                >
                                
                                <zg-colgroup>
                                    <zg-column 
                                        index='a'  
                                        type='text'
                                        header='Folio de Servicio Asignado '
                                    ></zg-column>
                                    <zg-column 
                                        index='b'  
                                        type='text' 
                                        header='Folio de Factura'
                                    ></zg-column>
                                     <zg-column 
                                        index='c'  
                                        type='text'
                                        header='Fecha de Servicio'
                                        filter='disabled'
                                    ></zg-column>
                                   <zg-column 
                                        index='d'  
                                        type='text' 
                                        header='Fecha de Pago'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='e'  
                                        type='text' 
                                        header='Cliente'
                                    ></zg-column>
                                    <zg-column 
                                        index='f'  
                                        type='text' 
                                        header='Sucursal'
										foot-cell='Totales'
                                    ></zg-column>
                                    <zg-column 
                                        index='g'                                        
                                        type='currency'
                                        type-currency='MXN'
                                        header='Monto de Cotización'
                                        filter='disabled'
                                        foot-cell='totalMonto'
                                    ></zg-column>
                                   <!--  <zg-column 
                                        index='h'  
                                        header='Total'
                                        type='currency'
                                        type-currency='MXN'
                                        filter='disabled'
                                    ></zg-column> -->
                                    <zg-column 
                                        index='i'                                        
                                        type='currency'
                                        type-currency='MXN'
                                        header='Total de Gastos'
                                        filter='disabled'
                                        foot-cell='totalGastos'
                                    ></zg-column>
                                    <zg-column 
                                        index='j'                                          
                                        type='currency'
                                        type-currency='MXN' 
                                        header='Total de Compras'
                                        filter='disabled'
                                        foot-cell='totalCompras'
                                    ></zg-column>
                                    <zg-column 
                                        index='k'                                         
                                        type='currency'
                                        type-currency='MXN' 
                                        header='Utilidad del Servicio'
                                        filter='disabled'
                                        foot-cell='totalUtilidad'
                                    ></zg-column>
                                </zg-colgroup>
                            </zing-grid> 
                        </div>                                        
                    </div>
                </div>

                </div>                       
                </div>
            </div>
        </div>
    </div>

<script src='https://cdn.zinggrid.com/zinggrid.min.js' defer>
</script>
<script>
        function totalUtilidad(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-US",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}

function totalMonto(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-US",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}

function totalGastos(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-US",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}

function totalCompras(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-US",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}
$( document ).ready( () => {


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
            url: "{{ route('excelUtilidadGeneral') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporteUtilidad.xlsx";
                link.click();

	        },
        });

    } );
 
  //  window.onload = function() {

       document.querySelector('#tabla').data = @json($consulta);
    
    
    //}

    const zgRef1 = document.querySelector('#tabla');
} );
</script>
<style>
 zg-head-cell, zg-cell,zg-filter {
      font-size: 1.5rem;
    }
.fullscreen-modal .modal-dialog {
  margin: 0;
  margin-right: auto;
  margin-left: auto;
  width: 100%;
}
@media (min-width: 768px) {
  .fullscreen-modal .modal-dialog {
    width: 750px;
  }
}
@media (min-width: 992px) {
  .fullscreen-modal .modal-dialog {
    width: 970px;
  }
}
@media (min-width: 1200px) {
  .fullscreen-modal .modal-dialog {
     width: 1170px;
  }
}
</style>
