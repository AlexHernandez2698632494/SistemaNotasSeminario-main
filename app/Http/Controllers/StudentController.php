<?php

namespace App\Http\Controllers;

use App\Mail\Credentials;
use App\Models\ActividadesExtraordinaria;
use App\Models\Estudiantes;
use App\Models\HistorialEstudiante;
use App\Models\MateriaAuxiliar;
use App\Models\Usuarios;
use Carbon\Carbon;
use DateTime;
use Dotenv\Util\Str;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        if(session()->has('administrador')){
        $students = DB::table('estudiante')
                        ->where('estadoEliminacion','=',1)
                        ->where('estadoAceptacion','=',1)
                        ->orderBy('nombreEstudiante','desc')
                        ->get();
        return view('student.index', compact('students'));}
        else{
            return view('layout.403');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(session()->has('administrador')){
        return view('student.add');}
        else {
            return view('layout.403');
        }
    }

    public function storeMateria(Request $request)
    {   
        if(session()->has('administrador')){
        $materias = DB::table('materiaauxiliar')
        ->join('materia','materiaauxiliar.idMateria','=','materia.idMateria')
        ->join('etapa','materia.idEtapa','=','etapa.idEtapa')
        ->orderBy('nivel','desc')
        ->get();
        $estudiante = DB::table('materiaauxiliar')
        ->join('estudiante','materiaauxiliar.idEstudiante','=','estudiante.idEstudiante')
        ->get();
        if($materias->isEmpty()){ return to_route('student.create')->with('exitoAgregar','El seminarista ha sido registrado exitosamente');}
        else {return view('student.agregarMaterias',compact('materias','estudiante'));}}
        else{
            return view('layout.403');
        }
    }   

    /**
	 * Función para generar usuario de docente
	 */
	public function generateUser(string $name, string $lastName)
	{
        if(session()->has('administrador')){
        $nameElements = explode(' ',$name);
		$lastNameElements = explode(' ',$lastName);

		$firsLetterName = mb_substr($nameElements[0],0,1);
        if($firsLetterName == "Á"){
            $firsLetterName = "A";
        } else if($firsLetterName == "É"){
            $firsLetterName = "E";
        } else if($firsLetterName == "Í"){
            $firsLetterName = "I";
        } else if($firsLetterName == "Ó"){
            $firsLetterName = "O";
        } else if($firsLetterName == "Ú"){
            $firsLetterName = "U";
        }
        $firstLetterLastName = mb_substr($lastNameElements[0], 0, 1);
        if($firstLetterLastName == "Á"){
            $firstLetterLastName = "A";
        } else if($firstLetterLastName == "É"){
            $firstLetterLastName = "E";
        } else if($firstLetterLastName == "Í"){
            $firstLetterLastName = "I";
        } else if($firstLetterLastName == "Ó"){
            $firstLetterLastName = "O";
        } else if($firstLetterLastName == "Ú"){
            $firstLetterLastName = "U";
        }

        $year = date('y');

        $randomNumber = rand(1000,9999);

        $user = $firsLetterName.$firstLetterLastName.$year.$randomNumber;
		
		do{
			$userVerification = DB::table('usuario')
									->where('usuario', $user)
									->exists();

			if($userVerification){
				$randomNumber = rand(1000,9999);
                $user = $firsLetterName.$firstLetterLastName.$year.$randomNumber;
			}

		}while($userVerification);

		return $user;	}
        else{
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
		return $pass;}
        else {
            return view('layout.403');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {          
        if(session()->has('administrador')){         
        $validator = Validator::make($request->all(), [
            'nombreSeminarista' => ['required','string'],
            'apellidoSeminarista' => ['required', 'string'],
            'fechaNacimientoSeminarista' => ['required'],                
            'duiSeminarista' => ['required','regex:/^[0-9]{8}-[0-9]{1}$/'],
            'fechaBautismo' => ['required'],
            'fechaConfirmacion' => ['required'],                
            'telefonoCasa' => ['regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', 'unique:estudiante,numeroTelefonicoCasa'],
            'numeroCelular' => ['regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', 'unique:estudiante,numeroMovil'],
            'fechaIngreso' => ['required', 'date_format:Y-m-d','before_or_equal:today'],
            'correoSeminarista' => ['required', 'email', 'unique:estudiante,correoEstudiante'],            
        ]);   

        //Validación para que la fecha de nacimiento sea mayor a 18 años
        $validator->after(function($validator) use($request){
            date_default_timezone_set('America/El_Salvador');
        
            $currentDate = date('Y-m-d');
            
            $date = $request->input('fechaNacimientoSeminarista');
            $birthday = Carbon::parse($date);

            $years = $birthday->diffInYears($currentDate);
            
            if($years < 18){
                $validator->errors()->add('fechaNacimientoSeminarista','El seminarista debe ser mayor de edad');
            }
        });       

        //Validando fechas
        $validator->after(function($validator) use($request){
            $baptismDate = Carbon::parse($request->input('fechaBautismo'));
            $confirmationDate = Carbon::parse($request->input('fechaConfirmacion'));
            $birthdayDate = Carbon::parse($request->input('fechaNacimientoSeminarista'));
            $admissionDate = Carbon::parse($request->input('fechaIngreso'));
            
            //Validando que la fecha de bautismo no sea mayor que la fecha de confirmación
            if($baptismDate->gt($confirmationDate)){
                $validator->errors()->add('fechaBautismo','La fecha de bautismo debe ser menor que la fecha de confirmación');
            }

            //Validando que la fecha de nacimiento no sea mayor que la fecha de bautismo
            if($birthdayDate->gt($baptismDate)){
                $validator->errors()->add('fechaNacimientoSeminarista','La fecha de nacimiento no puede ser mayor que la fecha de bautismo');
            }

            //Validando que la fecha de nacimiento no sea mayor que la fecha de confirmación
            if($birthdayDate->gt($confirmationDate)){
                $validator->errors()->add('fechaNacimientoSeminarista','La fecha de nacimiento no puede ser mayor que la fecha de confimación');
            }

            if($birthdayDate->gt($admissionDate)){
                $validator->errors()->add('fechaNacimientoSeminarista','La fecha de nacimiento no puede ser mayor que la fecha de admisión');
            }
        });

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        DB::beginTransaction();
        try{
            $student = new Estudiantes();

            $student->nombreEstudiante = $request->input('nombreSeminarista');
            $student->apellidoEstudiante = $request->input('apellidoSeminarista');
            $student->fechaNacimiento = $request->input('fechaNacimientoSeminarista');
            $student->duiEstudiante = $request->input('duiSeminarista');
            $student->fechaIngreso = $request->input('fechaIngreso');
            $student->fechaBautismo = $request->input('fechaBautismo');
            $student->fechaConfirmacion = $request->input('fechaConfirmacion');
            $student->parroquia = $request->input('nombreParroquia')?? 'No se ha añadido información';
            $student->direccion = $request->input('direccionResidencia')?? 'No se ha añadido información';
            $student->numeroTelefonicoCasa = $request->input('telefonoCasa');
            $student->numeroMovil = $request->input('numeroCelular');
            $student->nombrePadre = $request->input('nombrePadre')?? 'No se ha añadido información';
            $student->nombreMadre = $request->input('nombreMadre')?? 'No se ha añadido información';        
            $student->enfermedades = $request->input('enfermedades')?? 'No se ha añadido información';
            $student->correoEstudiante = $request->input('correoSeminarista');
            $student->cum = 0;
            $student->estadoAceptacion = $request->input('estadoAceptacion');

            if($student->save()){                

                $acceptStatus = $request->input('estadoAceptacion');

                if($acceptStatus == 1){
                    $studentEmail = $request->input('correoSeminarista');
                    $studentName = $request->input('nombreSeminarista').' '.$request->input('apellidoSeminarista');

                    $userName = $this->generateUser($request->input('nombreSeminarista'),$request->input('apellidoSeminarista'));
                    $pass = $this->generatePass();

                    $userObj = new Usuarios();

                    $userObj->idUsuario = $request->input('duiSeminarista');
                    $userObj->usuario = $userName;
                    // $userObj->password = Hash::make($pass);
                    $userObj->password = Hash('SHA256',$pass);
                    $userObj->nivel = 2;

                    $userObj->save();

                    /*$email = new Credentials($userName, $pass, $studentName);

                    Mail::to($studentEmail)->send($email);*/

                    $pdfData=[
                        'studentName'=>$studentName,
                        'userName'=>$userName,
                        'pass'=>$pass,
                    ];

                    $pdf=Pdf::loadView('pdf.estudiante',$pdfData);
                    $pdf->save(public_path('pdf/estudiante.pdf'));
                    DB::commit();
                    $nivel = $request->input('txtNivel');
                    if($nivel == 0){
                        return to_route('student.create')->with('exitoAgregar','El seminarista ha sido registrado exitosamente');    
                    }
                    else if($nivel =! 0){
                        DB::table('materiaauxiliar')->delete();
                        $estudiante = DB::table('estudiante')
                        ->where('estudiante.duiEstudiante','=',$request->input('duiSeminarista'))
                        ->get();
                        $materias = DB::table('materia')
                        ->where('materia.nivel','<=',$request->input('txtNivel'))
                        ->join('etapa','materia.idEtapa','=','etapa.idEtapa')
                        ->orderBy('nivel','desc')
                        ->get();
                        foreach($materias as $materia){
                            DB::beginTransaction();    
                            $materiaAgregar = new MateriaAuxiliar();
                            $materiaAgregar->idMateria = $materia->idMateria;
                            $materiaAgregar->idEstudiante = $estudiante[0]->idEstudiante;
                            $materiaAgregar->save();
                            DB::commit();
                        }
                        return view('student.agregarMaterias',compact('materias','estudiante'));
                    }
                }else{
                    DB::commit();
                    return to_route('student.create')->with('exitoAgregarRechazado','El seminarista no aceptado ha sido registrado exitosamente');    
                }           
            }else{
                DB::rollback();
                return to_route('student.create')->with('errorAgregar','Ha ocurrido un error al registrar el seminarista');
            }  
        }catch(Exception $e){
			DB::rollback();
            return to_route('student.create')->with('errorAgregar','Ha ocurrido un error al registrar el seminarista');
        }     }
        else{
            return view('layout.403');
        }               
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(session()->has('administrador')){
        $student = Estudiantes::find($id);       
        if($student != null){
            return view('student.studentInformation',compact('student'));
        }else{
            return view('layout.404');
        }}
        else{
            return view('layout.403');
        }                         
            
    }

    public function getStudentInfo(string $id)
    {
        if(session()->has('administrador')){
        $student = Estudiantes::find($id);
        return $student;}
        else{
            return view('layout.403');
        }
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
    public function update(Request $request)
    {
        if(session()->has('administrador')){
        $studentId = $request->input('idSeminarista');

        $validator = Validator::make($request->all(),[
            'nombreSeminarista' => ['required','string'],
            'apellidoSeminarista' => ['required', 'string'],                    
            'duiSeminarista' => ['required','regex:/^[0-9]{8}-[0-9]{1}$/',Rule::unique('estudiante', 'duiEstudiante')->ignore($studentId, 'idEstudiante')],                                   
            'telefonoCasa' => ['regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', Rule::unique('estudiante', 'numeroTelefonicoCasa')->ignore($studentId, 'idEstudiante')],
            'numeroCelular' => ['regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', Rule::unique('estudiante', 'numeroMovil')->ignore($studentId, 'idEstudiante')],            
            'correoSeminarista' => ['required', 'email', Rule::unique('estudiante', 'correoEstudiante')->ignore($studentId, 'idEstudiante')],
        ]);   

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        
        $studentName = $request->input('nombreSeminarista');
        $studentLastName = $request->input('apellidoSeminarista');
        $studentDui = $request->input('duiSeminarista');
        $studentEmail = $request->input('correoSeminarista');
        $studenMobile = $request->input('numeroCelular');
        $studentPhone = $request->input('telefonoCasa');
        $studentAdress = $request->input('direccionResidencia')?? 'No se ha añadido información';
        $studentDisease = $request->input('enfermedades')?? 'No se ha añadido información';       
        
        try{
            $affected = DB::table('estudiante')
            ->where('idEstudiante', '=', $studentId)
            ->update([
                'nombreEstudiante' => $studentName,
                'apellidoEstudiante' => $studentLastName,
                'duiEstudiante' => $studentDui,
                'correoEstudiante' => $studentEmail,
                'numeroMovil' => $studenMobile,
                'numeroTelefonicoCasa' => $studentPhone,
                'direccion' => $studentAdress,
                'enfermedades' => $studentDisease
            ]);        

            
            return to_route('student.showInfo',$studentId)->with('exitoModificar','La información del seminarista ha sido actualizada correctamente');            
            
        }catch(QueryException $e){
            return to_route('student.showInfo',$studentId)->with('errorModificar','Ha ocurrido un error al modificar el registro');
        }   }
        else{
            return view('layout.403');
        }                
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(session()->has('administrador')){
            $request->validate([
                'idEstudianteEliminar' => 'required'
            ]);

            $deleteTeacherId = $request->input('idEstudianteEliminar');

            $affected = DB::table('estudiante')
                            ->where('idEstudiante','=',$deleteTeacherId)
                            ->update(['estadoEliminacion' => 0]);
        
            if($affected == 1){
                return to_route('student.index')->with('exitoEliminar','El seminarista se ha eliminado correctamente');
            }else{
                return to_route('student.index')->with('errorEliminar','Error al eliminar el seminarista');            
            }
        }else{
            return view('layout.403');
        }
    }

    public function restoreView()
    {
        if(session()->has('administrador')){
        $removedStudents = Estudiantes::where('estadoEliminacion','=',0)->get();
        return view('student.removed', compact('removedStudents'));        
        } else{
            return view('layout.403');
        }
    }

    public function restore(Request $request)
    {
        if(session()->has('administrador')){
        $request->validate([
            'idEstudianteRestaurar' => ['required','integer']
        ]);

        $restoreStudentId = $request->input('idEstudianteRestaurar');

        try{
            $affected = DB::table('estudiante')
                        ->where('idEstudiante','=',$restoreStudentId)
                        ->update(['estadoEliminacion' => 1]);
        
            if($affected == 1){
                return to_route('student.restoreView')->with('exitoRestaurar','El seminarista se ha restaurado correctamente');
            }else{
                return to_route('student.restoreView')->with('errorRestaurar','Error al restaurar el seminarista');            
            }

        }catch(Exception $e){
            return to_route('student.restoreView')->with('errorRestaurar','Error al restaurar el seminarista: '.$e->getMessage());            
        }}else{
            return view('layout.403');
        }
    }

    /**
     * Función para mostrar vista de los candidatos rechazados
     */
    public function rejected()
    {
        if(session()->has('administrador')){
        $rejectedStudents = Estudiantes::where('estadoAceptacion','=',0)->get();
        return view('student.rejected',compact('rejectedStudents'));   }
        else{
            return view('layout.403');
        }    
    }

    /**
     * Función para mostrar vista con la información del candidato rechazado
     */
    public function getRejectedCandidateInfo(string $id)
    {
        if(session()->has('administrador')){
        $rejectedCandidate = Estudiantes::find($id);
        return view('student.rejectedStudentInfo',compact('rejectedCandidate'));}
        else{
            return view('layout.403');
        }
    }

    /**
     * Función para eliminar candidato rechazado
     */
    public function deleteCandidate(Request $request)
    {
        if(session()->has('administrador')){
        $request->validate([
            'idCandidatoEliminar' => ['required']
        ]);

        $idCandidateDelete = $request->input('idCandidatoEliminar');

        try{
            $deleted = DB::table('estudiante')
            ->where('idEstudiante','=',$idCandidateDelete)
            ->delete();

            if($deleted == 1){
                return to_route('student.rejected')->with('exitoEliminacion','El candidato ha sido eliminado correctamente');
            }else{
                return to_route('student.rejectedInfo',$idCandidateDelete)->with('errorEliminacion','Ha ocurrido un error al eliminar candidato');
            }
        }catch(Exception $e){
            return to_route('student.rejectedInfo',$idCandidateDelete)->with('errorEliminacion','Ha ocurrido un error al eliminar candidato');
        }      }else{
            return view('layout.403');
        }      
    }

    /**
     * Función para aceptar candidato
     */
    public function acceptCandidate(Request $request)
    {
        if(session()->has('administrador')){
            $request->validate([
                'idCandidatoAceptar' => ['required']
            ]);

            try{
                
                DB::beginTransaction();
                $idAcceptCandidate = $request->input('idCandidatoAceptar');

                $affected = DB::table('estudiante')
                                ->where('idEstudiante',$idAcceptCandidate)
                                ->update(['estadoAceptacion' => 1]);

                $student = DB::table('estudiante')
                                ->where('idEstudiante',$idAcceptCandidate)
                                ->get();
                $students = DB::table('estudiante')
                                ->select('nombreEstudiante', 'apellidoEstudiante')
                                ->where('idEstudiante', $idAcceptCandidate)
                                ->first();
                                
                $studentName = $students->nombreEstudiante . ' ' . $students->apellidoEstudiante;

                $userName = $this->generateUser($student[0]->nombreEstudiante,$student[0]->apellidoEstudiante);//Para generacion de reporte
                $pass = $this->generatePass(); //Para generacion de reporte
                
                $usuario = new Usuarios();

                $usuario->idUsuario = $student[0]->duiEstudiante;
                $usuario->usuario = $userName;
                $usuario->password = $pass;
                $usuario->nivel = 2;               

                if($affected == 1){
                    $usuario->save();
                    $pdfData=[
                        'studentName'=>$studentName,
                        'userName'=>$userName,
                        'pass'=>$pass
                    ];
                    $pdf=Pdf::loadView('pdf.estudianteAceptado',$pdfData);
                    $pdf->save(public_path('pdf/estudianteAceptado.pdf'));
                    DB::commit();                    
                    return to_route('student.rejected')->with('exitoAceptacion','El candidato ha sido aceptado correctamente');
                }else{
                    DB::rollBack();
                    return to_route('student.rejectedInfo',$idAcceptCandidate)->with('errorAceptacion','Ha ocurrido un error al aceptar candidato');            
                }
            }catch(Exception $e){
                DB::rollBack();
                return to_route('student.rejectedInfo',$idAcceptCandidate)->with('errorAceptacion','Ha ocurrido un error al aceptar candidato'.$e->getMessage());                        
            } 
        }else{
            return view('layout.403');
        }           
    }

    /**
     * Función para mostrar el historial del estudiante
     */
    public function showRecord(string $id)
    {
        if(session()->has('administrador')){
        $student = Estudiantes::find($id);
        
        if($student != null){

            $subjectStudied = DB::table('historialestudiante')
                                    ->where('idEstudiante',$id)
                                    ->select(DB::raw('count(idMateria) as cantidadMaterias'))
                                    ->get();
            $average = DB::table('historialestudiante')
                                ->where('idEstudiante',$id)
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
                                ->select('materia.nivel','historialestudiante.convocatoria','historialestudiante.anio','etapa.nombreEtapa', 'materia.cuatrimestre', 'materia.nombreMateria','promedio','historialestudiante.idEstudiante')
                                ->where('idEstudiante', $id)
                                ->get();

            
            return view('student.record',compact('student','subjectStudied','average','percent','subjects'));
            // return $average;
        }else{
            return view('layout.404');
        }}else{
            return view('layout.403');
        }
            
        
    }

    public function registroMateria(int $estudianteID, int $materiaID, Request $request)
    {     
        if(session()->has('administrador')){
            $validator = Validator::make($request->all(), [
                
            ]); 
            $validator->after(function($validator) use($request, $materiaID){
                date_default_timezone_set('America/El_Salvador');
                $anioActual = date('Y');
                $anio = $request->input('anio'.$materiaID);

                if($anio>$anioActual){
                    $validator->errors()->add('anioMayor','El año no puede ser mayor que el año actual');
                }
            });
            if($validator->fails()){
                return to_route('student.storeMateria')->withErrors($validator)->withInput();
            }

        DB::beginTransaction();
        try{
            $historial = new HistorialEstudiante();

            $historial->idEstudiante = $estudianteID;
            $historial->idMateria = $materiaID;
            $historial->anio = $request->input('anio'.$materiaID);
            $historial->promedio = $request->input('nota'.$materiaID);
            $historial->convocatoria = $request->input('convocatoria'.$materiaID);

            if($historial->save()){                
                    DB::commit();
                    DB::table('materiaauxiliar')->where('materiaauxiliar.idMateria','=',$historial->idMateria)->delete();
                    return to_route('student.storeMateria')->with('exitoMateria','La materia ha sido registrada exitosamente');
            }
            else{
                DB::rollback();
                return redirect()->back()->with('errorMateria','Ha ocurrido un error al registrar la materia');
            }  
        }catch(Exception $e){
			DB::rollback();
            
            return redirect()->back()->with('errorMateria','Ha ocurrido un error al registrar la materia');
        }  } else{
            return view('layout.403');

        }             
    }

    //Función para mostrar los estudiantes reprobados extraordinarios
    public function showFailedExtra()
    {
        if(session()->has('administrador')){

            $failedStudents = $failedStudents = DB::table('estudiantesreprobados')
                                                ->join('grupo','estudiantesreprobados.idGrupo','=','grupo.idGrupo')
                                                ->join('materia','grupo.idMateria','=','materia.idMateria')
                                                ->join('estudiante','estudiantesreprobados.idEstudiante','=','estudiante.idEstudiante')                                                
                                                ->where('estudiantesreprobados.estadoReprobado','=',2)
                                                ->get();
            return view('student.failedStudents',compact('failedStudents')); 
        }else{
            return view('layout.403');            
        }
    }

    public function showFailedExtraInfo(string $detailId)
    {     
        if(session()->has('administrador')){               
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
                                ->where('actividadesextraordinarias.estadoFinalizacion','=',1)                                    
                                ->count();
            
            // return $activity;
            return view('student.failedStudentInfo',compact('studentInfo','activity'));
        }else{
            return view('layout.403');
        }                       
    }

        /**
         * Función para asignar actividad extraordinaria a estudiante
         */
        public function storeActivityExtra(Request $request)
        {
            if(session()->has('administrador'))
            {
                $validator = Validator::make($request->all(), [
                    'detalle' => ['required'],
                    'nombreActividad' => ['required'],  
                    'promedio' => ['required'],
                    'porcentaje' => ['required','min:0','numeric']                                    
                ]); 
                

                //Validación para que el porcentaje de la actividad sea mayor o igual al necesario
                $validator->after(function($validator) use($request){                    
                    
                    $average = $request->input('promedio');
                    $percent = $request->input('porcentaje');

                    $neccesary = (7 - $average) * 10;

                    if($percent < $neccesary){
                        $validator->errors()->add('porcentaje','El porcentaje de la actividad debe de ser mayor o igual a '.$neccesary.'%');
                    }
                }); 

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
                    return to_route('student.showFailedExtra')->with('exitoAgregar','Actividad asignada correctamente');
                }catch(Exception $e){
                    return to_route('student.showFailedExtra')->with('errorAgregar','Ha ocurrido un error al asignar actividad'.$e->getMessage());
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
            if(session()->has('administrador')){
                $information = DB::table('actividadesextraordinarias')
                                    ->join('estudiantesreprobados','actividadesextraordinarias.idDetalle','=','estudiantesreprobados.idDetalle')
                                    ->join('estudiante','estudiantesreprobados.idEstudiante','=','estudiante.idEstudiante')
                                    ->join('grupo','estudiantesreprobados.idGrupo','=','grupo.idGrupo')
                                    ->join('materia','grupo.idMateria','=','materia.idMateria')
                                    ->where('actividadesextraordinarias.idDetalle','=',$detailId)
                                    ->where('actividadesextraordinarias.estadoFinalizacion','=',1)
                                    ->get();
                // return $information;
                return view('student.extraEvaluationG',compact('information'));                    
            }else{
                return view('layout.403');
            }
        }

        /**
         * Función para registrar calificacion extra
         */
        public function storeGradeE(Request $request)
        {            
            if(session()->has('administrador'))
            {
                $request->validate([
                    'calificacion' => ['required','numeric','min:0','max:10'],
                    'detalle' => ['required'],
                    'porcentaje' => ['required','numeric','max:100'],
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
                    $promedioFinal = ROUND($average + $porcentajeGanado,2);

                    if($promedioFinal >= 7){

                        $affected = DB::table('estudiantesreprobados')
                                            ->where('idDetalle',$detail)
                                            ->update(['estadoReprobado' => 0]);

                        $affectedActivity = DB::table('actividadesextraordinarias')
                                        ->where('idDetalle',$detail)
                                        ->where('estadoFinalizacion','=',1)
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
                            return to_route('student.showFailedExtra')->with('exitoAgregarCalificacion','La calificación ha sido asignada correctamente, el estudiante ha aprobado la asignatura');

                        }else{
                            DB::rollBack();
                            return to_route('student.showFailedExtra')->with('errorAgregarCalificacion','Ha ocurrido al agregar la calificación');                    
                        }
                        
                    }else{
                        $affectedActivity = DB::table('actividadesextraordinarias')
                                                ->where('idDetalle',$detail)
                                                ->where('estadoFinalizacion','=',1)
                                                ->update(['nota' => $grade, 'porcentajeGanado' => $porcentajeGanado,'estadoFinalizacion' => 0]);                    
                        // return "Estudiante reprobado ".$promedioFinal;
                        return to_route('student.showFailedExtra')->with('estudianteReprobado','El estudiante ha reprobado');                    
                    }


                }catch(Exception $e){
                    return to_route('student.showFailedExtra')->with('errorAgregarCalificacion','Ha ocurrido al agregar la calificación');                    
                }


            }else{
                return view('layout.403');
            }
        }
}
