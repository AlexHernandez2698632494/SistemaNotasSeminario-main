<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Estudiantes;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class StudentSiteController extends Controller
{
    public function index()
    {
        if(session()->has('estudiante')){ //Verificando que exista un sesion de estudiante iniciada
            $studentInfo = session()->get('estudiante');
            $studentId = $studentInfo[0]->idEstudiante;

            $period = DB::table('ciclo')
                            ->where('estado','=',1)
                            ->get();
            
            if(!empty($period[0])){                
                $periodId = $period[0]->idCiclo;
                $studentGroups = DB::table('detalleestudiantegrupo')
                                        ->join('grupo','detalleestudiantegrupo.idGrupo','=','grupo.idGrupo')
                                        ->join('materia','grupo.idMateria','=','materia.idMateria')
                                        ->join('ciclo','grupo.idCiclo','=','ciclo.idCiclo')
                                        ->where('grupo.idCiclo','=',$periodId)
                                        ->where('detalleestudiantegrupo.idEstudiante','=',$studentId)
                                        ->get(); 
                                        
                $studentGroupArray = [];
                foreach($studentGroups as $group)
                {
                    $studentGroupArray[] = $group->idGrupo;
                }

                session()->put('studentGroups',$studentGroups);
                session()->put('studentGroupsArray',$studentGroupArray);
                return view('studentSite.index',compact('period','studentGroups'));
            }else{
                return view('studentSite.index');
            }                          
        }else{
            return to_route('showLogin');
        }              
    }
    
    /**
     * Función para mostrar notas del estudiante
     */
    public function showSubjectGrade(string $groupId)
    {   
        if(session()->has('estudiante')){  
            $information = DB::table('grupo')
                                ->join('materia','grupo.idMateria','=','materia.idMateria')
                                ->where('grupo.idGrupo','=',$groupId)
                                ->get();          
            if($information->count() > 0){
                if(in_array($groupId,session()->get('studentGroupsArray'))){   
                    
                    $studentId = session()->get('estudiante')[0]->idEstudiante;
                    $grades = DB::table('nota')
                                    ->join('evaluacion','nota.idEvaluacion','=','evaluacion.idEvaluacion')
                                    ->where('nota.idGrupo','=',$groupId)
                                    ->where('idEstudiante','=',$studentId)
                                    ->get();      
                    $average = DB::table('historialestudiante')
                                    ->where('idMateria','=',$information[0]->idMateria)
                                    ->where('idEstudiante','=',$studentId)
                                    ->get();
                    
                    if($average->count() > 0){
                        if($average[0]->convocatoria == 'Extraordinaria'){
                            $extraEvaluationInfo = DB::table('estudiantesreprobados')
                                                        ->join('actividadesextraordinarias','estudiantesreprobados.idDetalle','=','actividadesextraordinarias.idDetalle')
                                                        ->where('estudiantesreprobados.idEstudiante','=',$studentId)
                                                        ->get();
                            return view('studentSite.subjectGrade',compact('information','grades','average','extraEvaluationInfo'));                        
                        }else{
                            // return $grades;
                            return view('studentSite.subjectGrade',compact('information','grades','average'));
                        }  
                    }else{
                        return view('studentSite.subjectGrade',compact('information','grades','average'));
                    }                                          
                }else{
                    return view('layout.403');
                }                
            }else{
                return view('layout.404');
            }                    
        }else{
            return view('layout.403');
        }
    }

     /**
     * Función para mostrar expediente
     */
    public function showRecord()
    {
        if(session()->has('estudiante')){
            $studentInfo = session()->get('estudiante');
        
            if($studentInfo != null){
                $studentId = $studentInfo[0]->idEstudiante;
                $student = Estudiantes::find($studentId);

                $subjectStudied = DB::table('historialestudiante')
                                        ->where('idEstudiante',$studentId)
                                        ->select(DB::raw('count(idMateria) as cantidadMaterias'))
                                        ->get();
                $average = DB::table('historialestudiante')
                                    ->where('idEstudiante',$studentId)
                                    ->select(DB::raw('ROUND(AVG(promedio), 2) as promedio'))
                                    ->get();
                $subjectsQuantity = DB::table('materia')
                                        ->select(DB::raw('count(idMateria) as materias'))
                                        ->where('estadoEliminacion','=',1)
                                        ->get();
                $percent = round(($subjectStudied[0]->cantidadMaterias * 100) / $subjectsQuantity[0]->materias,2);

                $subjects = DB::table('historialestudiante')
                                    ->join('materia','historialestudiante.idMateria','=','materia.idMateria')
                                    ->join('etapa','materia.idEtapa','=','etapa.idEtapa')
                                    ->select('materia.nivel','historialestudiante.convocatoria','historialestudiante.anio','etapa.nombreEtapa', 'materia.cuatrimestre', 'materia.nombreMateria','promedio')
                                    ->where('idEstudiante', $studentId)
                                    ->get();

                
                return view('studentSite.record',compact('student','subjectStudied','average','percent','subjects'));
        }else{
            return view('layout.404');
        }}else{
            return view('layout.403');
        }
            
        
    }

    public function miPerfil(){
        if(session()->has('estudiante')){
            $id= session()->get('estudiante');
            $informacionEstudiante = DB::table('estudiante')->where('idEstudiante','=',$id[0]->idEstudiante)->get();
            return view('studentSite.miPerfil', compact('informacionEstudiante'));
        } else{
            return view('layout.403');
        }
    }

    public function updateInfor(Request $request){
        if(session()->has('estudiante')){
            $studentId = $request->input('idEstudianteActualizar');
            $validator = Validator::make($request->all(),[
                'telefonoCasa' => ['regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', Rule::unique('estudiante', 'numeroTelefonicoCasa')->ignore($studentId, 'idEstudiante')],
                'numeroCelular' => ['regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', Rule::unique('estudiante', 'numeroMovil')->ignore($studentId, 'idEstudiante')],            
                'correoSeminarista' => ['required', 'email', Rule::unique('estudiante', 'correoEstudiante')->ignore($studentId, 'idEstudiante')],
            ]);   
    
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            $studentEmail = $request->input('correoSeminarista');
            $studenMobile = $request->input('numeroCelular');
            $studentPhone = $request->input('telefonoCasa');
            $studentAdress = $request->input('direccionResidencia')?? 'No se ha añadido información';
            
            try{
                $affected = DB::table('estudiante')
                ->where('idEstudiante', '=', $studentId)
                ->update([
                    'correoEstudiante' => $studentEmail,
                    'numeroMovil' => $studenMobile,
                    'numeroTelefonicoCasa' => $studentPhone,
                    'direccion' => $studentAdress,
                ]);        
    
                
                return to_route('studentSite.miPerfil')->with('exitoModificar','La información del seminarista ha sido actualizada correctamente');            
                
            }catch(QueryException $e){
                return to_route('studentSite.miPerfil')->with('errorModificar','Ha ocurrido un error al modificar el registro');
            }}else{
                return view('layout.403');
            }}
}
