<?php

namespace App\Http\Controllers;

use App\Models\Materias;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(session()->has('administrador')){
            $phases = DB::table('etapa')->get();
        $subjects = DB::table('materia')
                            ->join('etapa','materia.idEtapa','=','etapa.idEtapa')
                            ->where('materia.estadoEliminacion','=',1)                            
                            ->get();
        return view('subject.index', compact('phases','subjects'));}
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
        $phases = DB::table('etapa')->get();
        $subjects = DB::table('materia')
                            ->join('etapa','materia.idEtapa','=','etapa.idEtapa')
                            ->where('materia.estadoEliminacion','=',1)                            
                            ->get();
        return view('subject.add', compact('phases','subjects'));}
        else{
            return view('layout.403');
        }
    }

    public function getSubjectLevel($phase,$year,$period)
    {        
        if(session()->has('administrador')){
        $levels = [
            1 => 'E1A1Cuatrimestre 1',
            2 => 'E1A1Cuatrimestre 2',
            3 => 'E2A2Cuatrimestre 1',
            4 => 'E2A2Cuatrimestre 2',
            5 => 'E2A3Cuatrimestre 1',
            6 => 'E2A3Cuatrimestre 2',
            7 => 'E3A4Cuatrimestre 1',
            8 => 'E3A4Cuatrimestre 2',
            9 => 'E4A5Cuatrimestre 1',
            10 => 'E4A5Cuatrimestre 2',
            11 => 'E4A6Cuatrimestre 1',
            12 => 'E4A6Cuatrimestre 2',
            13 => 'E4A7Cuatrimestre 1',
            14 => 'E4A7Cuatrimestre 2',
            
            
            
        ];

        $phaseName = "E".$phase."A".$year.$period;
        
        $level = array_search($phaseName,$levels);
        
        return $level;}
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
        $request->validate([
            'nombreMateria' => ['required'],
            'seleccionGrado' => ['required', 'min:1'],
            'anioCarrera' => ['required'],
            'Cuatrimestre' => ['required']
        ]);

        $subject = new Materias();

        $subject->nombreMateria = $request->input('nombreMateria');
        $subject->idEtapa = $request->input('seleccionGrado');
        $subject->anio = $request->input('anioCarrera');
        $subject->cuatrimestre = $request->input('Cuatrimestre');

        $level = $this->getSubjectLevel($request->input('seleccionGrado'),$request->input('anioCarrera'),$request->input('Cuatrimestre'));       
        
        $subject->nivel = $level;
        try{

            if($subject->save()){
                return to_route('subject.create')->with('exitoAgregar','Materia registrada correctamente');
            }else{
                return to_route('subject.create')->with('errorAgregar','Ha ocurrido un error al asignar materia');
            }

        }catch(Exception $e){
            return to_route('subject.create')->with('errorAgregar','Ha ocurrido un error al asignar materia');

        }}else{
            return view('layout.403');
        }

        // return $request->input('nivelAsignatura');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            try{
                $id=$request->input('idMateria');
                $nombreMateria = $request->input('nombreMateria');
                $etapa = $request->input('etapa');
                $anio = $request->input('anio');
                $cuatrimestre = $request->input('cuatrimestre');
                $nivel = $this->getSubjectLevel($request->input('etapa'),$request->input('anio'),$request->input('cuatrimestre'));       
                
                $affected = DB::table('materia')
                                ->where('materia.idMateria','=',$id)
                                ->update(['nombreMateria' => $nombreMateria, 'idEtapa' => $etapa, 'anio' => $anio, 'cuatrimestre' => $cuatrimestre, 'nivel' => $nivel]);
                return to_route('subject.index')->with('exitoActualizar','Se ha actualizado correctamente la materia');
            }catch(Exception $e){
                return to_route('subject.index')->with('errorActualizar','Ha ocurrido un error al modificar la materia');
    
            }}else{
                return view('layout.403');
            }
    }

    public function getPhaseDuration(string $id)
    {
        if(session()->has('administrador')){
        $duration = DB::table('etapa')
                    ->where('idEtapa',$id)
                    ->get();
        return $duration;}
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
            try{
                $id=$request->input('idMateria');
                DB::table('materia')->where('idMateria','=',$id)->update(['estadoEliminacion' => 0]);
                return to_route('subject.index')->with('exitoEliminar','Se ha eliminado correctamente la materia');
            }catch(Exception $e){
                return to_route('subject.index')->with('errorEliminar','Ha ocurrido un error al eliminar la materia');
    
            }
            
        } else{
            return view('layout.403');
        }
    }

    public function getMateria(string $id){
        if(session()->has('administrador')){
            $materia = DB::table('materia')->where('materia.idMateria','=',$id)->get();
		    return $materia[0];
        }
        else {
            return view('layout.403');
        }
    }

    public function indexEliminadas()
    {
        if(session()->has('administrador')){
        $subjects = DB::table('materia')
                            ->join('etapa','materia.idEtapa','=','etapa.idEtapa')
                            ->where('materia.estadoEliminacion','=',0)                            
                            ->get();
        return view('subject.indexEliminadas', compact('subjects'));}
        else{
            return view('layout.403');
        }
    }

    public function restore(Request $request)
    {
        if(session()->has('administrador')){
            try{
                $id=$request->input('idMateria');
                DB::table('materia')->where('idMateria','=',$id)->update(['estadoEliminacion' => 1]);
                return to_route('subject.indexEliminadas')->with('exitoRestaurar','Se ha restaurado correctamente la materia');
            }catch(Exception $e){
                return to_route('subject.indexEliminadas')->with('errorRestaurar','Ha ocurrido un error al restaurar la materia');
    
            }
            
        } else{
            return view('layout.403');
        }
    }
}
