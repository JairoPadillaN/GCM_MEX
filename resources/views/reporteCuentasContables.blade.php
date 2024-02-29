@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#cuentas").chosen();

    $("#agrega").click(function() {
        //  alert ("hola");
        $("#filtroCuentasContables").load('{{url('reporteCuentasContablesAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#limpiar").click(function() {
        location.reload();
    });

    $("input[name=nombreEmpresa]").change(function () {	 
        if ($('#nombreEmpresa').is(':checked'))
         {
            // alert($(this).val());
            $("#comboCuentas").load('{{url('cuentasgcm')}}' + '?nombreEmpresa=' + this.value);
         }  
         else if($('#nombreEmpresa1').is(':checked'))
         {
            $("#comboCuentas").load('{{url('cuentascym')}}' + '?nombreEmpresa=' + this.value);
            // alert($(this).val());
         } 
         else if($('#nombreEmpresa2').is(':checked'))
         {
            $("#comboCuentas").load('{{url('cuentassurja')}}' + '?nombreEmpresa=' + this.value);
            // alert($(this).val());
         } 
 
		 });
});
</script>


    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Registro de cuentas contables</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                 <form action="">
                    <div class="col-sm-2"> Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-sm-2">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>

                    <div class="col-sm-2">
                         Empresa:
                         <br>
                        <div>
                            GCM <input id="nombreEmpresa" type="radio" name="nombreEmpresa" value="gcm"/>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            C&M <input id="nombreEmpresa1" type="radio" name="nombreEmpresa" value="cym" />
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            SURJA <input id="nombreEmpresa2" type="radio" name="nombreEmpresa" value="surja" />
                        </div>
                    </div>



                        <div class="col-lg-3" id="comboCuentas">
                            
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
                <div id='filtroCuentasContables'>
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
                                        header='Folio '
                                    ></zg-column>
                                    <zg-column 
                                        index='m'  
                                        type='text' 
                                        header='Empresa'
                                    ></zg-column>
                                    <zg-column 
                                        index='b'  
                                        type='text' 
                                        header='Fecha'
                                        filter='disabled'
                                    ></zg-column>
                                     <zg-column 
                                        index='c'  
                                        type='text'
                                        header='Cuenta'
                                    ></zg-column>
                                   <zg-column 
                                        index='d'  
                                        type='text' 
                                        header='Tipo'
                                    ></zg-column>
                                    <zg-column 
                                        index='e'  
                                        type='text' 
                                        header='Beneficiario'
                                    ></zg-column>
                                    <zg-column 
                                        index='f'  
                                        type='text' 
                                        header='Forma de pago'
                                    ></zg-column>
                                    <zg-column 
                                        index='l'  
                                        type='text' 
                                        header='Divisa'
                                    ></zg-column>
                                    <zg-column 
                                        index='g'  
                                        filter='disabled'
                                        header='Importe'
                                    ></zg-column>
                                    <zg-column 
                                        index='h'  
                                        type='text' 
                                        filter='disabled'
                                        header='IVA'
                                    ></zg-column>
                                    <zg-column 
                                        index='i'  
                                        type='text' 
                                        filter='disabled'
                                        header='ISR'
                                    ></zg-column>
                                    <zg-column
                                        index='j'
                                        type='text'
                                        filter='disabled'
                                        header='Total'
                                        foot-cell='TOTAL'
                                    ></zg-column>
                                    <zg-column
                                        index='k'
                                        type='currency'
                                        type-currency='MXN'
                                        header='Total MXN'
                                        foot-cell='total'
                                        filter='disabled'
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

function total(columnData) {
    var total = 0;

    for (const value of columnData) {
        total += parseFloat(value);  
    }
    totalFormato=(new Intl.NumberFormat("de-DE",{style: "currency", currency: "MXN"}).format(total));

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
            url: "{{ route('excelCuentasContables') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporteCuentas.xlsx";
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