
<div id='filtroEquiposenSevicios'>
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
                                    page-size-options='10,20,50,100'
                                    theme='android'
                                    context-menu
                                    columns-controls
                                    lang="es"
                                >
                                
                                <zg-colgroup>
                                <zg-column 
                                        index='id'  
                                        type='text' 
                                        header='Servicio'
                                    ><a href="/modificarFacturas/[[index.id]]" target="_blank">[[index.id]]</a></zg-column>
                                    <zg-column 
                                        index='a,id'  
                                        type='text'
                                        header='Factura'
                                    ><a href="/modificarFacturas/[[index.id]]" target="_blank">[[index.a]]</a></zg-column></zg-column>
                                    <zg-column 
                                        index='b,c'  
                                        type='text' 
                                        header='Cotizacion'
                                    ><a href="/pdfCotizacion?idCotizacion=[[index.b]]&verTotal=Si&pld=1&cD=Si" target="_blank">[[index.c]]</a></zg-column>
                                     <zg-column 
                                        index='d'  
                                        type='text'
                                        header='Fecha'
                                    ></zg-column>
                                   <zg-column 
                                        index='e'  
                                        type='text' 
                                        header='Fecha Pago'
                                    ></zg-column>
                                    <zg-column 
                                        index='f'  
                                        type='text' 
                                        header='Razon Social'
                                    ></zg-column>
                                    <zg-column 
                                        index='g'  
                                        type='text' 
                                        header='Sucursal'
                                    ></zg-column>
                                    <zg-column 
                                        index='h'  
                                        type='text' 
                                        header='GCMID'
                                    ></zg-column>
                                    <zg-column 
                                        index='i'  
                                        type='text' 
                                        header='Descripcion'
                                    ></zg-column>
                                    <zg-column 
                                        index='j'  
                                        type='text' 
                                        header='Tipo'
                                    ></zg-column>
                                    <zg-column 
                                        index='k'  
                                        type='text'
                                        header='Subtipo'
                                    ></zg-column>
                                    <zg-column 
                                        index='l'  
                                        type='text'
                                        header='Serie'
                                    ></zg-column>
                                    <zg-column 
                                        index='m'  
                                        type='text'
                                        header='Marca'
                                    ></zg-column>
                                    <zg-column 
                                        index='n'  
                                        type='text'
                                        header='Modelo'
                                    ></zg-column>
                                    <zg-column 
                                        index='o'  
                                        type='text'
                                        header='Ubicacion'
                                    ></zg-column>
                                    
                                </zg-colgroup>
                            </zing-grid> 
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
             clv.push( val['h'] );
         }        

         $.ajax({
             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
             type:"POST",
             url: "{{ route('excelEquiposServicios') }}",
            xhrFields: { responseType: 'blob' },
             data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                 let blob = new Blob([response]);
                 let link = document.createElement('a');
                 link.href = window.URL.createObjectURL(blob);
                 link.download = "reporteEquiposServicios.xlsx";
                link.click();

             },
         });

     } );

    // window.onload = function() { //quitar para ver los datos filtrados por la fecha

        document.querySelector('#tabla').data = @json($consulta);
       
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