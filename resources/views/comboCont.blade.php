<option value="">Seleccione contacto</option>
@foreach ($contactos as $c)
    <option value='{{ $c->idContactoSuc }}'>{{ $c->contacto }}</option>
@endforeach
