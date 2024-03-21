<?php

namespace App\Http\Controllers;

use App\Models\Ciclos;
use App\Models\DetalleEstudiantesGrupo;
use App\Models\Docentes;
use App\Models\Estudiantes;
use App\Models\Grupos;
use App\Models\Materias;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(session()->has('administrador')){
        $subjects = Materias::where('estadoEliminacion','=',1)->join('etapa','materia.idEtapa','=','etapa.idEtapa')->get();
        $teachers = Docentes::where('estadoEliminacion','=',1)->get();
        $periods = DB::table('ciclo')
                        ->where('estado',1)
                        ->orWhere('estado',0)
                        ->orderBy('estado','desc')
                        ->get();
        return view('group.add', compact('subjects','teachers','periods'));
        }else{
            return view('layout.403');
        }
        // return $periods;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(session()->has('administrador')){
        date_default_timezone_set('America/El_Salvador');
        $request->validate([
            'groupName' => ['required'],
            'subjectGroup' => ['required','gt:0'],
            'teacherGroup' => ['required','gt:0']
        ],[
            'subjectGroup.gt' => 'Debe seleccionar una materia',
            'teacherGroup.gt' => 'Debe seleccionar un docente',
        ]);

        if($request->input('action') == 'registrarGrupo'){ //Registrar grupo sin seminaristas
            try{   
                    
                $group = new Grupos();
                
                $group->nombreGrupo = $request->input('groupName');
                $group->anio = date('Y-m-d');
                $group->idMateria = $request->input('subjectGroup');
                $group->idDocente = $request->input('teacherGroup');
                $group->idCiclo = $request->input('periodGroup');
                $group->estadoFinalizacion = 1;

                if($group->save()){
                    return to_route('group.create')->with('exitoAgregar','Grupo registrado correctamente');
                }else{
                    return to_route('group.create')->with('errorAgregar','Ha ocurrido un error al registrar grupo');
                }

            }catch(Exception $e){ 
                return to_route('group.create')->with('errorAgregar','Ha ocurrido un error al registrar grupo');
            }                   
        }}else{
            return view('layout.403');
        }
            
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(session()->has('administrador')){
        $group = DB::table('grupo')
                    ->join('materia', 'grupo.idMateria','=','materia.idMateria')
                    ->join('docente', 'grupo.idDocente','=','docente.idDocente')
                    ->select('nombreGrupo', 'nombreMateria', 'nombreDocente', 'apellidoDocente', 'grupo.anio', 'estadoFinalizacion','grupo.idGrupo')
                    ->where('idGrupo',$id)
                    ->get();
        if($group != null)
        {
            $studentQuantity = DB::table('detalleestudiantegrupo')
                                    ->where('idGrupo',$id)
                                    ->count();
            
            $students = DB::table('detalleestudiantegrupo')
                            ->join('estudiante','detalleestudiantegrupo.idEstudiante','=','estudiante.idEstudiante')
                            ->select('nombreEstudiante','apellidoEstudiante','detalleestudiantegrupo.idDetalle','idGrupo')
                            ->where('idGrupo',$id)
                            ->get(); 
            
            $evaluationQuantity = DB::table('evaluacion')
                                        ->where('idGrupo','=',$id)
                                        ->count();
            
            
            return view('group.groupInformation', compact('group','studentQuantity', 'students','evaluationQuantity'));        
            // return $students;
        }else{
            return "Registro no encontrado";
        }}else{
            return view('layout.403');
        }
    }


    /**
     * Función para obtner los docentes de la materia
     */
    public function getTeacherSubject(string $id)
    {
        if(session()->has('administrador')){
        $teachers = DB::table('docente')
                        ->join('materiasdocente','docente.idDocente','=','materiasdocente.idDocente')
                        ->where('materiasDocente.idMateria','=', $id)
                        ->get();
        return $teachers;
        }else{
            return view('layout.403');
        }
    }
    
    public function storeGroupStudent(Request $request)
    {
        if(session()->has('administrador')){
        return $request->input('groupName');
        }else{
            return view('layout.403');
        }
    }

    /**
     * Función para agregar estudiantes al grupo de clases
     */
    public function storeStudentsGroup(Request $request)
    {           
        if(session()->has('administrador')){
        try{
            DB::beginTransaction();

            $groupId = $request->input("idGrupo");
            
            $students = $request->input("estudiantes");

            foreach($students as $student)
            {
                $studentGroupDetail = new DetalleEstudiantesGrupo();
                $studentGroupDetail->idEstudiante = $student;
                $studentGroupDetail->idGrupo = $groupId;
                $studentGroupDetail->save();
            }

            DB::commit();

            return to_route('group.addStudents',$groupId)->with("registroAlumnosExito","Se han agregado los seminaristas al grupo de clases");

        }catch(Exception $e){
            DB::rollBack();
            return to_route('group.addStudents',$groupId)->with('registroAlumnosError','Ha ocurrido un error al agregar estudiantes al grupo');
        }}else{
            return view('layout.403');
        }
            
    }

    /**
     * Función para obtener los grupos
     */
    public function groupControl(Request $request)
    {        
        if(session()->has('administrador')){   
        $groups = DB::table('Grupo')
                        ->join('Materia', 'Grupo.idMateria', '=', 'Materia.idMateria')
                        ->join('Docente', 'Grupo.idDocente', '=', 'Docente.idDocente')
                        ->join('Ciclo', 'Grupo.idCiclo', '=', 'Ciclo.idCiclo')
                        ->leftJoin('DetalleEstudianteGrupo', 'Grupo.idGrupo', '=', 'DetalleEstudianteGrupo.idGrupo')
                        ->select(
                            'Grupo.idGrupo',
                            'Grupo.idMateria',  
                            'Grupo.nombreGrupo',
                            'Grupo.anio',
                            'Grupo.idDocente',
                            'Grupo.idCiclo',
                            'Materia.nombreMateria',
                            'Docente.nombreDocente',
                            'Docente.apellidoDocente',
                            'Ciclo.nombreCiclo',
                            DB::raw('COUNT(DetalleEstudianteGrupo.idEstudiante) as cantidadAlumnos')
                        )
                        ->where('grupo.estadoFinalizacion','=',1)
                        ->groupBy('Grupo.idGrupo', 'Grupo.idMateria', 'Grupo.nombreGrupo', 'Grupo.anio', 'Grupo.idDocente', 'Grupo.idCiclo', 'Materia.nombreMateria', 'Docente.nombreDocente',  'Docente.apellidoDocente', 'Ciclo.nombreCiclo')
                        ->get();
    

        return view('group.groupControl',compact('groups'));
        } else{
            return view('layout.403');
        }
        // return $groups;
    }   

    /**
     * Función para obtener los estudiantes que pueden ser inscritos a grupos de materias nivel 1
     */
    public function getStudentsLevel1(string $subjectId)
    {        
        // $groupStudents = Estudiantes::whereNotExists(function ($query){
        //     $query->select('idEstudiante')
        //         ->from('historialestudiante')
        //         ->whereColumn('historialestudiante.idEstudiante', 'estudiante.idEstudiante');
        //         })
        //         ->get();
        // $groupStudents = Estudiantes::whereNotExists(function ($query) use ($subjectId) {
        //     $query->select('idEstudiante')
        //         ->from('historialestudiante')
        //         ->whereColumn('historialestudiante.idEstudiante', 'estudiante.idEstudiante');
        // })->whereNotExists(function ($query) use ($subjectId) {
        //     $query->select('detalleestudiantegrupo.idEstudiante')
        //         ->from('detalleestudiantegrupo')
        //         ->join('grupo', 'detalleestudiantegrupo.idGrupo', '=', 'grupo.idGrupo') 
        //         ->join('estudiante','detalleestudiantegrupo.idEstudiante','=','estudiante.idEstudiante')               
        //         ->where('grupo.idMateria', '=',$subjectId);
        // })->get();  
        if(session()->has('administrador')){     
            $groupStudents = DB::table('Estudiante')
                                    ->leftJoin('DetalleEstudianteGrupo', function ($join) use ($subjectId) {
                                        $join->on('Estudiante.idEstudiante', '=', 'DetalleEstudianteGrupo.idEstudiante')
                                            ->leftJoin('Grupo', 'DetalleEstudianteGrupo.idGrupo', '=', 'Grupo.idGrupo')
                                            ->where('Grupo.idMateria', '=', $subjectId);
                                            //->where('Grupo.estadoFinalizacion', '=', 1); // Solo estudiantes en grupos activos
                                    })
                                    ->leftJoin('HistorialEstudiante', function ($join) use ($subjectId) {
                                        $join->on('Estudiante.idEstudiante', '=', 'HistorialEstudiante.idEstudiante')
                                            ->where('HistorialEstudiante.idMateria', '=', $subjectId);
                                    })
                                    ->whereNull('DetalleEstudianteGrupo.idDetalle')
                                    ->whereNull('HistorialEstudiante.idHistorial')
                                    ->where('estudiante.estadoAceptacion','=',1)
                                    ->where('estudiante.estadoEliminacion','=',1)
                                    ->select('Estudiante.*')
                                    ->get();
                        
            return $groupStudents;
        }else{
            return view('layout.403');
        }
        
    }

    /**
     * Función para obtener los estudiantes que se pueden inscribir a grupos de materias mayores a nivel 1
     */
    public function getStudentsLevel(string $subjectId, string $studentsLevel)
    {
        if(session()->has('administrador')){
            $subjectQuantity = DB::table('materia')
                                    ->where('nivel','=',$studentsLevel)
                                    ->count();

            $groupStudents = DB::table('historialestudiante')
                                ->join('estudiante','historialestudiante.idEstudiante','=','estudiante.idEstudiante')
                                ->join('materia','historialestudiante.idMateria','=','materia.idMateria')
                                ->where('materia.nivel','=',$studentsLevel)                                                               
                                ->whereNotIn('estudiante.idEstudiante',function($subquery) use ($subjectId){
                                    $subquery->select('idEstudiante')
                                                ->from('detalleestudiantegrupo')
                                                ->join('grupo','detalleestudiantegrupo.idGrupo','=','grupo.idGrupo')
                                                ->join('materia','grupo.idMateria','=','materia.idMateria')
                                                ->where('materia.idMateria', '=',$subjectId);
                                })  
                                ->select('estudiante.idEstudiante','estudiante.nombreEstudiante','estudiante.apellidoEstudiante',DB::raw('count(materia.idMateria) as cantidadMateria'))
                                ->where('materia.nivel', '=', $studentsLevel)
                                ->where('estudiante.estadoAceptacion','=',1)  
                                ->where('estudiante.estadoEliminacion','=',1)  
                                ->groupBy('estudiante.idEstudiante','estudiante.nombreEstudiante','estudiante.apellidoEstudiante')  
                                ->having('cantidadMateria', '=', $subjectQuantity)                   
                                ->get();

        // $groupStudents = DB::table('historialestudiante')
        //                         ->join('estudiante','historialestudiante.idEstudiante','=','estudiante.idEstudiante')
        //                         ->join('materia','historialestudiante.idMateria','=','materia.idMateria')                                    
        //                         ->where('estudiante.estadoAceptacion','=',1)        
        //                         ->where('estudiante.estadoEliminacion','=',1)                                                                                   
        //                         ->select('estudiante.idEstudiante','estudiante.nombreEstudiante','estudiante.apellidoEstudiante',DB::raw('count(materia.idMateria) as cantidadMateria'))
        //                         ->where('materia.nivel', '=', $studentsLevel)
        //                         ->groupBy('estudiante.idEstudiante','estudiante.nombreEstudiante','estudiante.apellidoEstudiante')  
        //                         ->having('cantidadMateria', '=', $subjectQuantity)                   
        //                         ->get();
                    
        return $groupStudents;
        } else{
            return view('layout.403');
        }
        // return $subjectQuantity;
    }

    /**
     * Función para mostrar grupos finalizados
     */
    public function showFinalizedGroups()
    {
        if(session()->has('administrador')){
        $groups = $groups = DB::table('Grupo')
                                ->join('Materia', 'Grupo.idMateria', '=', 'Materia.idMateria')
                                ->join('Docente', 'Grupo.idDocente', '=', 'Docente.idDocente')
                                ->join('Ciclo', 'Grupo.idCiclo', '=', 'Ciclo.idCiclo')
                                ->leftJoin('DetalleEstudianteGrupo', 'Grupo.idGrupo', '=', 'DetalleEstudianteGrupo.idGrupo')
                                ->select(
                                    'Grupo.idGrupo',
                                    'Grupo.idMateria',  
                                    'Grupo.nombreGrupo',
                                    'Grupo.anio',
                                    'Grupo.idDocente',
                                    'Grupo.idCiclo',
                                    'Materia.nombreMateria',
                                    'Docente.nombreDocente',
                                    'Docente.apellidoDocente',
                                    'Ciclo.nombreCiclo',
                                    DB::raw('COUNT(DetalleEstudianteGrupo.idEstudiante) as cantidadAlumnos')
                                )
                                ->where('grupo.estadoFinalizacion','=',0)
                                ->groupBy('Grupo.idGrupo', 'Grupo.idMateria', 'Grupo.nombreGrupo', 'Grupo.anio', 'Grupo.idDocente', 'Grupo.idCiclo', 'Materia.nombreMateria', 'Docente.nombreDocente',  'Docente.apellidoDocente', 'Ciclo.nombreCiclo')
                                ->get();


        return view('group.finalizedGroups',compact('groups'));
        } else{
            return view('layout.403');
        }

    }

    /**
     * Función para mostrar vista para agregar estudiantes al grupo seleccionado
     */
    public function addStudentsGroup(string $id)
    {   
        if(session()->has('administrador')){
            $group = DB::table('grupo')
                            ->join('docente','grupo.idDocente','=','docente.idDocente')
                            ->join('ciclo','grupo.idCiclo','=','ciclo.idCiclo')
                            ->join('materia','grupo.idMateria','=','materia.idMateria')
                            ->where('grupo.idGrupo',$id)
                            ->get();

        if($group->count() > 0){

            if($group[0]->nivel == 1){//Materias de nivel 1
                $students = $this->getStudentsLevel1($group[0]->idMateria);                   
                $studentFinalizedArray = [];

                return view('group.addStudent',compact('group','students','studentFinalizedArray'));                     
            }else{//Materias de nivel superior a 1
                
                $subjectLevel = $group[0]->nivel;
                $studentsLevel = $subjectLevel - 1;

                $subjectGroup = DB::table('grupo')
                                        ->where('idGrupo','=',$id)
                                        ->select('idMateria')
                                        ->get();
                $studentsFinalized = DB::table('historialestudiante')
                                            ->join('materia','historialestudiante.idMateria','=','materia.idMateria')
                                            ->where('historialestudiante.idMateria','=',$subjectGroup[0]->idMateria)
                                            ->get();
                $studentFinalizedArray = [];
                foreach($studentsFinalized as $student)
                {
                    $studentFinalizedArray[] = $student->idEstudiante;
                }      

                $students = $this->getStudentsLevel($group[0]->idMateria, $studentsLevel);
                return view('group.addStudent',compact('group','students','studentFinalizedArray'));
                // return $studentsFinalized;
                
            }
    
        }else{
            return view('layout.404');
        } }else{
            return view('layout.403');
        }       
    }

    public function deleteStudent(Request $request)
    {
        if(session()->has('administrador')){
        $request->validate([
            'idDetalleEliminar' => ['required'], 
            'idGrupo' => ['required'],           
        ]);

        try{
            $detailId = $request->input('idDetalleEliminar');
            $groupId = $request->input('idGrupo');

            $deleted = DB::table('detalleestudiantegrupo')
                            ->where('idDetalle','=',$detailId)
                            ->delete();
            
            if($deleted == 1){
                return to_route('group.information',$groupId)->with('exitoEliminarEstudiante','El seminarista ha sido eliminado del grupo');
            }
        }catch(Exception $e){
            return to_route('group.information',$groupId)->with('errorEliminarEstudiante','Ha ocurrido un error al eliminar el estudiante del grupo');
        }} else{
            return view('layout.403');
        }
        //return "hola";
    }

    public function deleteGroup(Request $request)
    {
        if(session()->has('administrador')){
            try{
                DB::beginTransaction();
                $groupId = $request->input('idGrupoEliminar');

                $deleteStudent = DB::table('detalleestudiantegrupo')
                                ->where('idGrupo', '=', $groupId)
                                ->delete();
                $deleteGroup = DB::table('grupo')
                                ->where('idGrupo', '=', $groupId)
                                ->delete();
                DB::commit();     
                return to_route('group.index')->with('eliminacionGrupo','Grupo eliminado correctamente');     
                
            }catch(Exception $e){
                DB::rollBack();
                return to_route('group.index')->with('eliminacionGrupoError','Ha ocurrido un error al eliminar grupo');       
            }                
        }else{
            return view('layout.403');
        }
            
        // return $groupId;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
