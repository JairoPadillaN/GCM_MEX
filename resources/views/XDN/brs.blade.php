@extends('principal')
@section('contenido')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Recepción de entradas al taller</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3"> Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">Cliente:
                        <select name='idc' id='idc' class="form-control rounded-0">
                            <option value="">Seleccione un cliente</option>
                            <option value='Todos'>Todos</option>
                            @foreach($clientes as $c) <option value='{{$c->idc}}'>
                            {{$c->razonSocial}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-3">Estatus:
                        <select name="estatus" class="form-control" id='status'>
                            <option value="" selected>Seleccione un estatus</option>
                            <option value="Todos">Todos</option>
                            <option value="En revisión">En revisión</option>
                            <option value="Reparación interna">Reparación interna</option>
                            <option value="Reparación externa">Reparación externa</option>
                            <option value="Reparado">Reparado</option>
                            <option value="Entregado">Entregado</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-6">
                    <button type="button" class="btn  btn-default" name="agrega" id="agrega">Aplicar filtro</button>
                    <button type="button" class="btn  btn-default" name="agrega" id="limpiar">Limpiar filtro</button>
                        <a href="{{asset('altaEquipos')}}">
                            <button type="button" class="btn btn-primary ">Agregar nuevo equipo
                            </button>
                        </a>
                    </div>
                    @if(Session::get('sesiontipo')=="Administrador")
                        <div class="col-lg-6" align="right">
                            <b>Mostrar solo:</b>&nbsp;&nbsp;&nbsp;
                            GCM <input type="radio" name="empresa" id="GCM" value="GCM">&nbsp;&nbsp;&nbsp;
                            CYM <input type="radio" name="empresa" id="CYM" value="CYM">&nbsp;&nbsp;&nbsp;
                            GCM y CYM <input type="radio" name="empresa" id="GCMyCYM" value="GCMyCYM">                                                            
                        </div>                
                    @endif
                </div>
                <br>
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
                    >
                    <zg-caption>
                        <button id='getData' class='btn btn-sm btn-success'>Obtener data</button>
                    </zg-caption>
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
                            height='100' 
                            width='100' 
                            filter='disabled'
                            header='Foto principal'
                        ></zg-column>
                        <zg-column 
                            index='b'  
                            type='image' 
                            height='100' 
                            width='100' 
                            filter='disabled'
                            header="Foto vista-frontal"
                        ></zg-column>
                        <zg-column 
                            index='c'  
                            type='image' 
                            height='100' 
                            width='100' 
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
                            height='50' 
                            width='50'
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
            url: "{{ route('excel') }}",
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

    window.onload = function() {

        document.querySelector('zing-grid').data = @json($consulta);
    
    }
} );
</script>
@endsection
