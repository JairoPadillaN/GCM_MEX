<!-- aqui se pasa a fomato numerico ejemplo 5,400.50 pero asi no hace el calculo por la coma "," 
solo es para mostrar en la vista al usuario
-->
<?php $montoServicioVista = number_format ($montoServicio,2)?>
{{Form::text('montoServicioVista',$montoServicioVista,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'','onblur'=>'calcularFinanciamiento()','readonly'])}}
<!-- Aqui esta el valor numerico que si hace el calculo oculto porque no tiene formato numerico
para que haga los calculo correctamente -->
{{Form::hidden('montoServicio',$montoServicio,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'montoServicio','onblur'=>'calcularFinanciamiento()','readonly'])}}