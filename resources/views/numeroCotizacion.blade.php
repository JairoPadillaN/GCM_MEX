@if($activarEditar == 'no')
    {{Form::text('numeroCotizacionVista',$cadenaNumeroCotizacion,['class' => 'form-control rounded-0', 'readonly','id'=>'numCot'])}}
    {{Form::hidden('numeroCotizacion',$cadenaNumeroCotizacionOriginal,['class' => 'form-control rounded-0', 'readonly'])}}
    {{Form::hidden('numConsecutivo',$numConsecutivo,['class' => 'form-control rounded-0', 'readonly'])}}
@else
    {{Form::text('numeroCotizacionVista',$cadenaNumeroCotizacion,['class' => 'form-control rounded-0', 'readonly','id'=>'numCot'])}}
    {{Form::hidden('numeroCotizacion',$cadenaNumeroCotizacionOriginal,['class' => 'form-control rounded-0', 'readonly'])}}
@endif
