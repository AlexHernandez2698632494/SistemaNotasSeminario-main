<?php

namespace App\Http\Controllers;

use App\Models\Ciclos;
use App\Models\Grupos;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodController extends Controller
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
        date_default_timezone_set('America/El_Salvador');
        $periods = Ciclos::get();
        $availablePeriod = DB::table('ciclo')
                                ->where('estado',0)
                                ->where('fechaInicio','<=', date('Y-m-d'))
                                ->where('fechaFinalizacion', '>=',date('Y-m-d'))
                                ->get();
        return view('period.add', compact('periods','availablePeriod'));}
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
        $validator = Validator::make($request->all(), [
            'nombreCiclo' => ['required','unique:ciclo,nombreCiclo'],            
        ]);

        //Validando que solo exista un ciclo activo
        if($request->input('estadoCiclo') == 1){
            $validator->after(function($validator) use($request){
                $activesPeriod = Ciclos::where('estado',1)->count();
                if($activesPeriod >= 1){
                    $validator->errors()->add('estadoCiclo','Ya existe un ciclo activo, debe finalizar el ciclo activo para iniciar otro');
                }
            });                         
        }

        //Validando fecha de inicio menor que fecha de finalizacion
        $validator->after(function($validator) use($request){
            $starDateValidation = Carbon::parse($request->input('fechaInicio'));
            $endDateValidation = Carbon::parse($request->input('fechaFinalizacion'));

            if($starDateValidation->gt($endDateValidation)){
                $validator->errors()->add('fechaInicio','La fecha de inicio del ciclo no puede ser mayor que la fecha de finalización');
            }
        });

        $startDate = $request->input('fechaInicio');
        $endDate = $request->input('fechaFinalizacion');

        $result = $this->validateDateOutRange('',$startDate, $endDate);
        
        //Verificando que no se actualice la información si las nuevas fechas cubren un ciclo existente
        $validator->after(function($validator) use($result){                                
            if($result >= 1){
                $validator->errors()->add('idCicloActualizar','Ya existe un ciclo que cubra el rango de fechas seleccionado, seleccione otro rango');
            }
        });

        //Validando que las fechas no se encuentren dentro de otro ciclo
        $validator->after(function($validator) use($startDate,$endDate){  
            $resultOR = $this->validateDateOnRange('',$startDate,$endDate) ;                             
            if($resultOR >= 1){
                $validator->errors()->add('idCicloActualizar','Las fechas ingresadas se encuentran contenidas en otro ciclo, seleccione otro rango');
            }
        });

        //Validando que las fechas no pertenezcan a otro periodo
        $validator->after(function($validator) use($startDate,$endDate){  
            $result = $this->validateDateInRange('',$startDate,$endDate) ;                             
            if($result >= 1){
                $validator->errors()->add('idCicloActualizar','Una de las dos fechas seleccionadas pertenecen a otro ciclo, seleccione otro rango');
            }
        });
        
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try{

            $periodName = $request->input('nombreCiclo');
            $startDate = $request->input('fechaInicio');
            $endDate = $request->input('fechaFinalizacion');
            $periodStatus = $request->input('estadoCiclo');            

            $period = new Ciclos();

            $period->nombreCiclo = $periodName;
            $period->fechaInicio = $startDate;
            $period->fechaFinalizacion = $endDate;
            $period->estado = $periodStatus;
                        
            if($period->save()){
                DB::commit();
                return to_route('period.create')->with('exitoAgregar','El ciclo ha sido registrado correctamente');
            }else{
                DB::rollBack();
                return to_route('period.create')->with('errorAgregar','Ha ocurrido un error al registrar la información');
            }                            

        }catch(Exception $e){
            DB::rollBack();
            return to_route('period.create')->with('errorRegistro','Ha ocurrido un error al registrar la información');
        }}else{
            return view('layout.403');
        }
    }

    /**
     * Función para obtener las materias que se imparten en un ciclo
     */
    public function getSubjects(string $periodId)
    {
        if(session()->has('administrador')){
        $subjects = DB::table('materia')
                        ->join('grupo', 'materia.idMateria','=','grupo.idMateria')
                        ->select('materia.nombreMateria')
                        ->where('idCiclo','=',$periodId)          
                        ->groupBy('materia.nombreMateria')              
                        ->get();                       
        return $subjects;}else{
            return view('layout.403');
        }
    }

    public function getSubjectNumber(string $periodId)
    {
        if(session()->has('administrador')){
        $subjectNumber = DB::table('grupo')
                            ->select(DB::raw('COUNT(DISTINCT idMateria) as total'))
                            ->where('idCiclo','=', $periodId)
                            ->first();
        return $subjectNumber->total;       }
        else{
            return view('layout.403');
        }                
    }

    public function getGroupNumber(string $periodId)
    {
        if(session()->has('administrador')){
        $groupNumber = DB::table('grupo')
                            ->select(DB::raw('COUNT(idGrupo) as total'))
                            ->where('idCiclo','=',$periodId)
                            ->first();
        return $groupNumber->total;}
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
        $period = Ciclos::find($id);
        
        if($period != null){        
            $subjects = $this->getSubjects($id);   
            $subjectNumber = $this->getSubjectNumber($id);
            $groupsNumber = $this->getGroupNumber($id);
            return view('period.information', compact('period','subjects','subjectNumber','groupsNumber'));                   
        }else{
            return view('layout.404');
        }  }else{
            return view('layout.403');
        }              
    }

    public function endPeriod(Request $request)
    {
        if(session()->has('administrador')){
        $validator = Validator::make($request->all(), [
            'idCicloFinalizar' => ['required'],            
        ]);
        
        $periodId = $request->input('idCicloFinalizar');
        $period = Ciclos::find($periodId);
        
        
        if($period != null){
            //Validando que el ciclo se encuentre activo
            $validator->after(function($validator) use($period){        
                if($period->estado != 1){
                    $validator->errors()->add('idCicloFinalizar','Para poder finalizar el ciclo, debe de encontrarse activo');
                }
            });      
            
            //Validando que el ciclo haya alcanzado su fecha de finalización
            $validator->after(function($validator) use($period){
                date_default_timezone_set('America/El_Salvador');
                
                $currentDate = Carbon::parse(date('Y-m-d'));
                $endDate = Carbon::parse($period->fechaFinalizacion);
    
                if($currentDate->lt($endDate)){
                    $validator->errors()->add('idCicloFinalizar','El ciclo no se puede finalizar ya que no ha alcanzado su fecha de finalización');
                }
            });

            //Validando que el los grupos del ciclo hayan sido finalizados
            $validator->after(function($validator) use($periodId){               
                
                $groupsPeriod = DB::table('grupo')
                                    ->where('idCiclo','=',$periodId)
                                    ->count();
                $groupFinalized = DB::table('grupo')
                                    ->where('idCiclo','=',$periodId)
                                    ->where('estadoFinalizacion','=',0)
                                    ->count();
                if($groupsPeriod != $groupFinalized){
                    $validator->errors()->add('idCicloFinalizar','El ciclo no se puede finalizar ya que existen grupos de clase sin finalizar');
                }
            });

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $affected = DB::table('ciclo')
                            ->where('idCiclo',$periodId)
                            ->update(['estado' => 2]);
            
            if($affected == 1)
            {
                return to_route('period.information',$periodId)->with('exitoFinalizacion','El ciclo ha sido finalizado correctamente');
            }else{
                return to_route('period.information',$periodId)->with('errorFinalizacion','Ha ocurrido al finalizar ciclo');
            }

        }else{
            return view('layout.404');
        }}else{
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
     * Función para verificar si existe un ciclo que cubra las fechas de inicio y fin
     */
    public function validateDateOutRange($period, $startDate, $endDate)
    {    
        if(session()->has('administrador')){
        if($period != '') 
        {
            //Validacion para actualización
            $result = DB::table('ciclo')
                        ->where('fechaInicio', '>=', $startDate)
                        ->where('fechaFinalizacion' ,'<=', $endDate)
                        ->where('idCiclo','!=', $period->idCiclo)                        
                        ->count();
            return $result;
        }else{
            //Validacion para registro
            $result = DB::table('ciclo')
                        ->where('fechaInicio', '>=', $startDate)
                        ->where('fechaFinalizacion' ,'<=', $endDate)                                               
                        ->count();
            return $result;
        }    }else{
            return view('layout.403');
        }
            
    }

    /**
     * Función para verificar si existe un ciclo dentro del rango establecido
     */
    public function validateDateOnRange($period, $startDate, $endDate)
    {
        if(session()->has('administrador')){
        if($period != '')
        {
            $result = DB::table('ciclo')
                        ->where('fechaInicio', '<=', $startDate)
                        ->where('fechaFinalizacion' ,'>=', $endDate)
                        ->where('idCiclo','!=', $period->idCiclo)                        
                        ->count();
            return $result;
        }else{
            $result = DB::table('ciclo')
                            ->where('fechaInicio', '<=', $startDate)
                            ->where('fechaFinalizacion' ,'>=', $endDate)                                                    
                            ->count();
            return $result;
        }}else{
            return view('layout.403');
        }
            
    }

    public function validateDateInRange($period, $startDate, $endDate)
    {
        if(session()->has('administrador')){
        if($period != '')
        {
            $result = DB::table('ciclo')
                        ->where(function ($query) use ($period,$startDate, $endDate) {
                            $query->where(function ($query) use ($period,$startDate, $endDate) {
                                $query->where('fechaInicio', '>=', $startDate)
                                    ->where('fechaInicio', '<=', $endDate)
                                    ->where('idCiclo','!=', $period->idCiclo);
                            })
                            ->orWhere(function ($query) use ($period,$startDate, $endDate) {
                                $query->where('fechaFinalizacion', '>=', $startDate)
                                    ->where('fechaFinalizacion', '<=', $endDate)
                                    ->where('idCiclo','!=', $period->idCiclo);
                            });
                        })
                        ->count();
            return $result;
        }else{
            $result = DB::table('ciclo')
                        ->where(function ($query) use ($period,$startDate, $endDate) {
                            $query->where(function ($query) use ($period,$startDate, $endDate) {
                                $query->where('fechaInicio', '>=', $startDate)
                                    ->where('fechaInicio', '<=', $endDate);                                   
                            })
                            ->orWhere(function ($query) use ($period,$startDate, $endDate) {
                                $query->where('fechaFinalizacion', '>=', $startDate)
                                    ->where('fechaFinalizacion', '<=', $endDate);                                    
                            });
                        })
                        ->count();
            return $result;
        }}else{
            return view('layout.403');
        }
            
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if(session()->has('administrador')){
        $validator = Validator::make($request->all(), [
            'idCicloActualizar' => ['required'],            
        ]);
        
        $periodId = $request->input('idCicloActualizar');
        $period = Ciclos::find($periodId);
        
        //Verificando que exista el ciclo a actualizar
        if($period != null){      
            
            //>Verificando que el ciclo no se encuentre finalizado
            $validator->after(function($validator) use($period){
                $periodStatus = $period->estado;
    
                if($periodStatus == 2){
                    $validator->errors()->add('idCicloActualizar','El ciclo no se puede actualizar ya que, se encuentra finalizado');
                }
            });

            $startDate = $request->input('fechaInicio');
            $endDate = $request->input('fechaFinalizacion');

            //Validando fecha de inicio menor que fecha de finalizacion
            $validator->after(function($validator) use($request){
                $starDateValidation = Carbon::parse($request->input('fechaInicio'));
                $endDateValidation = Carbon::parse($request->input('fechaFinalizacion'));
    
                if($starDateValidation->gt($endDateValidation)){
                    $validator->errors()->add('fechaInicio','La fecha de inicio del ciclo no puede ser mayor que la fecha de finalización');
                }
            });

            $result = $this->validateDateOutRange($period,$startDate, $endDate);
        
            //Verificando que no se actualice la información si las nuevas fechas cubren un ciclo existente
            $validator->after(function($validator) use($result){                                
                if($result >= 1){
                    $validator->errors()->add('idCicloActualizar','Ya existe un ciclo que cubra el rango de fechas seleccionado, seleccione otro rango');
                }
            });

            //Validando que las fechas no se encuentren dentro de otro ciclo
            $validator->after(function($validator) use($period, $startDate,$endDate){  
                $resultOR = $this->validateDateOnRange($period,$startDate,$endDate) ;                             
                if($resultOR >= 1){
                    $validator->errors()->add('idCicloActualizar','Las fechas ingresadas se encuentran contenidas en otro ciclo, seleccione otro rango');
                }
            });

            //Validando que las fechas no pertenezcan a otro periodo
            $validator->after(function($validator) use($period, $startDate,$endDate){  
                $result = $this->validateDateInRange($period,$startDate,$endDate) ;                             
                if($result >= 1){
                    $validator->errors()->add('idCicloActualizar','Una de las dos fechas seleccionadas pertenecen a otro ciclo, seleccione otro rango');
                }
            });
            

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }


            try{
                $periodName = $request->input('nombreCiclo');
                $startDate = $request->input('fechaInicio');
                $endDate = $request->input('fechaFinalizacion');
                
                $affected = DB::table('ciclo')
                                ->where('idCiclo',$periodId)
                                ->update(['nombreCiclo' => $periodName, 'fechaInicio' => $startDate,'fechaFinalizacion' => $endDate]);
                return redirect()->route('period.information',$periodId)->with('exitoActualizar','Se ha actualizado correctamente el ciclo');
            }catch(Exception $e){
                return to_route('period.information',$periodId)->with('errorActualizar','Ha ocurrido un error al actualizar información del ciclo');
            }
                
            
        }else{
            return view('layout.404');
        }}else{
            return view('layout.403');
        }
    }
    

    public function startPeriod(Request $request)
    {
        if(session()->has('administrador')){
        $validator = Validator::make($request->all(), [
            'idCicloIniciar' => ['required'],            
        ]);
        
        $periodId = $request->input('idCicloIniciar');
        $period = Ciclos::find($periodId);

        if($period != null)
        {
            //Validación para verificar si existe otro ciclo iniciado
            $validator->after(function($validator){                                
                $startedPeriods = Ciclos::where('estado',1)->count();

                if($startedPeriods >= 1)
                {
                    $validator->errors()->add('idCicloIniciar','Ya existe un ciclo iniciado, finalice el ciclo activo para iniciar uno nuevo');
                }
            });

            //$periodInfo = Ciclos::find($periodId);
            //Validación para verificar que se haya alcanzado la fecha de inicio
            $validator->after(function($validator) use($period){   
                date_default_timezone_set('America/El_Salvador');                                                
                
                $currentDate = Carbon::parse(date('Y-m-d'));    
                $sd = Carbon::parse($period->fechaInicio);
                
                if($currentDate->lt($sd)){
                    $validator->errors()->add('idCicloIniciar','El ciclo que desea iniciar no ha alcanzado su fecha de inicio');
                }
            });

            //Validación para verificar que no se haya superado su fecha de finalización
            $validator->after(function($validator) use($period){   
                date_default_timezone_set('America/El_Salvador');                                                
                
                $currentDate = Carbon::parse(date('Y-m-d'));    
                $ed = Carbon::parse($period->fechaFinalizacion);
                
                if($currentDate->gt($ed)){
                    $validator->errors()->add('idCicloIniciar','El ciclo que desea iniciar ha superado su fecha de finalización');
                }
            });

            $validator->after(function($validator) use($period){   
            
                $periodStatus = $period->estado;    
                if($periodStatus != 0){
                    if($periodStatus == 1){
                        $validator->errors()->add('idCicloIniciar','El ciclo que desea iniciar ya se encuentra iniciado');
                    }

                    if($periodStatus == 2){
                        $validator->errors()->add('idCicloIniciar','El ciclo que desea iniciar ya ha finalizado ');
                    }
                    
                }
            });

            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            try{
                $affected = DB::table('ciclo')
                ->where('idCiclo',$periodId)
                ->update(['estado' => 1]);

                if($affected == 1){
                    return to_route('period.information',$periodId)->with('exitoIniciar','El ciclo se ha iniciado correctamente');
                }else{
                    return to_route('period.information',$periodId)->with('errorIniciar','Ha ocurrido un errror al iniciar ciclo');
                }
            }catch(Exception $e){
                return to_route('period.information',$periodId)->with('errorIniciar','Ha ocurrido un errror al iniciar ciclo');
            }                
        }else{
            return view('layout.404');
        }}else{
            return view('layout.403');
        }
            


    }

    public function showGroups(string $id)
    {
        if(session()->has('administrador')){
        $period = Ciclos::find($id);

        if($period !=  null){
            $groups = DB::table('grupo')
                        ->join('materia','grupo.idMateria','=','materia.idMateria')
                        ->join('docente','docente.idDocente','=','grupo.idDocente')
                        ->where('idCiclo',$id)
                        ->get();
            return view('group.periodGroups', compact('groups','period'));            
        }else{
            return view('layout.404');
        }}else{
            return view('layout.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
