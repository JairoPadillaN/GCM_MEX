<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\marcaRefaccion;
use Session;

class MarcasRefaccionController extends Controller
{
    // 
    public function AltaMarcasRefaccion(){
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
            $cuantosHay=0;
        return view ("altaMarcasRefaccion") 
        ->with('cuantosHay',$cuantosHay);
        }
    }

    public function guardarMarcasRefaccion(Request $request){
        $marcaRefaccion=$request->marcaRefaccion;
        $codigoMarca=$request->codigoMarca;
        $MarcaRFQ=$request->MarcaRFQ;
        $existencia = \DB::select("SELECT codigoMarca FROM marcasRefaccion WHERE codigoMarca=?",[$codigoMarca]);
        $cuantosHay = count($existencia);
        
        //En esta parte van las reglas de validacion del campo codigo RFQ
        $reglas = [
            'MarcaRFQ' => 'required|min:3',
        ];
     /* ↓ En estas dos lineas de codigo se crean la reglas de valiadacion y los mensajes de error ↑ */
        $mensajes = [
            'MarcaRFQ.required' => 'El campo Codigo RFQ es obligatorio',
            'MarcaRFQ.min' => 'El campo Codigo RFQ debe tener al menos 3 caracteres',
        ];
         //Se llama al validador y se comprueba si se cumplen las reglas en un ciclo if
        $validator = Validator::make($request->all(), $reglas, $mensajes);
    
        if ($validator->fails()) {
            return redirect('altaMarcasRefaccion')
                ->withErrors($validator)
                ->withInput();
        }
  
        $marca = new marcaRefaccion;
        $marca->marcaRefaccion = $request->marcaRefaccion;
        $marca->codigoMarca = $request->codigoMarca;
        $marca->MarcaRFQ = $request -> MarcaRFQ;
        $marca->activo="Si";
        $marca->save();

        $validator = Validator::make($request->all(), $reglas, $mensajes);
    
        

        $proceso='Alta de marcas refacción';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeMarcaRefaccion')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
        
        }
        public function reporteMarcasRefaccion(){
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
                $consulta = \DB::select("SELECT * FROM marcasRefaccion order by marcaRefaccion asc");
                return view('reporteMarcasRefaccion')
                ->with('consulta', $consulta);
            }
        }

        public function modificarMarcaRefaccion($idMarcaRefa){
            $consulta = \DB::select("SELECT * FROM marcasRefaccion WHERE idMarcaRefa=?",[$idMarcaRefa]);
            return view ('editarMarcasRefaccion')
            ->with('consulta', $consulta[0]);

        }

        public function editarMarcaRefaccion(Request $request){
        $idMarcaRefa=$request->idMarcaRefa;
        $marcaRefaccion=$request->marcaRefaccion;
        $codigoMarca=$request->codigoMarca;
        $MarcaRFQ=$request->MarcaRFQ;

        //En esta parte van las reglas de validacion del campo codigo RFQ
        $reglas = [
            'MarcaRFQ' => 'required|min:3',
        ];
        /* ↓ En estas dos lineas de codigo se crean la reglas de valiadacion y los mensajes de error ↑ */
        $mensajes = [
            'MarcaRFQ.required' => 'El campo Codigo RFQ es obligatorio',
            'MarcaRFQ.min' => 'El campo Codigo RFQ debe tener al menos 3 caracteres',
        ];

        //Se llama al validador y se comprueba si se cumplen las reglas en un ciclo if
        $validator = Validator::make($request->all(), $reglas, $mensajes);
    
        if ($validator->fails()) {
            return redirect('altaMarcasRefaccion')
                ->withErrors($validator)
                ->withInput();
        }
       $this->validate ($request,[
            'marcaRefaccion'=>['required'],
            'codigoMarca'=>['required'],
            ]);
        
        
        $marca = marcaRefaccion::find($idMarcaRefa);
        $marca->marcaRefaccion = $request->marcaRefaccion;
        $marca->codigoMarca = $request->codigoMarca;
        $marca->MarcaRFQ = $request -> MarcaRFQ; 
        $marca->activo="Si";
        $marca->save();

       
        $proceso='Modificación de marcas refacción';
        $mensaje="Registro guardado correctamente";
        return view ('mensajeMarcaRefaccion')
        ->with('proceso',$proceso)
        ->with('mensaje',$mensaje);
        // return $request;
        
        }

        public function eliminarMarcaRefaccion ($idMarcaRefa){/*Rerecibe este parametro y lo guarda en esa variable*/
            $entradas = \DB::UPDATE("update marcasRefaccion
            set activo ='No' where idMarcaRefa=$idMarcaRefa");
    
        $proceso ="Eliminación de marca refacción";
        $mensaje="La marca ha sido desactivada correctamente";
        return view('mensajeMarcaRefaccion')
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);
    }
        public function restaurarMarcaRefaccion ($idMarcaRefa){/*Rerecibe este parametro y lo guarda en esa variable*/
            $entradas = \DB::UPDATE("update marcasRefaccion
            set activo ='Si' where idMarcaRefa=$idMarcaRefa");
    
        $proceso ="Restauración de marca refacción";
        $mensaje="La marca ha sido activada correctamente";
        return view('mensajeMarcaRefaccion')
            ->with('proceso',$proceso)
            ->with('mensaje',$mensaje);
    }
}
