<div>Seleccione cuenta: </div>
       <select name="cuenta[]" data-placeholder="Seleccione" id="cuentas" class="form-control rounded-0" multiple>
         <option value="">Seleccione</option>
         <!-- <option value="Todos">Todos</option> -->
         @foreach($cuentas as $cuentas)
         @if($cuentas->activo=="Si")
         <option value='{{$cuentas->idCuenta}}'>{{$cuentas->numeroCu}} - {{$cuentas->nombreCuenta}}</option>
         @endif
         @endforeach
         
      </select>
      
<script type="text/javascript">
$(document).ready(function() {
    $("#cuentas").chosen();

    
});
</script>

