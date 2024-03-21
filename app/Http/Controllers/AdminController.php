<?php

namespace App\Http\Controllers;

use App\Models\Administradores;
use App\Models\Usuarios;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function create(){
        if (session()->has('administrador')){
        return view('admin.add');
        } else{
            return view('layout.403');
        }
    }

    public function store(Request $request){
        if(session()->has('administrador')){
            $request->validate([
                'nombreAdministrador' => ['required','max:255','string'],
                'apellidoAdministrador' => ['required','max:255','string'],
                'duiAdministrador' => ['required', 'regex:/^[0-9]{8}-[0-9]{1}$/', 'unique:administradores,duiAdministrador'],
                'telefono' => ['required', 'regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', 'unique:administradores,telefonoAdministrador'],
                'correoAdministrador' => ['required', 'email', 'unique:administradores,correoAdministrador'],
                'usuarioAdministrador' => ['required','max:500'],
                'passwordAdministrador' => ['required', 'min:8']
            ]);
    
            try{
                date_default_timezone_set('America/El_Salvador');
                DB::beginTransaction();
                $name = $request->input('nombreAdministrador');
                $lastName = $request->input('apellidoAdministrador');
                $dui = $request->input('duiAdministrador');
                $phone = $request->input('telefono');
                $email = $request->input('correoAdministrador')??'No se ha añadido un correo';
                $user = $request->input('usuarioAdministrador');
                $password = $request->input('passwordAdministrador');
                
                
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
                        return to_route('admin.create')->with('exitoAgregar','Administrador registrado correctamente');
                    }else{
                        DB::rollBack();
                        return redirect()->back()->with('errorAgregar','Ha ocurrido un error al registrar administrador');
                    }
                }else{
                    DB::rollBack();
                    return redirect()->back()->with('errorAgregar','Ha ocurrido un error al registrar administrador');
                }
            }catch(Exception $e){
                DB::rollBack();
                return redirect()->back()->with('errorAgregar','Ha ocurrido un error al registrar administrador'.$e->getMessage());
            }
        } else{
            return view('layout.403');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(session()->has('administrador')){
        $administradores = DB::table('administradores')
                        ->where('administradores.estadoEliminacion','=',1)->get();
        return view('admin.index',compact('administradores'));
        } else{
            return view('layout.403');
        }
    }

    public function indexE()
    {
        if(session()->has('administrador')){
        $administradores = DB::table('administradores')
                        ->where('administradores.estadoEliminacion','=',0)->get();
        return view('admin.indexEliminados',compact('administradores'));
        } else{
            return view('layout.403');
        }
    }

    public function getAdmin(string $id)
    {
        if(session()->has('administrador')){
        $admin = DB::table('administradores')->where('administradores.idAdministrador','=',$id)->get();
        return $admin[0];
        } else{
            return view('layout.403');
        }
    }

    public function update(Request $request){
        if(session()->has('administrador')){
        $id=$request->input('idAdministrador');
        $request->validate([
            'nombreAdministrador' => ['required','max:255','string'],
            'apellidoAdministrador' => ['required','max:255','string'],
            'duiAdministrador' => ['required', 'regex:/^[0-9]{8}-[0-9]{1}$/'],
            'telefono' => ['required', 'regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/'],
            'correoAdministrador' => ['required', 'email'],
        ]);

        try{
            $name = $request->input('nombreAdministrador');
            $lastName = $request->input('apellidoAdministrador');
            $dui = $request->input('duiAdministrador');
            $phone = $request->input('telefono');
            $email = $request->input('correoAdministrador');
            
            $affected = DB::table('administradores')
                            ->where('administradores.idAdministrador','=',$id)
                            ->update(['nombreAdministrador' => $name, 'apellidoAdministrador' => $lastName, 'duiAdministrador' => $dui, 'telefonoAdministrador' => $phone, 'correoAdministrador' => $email]);
            return redirect()->route('admin.index')->with('exitoActualizar','Se ha actualizado correctamente la información del administrador');
        }catch(Exception $e){
            return to_route('admin.index')->with('errorActualizar','Ha ocurrido un error al actualizar información del administrador');
        }}else{
            return view('layout.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(session()->has('administrador')){
        $deleteAdministradorId = $request->input('idAdministradorEliminar');

        $affected = DB::table('administradores')
                        ->where('idAdministrador','=',$deleteAdministradorId)
                        ->update(['estadoEliminacion' => 0]);
        
        if($affected == 1){
            return to_route('admin.index')->with('exitoEliminar','El administrador se ha eliminado correctamente');
        }else{
            return to_route('admin.index')->with('errorEliminar','Error al eliminar el administrador');            
        }} else{
            return view('layout.403');
        }
    }

    public function restore(Request $request)
    {
        if(session()->has('administrador')){
        $restoreAdministradorId = $request->input('idAdministradorRestaurar');

        $affected = DB::table('administradores')
                        ->where('idAdministrador','=',$restoreAdministradorId)
                        ->update(['estadoEliminacion' => 1]);
        
        if($affected == 1){
            return to_route('admin.indexE')->with('exitoRestaurar','El administrador se ha restaurado correctamente');
        }else{
            return to_route('admin.indexE')->with('errorRestaurar','Error al restaurar el administrador');            
        }}else{
            return view('layout.403');
        }
    }
}