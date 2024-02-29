<div id='filtroGastos'>
                    <div>
                        <div class="tab-content">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                            <button id='getData' class='btn btn-sm btn-success'>Generar Excel</button>
                            <zing-grid 
                                    id='tabla'
                                    filter
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
                                        header='Gasto'
                                    ></zg-column>
                                   <zg-column 
                                        index='d'  
                                        type='text' 
                                        header='Beneficiario'
                                    ></zg-column>
                                    <zg-column 
                                        index='e'  
                                        type='text' 
                                        header='Empresa'
                                    ></zg-column>
                                    <zg-column 
                                        index='f'  
                                        type='text' 
                                        header='Cuenta'
                                    ></zg-column>
                                    <zg-column 
                                        index='g'                                        
                                        type='text'
                                        header='Razón Social'
                                    ></zg-column>
                                    <zg-column 
                                        index='h'                                        
                                        type='text'
                                        header='Sucursal'
                                    ></zg-column>
                                    <zg-column 
                                        index='i'                                          
                                        type='date'
                                        header='Fecha de Pago'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='j'                                         
                                        type='text' 
                                        header='Referencia'
                                    ></zg-column>
                                    <zg-column 
                                        index='k'                                        
                                        type='text'
                                        header='Divisa'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='l'                                        
                                        type='currency'
                                        type-currency='disable'
                                        header='Subtotal'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='m'                                        
                                        type='currency'
                                        type-currency='disable'
                                        header='IVA'
                                        filter='disabled'
                                        foot-cell='Total = '
                                    ></zg-column>
                                    <zg-column 
                                        index='n'                                        
                                        type='currency'
                                        type-currency='MXN'
                                        header='IVA MXN'
                                        filter='disabled'
                                        foot-cell='totalIVA'
                                    ></zg-column>
                                    <zg-column 
                                        index='o'                                        
                                        type='currency'
                                        type-currency='disable'
                                        header='ISR'
                                        filter='disabled'
                                        foot-cell='Total = '
                                    ></zg-column>
                                    <zg-column 
                                        index='p'                                        
                                        type='currency'
                                        type-currency='MXN'
                                        header='ISR MXN'
                                        filter='disabled'
                                        foot-cell='totalISR'
                                    ></zg-column>
                                    <zg-column 
                                        index='q'                                        
                                        type='currency'
                                        type-currency='disable'
                                        header='Total'
                                        filter='disabled'
                                        foot-cell='Total = '
                                    ></zg-column>
                                    <zg-column 
                                        index='r'                                        
                                        type='currency'
                                        type-currency='MXN'
                                        header='Total MXN'
                                        filter='disabled'
                                        foot-cell='totalMEX'
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
        function totalIVA(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-US",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}

function totalISR(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("en-US",{style: "currency", currency: "MXN"}).format(total));

    return totalFormato;
}

function totalMEX(columnData) {
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
            url: "{{ route('excelGastosGeneral') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporteGastos.xlsx";
                link.click();

	        },
        });

    } );
 
    // window.onload = function() {

        document.querySelector('#tabla').data = @json($consulta);
    //    3);
    
    // }

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
