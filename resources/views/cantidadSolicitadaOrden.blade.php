
@if($cuantasPiezas==0)
    {{Form::text('cantidadSolicitada', $consulta,['class'=>'form-control','id'=>'cantidadSolicitada','readonly'])}}
@else
    {{Form::text('cantidadSolicitada', $consulta->piezas,['class'=>'form-control','id'=>'cantidadSolicitada','readonly'])}}
@endif
