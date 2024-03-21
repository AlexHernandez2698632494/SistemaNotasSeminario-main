<?php

namespace App\Http\Controllers;

use App\Models\Administradores;
use App\Models\Usuarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Solicitudes;

class LoginController extends Controller
{
    /**
     * Función para realizar el login de la aplicación
     */
    public function login(Request $request){
        $request->validate([
            'user' => ['required'],
            'password' => ['required']
        ]);
        
        try{
            $userName = $request->input("user");
            $pass = $request->input("password");

            $user = DB::table('usuario')
                            ->where('usuario','=',$userName)
                            ->get();
                    

            if(!empty($user[0])){                                 
                if($user[0]->password == Hash('SHA256',$pass)){
                    $accessLevel = $user[0]->nivel;

                    if($accessLevel == 1){ //Docente
                        $teacherDui = $user[0]->idUsuario;
                        $teacherStatus = DB::table('docente')
                                                ->where('duiDocente','=',$teacherDui)                                                
                                                ->get();                        
                        if($teacherStatus[0]->estadoEliminacion == 1){                            
                            $request->session()->put('user',$user);
                            session()->put('docente',$teacherStatus);
                            return to_route('teacherSite.index');
                        }else{
                            return redirect()->back()->with('error','Acceso denegado');
                        }
                    }else if($accessLevel == 2){//Estudiante
                        $studentDui = $user[0]->idUsuario;
                        $studentStatus = DB::table('estudiante')
                                                ->where('duiEstudiante','=',$studentDui)                                                
                                                ->get();
                        if($studentStatus[0]->estadoEliminacion == 1){
                            session()->put('estudiante',$studentStatus);
                            return to_route('studentSite.index');
                        }else{
                            return redirect()->back()->with('error','Acceso denegado');
                        }
                    }else if($accessLevel == 0){//Administrador
                        $adminDui = $user[0]->idUsuario;
                        $adminStatus = DB::table('administradores')
                                                ->where('duiAdministrador','=',$adminDui)
                                                ->get();
                        if($adminStatus[0]->estadoEliminacion == 1){
                            $request->session()->put('user',$user); //Creando variable de sesion con la información del usuario
                            session()->put('administrador',$adminStatus);
                            return to_route('teachers.index');
                        }else{
                            return redirect()->back()->with('error','Acceso denegado');
                        }
                    }else{
                        return redirect()->back()->with('error','Acceso denegado');
                    }

                }else{
                    return redirect()->back()->with('error','Usuario y/o contraseña incorrectos');
                }
            } else{
                return redirect()->back()->with('error','Usuario y/o contraseña incorrectos');
            }
        }catch(Exception $e){
            return redirect()->back()->with('error','Error al iniciar sesión');
        }            

        
    }

    /**
     * Función para mostrar vista de inicio de la aplicación
     */
    public function welcome()
    {

        if(session()->has('user')){
            session()->forget('user');
        }     
        
        if(session()->has('teacherGroups')){
            session()->forget('teacherGroups');
        }

        if(session()->has('studentGroups')){
            session()->forget('studentGroups');
        }

        if(session()->has('studentGroupsArray')){
            session()->forget('studentGroupsArray');
        }

        if(session()->has('teacherGroupsArray')){
            session()->forget('teacherGroupsArray');
        }

        if(session()->has('docente')){
            session()->forget('docente');
        }else if(session()->has('estudiante')){
            session()->forget('estudiante');
        }else if(session()->has('administrador')){
            session()->forget('administrador');            
        }

        $admins = DB::table('usuario')
                        ->where('nivel','=',0)
                        ->count();
        
        if($admins > 0){ 
            return view('welcome');
        }else{
            return view('firstAdmin');
        }
                
    }

    /**
     * Función para mostrar vista de login
     */
    public function showLogin()
    {
        if(session()->has('user')){
            session()->forget('user');
        }            

        if(session()->has('teacherGroups')){
            session()->forget('teacherGroups');
        }
        
        if(session()->has('studentGroups')){
            session()->forget('studentGroups');
        }

        if(session()->has('studentGroupsArray')){
            session()->forget('studentGroupsArray');
        }

        if(session()->has('teacherGroupsArray')){
            session()->forget('teacherGroupsArray');
        }

        if(session()->has('docente')){
            session()->forget('docente');
        }else if(session()->has('estudiante')){
            session()->forget('estudiante');
        }else if(session()->has('administrador')){
            session()->forget('administrador');            
        }

        return view('welcome');
    }

    /**
     * Función para registrar primer administrador
     */
    public function storeFirstAdmin(Request $request)
    {
        $request->validate([
            'nombre' => ['required','max:255','string'],
            'apellido' => ['required','max:255','string'],
            'dui' => ['required', 'regex:/^[0-9]{8}-[0-9]{1}$/', 'unique:administradores,duiAdministrador'],
            'telefono' => ['required', 'regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', 'unique:administradores,telefonoAdministrador'],
            'correo' => ['required', 'email', 'unique:administradores,correoAdministrador'],
            'usuario' => ['required','max:500'],
            'contraseña' => ['required', 'min:8']
        ]);
        
        try{
            date_default_timezone_set('America/El_Salvador');
            DB::beginTransaction();
            $name = $request->input('nombre');
            $lastName = $request->input('apellido');
            $dui = $request->input('dui');
            $phone = $request->input('telefono');
            $email = $request->input('correo')??'No se ha añadido un correo';
            $user = $request->input('usuario');
            $password = $request->input('contraseña');
            
            
            $admin = new Administradores();

            $admin->nombreAdministrador = $name;
            $admin->apellidoAdministrador = $lastName;
            $admin->duiAdministrador = $dui;
            $admin->telefonoAdministrador = $phone;
            $admin->correoAdministrador = $email;
            $admin->fechaIngreso = date('Y-m-d');
            $admin->estadoEliminacion = 1;

            if($admin->save()){
                $newUser = new Usuarios();
                
                $newUser->idUsuario = $dui;
                $newUser->usuario = $user;
                $newUser->password = Hash('SHA256',$password);
                $newUser->nivel = 0;

                if($newUser->save()){
                    DB::commit();
                    return to_route('showLogin')->with('exitoRegistoAdmin','Administrador registrado correctamente');
                }else{
                    DB::rollBack();
                    return redirect()->back()->with('error','Ha ocurrido un error al registrar administrador');
                }
            }else{
                DB::rollBack();
                return redirect()->back()->with('error','Ha ocurrido un error al registrar administrador');
            }
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error','Ha ocurrido un error al registrar administrador'.$e->getMessage());
        } 
            


        

    }

    /**
     * Función para cerrar sesión
     */
    public function logout()
    {
        if(session()->has('user')){
            session()->forget('user');
        }  
        
        if(session()->has('teacherGroups')){
            session()->forget('teacherGroups');
        }  
        
        if(session()->has('studentGroups')){
            session()->forget('studentGroups');
        }

        if(session()->has('studentGroupsArray')){
            session()->forget('studentGroupsArray');
        }

        if(session()->has('teacherGroupsArray')){
            session()->forget('teacherGroupsArray');
        }

        if(session()->has('docente')){
            session()->forget('docente');
        }else if(session()->has('estudiante')){
            session()->forget('estudiante');
        }else if(session()->has('administrador')){
            session()->forget('administrador');            
        }
        
        return to_route('showLogin');
    }

    public function recuperarView(){
        return view('recuperarContra');
    }

    public function recuperarContra(Request $request){
        $request->validate([
            'user' => ['required']
        ]);
        $usuario = $request->input("user");
        $user = DB::table('usuario')->where('usuario','=',$usuario)->get();
        if(!empty($user[0])){
            try{
                date_default_timezone_set('America/El_Salvador');
                DB::beginTransaction();
                $solicitud = new Solicitudes();
                $solicitud->idUsuario = $user[0]->idUsuario;
                $solicitud->estado = 1;
                $solicitud->fecha = date('Y-m-d');

                if($solicitud->save()){
                    DB::commit();
                    return to_route('showLogin')->with('exitoSolicitud','La solicitud para recuperar contraseña ha sido realizada. Deberá contactarse con un administrador del sitio para terminar el proceso');
                }
                else{
                    DB::rollBack();
                    return redirect()->back()->with('errorSolicitud','Error al realizar solicitud');
                }
            }
            catch(Exception $e){
                return redirect()->back()->with('error','Error al realizar solicitud');
            }
        } else{
            return redirect()->back()->with('error','Usuario incorrecto');
        }
        
    }
}
