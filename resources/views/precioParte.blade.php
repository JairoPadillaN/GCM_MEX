<b>*Monto de venta:</b>

@if($presentacion == "Servicio")
    {{Form::text('precioVenta',$precioParte,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'precioV','onblur'=>'calcularFinanciamiento()','onclick'=>'calcularFinanciamiento()',])}}
@else
    <!-- aqui se pasa a fomato numerico ejemplo 5,400.50 pero asi no hace el calculo por la coma "," 
    solo es para mostrar en la vista al usuario
    -->
    <?php $precioPartesVista = number_format ($precioParte,2)?>
    {{Form::text('precioParavista',$precioPartesVista,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'precioVista','onblur'=>'calcularFinanciamiento()','readonly','onclick'=>'calcularFinanciamiento()',])}}
    <!-- Aqui esta el valor numerico que si hace el calculo oculto porque no tiene formato numerico
    para que haga los calculo correctamente -->
    {{Form::hidden('precioVenta',$precioParte,['class' => 'form-control rounded-0', 'placeholder' => 'Ejemplo: 20390','id'=>'precioV','onblur'=>'calcularFinanciamiento()','readonly','onclick'=>'calcularFinanciamiento()',])}}
@endif