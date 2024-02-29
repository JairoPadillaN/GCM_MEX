@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        //  alert ("hola");
        $("#filtroEquipos").load('{{url('reporteEquiposAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#GCM").click(function() {
        $("#filtroEquipos").load('{{url('reporteGCM')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#CYM").click(function() {
        $("#filtroEquipos").load('{{url('reporteCYM')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#GCMyCYM").click(function() {
        $("#filtroEquipos").load('{{url('reporteGCMyCYM')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#limpiar").click(function() {
        location.reload();
    });
});
</script>


    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Registro de equipos</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                <form action="">
                    <div class="col-lg-3"> Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div><br>
                    <div class="row">
                        <div class="col-lg-6">
                        <button type="button" class="btn  btn-default" name="agrega" id="agrega">Aplicar filtro</button>
                        <button type="button" class="btn  btn-default" name="agrega" id="limpiar">Limpiar filtro</button>
                            <a href="{{asset('altaEquipos')}}">
                                <button type="button" class="btn btn-primary ">Agregar nuevo equipo
                                </button>
                            </a>
                        </div>
                    </div>
                </form>  
                <br>
                <div id='filtroEquipos'>
                    <div>
                        <ul class="nav nav-tabs">
                            <li class="active" id='tab1' ><a href="#1" data-toggle="tab"><label for="">En almacen</label></a></li>
                            <li class="" id='tab2' ><a href="#2" data-toggle="tab"><label for="">Cotizado en sitio</label></a></li>
                            <li class="" id='tab2' ><a href="#3" data-toggle="tab"><label for="">Servicios cancelados</label></a></li>
                        </ul>

                        <div class="tab-content">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
    
                        <div class="tab-pane active" id="1">             
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
                                        index='id'
                                        type='text'
                                        header='Clave'
                                        filter='disabled'
                                        hidden='true'
                                    ></zg-column>
                                    <zg-column 
                                        index='o'  
                                        type='text' 
                                        header='Opciones'
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
                                        index='h'  
                                        type='text' 
                                        header='Datos para certificado'
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
                                        index='q'  
                                        type='text' 
                                        header='Ubicación'
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
                                </zg-colgroup>
                            </zing-grid> 
                            </div>                                        
                        </div>

                        <div class="tab-pane fade" id="2">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            
                            <div class="table-responsive">
                            <button id='getDataCotizado' class='btn btn-sm btn-success'>Generar Excel</button>
                            <zing-grid 
                                    id='tablaCotizado'
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
                                        index='o'  
                                        type='text' 
                                        header='Opciones'
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
                                        index='h'  
                                        type='text' 
                                        header='Datos para certificado'
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
                                        index='q'  
                                        type='text' 
                                        header='Ubicación'
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
                                </zg-colgroup>
                            </zing-grid> 
                            </div> 
                        </div>  
                        <div class="tab-pane fade" id="3">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            
                            <div class="table-responsive">
                            <button id='getDataSeguimiento' class='btn btn-sm btn-success'>Generar Excel</button>
                            <zing-grid 
                                    id='tablaSeguimiento'
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
                                        index='o'  
                                        type='text' 
                                        header='Opciones'
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
                                        index='h'  
                                        type='text' 
                                        header='Datos para certificado'
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
                                        index='q'  
                                        type='text' 
                                        header='Ubicación'
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
            url: "{{ route('excelEquipos') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporte.xlsx";
                link.click();

	        },
        });

    } );
    $("#getDataCotizado").on('click', () => {

        let clv = [];

        const zgRef = document.querySelector( '#tablaCotizado' );

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
            url: "{{ route('excelEquipos') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporte.xlsx";
                link.click();

	        },
        });

    } );
    $("#getDataSeguimiento").on('click', () => {

        let clv = [];

        const zgRef = document.querySelector( '#tablaSeguimiento' );

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
            url: "{{ route('excelEquipos') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporte.xlsx";
                link.click();

	        },
        });

    } );

    // window.onload = function() {

        document.querySelector('#tabla').data = @json($consulta);
        document.querySelector('#tablaCotizado').data = @json($consulta2);
        document.querySelector('#tablaSeguimiento').data = @json($consulta3);
    
    // }

    const zgRef1 = document.querySelector('#tabla');
    const zgRef2 = document.querySelector('#tablaCotizado');
    const zgRef3 = document.querySelector('#tablaSeguimiento');
            
            zgRef1.addEventListener( 'record:click' , ( event ) => {

                let { type, value, fieldIndex } = event.detail.ZGData.cell;
                
                if( fieldIndex === 'a' && type === 'image' ||  fieldIndex === 'b' && type === 'image'||  fieldIndex === 'c' && type === 'image' )
                {

                    $( '#img' ).attr( 'src', value ); 

                    $( '#modal' ).modal( 'show' );

                }

            });
            zgRef2.addEventListener( 'record:click' , ( event ) => {

                let { type, value, fieldIndex } = event.detail.ZGData.cell;
                
                if( fieldIndex === 'a' && type === 'image' ||  fieldIndex === 'b' && type === 'image'||  fieldIndex === 'c' && type === 'image' )
                {

                    $( '#img' ).attr( 'src', value ); 

                    $( '#modal' ).modal( 'show' );

                }

            });
            zgRef3.addEventListener( 'record:click' , ( event ) => {

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

@stop