<?php

namespace App\Http\Controllers;

use App\Models\Docentes;
use App\Models\Materias;
use App\Models\MateriasDocente;
use App\Models\TitulosDocente;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr;
use App\Mail\Credentials;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		if(session()->has('administrador')){
		$teachers = Docentes::where('estadoEliminacion',"=","1")->get();
        return view('teacher.index', compact('teachers'));
		} else{
			return view('layout.403');
		}
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
		if(session()->has('administrador')){
        $materias = Materias::all();
        return view('teacher.add', compact('materias'));
		}
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

		$user = strtolower($nameElements[0].'.'.$lastNameElements[0]);

		$counter = 2;
		
		do{
			$userVerification = DB::table('usuario')
									->where('usuario', $user)
									->exists();

			if($userVerification){
				$user = strtolower($nameElements[0].'.'.$lastNameElements[0].$counter);
				$counter++;
			}

		}while($userVerification);

		return $user;
		}
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
		else{
			return view('layout.403');
		}
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {	
		if(session()->has('administrador')){
			//Validando los datos que se reciben del request
			$validator = Validator::make($request->all(), [
				'nombreDocente' => ['required'],
				'apellidoDocente' => ['required'],
				'duiDocente' => ['required', 'regex:/^[0-9]{8}-[0-9]{1}$/', 'unique:docente,duiDocente'],
				'correoDocente' => ['required', 'email', 'unique:docente,correoDocente'],
				'telefonoDocente' => ['required', 'regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/', 'unique:docente,numeroTelefono'],				
			],[
				'duiDocente.regex' => 'Formato incorrecto de DUI',
				'telefonoDocente.regex' => 'Formato incorrecto de teléfono',
			]);

			//Validación que se realiza despues de las validaciones superiores
			//Sirve para verificar que se haya seleccionado al menos una materia
			$validator->after(function ($validator) use ($request)
			{
				$selectedCheckboxes = $request->input('materias');
				if (empty($selectedCheckboxes)) {
					$validator->errors()->add('materias', 'Debe seleccionar al menos una materia.');
				}
			});

			if ($validator->fails())
			{
				// La validación ha fallado
				return redirect()->back()->withErrors($validator)->withInput();
			}

			//return redirect()->back()->withSuccess('Datos válidos');
		
		DB::beginTransaction();
		try{
			//Ingresando información general del docente y obteniendo su id
			$teacherId = DB::table('docente')->insertGetId([
				'nombreDocente' => $request->input('nombreDocente'),
				'apellidoDocente' => $request->input('apellidoDocente'),
				'duiDocente' => $request->input('duiDocente'),
				'numeroTelefono' => $request->input('telefonoDocente'),
				'correoDocente' => $request->input('correoDocente'),
				'estadoEliminacion' => 1,
			]);

			//Ingresando las materias que el docente puede impartir
			$subjects = $request->input('materias');
			foreach($subjects as $subject){
				$subjectDetail = new MateriasDocente();
				$subjectDetail->idDocente = $teacherId;
				$subjectDetail->idMateria = $subject;

				$subjectDetail->save();
			}

			//Ingresando titulos del docente
			$teacherTitlesList = $request->input('titulosDocente')??'No se ha añadido';
			$teacherTitles = explode(",",$teacherTitlesList);

			foreach($teacherTitles as $teacherTitle)
			{
				$title = new TitulosDocente();
				$title->idDocente = $teacherId;
				$title->tituloDocente = $teacherTitle;

				$title->save();
			}

			$teacherEmail = $request->input('correoDocente');
			$teacherName = $request->input('nombreDocente').' '.$request->input('apellidoDocente');

			$userName = $this->generateUser($request->input('nombreDocente'),$request->input('apellidoDocente'));
			$pass = $this->generatePass();

			$userObj = new Usuarios();

			$userObj->idUsuario = $request->input('duiDocente');
			$userObj->usuario = $userName;
			// $userObj->password = Hash::make($pass);
			$userObj->password = hash('SHA256',$pass);
			$userObj->nivel = 1;

			$userObj->save();
			
			/**$email = new Credentials($userName, $pass, $teacherName);

			Mail::to($teacherEmail)->send($email);*/
			$pdfData = [
				'teacherName' => $teacherName,
				'userName' => $userName,
				'pass' => $pass,
			];
	
			$pdf = PDF::loadView('pdf.docente', $pdfData);
			$pdf->save(public_path('pdf/docente.pdf'));
			DB::commit();
			return to_route('teachers.create')->with('exitoRegistro', 'Docente y materias asignadas correctamente.');

			// if($user->save()){
				
				
			// }else{
			// 	return to_route('teachers.create')->with('errorRegistro', 'Ha ocurrido un error al registar el docentezxcxzc, contactese con el administrador');			
			// }

			
		}catch(Exception $e){
			DB::rollback();
			return to_route('teachers.create')->with('errorRegistro', 'Ha ocurrido un error al registar el docente, contactese con el administrador'.$e->getMessage());			
		}}
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
		$teacherInfo = Docentes::find($id);
		if($teacherInfo != null)
		{
			$teacherSubjects = $this->getTeacherSubjectsById($id);
			$teacherTitles = $this->getTeacherTitlesById($id);

			return view('teacher.teacherInformation', compact('teacherInfo','teacherSubjects','teacherTitles'));
		}else{
			return view('layout.404');
		}}
        else{
            return view('layout.403');
        } 

    }

	public function getTeacherSubjectsById(string $id)
	{
		if(session()->has('administrador')){
		$teacherSubjects = DB::table('materia')
								->join('materiasdocente','materia.idMateria','=','materiasdocente.idMateria')
								->join('etapa','materia.idEtapa','=','etapa.idEtapa')
								->where('materiasdocente.idDocente','=', $id)
								->orderBy('nivel','asc')
								->get();
		return $teacherSubjects;}
        else{
            return view('layout.403');
        } 

	}

	public function getTeacherTitlesById(string $id)
	{
		if(session()->has('administrador')){
		$teacherTitles = DB::table('titulosDocente')
								->where('idDocente','=',$id)
								->orderBy('tituloDocente','desc')
								->get();
		return $teacherTitles;}
        else{
            return view('layout.403');
        } 
	}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
		if(session()->has('administrador')){
		$teacher = Docentes::find($id);
		if($teacher != null){
			$teacherSubjects = $this->getTeacherSubjectsById($id);
			$teacherTitles = $this->getTeacherTitlesById($id);
			$subjectsAvailable = DB::table('materia')
						->join('etapa','materia.idEtapa','=','etapa.idEtapa')
						->select('idMateria', 'nombreMateria', 'nivel','nombreEtapa','anio','cuatrimestre')
						->whereNotExists(function ($subquery) use($id){
							$subquery->select('idMateria')
								->from('materiasdocente')
								->whereColumn('materia.idMateria', 'materiasdocente.idMateria')
								->where('idDocente', $id);
						})
						->orderBy('nivel','asc')
						->get();
			return view('teacher.update', compact('teacher', 'teacherSubjects','teacherTitles', 'subjectsAvailable'));
		}else{
			return view('layout.404');
		}}
        else{
            return view('layout.403');
        } 

    }

	/**
	 * Agregar nueva materia que el docente puede impartir
	 */
	public function addTeacherSubject(Request $request)
	{
		if(session()->has('administrador')){
		$request->validate([
			'teacherId' => 'required',
		]);

        $subject = new MateriasDocente();
        
        $subject->idDocente = $request->input('teacherId');
        $subject->idMateria = $request->input('newSubject');
		
		try{
			if($subject->save())
			{
				return to_route('teachers.edit',$request->input('teacherId'))->with('exitoAgregar','La materia fue registrada correctamente');
			}else{
				return to_route('teachers.edit',$request->input('teacherId'))->with('errorAgregar','Error al registrar la materia');
			}
		}catch(Exception $e){
			return to_route('teachers.edit',$request->input('teacherId'))->with('errorAgregar','Error al registrar la materia');
		}}
        else{
            return view('layout.403');
        } 
			
	}


	/**
	 * Agregando título al docente
	 */
	public function addTeacherTitle(Request $request)
	{
		if(session()->has('administrador')){
			$request->validate([
				'tituloDocente' => 'required'
			]);

			$title = new TitulosDocente();

			$title->idDocente = $request->input('teacherId');
			$title->tituloDocente = $request->input('tituloDocente');
		
		try{
			if($title->save())
			{
				return to_route('teachers.edit',$request->input('teacherId'))->with('exitoAgregar','Título del docente registrado correctamente');
			}
		}catch(Exception $e){
			return to_route('teachers.edit',$request->input('teacherId'))->with('errorAgregar','Ha ocurrido un error al agregar el titulo del docente');
		}}
        else{
            return view('layout.403');
        } 
			
	}

	public function deleteTeacherSubject(Request $request)
	{
		if(session()->has('administrador')){
			$detailId = $request->input('idDetalleEliminar');

			if($detailId != null)
			{
				try{
					$deleted = DB::table('materiasdocente')
									->where('idDetalle','=',$detailId)
									->delete();
					
					if($deleted == 1)
					{
						return to_route('teachers.edit',$request->input('teacherId'))->with('exitoEliminacion','Se ha eliminado la materia que el docente puede impartir');
					}else{
						return to_route('teachers.edit',$request->input('teacherId'))->with('errorEliminacion','Ha ocurrido un error al eliminar registro');
					}		

				}catch(Exception $e){
					return to_route('teachers.edit',$request->input('teacherId'))->with('errorEliminacion','Ha ocurrido un error al eliminar registro');
				}					
			}else{
				return to_route('teachers.edit',$request->input('teacherId'))->with('errorEliminacion','No se ha brindado un id para eliminar');

			}}
			else{
				return view('layout.403');
			} 					
	
	}

	public function deleteTeacherTitle(Request $request)
	{
		if(session()->has('administrador')){
		try{
			$detailId = $request->input('idDetalleTituloEliminar');

			if($detailId != null)
			{
				$deleted = DB::table('titulosdocente')
								->where('idDetalleTitulo','=',$detailId)
								->delete();
				
				if($deleted == 1)
				{
					return to_route('teachers.edit',$request->input('teacherIdTitle'))->with('exitoEliminacion','Se ha eliminado el título del docente');
				}else{
					return to_route('teachers.edit',$request->input('teacherIdTitle'))->with('errorEliminacion','Ha ocurrido un error al eliminar registro');
				}							
			}else{
				return to_route('teachers.edit',$request->input('teacherIdTitle'))->with('errorEliminacion','No se ha brindado un id para eliminar');				
			}
			
		}catch(Exception $e){
			return to_route('teachers.edit',$request->input('teacherIdTitle'))->with('errorEliminacion','Ha ocurrido un error al eliminar registro');
		}}
        else{
            return view('layout.403');
        } 
	}

	public function getTeacher()
	{

	}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {	if(session()->has('administrador')){
		$teacherId = $request->input('idDocenteActualizar');
        $request->validate([
			'nombreDocente' => ['required'],
			'apellidoDocente' => ['required'],
			'duiDocente' => ['required','regex:/^[0-9]{8}-[0-9]{1}$/',Rule::unique('docente', 'duiDocente')->ignore($teacherId, 'idDocente')],
			'correoDocente' => ['required','email',Rule::unique('docente', 'correoDocente')->ignore($teacherId, 'idDocente')],
			'telefonoDocente' => ['required','regex:/^([2,6,7][0-9]{3})(-)([0-9]{4})$/',Rule::unique('docente', 'numeroTelefono')->ignore($teacherId, 'idDocente')],
		],[
			'duiDocente.regex' => 'Formato incorrecto de DUI',
			'telefonoDocente.regex' => 'Formato incorrecto de teléfono',
		]);

		$teacherName = $request->input('nombreDocente');
		$teacherLastName = $request->input('apellidoDocente');
		$teacherDui = $request->input('duiDocente');
		$teacherEmail = $request->input('correoDocente');
		$teacherPhone = $request->input('telefonoDocente');

		try{
            $affected = DB::table('docente')
            ->where('idDocente', '=', $teacherId)
            ->update([
                'nombreDocente' => $teacherName,
                'apellidoDocente' => $teacherLastName,
                'duiDocente' => $teacherDui,
                'correoDocente' => $teacherEmail,
                'numeroTelefono' => $teacherPhone,              
            ]);        

            return to_route('teachers.edit',$teacherId)->with('exitoModificar','La información del docente ha sido actualizada correctamente');
        }catch(QueryException $e){
            return to_route('teachers.edit',$teacherId)->with('errorModificar','Ha ocurrido un error al modificar el registro');
        } }
        else{
            return view('layout.403');
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {	if(session()->has('administrador')){
		try{
			
			$request->validate([
				'idDocenteEliminar' => 'required'
			]);

			$teacherId = $request->input('idDocenteEliminar');

			$rowAffected = DB::table('docente')
							->where('idDocente', $teacherId)
							->update(['estadoEliminacion' => 0]);

			if($rowAffected == 1)
			{
				return to_route('teachers.index')->with('exitoEliminacion','El docente ha sido eliminado correctamente');
			}else{
				return to_route('teachers.index')->with('errorEliminacion','Ha ocurrido un error al eliminar el docente');
			}

		}catch(Exception $e){
			return to_route('teachers.index')->with('errorEliminacion','Ha ocurrido un error al eliminar el docente');
		}	}
        else{
            return view('layout.403');
        } 		
    }


	/**
	 * Función para obtener la información de un docente
	 */
	public function getTeacherInfo(string $id)
	{
		if(session()->has('administrador')){
		$teacher = Docentes::find($id);
		return $teacher;}
        else{
            return view('layout.403');
        } 		
	}

	/**
	 * Función para mostrar la vista de restaurar docente
	 */

	public function restoreView()
	{
		if(session()->has('administrador')){
		$removedTeachers = Docentes::where('estadoEliminacion','=',0)->get();
		return view('teacher.removed',compact('removedTeachers'));}
        else{
            return view('layout.403');
        } }

	/**
	 * Función para restaurar docente
	 */
	public function restore(Request $request)
	{
		if(session()->has('administrador')){
		$request->validate([
			'idDocenteRestaurar' => ['required','integer']
		]);

		$restoreTeacherId = $request->input('idDocenteRestaurar');

		if($restoreTeacherId != null){
			try{
				$affected = DB::table('docente')
				->where('idDocente', $restoreTeacherId)
				->update(['estadoEliminacion' => 1]);
	
				if($affected == 1){
					return to_route('teacher.restoreView')->with('exitoRestaurar','El docente se ha restaurado correctamente');
				}else{
					return to_route('teacher.restoreView')->with('errorRestaurar','Ha ocurrido un error al restaurar el docente');
				}
			}catch(Exception $e){
				return to_route('teacher.restoreView')->with('errorRestaurar','Ha ocurrido un error al restaurar el docente');
			}				
		}else{
			return to_route('teacher.restoreView')->with('errorRestaurar','Debe de seleccionar un docente para restaurar');
		}
	}
	else{
		return view('layout.403');
	} }
}
