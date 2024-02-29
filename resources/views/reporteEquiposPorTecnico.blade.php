@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    //$("#cuentas").chosen();

    $("#agrega").click(function() {
        //  alert ("hola");
        $("#filtroEquiposPorTecnico").load('{{url('reporteEquiposPorTecnicoAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#limpiar").click(function() {
        location.reload();
    });
});
</script>


    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Equipos por Tecnico</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                 <form action="">
                    <div class="col-lg-3"> Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>


                        <br>
                    <!-- <div class="row"> -->
                        <div class="col-lg-3">
                        <button type="button" class="btn  btn-default" name="agrega" id="agrega">Aplicar filtro</button>
                        <button type="button" class="btn  btn-default" name="agrega" id="limpiar">Limpiar filtro</button>
                        </div>
                    </div>
                </form>  
                <br>
                <div id='filtroEquiposPorTecnico'>
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
                                        index='a'  
                                        type='text' 
                                        header='GCMID'
                                    ></zg-column>
                                    <zg-column 
                                        index='b'  
                                        type='text' 
                                        header='Parte GCMID'
                                    ></zg-column>
                                     <zg-column 
                                        index='c'  
                                        type='text'
                                        header='Nombre de Parte'
                                    ></zg-column>
                                   <zg-column 
                                        index='d'  
                                        type='text' 
                                        header='Fecha Registro'
                                    ></zg-column>
                                    <zg-column 
                                        index='e'  
                                        type='text' 
                                        header='Fecha salida'
                                    ></zg-column>
                                    <zg-column 
                                        index='f'  
                                        type='text' 
                                        header='Sucursal'
                                    ></zg-column>
                                    <zg-column 
                                        index='g'  
                                        type='text' 
                                        header='Razon Social'
                                    ></zg-column>
                                    <zg-column 
                                        index='h'  
                                        type='text'
                                        header='Marca'
                                    ></zg-column>
                                    <zg-column 
                                        index='i'  
                                        type='text' 
                                        header='Modelo'
                                    ></zg-column>
                                    <zg-column 
                                        index='j'  
                                        type='text' 
                                        header='Serie'
                                    ></zg-column>
                                    <zg-column
                                        index='k'  
                                        type='text' 
                                        header='Ultima Cotizacion'
                                    ></zg-column>
                                    <zg-column
                                        index='l'  
                                        type='text' 
                                        header='Num.Cotizacion'
                                    ></zg-column>
                                    <zg-column
                                        index='m'  
                                        type='text' 
                                        header='Servicio Ult.Cotizacion'
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
             url: "{{ route('excelEquiposPorTecnico') }}",
             xhrFields: { responseType: 'blob' },
             data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
             success: function( response ) {

                let blob = new Blob([response]);
                 let link = document.createElement('a');
                 link.href = window.URL.createObjectURL(blob);
                 link.download = "reporteEquiposPorTecnico.xlsx";
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

@stop