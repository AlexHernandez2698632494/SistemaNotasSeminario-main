<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(session()->has('administrador')){
        $estudiantes = DB::table('usuario')->join('estudiante','usuario.idUsuario','=','estudiante.duiEstudiante')
                        ->where('usuario.nivel','=',2)->get();
        $profesores = DB::table('usuario')->join('docente','usuario.idUsuario','=','docente.duiDocente')
                        ->where('usuario.nivel','=',1)->get();
        $administradores = DB::table('usuario')->join('administradores','usuario.idUsuario','=','administradores.duiAdministrador')
                        ->where('usuario.nivel','=',0)->get();
        return view('users.index',compact('estudiantes','profesores','administradores'));
        }else{
            return view('layout.403');
        }
    }

    public function getUser(string $id)
    {
        if(session()->has('administrador')){
        $user = DB::table('usuario')->where('usuario.idUsuario','=',$id)->get();
        return $user[0];
        }else{
            return view('layout.403');
        }
    }

    public function getSolicitud(string $id)
    {
        if(session()->has('administrador')){
        $solicitud = DB::table('solicitudes')->join('usuario','solicitudes.idUsuario','=','usuario.idUsuario')->where('solicitudes.idSolicitud','=',$id)->get();
        return $solicitud[0];
        } else{
            return view('layout.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(session()->has('administrador')){
        try{
            DB::table('usuario')->where('usuario.idUsuario','=',$request->input('idUsuarioEliminar'))->delete();
            return redirect()->route('users.index')->with('exitoEliminar','Se ha eliminado correctamente el usuario');
        }catch(Exception $e){
            return to_route('users.index')->with('errorEliminar','Ha ocurrido un error al eliminar el usuario');
        }}else{
            return view('layout.403');
        }
    }

    public function generatePass()
    {
        if(session()->has('administrador')){
        $permittedChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$pass = '';
		$strength = 10;

		$stringLenght = strlen($permittedChars);

		for($i = 0; $i < $strength; $i++) {
			$randomCharacter = $permittedChars[mt_rand(0, $stringLenght - 1)];
			$pass .= $randomCharacter;
		}
		return $pass;
        }else{
            return view('layout.403');
        }
    }

    public function solicitudes(Request $request){
        if(session()->has('administrador')){
        $solicitudes = DB::table('solicitudes')->join('usuario','solicitudes.idUsuario','=','usuario.idUsuario')
                            ->where('solicitudes.estado','=',1)->get();
        return view('users.solicitudes',compact('solicitudes'));
        }else{
            return view('layout.403');
        }
    }

    public function update(Request $request){
        if(session()->has('administrador')){
        $idSolicitud=$request->input('idUsuario');
        $idUsuario=DB::table('solicitudes')->where('idSolicitud','=',$idSolicitud)->get();
        $usuario=DB::table('usuario')->where('idUsuario','=',$idUsuario[0]->idUsuario)->get();

        if($usuario[0]->nivel == 1){
            $docente=DB::table('docente')->where('duiDocente','=',$usuario[0]->idUsuario)->get();
            $nombre=$docente[0]->nombreDocente.' '.$docente[0]->apellidoDocente;
        } else if($usuario[0]->nivel == 2){
            $estudiante=DB::table('estudiante')->where('duiEstudiante','=',$usuario[0]->idUsuario)->get();
            $nombre=$estudiante[0]->nombreEstudiante.' '.$estudiante[0]->apellidoEstudiante;
        } else if($usuario[0]->nivel == 0){
            $admin=DB::table('administradores')->where('duiAdministrador','=',$usuario[0]->idUsuario)->get();
            $nombre=$admin[0]->nombreAdministrador.' '.$admin[0]->apellidoAdministrador;
        }
        $userName = $usuario[0]->usuario;
        $password=$this->generatePass();
        $contra=Hash('SHA256',$password); 
 $pdfData=[
                'nombre'=>$nombre,
                'userName'=>$userName,
                'password'=>$password
            ];
            $pdf = PDF::loadView('pdf.RestorePassword', $pdfData);
            $pdf->save(public_path('pdf/newCredential.pdf'));
            try{
            DB::table('solicitudes')->where('idSolicitud','=',$idSolicitud)->update(['estado'=>0]);
            DB::table('usuario')->where('idUsuario','=',$idUsuario[0]->idUsuario)->update(['password'=>$contra]);
            /*Generar reporte con las variables $nombre para el nombre de la persona,
              $userName para el nombre de usuario y $password para la contraseña*/
             
            return to_route('users.solicitudes')->with('exitoRestablecer','La contraseña del usuario ha sido restablecida');
        }
        catch(Exception $e){
            return to_route('users.solicitudes')->with('errorRestablecer','La contraseña del usuario no ha sido restablecida');
        }}else{
            return view('layout.403');
        }

    }

    public function formContra(){
        if (session()->has('docente')){
            return view('users.cambiarContraTeacher');
        } else if (session()->has('administrador')){
            return view('users.cambiarContraAdmin');
        } else if (session()->has('estudiante')){
            return view('users.cambiarContraStudent');
        } else{
            return view('layout.403');
        }
    }

    public function cambiarContra(Request $request){
        if(session()->has('docente')){
            $teacherInfo = session()->get('docente');
            $usuarioId = $teacherInfo[0]->duiDocente;

            $user = DB::table('usuario')->where('idUsuario', '=',$usuarioId)->get();

            $passwordActual = $request->input('passwordActual');
            $passwordNueva = $request->input('passwordNueva');
            $passwordConfirmar = $request->input('passwordConfirmar');

            if($user[0]->password == Hash('SHA256',$passwordActual)){
                if($passwordNueva == $passwordConfirmar){
                    $contra=Hash('SHA256',$passwordNueva);
                    try{
                        DB::table('usuario')->where('idUsuario','=',$usuarioId)->update(['password'=>$contra]);
            
                        return to_route('users.formContra')->with('exitoCambiar','La contraseña del usuario ha sido cambiada');
                    }
                    catch(Exception $e){
                        return to_route('users.formContra')->with('errorCambiar','La contraseña del usuario no ha sido cambiada');
                    }
                }
                else{
                    return to_route('users.formContra')->with('errorCambiar','La contraseña nueva no ha sido confirmada');
                }
            }
            else{
                return to_route('users.formContra')->with('errorCambiar','Contraseña actual incorrecta');
            }
        }else if(session()->has('administrador')){
            $adminInfo = session()->get('administrador');
            $usuarioId = $adminInfo[0]->duiAdministrador;

            $user = DB::table('usuario')->where('idUsuario', '=',$usuarioId)->get();

            $passwordActual = $request->input('passwordActual');
            $passwordNueva = $request->input('passwordNueva');
            $passwordConfirmar = $request->input('passwordConfirmar');

            if($user[0]->password == Hash('SHA256',$passwordActual)){
                if($passwordNueva == $passwordConfirmar){
                    $contra=Hash('SHA256',$passwordNueva);
                    try{
                        DB::table('usuario')->where('idUsuario','=',$usuarioId)->update(['password'=>$contra]);
            
                        return to_route('users.formContra')->with('exitoCambiar','La contraseña del usuario ha sido cambiada');
                    }
                    catch(Exception $e){
                        return to_route('users.formContra')->with('errorCambiar','La contraseña del usuario no ha sido cambiada');
                    }
                }
                else{
                    return to_route('users.formContra')->with('errorCambiar','La contraseña nueva no ha sido confirmada');
                }
            }
            else{
                return to_route('users.formContra')->with('errorCambiar','Contraseña actual incorrecta');
            }
        }
        else if(session()->has('estudiante')){
            $studentInfo = session()->get('estudiante');
            $usuarioId = $studentInfo[0]->duiEstudiante;

            $user = DB::table('usuario')->where('idUsuario', '=',$usuarioId)->get();

            $passwordActual = $request->input('passwordActual');
            $passwordNueva = $request->input('passwordNueva');
            $passwordConfirmar = $request->input('passwordConfirmar');

            if($user[0]->password == Hash('SHA256',$passwordActual)){
                if($passwordNueva == $passwordConfirmar){
                    $contra=Hash('SHA256',$passwordNueva);
                    try{
                        DB::table('usuario')->where('idUsuario','=',$usuarioId)->update(['password'=>$contra]);
            
                        return to_route('users.formContra')->with('exitoCambiar','La contraseña del usuario ha sido cambiada');
                    }
                    catch(Exception $e){
                        return to_route('users.formContra')->with('errorCambiar','La contraseña del usuario no ha sido cambiada');
                    }
                }
                else{
                    return to_route('users.formContra')->with('errorCambiar','La contraseña nueva no ha sido confirmada');
                }
            }
            else{
                return to_route('users.formContra')->with('errorCambiar','Contraseña actual incorrecta');
            }
        }
        else{
            return view('layout.403');
        }

    }
}