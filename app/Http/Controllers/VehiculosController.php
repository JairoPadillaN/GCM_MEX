<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\vehiculos;
use App\marcas;
use Session;

class VehiculosController extends Controller
{
  public function AltaVehiculos(){
    $sname = Session::get('sesionname');
        $sidu = Session::get('sesionidu');
        $stipo = Session::get('sesiontipo');

        if($sname == '' or $sidu =='' or $stipo=='')
        {
            Session::flash('error', 'Es necesario logearse antes de continuar');
            return redirect()->route('login');
        }
        else
        {
          $consulta=vehiculos::orderby('idVehiculo','desc')
            ->take(1)
            ->get();
          $marca = marcas::orderby('nombreMarca','asc')->get();
          return view ('altaVehiculos')
            ->with('marca',$marca);
          }
}

public function GuardarVehiculos(Request $request)
{
        $nombreVehiculo = $request-> nombreVehiculo;
        $tipoVehiculo = $request-> tipoVehiculo;
        $kmActual= $request-> kmActual;
        $serial = $request-> serial;
        $placas = $request-> placas;
        $toneladas = $request-> toneladas;
        $motor = $request-> motor;
        $transmision = $request-> transmision;
        $empresaAseguradora = $request-> empresaAseguradora;
        $numPoliza = $request-> numPoliza;
        $descripcionPoliza = $request-> descripcionPoliza;
        $idMarca = $request-> idMarca;



        $this->validate ($request,[
        'nombreVehiculo'=>['required'],
        'kmActual'=>['regex:/^[0-9]*$/'],
        'idMarca'=>['required'],
        'tipoVehiculo'=>['required'],
        'serial'=>['required'],
        'placas'=>['required'],
        'toneladas'=>['required'],
        'motor'=>['required'],
        'transmision'=>['required'],
        'empresaAseguradora'=>['regex:/^[A-Z,][a-z, A-Z,,0-9]*$/'],
        'numPoliza'=>['required'],
        'descripcionPoliza'=>['required'],
        ]);

        $vehiculo = new vehiculos;
        $vehiculo-> nombreVehiculo = $request-> nombreVehiculo;
        $vehiculo-> tipoVehiculo=$request-> tipoVehiculo;
        $vehiculo-> kmActual=$request-> kmActual;
        $vehiculo-> serial =$request-> serial;
        $vehiculo-> placas =$request-> placas;
        $vehiculo-> toneladas =$request-> toneladas;
        $vehiculo-> motor =$request-> motor;
        $vehiculo-> transmision =$request-> transmision;
        $vehiculo-> empresaAseguradora =$request-> empresaAseguradora;
        $vehiculo-> numPoliza =$request-> numPoliza;
        $vehiculo-> descripcionPoliza =$request-> descripcionPoliza;
        $vehiculo-> idMarca= $request-> idMarca;
        $vehiculo-> activo="Si";
        $vehiculo->save();

          $proceso ="Alta de Vehículos";
          $mensaje="Registro guardado correctamente";
          return view('mensajeVehiculos')
          ->with('proceso',$proceso)
          ->with('mensaje',$mensaje);
        }

        public function ReporteVehiculos(){
            $sname = Session::get('sesionname');
            $sidu = Session::get('sesionidu');
            $stipo = Session::get('sesiontipo');

            if($sname == '' or $sidu =='' or $stipo=='')
            {
                Session::flash('error', 'Es necesario logearse antes de continuar');
                return redirect()->route('login');
            }
            else
            {
            $consulta = \DB::select("SELECT v.idVehiculo,v.nombreVehiculo,v.tipoVehiculo,v.kmActual,v.serial,v.placas,
          	v.toneladas,v.motor,v.transmision,v.empresaAseguradora,v.numPoliza,v.descripcionPoliza,nombreMarca.nombreMarca AS nombreMarca,v.activo FROM vehiculos AS v
            INNER JOIN marcas AS nombreMarca ON v.idMarca=nombreMarca.idMarca");
            return view ('reporteVehiculos')
            ->with('consulta',$consulta);
          }
        }

        public function modificarVehiculos($idVehiculo){
          $consulta = vehiculos:: Where('idVehiculo', '=', $idVehiculo)
      					->get();

      		$marcaSel= marcas::where('idMarca',"=",$consulta[0]->idMarca)
      					->get();
      		$nomMarca =$marcaSel[0]->nombreMarca;
      		$marca = marcas::where ('idMarca','!=',$consulta[0]->idMarca)
      					->get();

      			return view ('editarVehiculos')
      			->with('consulta',$consulta[0])
      			->with('marca',$marca)
      			->with('idmarcasel',$consulta[0]->idMarca)
      			->with('nomMarca',$nomMarca);
          }

          public function editarVehiculos(Request $request)
          {
                  $idVehiculo=$request-> idVehiculo;
                  $nombreVehiculo =$request-> nombreVehiculo;
                  $tipoVehiculo=$request-> tipoVehiculo;
                  $kmActual=$request-> kmActual;
                  $serial =$request-> serial;
                  $placas =$request-> placas;
                  $toneladas =$request-> toneladas;
                  $motor =$request-> motor;
                  $transmision =$request-> transmision;
                  $empresaAseguradora =$request-> empresaAseguradora;
                  $numPoliza =$request-> numPoliza;
                  $descripcionPoliza =$request-> descripcionPoliza;
                  $idMarca =$request-> idMarca;



                  $this->validate ($request,[
                  'nombreVehiculo'=>['regex:/^[A-Z][a-z, A-Z, ,á,é,í,ó,ú,ñ]*$/'],
                  'kmActual'=>['regex:/^[0-9]*$/'],
                  'idMarca'=>['required'],
                  'tipoVehiculo'=>['required'],
                  'serial'=>['regex:/^[A-Z,a-z, A-Z,0-9]*$/'],
                  'placas'=>['required'],
                  'toneladas'=>['required'],
                  'motor'=>['required'],
                  'transmision'=>['regex:/^[A-Z,][a-z, A-Z,,0-9]*$/'],
                  'empresaAseguradora'=>['regex:/^[A-Z,][a-z, A-Z,,0-9]*$/'],
                  'numPoliza'=>['required'],
                  'descripcionPoliza'=>['required'],
                  ]);

                  $vehiculo = vehiculos::find($idVehiculo);
                  $vehiculo-> idVehiculo=$request-> idVehiculo;
                  $vehiculo-> nombreVehiculo = $request-> nombreVehiculo;
                  $vehiculo-> tipoVehiculo=$request-> tipoVehiculo;
                  $vehiculo-> kmActual=$request-> kmActual;
                  $vehiculo-> serial =$request-> serial;
                  $vehiculo-> placas =$request-> placas;
                  $vehiculo-> toneladas =$request-> toneladas;
                  $vehiculo-> motor =$request-> motor;
                  $vehiculo-> transmision =$request-> transmision;
                  $vehiculo-> empresaAseguradora =$request-> empresaAseguradora;
                  $vehiculo-> numPoliza =$request-> numPoliza;
                  $vehiculo-> descripcionPoliza =$request-> descripcionPoliza;
                  $vehiculo-> idMarca= $request-> idMarca;
                  $vehiculo->save();

                    $proceso ="Modificación de Vehículos";
                    $mensaje="Registro modificado correctamente";
                    return view('mensajeVehiculos')
                    ->with('proceso',$proceso)
                    ->with('mensaje',$mensaje);
                  }


          public function eliminarVehiculos ($idVehiculo){/*Rerecibe este parametro y lo guarda en esa variable*/
                   $vehiculo = \DB::UPDATE("update vehiculos
                   set activo ='No' where idVehiculo=$idVehiculo");

                 $proceso ="Eliminación de vehículos";
                 $mensaje="El vehículo ha sido desactivado correctamente";
                 return view('mensajeVehiculos')
                    ->with('proceso',$proceso)
                    ->with('mensaje',$mensaje);
          }

          public function restaurarVehiculos ($idVehiculo){ //restarura el valos de NO a SI en el campo activo
                 $vehiculo = \DB::UPDATE("update vehiculos
                 set activo ='Si' where idVehiculo=$idVehiculo");

                 $proceso ="Restauración de vehículos";
                 $mensaje="El vehículo ha sido activado correctamente";
                 return view('mensajeVehiculos')
                      ->with('proceso',$proceso)
                      ->with('mensaje',$mensaje);
          }

}
