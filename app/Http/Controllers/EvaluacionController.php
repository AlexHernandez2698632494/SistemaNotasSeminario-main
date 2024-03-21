<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluacionController extends Controller
{
    public function formulario(int $idGrupo){
        if(session()->has('docente')){
            $id = $idGrupo;
            return view('evaluacion.formulario', compact('id'));
        }else{
            return view('layout.403');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $idGrupo)
    {
        if(session()->has('docente')){
            $evaluaciones = DB::table('evaluacion')
                                ->where('evaluacion.idGrupo','=',$idGrupo)
                                ->get();
            $porcentajeAsignado = 0;
            foreach ($evaluaciones as $evaluacion){
                $porcentajeAsignado = $porcentajeAsignado + $evaluacion->porcentaje;
            }
            $porcentajePorAsignar = 100 - $porcentajeAsignado;
            $validator = Validator::make($request->all(), [
                'nombre' => ['required'],            
                'porcentaje' => ['required','numeric'],                                      
            ]);

            $validator->after(function($validator) use($request, $porcentajePorAsignar){
                if($request->input('porcentaje') > $porcentajePorAsignar){
                    $validator->errors()->add('errorPorcentaje','El porcentaje no puede ser mayor que el porcentaje por asignar');
                }
            });    
        
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();
            try{

                $nombre = $request->input('nombre');
                $porcentaje = $request->input('porcentaje');
                $descripcion = $request->input('descripcion')??'No se ha añadido descripción';
                $id = $idGrupo;         

                $evaluacion = new Evaluacion();

                $evaluacion->nombreEvaluacion = $nombre;
                $evaluacion->porcentaje = $porcentaje;
                $evaluacion->idGrupo = $id;
                $evaluacion->descripcion = $descripcion;

                if($evaluacion->save()){
                    DB::commit();
                    return to_route('teacherSite.gestionEvaluaciones',$idGrupo)->with('exitoAgregar','La evaluación ha sido registrada correctamente');
                }else{
                    DB::rollBack();
                    return to_route('teacherSite.gestionEvaluaciones',$idGrupo)->with('errorAgregar','Ha ocurrido un error al registrar la información');
                }                            

            }catch(Exception $e){
                DB::rollBack();
                return to_route('teacherSite.gestionEvaluaciones',$idGrupo)->with('errorRegistro','Ha ocurrido un error al registrar la información');
            }
        }else{
            return view('layout.403');
        }
    }

    public function getEvaluacionInfo(string $id)
	{
        if(session()->has('docente')){
            $evaluacion = DB::table('evaluacion')->where('evaluacion.idEvaluacion','=',$id)->get();
		    return $evaluacion[0];
        }else{
            return view('layout.403');
        }		
	}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if(session()->has('docente')){
            $id = $request->input('idEvaluacion');
            $evaluacion = DB::table('evaluacion')->where('evaluacion.idEvaluacion','=',$id)->get();
            $evaluaciones = DB::table('evaluacion')
                                    ->where('evaluacion.idGrupo','=',$evaluacion[0]->idGrupo)
                                    ->get();
            $porcentajeAsignado = 0;
            foreach ($evaluaciones as $eva){
                if($eva->idEvaluacion != $id){
                    $porcentajeAsignado = $porcentajeAsignado + $eva->porcentaje;
                }
            }
            $porcentajePorAsignar = 100 - $porcentajeAsignado;
            $validator = Validator::make($request->all(), [
                'nombre' => ['required'],            
                'porcentaje' => ['required'],            
                'descripcion' => ['required'],          
            ]);

            $validator->after(function($validator) use($request, $porcentajePorAsignar){
                if($request->input('porcentaje') > $porcentajePorAsignar){
                    $validator->errors()->add('errorPorcentaje','El porcentaje no puede ser mayor que el porcentaje por asignar');
                }
            });    
        
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            try{
                $nombre = $request->input('nombre');
                $porcentaje = $request->input('porcentaje');
                $descripcion = $request->input('descripcion');
                
                $affected = DB::table('evaluacion')
                                ->where('evaluacion.idEvaluacion','=',$id)
                                ->update(['nombreEvaluacion' => $nombre, 'porcentaje' => $porcentaje, 'descripcion' => $descripcion]);
                return to_route('teacherSite.gestionEvaluaciones',$evaluacion[0]->idGrupo)->with('exitoActualizar','Se ha actualizado correctamente la evaluación');
            }catch(Exception $e){
                return to_route('teacherSite.gestionEvaluaciones',$evaluacion[0]->idGrupo)->with('errorActualizar','Ha ocurrido un error al actualizar información de la evaluación');
            }
        }else{
            return view('layout.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(session()->has('docente')){
            $id = $request->input('idEvaluacionEliminar');
            $evaluacion = DB::table('evaluacion')->where('evaluacion.idEvaluacion','=',$id)->get();
            try{
                DB::table('evaluacion')->where('evaluacion.idEvaluacion','=',$id)->delete();
                return to_route('teacherSite.gestionEvaluaciones',$evaluacion[0]->idGrupo)->with('exitoEliminar','Se ha eliminado correctamente la evaluación');
            }catch(Exception $e){
                return to_route('teacherSite.gestionEvaluaciones',$evaluacion[0]->idGrupo)->with('errorEliminar','Ha ocurrido un error al eliminar la evaluación');
            }
        }else{
            return view('layout.403');
        }
    }
}