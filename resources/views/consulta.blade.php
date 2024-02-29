
<table class="egt">

  <tr>

    <td>Numero Fac</td>
    <td>Usur</td>
 

  </tr>
@foreach($consulta as $v)
  <tr>
                    <td>{{$v->numeroFactura}}</td>
                    <td>{{$v->idu}}</td>

  </tr>
@endforeach
</table>