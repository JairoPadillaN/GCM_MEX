@extends('principal')
@section('contenido')
<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte de Marcas</h1>
        </div><br>

        <div class="panel-body">
        <div>
            <a href="{{asset('altaMarcasRefaccion')}}"><button type="button" class="btn btn-primary ">Agregar nueva marca de refacción</button></a>
        </div><br>
        
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th width='300px;'>Código</th>
                            <th width='300px:'>Código RFQ</th> 
                            <th width='300px;'><center>Opciones</center></th>
                        </tr>
                    </thead> 
                    <tbody>  
                        @foreach($consulta as $cons)
                            <tr>
                                <td>{{$cons->marcaRefaccion}}</td>    
                                <td>{{$cons->codigoMarca}}</td>
                                <td>{{$cons->MarcaRFQ}}</td> <!--- Adicion de consulta del codigo de rfq-->   
                                <td align='center'>
                                    @if($cons->activo=='Si')
                                    <a href="{{URL::action('MarcasRefaccionController@modificarMarcaRefaccion',['idMarcaRefa'=>$cons->idMarcaRefa])}}"
                                            type="submit" class="btn btn-xs btn-info"><i
                                                class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                    <a href="{{URL::action('MarcasRefaccionController@eliminarMarcaRefaccion',['idMarcaRefa'=>$cons->idMarcaRefa])}}"
                                            type="submit" class="btn btn-xs btn-danger"><i
                                                class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>
                                    @else
                                    <a href="{{URL::action('MarcasRefaccionController@restaurarMarcaRefaccion',['idMarcaRefa'=>$cons->idMarcaRefa])}}"
                                            type="submit" class="btn btn-xs btn-warning"><i
                                                class="ace-icon fa fa-trash-o bigger-120"> Restaurar</i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop