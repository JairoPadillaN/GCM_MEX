<select name="idServicioTaller" id="comboServicio" class="form-control">
    <option value="">Seleccione un servicio:</option>
    @foreach($comboServiciosTaller as $comboServicios)
       @if($comboServicios->activo=="Si")
       <option value='{{$comboServicios->idServicioTaller}}'>{{$comboServicios->nombreServTaller}}</option>
       @endif
    @endforeach
</select>

<script>
$("#comboServicio").change(function() {
    $("#preciosS").load('{{url('preciosServicios')}}' + '?r=' + Date.now() + '&idServicioTaller=' + this.options[this.selectedIndex].value) ;
});
</script>