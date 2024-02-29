
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
                                    page-size-options='15,20,50'
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
                                        header='Sucursal'
                                        type='date'
                                    ></zg-column>
                                    <zg-column 
                                        index='i'  
                                        header='Fecha de Pago'
                                        type='date'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='j'                                        
                                        type='text'
                                        header='Referencia'
                                    ></zg-column>
                                    <zg-column 
                                        index='k'                                          
                                        type='currency'
                                        type-currency='disable' 
                                        header='Subtotal'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='l'                                         
                                        type='text'
                                        header='IVA Total'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='m'                                         
                                        type='text'
                                        header='ISR Total'
                                        filter='disabled'
                                    ></zg-column>
                                    <zg-column 
                                        index='n'                                         
                                        type='text'
                                        header='Total'
                                        filter='disabled'
                                        foot-cell='Totales'
                                    ></zg-column>
                                    <zg-column 
                                        index='o'                                         
                                        type='currency'
                                        type-currency='MXN' 
                                        header='Total MXN'
                                        filter='disabled'
                                        foot-cell='totalGeneral'
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
            url: "{{ route('excelReporteGastoz') }}",
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
