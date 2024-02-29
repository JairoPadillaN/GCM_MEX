<option value="">Seleccionar cuenta</option>
    @foreach($consultaCuentas as $ce)
        <option value='{{$ce->idCuenta}}'>{{$ce->numeroCu}} - {{$ce->nombreCuenta}}</option>
    @endforeach