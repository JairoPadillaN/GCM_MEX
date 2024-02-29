@extends('principal')
@section('contenido')

<script type="text/javascript">
    $(document).ready(function(){
   $(".cargar").click();
});

</script>


      <div class="header">
          <h1 class="page-header">Recepción de equipos <small>Registra un nuevo equipo </small></h1>
      </div>

    {{Form::open(['route' => 'GuardarEquipos','files'=>true])}}
    {{Form::token()}}

<div id="page-inner">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
              

                <div class="panel-heading">
                                      <div class="card-title">
                                          <div class="title">Ingrese los datos del equipo recibido</div>
                                    </div>
                </div>

<div class="panel-body">


<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="cargar" class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Datos de recepción</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Datos del equipo</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Archivos</a>
  </li>
</ul>
  
<div class="tab-content" id="pills-tabContent">

<!--Primer tab Datos de Factura --->
  <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
      <div class="form-group col-md-6">
             <div class="sub-title">Folio recepcion de equipo:</div>
                <div>
                     @if($errors->first('folioRecepcion'))
                    <i> {{ $errors->first('folioRecepcion') }}</i>
                    @endif
                  {{Form::text('folioRecepcion',old ('folioRecepcion'),['class' => 'form-control', 'readonly'])}}
                </div>

           <div class="sub-title">Empresa que da segumiento:</div>
            <div>
              GCM {{Form::radio('nombreEmpresa','GCM')}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              Freyer {{Form::radio('nombreEmpresa','Freyer')}}
            </div>   

            <div class="sub-title">Registrado por:</div>
            <div>
                <input type="text" name="usuarioViaje" class="form-control" readonly="true" value="{!! Session::get('sesionname')!!} {!! Session::get('sesionpaterno')!!} {!!
                  Session::get('sesionmaterno')!!}">
            </div>
            
            <div class="sub-title"> Fecha de recolección: </div>
              <div>
                @if($errors->first('fechaRecoleccion'))
                  <i> {{ $errors->first('fechaRecoleccion') }}</i>
                @endif
                {{Form::date('fechaRecoleccion', \Carbon\Carbon::now(),['class' => 'form-control'])}}
            </div>


            <div class="sub-title"> Fecha de registro: </div>
              <div>
                @if($errors->first('fechaRegistro'))
                  <i> {{ $errors->first('fechaRegistro') }}</i>
                @endif
                {{Form::date('fechaRegistro', \Carbon\Carbon::now(),['class' => 'form-control'])}}
            </div>

            <div class="sub-title">Selecciona cliente:</div>
                            @if($errors->first('idc'))
                            <i> {{ $errors->first('idc') }}</i>
                            @endif<div>
                                <select name='idc' id='idc' class="form-control">
                                    <option value="">Seleccionar cliente</option>
                                    @foreach($cliente as $cliente)
                                    <option value='{{$cliente->idc}}'>{{$cliente->razonSocial}}</option>
                                    @endforeach
                                </select>
      </div>
      <div class="row">     
      <div class="form-group col-md-6">
            <div class="sub-title">Selecciona sucursal:</div>
                            @if($errors->first('idSucursal'))
                            <i> {{ $errors->first('idSucursal') }}</i>
                            @endif<div>
                                <select name='idSucursal' id='idSucursal' class="form-control">
                                    <option value="">Seleccionar sucursal</option>
                                    @foreach($sucursal as $sucursal)
                                    <option value='{{$sucursal->idSucursal}}'>{{$sucursal->sucursal}}</option>
                                    @endforeach
                                </select>

            <div class="sub-title">Persona que entrega:</div>
                            <div>
                                @if($errors->first('personaEntrega'))
                                <i> {{ $errors->first('personaEntrega') }}</i>
                                @endif
                                {{Form::text('personaEntrega',old ('personaEntrega'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Luis Sánchez Júarez'])}}
                            </div>

            <div class="sub-title">Persona que recibe:</div>
                            <div>
                                @if($errors->first('personaRecibe'))
                                <i> {{ $errors->first('personaRecibe') }}</i>
                                @endif
                                {{Form::text('personaRecibe',old ('personaRecibe'),['class' => 'form-control', 'placeholder' => 'Ejemplo: Jorge Cisneros Hernández'])}}
                            </div>

            <div class="sub-title">Número de documento salida cliente:</div>
                            <div>
                                @if($errors->first('numeroDocumentoSalida'))
                                <i> {{ $errors->first('numeroDocumentoSalida') }}</i>
                                @endif
                                {{Form::text('numeroDocumentoSalida',old ('numeroDocumentoSalida'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                            </div>

            <div class="sub-title">Vale de salida cliente:</div>
                        <div>
                            @if($errors->first('archivoValeSalida'))
                            <i> {{ $errors->first('archivoValeSalida') }}</i>
                            @endif
                            {{Form::file('archivoValeSalida',['class' => 'form-control rounded-0'])}}
                        </div>

            <div class="sub-title">Documento de salida empresa:</div>
                        <div>
                            @if($errors->first('archivoDocumentoSalida'))
                            <i> {{ $errors->first('archivoDocumentoSalida') }}</i>
                            @endif
                            {{Form::file('archivoDocumentoSalida',['class' => 'form-control rounded-0'])}}
                        </div>
        </div>


  </div>

<!--Segundo tab Datos de pago --->
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
  <div class="form-group col-md-6">

          <div class="sub-title"> GCM ID:</div>
            <div>
              <input class="form-control" name="GCMid"  type="text" readonly="true" >                        
            </div>

          <div class="sub-title">Tipo:</div>
                                    @if($errors->first('idTipoEquipo'))
                                    <i> {{ $errors->first('idTipoEquipo') }}</i>
                                    @endif<div>
                                        <select name='idTipoEquipo' id='idTipoEquipo' class="form-control">
                                            <option value="">Seleccionar tipo</option>
                                            @foreach($tipoEquipo as $tipoEquipo)
                                            <option value='{{$tipoEquipo->idTipoEquipo}}'>{{$tipoEquipo->tipoEquipo}}</option>
                                            @endforeach
                                        </select>

          <div class="sub-title">Subtipo:</div>
                                    @if($errors->first('idSubtipoEquipo'))
                                    <i> {{ $errors->first('idSubtipoEquipo') }}</i>
                                    @endif<div>
                                        <select name='idSubtipoEquipo' id='idSubtipoEquipo' class="form-control">
                                            <option value="">Seleccionar tipo</option>
                                            @foreach($subtipoEquipo as $subtipoEquipo)
                                            <option value='{{$subtipoEquipo->idSubtipoEquipo}}'>{{$subtipoEquipo->subtipoEquipo}}</option>
                                            @endforeach
                                        </select>
  
          <div class="sub-title">Serie:</div>
                            <div>
                                @if($errors->first('serie'))
                                <i> {{ $errors->first('serie') }}</i>
                                @endif
                                {{Form::text('serie',old ('serie'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 00001203'])}}
                            </div>

          <div class="sub-title">Datos para certificado:</div>
                            <div>
                                {{Form::textarea('serie',old ('datosCertificado'),['class' => 'form-control'])}}
                            </div>
          
          
          <div class="sub-title">Marca:</div>
                            <div>
                                @if($errors->first('marca'))
                                <i> {{ $errors->first('marca') }}</i>
                                @endif
                                {{Form::text('marca',old ('marca'),['class' => 'form-control', 'placeholder' => 'Ejemplo: LouisV'])}}
                            </div>
          
          <div class="sub-title">Modelo:</div>
                            <div>
                                @if($errors->first('modelo'))
                                <i> {{ $errors->first('modelo') }}</i>
                                @endif
                                {{Form::text('modelo',old ('modelo'),['class' => 'form-control', 'placeholder' => 'Ejemplo: 206'])}}
                            </div>

          <div class="sub-title">Datos para certificado:</div>
                            <div>
                                {{Form::textarea('serie',old ('descripcionFalla'),['class' => 'form-control'])}}
                            </div>
    </div> 
</div>

<!--tercer tab Archivos --->
<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
    <div class="form-group col-md-6">

          <div class="sub-title">Foto vista superior</div><div>
              @if($errors->first('vistaSuperior'))
              <i> {{ $errors->first('vistaSuperior') }}</i>
              @endif
            {{Form::file('vistaSuperior')}}
          </div>
          <br>

          <div class="sub-title">Foto vista frente :</div><div>
              @if($errors->first('vistaFrente'))
              <i> {{ $errors->first('vistaFrente') }}</i>
              @endif
            {{Form::file('vistaFrente')}}
          </div>
          <br>
          
                                <div class="sub-title">Foto vista trasera:</div><div>
                                @if($errors->first('vistaTrasera'))
                                <i> {{ $errors->first('vistaTrasera') }}</i>
                                @endif
                                  {{Form::file('vistaTrasera')}}
                                </div>
                                <br>
                                <div class="sub-title">Foto lateral izquierda:</div><div>
                                @if($errors->first('lateralIzquierda'))
                                <i> {{ $errors->first('lateralIzquierda') }}</i>
                                @endif
                                  {{Form::file('lateralIzquierda')}}
                                </div>
                               <br>
                                <div class="sub-title">Foto lateral derecha:</div><div>
                                @if($errors->first('lateralDerecha'))
                                <i> {{ $errors->first('lateralDerecha') }}</i>
                                @endif
                                  {{Form::file('lateralDerecha')}}
                                </div>
                                <div class="sub-title">Foto placa 1:</div><div>
                                @if($errors->first('placa_1'))
                                <i> {{ $errors->first('placa_1') }}</i>
                                @endif
                                  {{Form::file('placa_1')}}
                                </div>
                                <div class="sub-title">Foto placa:</div><div>
                                @if($errors->first('placa_2'))
                                <i> {{ $errors->first('placa_2') }}</i>
                                @endif
                                  {{Form::file('placa_2')}}
                                </div>
                     <br><br><br><br><br>                     
    </div>



    <center>{{Form::submit('Guardar',['class' => 'btn  btn-default'])}}
    <a href="{{asset('reporteFacturas')}}"><button type="button" class="btn btn-default">Cancelar</button></a></center>
</div>


<style>
                       
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
  @stop