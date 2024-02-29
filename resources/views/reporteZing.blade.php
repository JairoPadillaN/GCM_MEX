<script src='https://cdn.zinggrid.com/zinggrid.min.js' defer></script>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
@extends('principal')
@section('contenido')
<div class='container'>
    <div class='row'>
        <div class='col-12'>
        <zing-grid
                id = 'tabla'
                filter
                search
                pager
                page-size=10
                page-size-options='1,5,10,15,20'
                theme='android'
                context-menu
            >
            <zg-colgroup>
                <zg-column 
                    index='idEquipos'  
                    type='number' 
                    header='Clave'
                ></zg-column>
               <zg-column 
                    index='folioRecepcion'  
                    type='text' 
                    header='Nombre Refaccion'
                ></zg-column>
                <zg-column 
                    index='canti'  
                    type='text' 
                    header='Banda'
                ></zg-column>
                <zg-column 
                    index='vistaSuperior'  
                    type='image' 
                    height='80' 
                    width='80' 
                    header='Foto'
                    filter='disabled'
                ></zg-column> 
                <zg-column 
                    index='vistaFrente'  
                    type='image' 
                    height='80' 
                    width='80' 
                    header='Foto'
                    filter='disabled'
                ></zg-column> 
                <zg-column 
                    index='placa_1'  
                    type='image' 
                    height='80' 
                    width='80' 
                    header='Foto'
                    filter='disabled'
                ></zg-column> 
            </zg-colgroup> 
            </zing-grid>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        const json = @json($data); // Convertir consulta a json javascript

        // console.log(json);
    
    const tabla = $("#tabla");

    const files = "{{ asset('archivos') }}";
    

    function list()
    {
        let content = [];

        tabla.removeAttr('data');
        tabla.attr('data');

        for( let i = 0; i < json.length; i++ )
        {
            content.push({
                            'idEquipos':json[i]['idEquipos'],
                            'folioRecepcion' : json[i]['folioRecepcion'],
                            'canti' : json[i]['canti'],
                            'vistaSuperior' : files+'/'+json[i]['vistaSuperior'],
                            'vistaFrente' : files+'/'+json[i]['vistaFrente'],
                            'placa_1' : files+'/'+json[i]['placa_1'],
                    });
                    
        }

        tabla.attr( 'data', JSON.stringify( content ) );
    }

    list();

    });
</script>
@stop
