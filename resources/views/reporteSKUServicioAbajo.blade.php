
                <div id='filtroSKU'>
                    <div>
                        <div class="tab-content">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                            <button id='getData' class='btn btn-sm btn-success'>Generar Excel</button>
                            <zing-grid 
                                    id='tabla'
                                    filter
                                    pager
                                    page-size=15
                                    page-size-options='15,20,50,100'
                                    theme='android'
                                    context-menu
                                    columns-controls
                                    lang="es"
                                >
                                >
                                
                                <zg-colgroup>
                                <zg-column 
                                        index='id'                                        
                                        type='text'
                                        header='Servicio'
                                    ><a href="modificarFacturas/[[index.id]]" target="_blank">[[index.id]]</a></zg-column>
                                    <zg-column 
                                        index='a'  
                                        type='text'
                                        header='Factura'
                                    ></zg-column>
                                    <zg-column 
                                        index='b'  
                                        type='date' 
                                        header='Fecha de la Factura'
                                        filter='disabled'
                                    ></zg-column>
                                     <zg-column 
                                        index='c'  
                                        type='text'
                                        header='Fecha de Pago'
                                        filter='disabled'
                                    ></zg-column>
                                   <zg-column 
                                        index='p,d'  
                                        type='text' 
                                        header='# Cotización'
                                    ><a href="pdfCotizacion?idCotizacion=[[index.p]]&verTotal=Si&pld=1&cD=Si" target="_blank">[[index.d]]</a></zg-column>
                                    <zg-column 
                                        index='e'  
                                        type='text' 
                                        header='Razón Social'
                                    ></zg-column>
                                    <zg-column 
                                        index='f'  
                                        type='text' 
                                        header='Sucursal'
                                    ></zg-column>
                                    <zg-column 
                                        index='g'                                        
                                        type='text'
                                        header='Nombre de la Refacción'
                                    ></zg-column>
                                    <zg-column 
                                        index='h'                                        
                                        type='text'
                                        header='# de Piezas'
                                    ></zg-column>
                                    <zg-column 
                                        index='i'                                        
                                        type='text'
                                        header='# de Parte'
                                    ></zg-column>
                                    <zg-column 
                                        index='j'                                          
                                        type='text'
                                        header='# de Serie'
                                    ></zg-column>
                                    <zg-column 
                                        index='k'                                         
                                        type='text'
                                        header='Modelo'
                                    ></zg-column>
                                    <zg-column 
                                        index='l'                                        
                                        type='text'
                                        header='SKU'
                                    ></zg-column>
                                    <zg-column 
                                        index='m'                                        
                                        type='text'
                                        header='Tipo'
                                    ></zg-column>
                                    <zg-column 
                                        index='n'                                        
                                        type='text'
                                        header='Marca'
                                    ></zg-column>
                                    <zg-column 
                                        index='o'                                        
                                        type='text'
                                        header='Tipo de Cotización'
                                    ></zg-column>
                                </zg-colgroup>
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
            url: "{{ route('excelSKU') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporteSKU.xlsx";
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
