<option value="">Seleccionar parte</option>
    @foreach($partes as $p)
            <option value='{{$p->idPartesVenta}}'>{{$p->codigo}} {{$p->nombreRefaccion}}</option>
    @endforeach