@extends('principal')
@section('contenido')
<div class="col-xs-12">
<div class="panel panel-default" style="margin-top:-55px">
    <div class="panel-heading">
        <h1>Registrar Contable
        </h1>
    </div>
    <div class="panel-body" style="overflow-x: auto;">
        <form action="{{ route('guardarContable') }}" method="POST">
            @csrf
            <div class="sub-title">Mes</div>
            <div>
              <select name="mes" id="mes" class="form-control">
                <option selected>Selecciona el mes</option>
                <option value="Enero">Enero</option>
                <option value="Febrero">Febrero</option>
                <option value="Marzo">Marzo</option>
                <option value="Abril">Abril</option>
                <option value="Mayo">Mayo</option>
                <option value="Junio">Junio</option>
                <option value="Julio">Julio</option>
                <option value="Agosto">Agosto</option>
                <option value="Septiembre">Septiembre</option>
                <option value="Octubre">Octubre</option>
                <option value="Noviembre">Noviembre</option>
                <option value="Diciembre">Diciembre</option>
                <option value="Declaración anual">Declaración anual</option>
              </select>

            </div>
            <div class="sub-title">Año</div>
            <div>
              <select name="ano" id="ano" class="form-control">
                <option selected>Selecciona el Año</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
              </select>

            </div>
            <div class="sub-title">Empresa</div>
            <div>
              <select name="empresa" id="empresa" class="form-control">
                <option selected>Selecciona la empresa</option>
                <option value="GCM">GCM</option>
                <option value="CYM">CYM</option>
                <option value="SURJA">SURJA</option>
                <option value="CMIN">CMIN</option>
                <option value="PEDRO">PEDRO</option>
                <option value="YANETH">YANETH</option>
              </select>

            </div>
            <div>
                <br>
            </div>

            <div class="col-auto">
                <input type="submit" id="guardarbtn" value="Guardar Contable" class="btn btn-primary">
            </div>

          </form>
    </div>
</div>
</div>


@stop
