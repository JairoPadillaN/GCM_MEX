
<meta name="csrf-token" content="{{ csrf_token() }}">
                <div id='filtroEquipos'>
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
                            index='id'
                            type='text'
                            header='Clave'
                            filter='disabled'
                            hidden='true'
                        ></zg-column>
                        <zg-column 
                            index='a'  
                            type='image' 
                            height='130' 
                            width='130' 
                            filter='disabled'
                            header='Foto principal'
                        ></zg-column>
                        <zg-column 
                            index='b'  
                            type='image' 
                            height='130' 
                            width='130' 
                            filter='disabled'
                            header="Foto vista-frontal"
                        ></zg-column>
                        <zg-column 
                            index='c'  
                            type='image' 
                            height='130' 
                            width='130' 
                            filter='disabled'
                            header='Foto placa 1'
                        ></zg-column>
                        <zg-column 
                            index='d'  
                            type='text'
                            header='Folio de recepción'
                        ></zg-column>
                        <zg-column 
                            index='e'  
                            type='text' 
                            header='GCM ID'
                        ></zg-column>
                        <zg-column 
                            index='f'  
                            type='text'
                            header='Fecha de entrada'
                        ></zg-column>
                        <zg-column 
                            index='g'  
                            type='text' 
                            header='Cliente - Sucursal'
                        ></zg-column>
                        <zg-column 
                            index='p'  
                            type='text' 
                            header='Quien entrego'
                        ></zg-column>
                        <zg-column 
                            index='i'  
                            type='text' 
                            header='Equipo'
                        ></zg-column>
                        <zg-column 
                            index='j'  
                            type='text' 
                            header='Marca - Modelo'
                        ></zg-column>
                        <zg-column 
                            index='k'  
                            type='image' 
                            height='80' 
                            width='80'
                            filter='disabled'
                            header='Importancia'
                        ></zg-column>
                        <zg-column 
                            index='l'  
                            type='text' 
                            header='Estatus Reparación'
                        ></zg-column>
                        <zg-column 
                            index='m'  
                            type='text' 
                            header='Estatus Entrega'
                        ></zg-column>
                        <zg-column 
                            index='n'  
                            type='text' 
                            header='Piezas divididas'
                        ></zg-column>
                        <zg-column 
                            index='o'  
                            type='text' 
                            header='Opciones'
                        ></zg-column>
                    </zg-colgroup>
                   </zing-grid> 
                
                   
                    </div>
                    
               
                <div id="list" class="table-responsive"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fullscreen-modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
        <div class="modal-body">
            <div align="center"><img src='' id='img' class='img-fluid' width="1200" height="1200"></div>
        </div>
        <div align="center">
            <button type='button' class='btn btn-default btn-lg' id='closeModal'>
                <span aria-hidden='true'>Cerrar</span>
            </button>
        </div>
	  </div>
	</div>

<script src='https://cdn.zinggrid.com/zinggrid.min.js' defer>
</script>
<script>
$( document ).ready( () => {


    $("#getData").on('click', () => {

        let clv = [];

        const zgRef = document.querySelector( 'zing-grid' );

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
            url: "{{ route('excelEquiposEmpresa') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporte_por_empresa.xlsx";
                link.click();

	        },
        });

    } );


        document.querySelector('zing-grid').data = @json($consulta);
    
        const zgRef = document.querySelector('zing-grid');
            
            zgRef.addEventListener( 'record:click' , ( event ) => {

                let { type, value, fieldIndex } = event.detail.ZGData.cell;
                
                if( fieldIndex === 'a' && type === 'image' ||  fieldIndex === 'b' && type === 'image'||  fieldIndex === 'c' && type === 'image' )
                {

                    $( '#img' ).attr( 'src', value ); 

                    $( '#modal' ).modal( 'show' );

                }

            });

            $('#closeModal').on( 'click', () => {

                $( '#modal' ).modal( 'hide' );                

            } );

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