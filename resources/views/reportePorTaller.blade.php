@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    
    $("#agrega").click(function() {
        $("#filtroTaller").load('{{url('reportePorTallerAbajo')}}' + '?' + $(this).closest('form').serialize());
    });
    $("#limpiar").click(function() {
        location.reload();
    });

    $("#idTaller").change(function() {
        $("#modelos").load('{{url('comboModelos')}}' + '?r=' + Date.now() + '&idTaller=' + this.options[this.selectedIndex].value);
    });

});
</script>


    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte por taller</h1>
            </div><br>
            <div class="panel-body">
            <form>
                <div class="row">
                    <div class="col-lg-3">Taller:
                        <select name='idTaller' id='idTaller' class="form-control rounded-0">
                            <option value="">Seleccione un taller</option>
                            @foreach($talleres as $t)
                            @if($t->activo == "Si")
                            <option value='{{$t->idTaller}}'>{{$t->nombreTaller}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3"> Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>


                    <div class="col-lg-3">Modelo:
                        <div id='combo'>
                            <select name='idEquipos' id="modelos" class="form-control">
                                <option value="">Seleccione un modelo</option>
                            </select>
                        </div>
                    </div>
                </div>
            <br>
            <div class="">
                <button type="button" class="btn  btn-default" name="agrega" id="agrega">Aplicar filtro</button>
                <button type="button" class="btn  btn-default" name="limpiar" id="limpiar">Limpiar filtro</button>
                <a href="{{asset('altaEquipos')}}">
                    <button type="button" class="btn btn-primary ">Agregar nuevo equipo
                    </button>
                </a>
            </div>
            <br><br><br>
            </form>

            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div id='filtroTaller'>
            <div class = "table-responsive">
                <button id='dataTaller' class='btn btn-sm btn-success'>Generar Excel</button>
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
                            header='Foto del equipo'
                        ></zg-column>
                        <zg-column 
                            index='b'  
                            type='image' 
                            height='130' 
                            width='130' 
                            filter='disabled'
                            header="Foto de parte"
                        ></zg-column>
                        <zg-column 
                            index='c'  
                            type='text'
                            header='GCM ID parte'
                        ></zg-column>
                        <zg-column 
                            index='d'  
                            type='text'
                            header='Marca-Modelo'
                        ></zg-column>
                        <zg-column 
                            index='e'  
                            type='text' 
                            header='Nombre de la parte'
                        ></zg-column>
                        <zg-column 
                            index='f'  
                            type='text'
                            header='Que se le realiza'
                        ></zg-column>
                        <zg-column 
                            index='g'  
                            type='text' 
                            header='Fecha de recepciòn'
                        ></zg-column>
                        <zg-column 
                            index='h'  
                            type='text' 
                            header='Fecha de entrega'
                        ></zg-column>
                        <zg-column 
                            index='i'  
                            type='text' 
                            header='Estatus'
                        ></zg-column>
                    </zg-colgroup>
                </zing-grid>                    
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

<script src='https://cdn.zinggrid.com/zinggrid.min.js' defer>
</script>
<script>
$( document ).ready( () => {


    $("#dataTaller").on('click', () => {

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
            url: "{{ route('excelTaller') }}",
            xhrFields: { responseType: 'blob' },
            data: { 'data': clv, 'user' : "{{ Session::get('sesionidu') }}" },
            success: function( response ) {

                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporte_taller.xlsx";
                link.click();

	        },
        });

    } );

    // window.onload = function() {

        document.querySelector('zing-grid').data = @json($consulta);
    
    // }

    const zgRef = document.querySelector('zing-grid');
            
            zgRef.addEventListener( 'record:click' , ( event ) => {

                let { type, value, fieldIndex } = event.detail.ZGData.cell;
                
                if( fieldIndex === 'a' && type === 'image' ||  fieldIndex === 'b' && type === 'image' )
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