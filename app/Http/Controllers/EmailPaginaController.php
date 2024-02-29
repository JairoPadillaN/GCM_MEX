<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class EmailPaginaController extends Controller
{
    public function correo(Request $req){
        $name=$req['name'];
        $para='contactanos@germancontrolmotion.com';
        $email=$req['email'];
        $subject=$req['subject'];
        $message=$req['message'];
        $datos = array('destinatario'=>$email,'asunto'=>$subject, 'mensaje'=>$message);

        Mail::send('paginaweb.email', $datos, function($msj)

            use($email,$subject,$para){
                $msj->from("contactanos@germancontrolmotion.com","German Control Motion");
                $msj->subject($subject);
                $msj->to( $email);
            });
            
            return redirect('contactanos')->with('Correcto', 'Se ha enviado su correo ');
    }
}
// Mail::send('correoArcProv', $datos, function($msj) use($subject,$correoProveedor){ //VISTA, DATOS QUE ENVIA, ASUNTO Y A QUE CORREO SE ENVIA
//     $msj->from("informacion@germancontrolmotion.com","German Control Motion"); //REMITENTE
//     $msj->subject($subject); //SE ENVIA EL ASUNTO
//     $msj->to($correoProveedor); //A QUIEN SE ENVIA(CORREO)
// });