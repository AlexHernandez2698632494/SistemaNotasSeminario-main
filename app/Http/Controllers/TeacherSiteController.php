<?php

namespace App\Http\Controllers;

use App\Models\ActividadesExtraordinaria;
use App\Models\Ciclos;
use App\Models\EstudiantesReprobados;
use App\Models\Grupos;
use App\Models\HistorialEstudiante;
use App\Models\Notas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class TeacherSiteController extends Controller
{
    /**
     * Función para mostrar grupos del docente
     */
    public function index()
    {        
        if(session()->has('docente')){ //Verificando que exista un sesion de docente iniciada
            $teacherInfo = session()->get('docente');
            $teacherId = $teacherInfo[0]->idDocente;

            $period = DB::table('ciclo')
                            ->where('estado','=',1)
                            ->get();
            
            if(!empty($period[0])){
                $periodId = $period[0]->idCiclo;
                $teacherGroups = DB::table('grupo')
                                    ->join('materia','grupo.idMateria','=','materia.idMateria')
                                    ->where('idCiclo','=',$periodId)
                                    ->where('idDocente',$teacherId)
                                    ->get();
                $teacherGroupsArray = [];
                foreach($teacherGroups as $group)
                {
                    $teacherGroupsArray[] = $group->idGrupo;
                }
                session()->put('teacherGroups',$teacherGroups);
                session()->put('teacherGroupsArray',$teacherGroupsArray);
                return view('teacherSite.index',compact('period','teacherGroups'));
            }else{
                return view('teacherSite.index');
            }                          
        }else{
            return to_route('showLogin');
        }        
    }

    /**
     * Función para mostrar la información de un grupo
     */
    public function showGroupInformation(string $id)
    {
        if(session()->has('docente')){
            $groupInformation = DB::table('grupo')
                                    ->join('materia','grupo.idMateria','=','materia.idMateria')
                                    ->where('idGrupo','=',$id)
                                    ->select('nombreMateria','idGrupo','grupo.anio','nombreGrupo','estadoFinalizacion')
                                    ->get();
            if(!empty($groupInformation[0])){ 
                if(in_array($groupInformation[0]->idGrupo,session()->get('teacherGroupsArray'))){               
                    $studentsQuantity = DB::table('detalleestudiantegrupo')
                                    ->where('idGrupo','=',$id)
                                    ->count();
                    $evaluationQuantity = DB::table('evaluacion')
                                            ->where('idGrupo','=',$id)
                                            ->count();
                    $students = DB::table('detalleestudiantegrupo')
                                    ->join('estudiante','detalleestudiantegrupo.idEstudiante','=','estudiante.idEstudiante')
                                    ->where('idGrupo','=',$id)
                                    ->get();
                return view('teacherSite.groupInformation',compact('groupInformation','studentsQuantity','evaluationQuantity','students'));
                // return $students;
            }else{
                return view('layout.403');
            }}  else{
                return view('layout.404');                
            }          
        }else{
            return to_route('showLogin');
        }
    }

    
    /**
     * Función para mostrar la vista de gestión de evaluaciones por grupo
     */
    public function gestionEvaluaciones(string $id)
    {
        if(session()->has('docente')){
            $grupo = DB::table('grupo')->join('materia','grupo.idMateria','=','materia.idMateria')
                                        ->join('docente','grupo.idDocente','=','docente.idDocente')
                                        ->where('grupo.idGrupo','=',$id)->get();
        
            if(!empty($grupo[0])){
                if(in_array($grupo[0]->idGrupo,session()->get('teacherGroupsArray'))){               
                    $evaluaciones = DB::table('evaluacion')->where('evaluacion.idGrupo','=',$id)->get();
                    $evaluationAssigned = DB::table('nota')
                                        ->where('idGrupo','=',$id)
                                        ->select('idEvaluacion')
                                        ->get()
                                        ->toArray();
                    $cantidadEvaluaciones = 0;
                    $porcentajeAsignado = 0;
                    foreach ($evaluaciones as $evaluacion){
                        $cantidadEvaluaciones++;
                        $porcentajeAsignado = $porcentajeAsignado + $evaluacion->porcentaje;
                    }
                    $evaluationAssignedArray = [];
                    foreach($evaluationAssigned as $evaluation)
                    {
                        $evaluationAssignedArray[] = $evaluation->idEvaluacion;
                    }        
                    $porcentajePorAsignar = 100 - $porcentajeAsignado;
                    return view('evaluacion.gestionEvaluaciones', compact('grupo', 'evaluaciones', 'cantidadEvaluaciones', 'porcentajeAsignado', 'porcentajePorAsignar', 'evaluationAssignedArray'));                   
                } else{
                    return view('layout.403');
                }
            }
            else{
                return view('layout.404');                
            }
        }else{
            return view('layout.403');
        }
    }

    /**
     * Función para mostrar las evaluaciones del grupo y poder seleccionarlas para asignarles nota
     */
    public function showEvaluations(string $id)
    {
        if(session()->has('docente')){
            $evaluations = DB::table('evaluacion')
                                ->where('idGrupo','=',$id)
                                ->get();
            
            $evaluationAssigned = DB::table('nota')
                                    ->where('idGrupo','=',$id)
                                    ->select('idEvaluacion')
                                    ->get()
                                    ->toArray();
            
            $groupInformation = DB::table('grupo')
                                    ->join('materia','grupo.idMateria','=','materia.idMateria')
                                    ->where('idGrupo','=',$id)
                                    ->get();
            
            $evaluationAssignedArray = [];
            foreach($evaluationAssigned as $evaluation)
            {
                $evaluationAssignedArray[] = $evaluation->idEvaluacion;
            }       
                
            if($evaluations->count() > 0){
                return view('teacherSite.evaluations',compact('evaluations','evaluationAssignedArray','groupInformation'));
            }else{
                return view('teacherSite.evaluations',compact('evaluations','evaluationAssignedArray','groupInformation'));
            }
        }else{
            return view('layout.403');            
        }            
        
        // return $evaluations;
    }

    /**
     * Función para mostrar vista de asignación de notas de una actividad
     */
    public function showGradesAssigment(string $evaluationId)
    {                
        $evaluationInfo = DB::table('evaluacion')
                            ->join('grupo','evaluacion.idGrupo','=','grupo.idGrupo')
                            ->join('materia','grupo.idMateria','=','materia.idMateria')
                            ->where('idEvaluacion','=',$evaluationId)                        
                            ->get();        
        if($evaluationInfo->count() > 0){
            $groupId = $evaluationInfo[0]->idGrupo;
            
            $students = DB::table('detalleestudiantegrupo')
                            ->join('estudiante','detalleestudiantegrupo.idEstudiante','=','estudiante.idEstudiante')
                            ->where('idGrupo','=',$groupId)
                            ->where('estudiante.estadoEliminacion','=',1)
                            ->where('estudiante.estadoAceptacion','=',1)  
                            ->orderBy('estudiante.apellidoEstudiante','asc')                          
                            ->get();
            return view('teacherSite.gradesAssigment',compact('evaluationInfo','students'));                        
        }else{
            return view('layout.404');            
        }
        
        // return $evaluationInfo;
    }

    /**
     * Función para guardar notas
     */
    public function storeGrades(Request $request)
    {    
            if(session()->has('docente')){
                // Validando calificaciones y estudiantes
                $request->validate([
                    'calificacion.*' => ['required', 'numeric', 'min:0', 'max:10'],
                    'estudiante.*' => ['required', 'numeric'],
                    'grupo' => ['required'],
                    'evaluacion' => ['required'],
                    'porcentaje' => ['required'], 
                ]);

                DB::beginTransaction();
                try {
                    // Obteniendo información
                    $grades = $request->input('calificacion');
                    $students = $request->input('estudiante');
                    $groupId = $request->input('grupo');
                    $evaluation = $request->input('evaluacion'); 
                    $percent = $request->input('porcentaje');
                    
                    $evaluationAssigned = DB::table('nota')
                                        ->where('idGrupo','=',$groupId)
                                        ->where('idEvaluacion','=',$evaluation)
                                        ->select('idEvaluacion')
                                        ->count(); 
                                    
                    if($evaluationAssigned == 0){
                        foreach ($grades as $index => $grade) {
                            // Obteniendo id del estudiante
                            $studentId = $students[$index];

                            $porcentajeGanado = $grade * ($percent / 100);

                            $nota = new Notas([
                                'idGrupo' => $groupId,
                                'idEstudiante' => $studentId,
                                'idEvaluacion' => $evaluation,
                                'nota' => $grade,
                                'porcentajeGanado' => $porcentajeGanado,
                            ]);

                            $nota->save();
                        }

                        DB::commit();
                        return redirect()->route('teacherSite.showEvaluations', $groupId)->with('flash', ['exitoAgregar' => 'Notas asignadas correctamente']);                                 
                        
                    }else{
                        return redirect()->route('teacherSite.showEvaluations', $groupId)->with('flash', ['errorAgregar' => 'Esta actividad ya tiene calificaciones asignadas']);                                 
                    }               

                        
                    
                    

                } catch (Exception $e) {
                    
                    return redirect()->route('teacherSite.showEvaluations', $groupId)->with('flash', ['errorAgregar' => 'Ha ocurrido un error al registrar notas, pongase en contacto con el administrador'.$e->getMessage()]);
                }
            } else{
                return view('layout.403');            
            }                          

            
        }

        public function updateGradesView(string $evaluationId)
        {
            if(session()->has('docente')){
            $evaluationInfo = DB::table('evaluacion')
                                ->join('grupo','evaluacion.idGrupo','=','grupo.idGrupo')
                                ->join('materia','grupo.idMateria','=','materia.idMateria')
                                ->where('idEvaluacion','=',$evaluationId)
                                ->get();
            if($evaluationInfo->count() > 0){
                $notasInfo = DB::table('nota')
                                ->join('estudiante','nota.idEstudiante','=','estudiante.idEstudiante')
                                ->where('idEvaluacion','=',$evaluationId)->orderBy('estudiante.apellidoEstudiante','asc')
                                ->get();
                
                return view('teacherSite.updateGrades',compact('evaluationInfo','notasInfo'));                
            }else{
                return view('layout.404');                
            }}else{
                return view('layout.403');                
            }
        }

        public function getNota(string $id){
            if(session()->has('docente')){
            $notas = DB::table('nota')
                                ->join('evaluacion','nota.idEvaluacion','=','evaluacion.idEvaluacion')
                                ->join('estudiante','nota.idEstudiante','=','estudiante.idEstudiante')
                                ->where('idNota','=',$id)
                                ->get();
            return $notas[0];
            }else{
                return view('layout.403');
            }
        }

        public function updateGrade(Request $request){
            if(session()->has('docente')){
                $idNota = $request->input('idNota');
                $idEva = $request->input('idEvaluacion');
                try{
                    $nota = $request->input('nota');
                    $affected = DB::table('nota')
                                    ->where('nota.idNota','=',$idNota)
                                    ->update(['nota' => $nota]);
                    return to_route('teacherSite.updateGradesView',$idEva)->with('exitoActualizar','Se ha actualizado correctamente la nota');
                }catch(Exception $e){
                    return to_route('teacherSite.updateGradesView',$idEva)->with('errorActualizar','Ha ocurrido un error al actualizar la nota');
                }
            }else{
                return view('layout.403');
            }
        }


        /**
         * Funcion para finalizar grupos y guardar registros en tabla de historial de estudiante
         */
        public function endGroup(Request $request)
        {            
            if(session()->has('docente')){                
                try{
                    $groupId = $request->input('idGrupo');
                    date_default_timezone_set('America/El_Salvador');

                    $groupStatus = DB::table('grupo')
                                    ->where('idGrupo','=',$groupId)
                                    ->select('estadoFinalizacion')
                                    ->get();

                    if($groupStatus[0]->estadoFinalizacion == 1){
                        
                        DB::beginTransaction();
                        $evaluationsQuantity = DB::table('evaluacion')
                                                    ->where('idGrupo','=',$groupId)
                                                    ->count();
                        $evaluationAssigned = DB::table('nota')
                                                    ->where('idGrupo', '=',$groupId)
                                                    ->select(DB::raw('COUNT(DISTINCT idEvaluacion) as total'))
                                                    ->first();
                        //SELECT idEstudiante, ROUND(SUM(porcentajeGanado),2) as Promedio, materia.idMateria FROM nota, grupo, materia WHERE nota.idGrupo = grupo.idGrupo AND grupo.idMateria = materia.idMateria AND grupo.idGrupo = 1 GROUP BY(idEstudiante) 

                        if($evaluationsQuantity == $evaluationAssigned->total){
                            $studentsAverage = DB::table('nota')
                                                    ->join('grupo', 'nota.idGrupo', '=', 'grupo.idGrupo')
                                                    ->join('materia', 'grupo.idMateria', '=', 'materia.idMateria')
                                                    ->where('grupo.idGrupo', '=',$groupId)
                                                    ->groupBy('idEstudiante', 'materia.idMateria')
                                                    ->select('idEstudiante', DB::raw('ROUND(SUM(porcentajeGanado), 1) as promedio'), 'materia.idMateria')
                                                    ->get();

                            
                            foreach($studentsAverage as $student)
                            {
                                if($student->promedio >= 7) {

                                    $historialEstudiante = new HistorialEstudiante([
                                        'idEstudiante' => $student->idEstudiante,
                                        'idMateria' => $student->idMateria,
                                        'anio' => date('Y'),
                                        'promedio' => $student->promedio,
                                        'convocatoria' => 'Ordinaria'
                                    ]);

                                    $historialEstudiante->save();                            

                                }else{

                                    if(7 - $student->promedio <= 4)
                                    {
                                        $historialReprobados = new EstudiantesReprobados([
                                            'idEstudiante' => $student->idEstudiante,
                                            'idGrupo' => $groupId,
                                            'promedio' => $student->promedio,
                                            'estadoReprobado' => 1
                                        ]);

                                        $historialReprobados->save();
                                    }else{
                                        $historialReprobados = new EstudiantesReprobados([
                                            'idEstudiante' => $student->idEstudiante,
                                            'idGrupo' => $groupId,
                                            'promedio' => $student->promedio,
                                            'estadoReprobado' => 2
                                        ]);

                                        $historialReprobados->save();
                                    }
                                        
                                }
                                    
                            }

                            $affected = DB::table('grupo')
                                            ->where('idGrupo',$groupId)
                                            ->update(['estadoFinalizacion' => 0]);
                            
                            if($affected == 1){
                                DB::commit();
                                return to_route('teacherSite.groupInformation',$groupId)->with('exitoAgregarHistorial','El grupo ha sido finalizado correctamente');
                            }else{
                                DB::rollBack();
                                return to_route('teacherSite.groupInformation',$groupId)->with('errorAgregarHistorial','Ha ocurrido un error al finalizar grupo, contactese con el administrador ');
                            }                                                               
                        }else{
                            DB::rollBack();
                            return to_route('teacherSite.groupInformation',$groupId)->with('informacionAgregarHistorial','El grupo no se puede finalizar ya que existen actividades sin calificaciones asignadas');                                        
                        }
                    }else{
                        DB::rollBack();
                        return to_route('teacherSite.groupInformation',$groupId)->with('informacionAgregarHistorial','El grupo ya ha sido finalizado');                                        
                    }                       
                }catch(Exception $e){
                    DB::rollBack();
                    return to_route('teacherSite.groupInformation',$groupId)->with('errorAgregarHistorial','Ha ocurrido un error al finalizar grupo, contactese con el administrador '.$e->getMessage());
                }     
            }else{
                return view('layout.403');
            }                         
        }


        /**
         * Función para mostrar los estudiantes reprobados
         */
        public function showFailedStudents()
        {
            if(session()->has('docente')){
                $failedStudents = DB::table('estudiantesreprobados')
                                    ->join('grupo','estudiantesreprobados.idGrupo','=','grupo.idGrupo')
                                    ->join('materia','grupo.idMateria','=','materia.idMateria')
                                    ->join('estudiante','estudiantesreprobados.idEstudiante','=','estudiante.idEstudiante')
                                    ->where('grupo.idDocente','=',session()->get('docente')[0]->idDocente)
                                    ->where('estudiantesreprobados.estadoReprobado','=',1)
                                    ->get();
                return view('teacherSite.failedStudents',compact('failedStudents'));                
            }else{
                return view('layout.403');
            }                
        }

        /**
         * Función para mostrar información del estudiante reprobado
         */
        public function showFailedStudentsInfo(string $detailId)
        {     
            if(session()->has('docente')){
                // $studentInfo = DB::table('estudiantesreprobados')
                //                     ->join('estudiante','estudiantesreprobados.idEstudiante','=','estudiante.idEstudiante')
                //                     ->join('grupo','estudiantesreprobados.idGrupo','=','grupo.idGrupo')
                //                     ->join('ciclo','grupo.idCiclo','=','ciclo.idCiclo')
                //                     ->join('materia','grupo.idMateria','=','materia.idMateria')
                //                     ->where('estudiantesreprobados.idEstudiante','=',$id)
                //                     ->get();
                $studentInfo = DB::table('estudiantesreprobados')
                                    ->join('estudiante','estudiantesreprobados.idEstudiante','=','estudiante.idEstudiante')
                                    ->join('grupo','estudiantesreprobados.idGrupo','=','grupo.idGrupo')
                                    ->join('ciclo','grupo.idCiclo','=','ciclo.idCiclo')
                                    ->join('materia','grupo.idMateria','=','materia.idMateria')
                                    ->where('estudiantesreprobados.idDetalle','=',$detailId)
                                    ->get();
                $activity = DB::table('actividadesextraordinarias')
                                    ->join('estudiantesreprobados','actividadesextraordinarias.idDetalle','=','estudiantesreprobados.idDetalle')
                                    ->where('actividadesextraordinarias.idDetalle','=',$detailId)                                    
                                    ->count();
                
                // return $activity;
                return view('teacherSite.failedStudentInfo',compact('studentInfo','activity'));
            }else{
                return view('layout.403');
            }                       
        }

        /**
         * Función para asignar actividad extraordinaria a estudiante
         */
        public function storeActivity(Request $request)
        {
            if(session()->has('docente'))
            {
                $validator = Validator::make($request->all(), [
                    'detalle' => ['required'],
                    'nombreActividad' => ['required'],  
                    'promedio' => ['required'],
                    'porcentaje' => ['required','min:0','max:40','numeric']                                    
                ]); 
                

                //Validación para que el porcentaje de la actividad sea mayor o igual al necesario
                // $validator->after(function($validator) use($request){                    
                    
                //     $average = $request->input('promedio');
                //     $percent = $request->input('porcentaje');

                //     $neccesary = (7 - $average) * 10;

                //     if($percent < $neccesary){
                //         $validator->errors()->add('porcentaje','El porcentaje de la actividad debe de ser mayor o igual a '.$neccesary.'%');
                //     }
                // }); 

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                try{

                    DB::beginTransaction();                    
                    $activity = $request->input('nombreActividad');
                    $percent = $request->input('porcentaje');
                    $description = $request->input('descripcion')??'No se ha añadido descripción';
                    $detailId = $request->input('detalle');

                    $newActivity = new ActividadesExtraordinaria([
                        'idDetalle' => $detailId,
                        'actividad' => $activity,
                        'descripcion' => $description,
                        'porcentaje' => $percent,
                        'nota' => 0,
                        'porcentajeGanado' => 0,
                        'estadoFinalizacion' => 1
                    ]);

                    $newActivity->save();
                    
                    DB::commit();                    
                    return to_route('teacherSite.showFailed')->with('exitoAgregar','Actividad asignada correctamente');
                }catch(Exception $e){
                    return to_route('teacherSite.showFailed')->with('errorAgregar','Ha ocurrido un error al asignar actividad'.$e->getMessage());
                }                    
                
            }else{
                return view('layout.403');
            }                

        }
        
        /**
         * Función para agreegar calificacion a actividad extra
         */
        public function storeGradeExtra(string $detailId)
        {
            if(session()->has('docente')){
                $information = DB::table('actividadesextraordinarias')
                                    ->join('estudiantesreprobados','actividadesextraordinarias.idDetalle','=','estudiantesreprobados.idDetalle')
                                    ->join('estudiante','estudiantesreprobados.idEstudiante','=','estudiante.idEstudiante')
                                    ->join('grupo','estudiantesreprobados.idGrupo','=','grupo.idGrupo')
                                    ->join('materia','grupo.idMateria','=','materia.idMateria')
                                    ->where('actividadesextraordinarias.idDetalle','=',$detailId)
                                    ->get();
                // return $information;
                return view('teacherSite.extraEvaluationGrade',compact('information'));                    
            }else{
                return view('layout.403');
            }
        }

        /**
         * Función para registrar calificacion extra
         */
        public function storeGradeE(Request $request)
        {            
            if(session()->has('docente'))
            {
                $request->validate([
                    'calificacion' => ['required','numeric','min:0','max:10'],
                    'detalle' => ['required'],
                    'porcentaje' => ['required','numeric','max:40'],
                    'promedioActual' => ['required'],
                    'estudiante' => ['required'],
                    'materia' => ['required'],
                ]);

                try{    
                    date_default_timezone_set('America/El_Salvador');

                    DB::beginTransaction();
                    $grade = $request->input('calificacion');
                    $percent = $request->input('porcentaje');
                    $average = $request->input('promedioActual');
                    $detail = $request->input('detalle');
                    $student = $request->input('estudiante');
                    $subject = $request->input('materia');

                    $porcentajeGanado = $grade * ($percent / 100);
                    $promedioFinal = ROUND($average + $porcentajeGanado,1);

                    if($promedioFinal >= 7){

                        $affected = DB::table('estudiantesreprobados')
                                            ->where('idDetalle',$detail)
                                            ->update(['estadoReprobado' => 0]);

                        $affectedActivity = DB::table('actividadesextraordinarias')
                                        ->where('idDetalle',$detail)
                                        ->update(['nota' => $grade, 'porcentajeGanado' => $porcentajeGanado,'estadoFinalizacion' => 0]);        

                        if($affected == 1){
                            $historialEstudiante = new HistorialEstudiante([
                                'idEstudiante' => $student,
                                'idMateria' => $subject,
                                'anio' => date('Y'),
                                'promedio' => $promedioFinal,  
                                'convocatoria' => 'Extraordinaria'                                                              
                            ]);

                            $historialEstudiante->save();
                            DB::commit();
                            return to_route('teacherSite.showFailed')->with('exitoAgregarCalificacion','La calificación ha sido asignada correctamente, el estudiante ha aprobado la asignatura');

                        }else{
                            DB::rollBack();
                            return to_route('teacherSite.showFailed')->with('errorAgregarCalificacion','Ha ocurrido al agregar la calificación');                    
                        }
                        
                    }else{
                            $affectedDetail = DB::table('estudiantesreprobados')
                                                ->where('idDetalle',$detail)
                                                ->update(['estadoReprobado' => 2]); 
                            $affectedActivity = DB::table('actividadesextraordinarias')
                                        ->where('idDetalle',$detail)
                                        ->update(['nota' => $grade, 'porcentajeGanado' => $porcentajeGanado,'estadoFinalizacion' => 0]);       
                            DB::commit();                                    
                            // return "Estudiante reprobado ".$promedioFinal;
                            return to_route('teacherSite.showFailed')->with('estudianteReprobado','El estudiante ha reprobado');                    
                    }


                }catch(Exception $e){
                    return to_route('teacherSite.showFailed')->with('errorAgregarCalificacion','Ha ocurrido al agregar la calificación');                    
                }


            }else{
                return view('layout.403');
            }
        }

        public function miPerfil(){
            if(session()->has('docente')){
                $id= session()->get('docente');
                $informacionDocente = DB::table('docente')->where('idDocente','=',$id[0]->idDocente)->get();
                return view('teacherSite.miPerfil', compact('informacionDocente'));
            } else{
                return view('layout.403');
            }
        }

        public function updateInfor(Request $request){
            if(session()->has('docente')){
                $teacherId = $request->input('idDocenteActualizar');
                $request->validate([
                    'correoDocente' => ['required','email',Rule::unique('docente', 'correoDocente')->ignore($teacherId, 'idDocente')],
                    'telefonoDocente' => ['required','regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/',Rule::unique('docente', 'numeroTelefono')->ignore($teacherId, 'idDocente')],
                ],[
                        'telefonoDocente.regex' => 'Formato incorrecto de teléfono',
                ]);

                $teacherEmail = $request->input('correoDocente');
                $teacherPhone = $request->input('telefonoDocente');

                try{
                    $affected = DB::table('docente')
                    ->where('idDocente', '=', $teacherId)
                    ->update([
                        'correoDocente' => $teacherEmail,
                        'numeroTelefono' => $teacherPhone,              
                    ]);        

                    return to_route('teacherSite.miPerfil')->with('exitoModificar','La información del docente ha sido actualizada correctamente');
                }catch(QueryException $e){
                    return to_route('teachers.edit',$teacherId)->with('errorModificar','Ha ocurrido un error al modificar el registro');
                } 
            }else{
                return view('layout.403');
            }
        }
}
