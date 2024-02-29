<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<div class="form-group row">
    <div class="col-sm-2">Paquete:</div>
    <div class="col-sm-6">
        <select name="idPaquete" id="idPaquete" class="form-control">
            <option value="">Seleccione un paquete</option>
            @foreach($paquetes as $p)
            @if($p->activo=="Si")
            <option value='{{$p->idPaquete}}'>{{$p->nombrePaquete}}</option>
            @endif
            @endforeach
        </select>
    </div>
    <input type="text" name="pac" id="paquete" >
    <div class="col-sm-2">
        <button type="button" class="btn btn-success btn-default" id="agregarPaquete">
            <span class="glyphicon glyphicon-plus-sign"></span> Agregar paquete
        </button>
    </div>
</div>
<script>

$("#idPaquete").click(function() {
        
        let paquete = $("#idPaquete").val();
        $("#paquete").load('{{url('detallePac')}}'+ '?r=' + Date.now() + '&idPaquete=' + this.options[this.selectedIndex].value) ;
        console.log(paquete);
   
    });

$("#agregarPaquete").click(function() {
        // var paquete = [];
        
        var detallePac = $("#paquete").val();

        // paquete.push(detallePac);
        console.log(detallePac);
        
    });

    //     let detallePac = $("#paquete").val();
       
       //    for(i=0;i<detallePac;i++){
       //     console.log(detallePac[i])
   
       //    }
</script>
