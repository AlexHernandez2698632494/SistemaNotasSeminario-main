<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Notas;
use App\Models\Grupos;
use App\Models\Materias;
use App\Models\HistorialEstudiante;
use App\Models\Docentes;
use App\Models\Estudiantes;
use App\Models\Evaluacion;

class reporteController extends Controller
{
    //
    public function pdfGrupal(string $groupId){        
        $groupInformation = DB::table('grupo')
                                ->join('materia','grupo.idMateria','=','materia.idMateria')
                                ->join('etapa','materia.idEtapa','=','etapa.idEtapa')
                                ->join('docente','grupo.idDocente','=','docente.idDocente')
                                ->select('docente.nombreDocente','docente.apellidoDocente','materia.nombreMateria','materia.cuatrimestre','etapa.nombreEtapa',DB::raw('YEAR(grupo.anio) as anio'))
                                ->where('grupo.idGrupo','=',$groupId)
                                ->get();

        $evaluations = DB::table('evaluacion')
                            ->where('idGrupo','=',$groupId)
                            ->orderBy('idEvaluacion','ASC')
                            ->get();

        $studentData = DB::table('Estudiante as e')
                    ->join('Nota as n', 'e.idEstudiante', '=', 'n.idEstudiante')
                    ->select('e.idEstudiante', DB::raw("CONCAT(e.apellidoEstudiante, ', ', e.nombreEstudiante) as Estudiante"), 'n.nota', 'n.idEvaluacion','porcentajeGanado')
                    ->where('idGrupo', '=',$groupId)
                    ->orderBy('e.apellidoEstudiante', 'ASC')
                    ->orderBy('n.idEvaluacion', 'ASC')
                    ->get();

        $average = DB::table('nota')
                        ->join('estudiante','nota.idEstudiante','=','estudiante.idEstudiante')
                        ->where('idGrupo','=',$groupId)
                        ->select(DB::raw('ROUND(SUM(porcentajeGanado),1) as porcentajeGanado'),'nota.idEstudiante')
                        ->groupBy('idEstudiante') 
                        ->orderBy('apellidoEstudiante','ASC')   
                        ->get();                    

        $notasEstudiantes = [];

        foreach ($studentData as $dato) {
            $idEstudiante = $dato->idEstudiante;
            $notasEstudiantes[$idEstudiante]['Estudiante'] = $dato->Estudiante;
            $notasEstudiantes[$idEstudiante]['Notas'][] = [
                'idEvaluacion' => $dato->idEvaluacion,
                'nota' => $dato->nota,                
            ];
        }

        
        foreach ($average as $avg) {
            $idEstudiante = $avg->idEstudiante;
            $notasEstudiantes[$idEstudiante]['Promedio'] = $avg->porcentajeGanado;
            
        }

        Config::set('dompdf.options.default_paper_orientation', 'landscape');
        $pdf = Pdf::loadView('pdf.cuadroNotas', compact('evaluations','notasEstudiantes','groupInformation'));
        
        return $pdf->stream();
        // return $notasEstudiantes;
    }
    
    public function pdfIndividual(Request $request , $studentId){
        $anio = $request->input('anio');
        $nombrePrefecto = $request->input('name');

        $studentData = DB::table('Estudiante as e')
        ->join('historialestudiante as he', 'e.idEstudiante', '=', 'he.idEstudiante')
        ->join('materia as m','he.idMateria','=','m.idMateria')
        ->join('etapa as et','m.idEtapa','=','et.idEtapa')
        ->select('e.idEstudiante', DB::raw("CONCAT(e.nombreEstudiante, ', ', e.apellidoEstudiante) as Estudiante"),'et.nombreEtapa','he.anio',
        'm.cuatrimestre','m.nombreMateria','he.convocatoria','he.promedio')
        ->where('e.idEstudiante', '=',$studentId)
        ->where('m.cuatrimestre','=','Cuatrimestre 1')
        ->where('he.anio','=',$anio)
        ->orderBy('e.apellidoEstudiante', 'ASC')
        ->get();  
        
        $studentData2 = DB::table('Estudiante as e')
        ->join('historialestudiante as he', 'e.idEstudiante', '=', 'he.idEstudiante')
        ->join('materia as m','he.idMateria','=','m.idMateria')
        ->join('etapa as et','m.idEtapa','=','et.idEtapa')
        ->select('e.idEstudiante', DB::raw("CONCAT(e.nombreEstudiante, ', ', e.apellidoEstudiante) as Estudiante"),'et.nombreEtapa','he.anio',
        'm.cuatrimestre','m.nombreMateria','he.convocatoria','he.promedio')
        ->where('e.idEstudiante', '=',$studentId)
        ->where('m.cuatrimestre','=','Cuatrimestre 2')
        ->where('he.anio','=',$anio)
        ->orderBy('e.apellidoEstudiante', 'ASC')
        ->get();        

        if($studentData->count()==0 || $studentData2->count()==0){
            return to_route('student.record',$studentId)->with('noData','No se ha encontrado información anual para generar reporte.');
        }else{
            $sum1 = 0;
            $sum2 = 0;
            $cont1 = 0;
            $cont2 = 0;
            foreach($studentData as $dato){
                $sum1 = $sum1 + $dato->promedio;
                $cont1++;
            }
            foreach($studentData2 as $dato){
                $sum2 = $sum2 + $dato->promedio;
                $cont2++;
            }
            $prom1 = round($sum1 / $cont1,1);
            $prom2 = round($sum2 / $cont2,1);
            date_default_timezone_set('America/El_Salvador');
            $dias = date('d');
            $mes = date('m');
            switch($mes){
                case 1: $mes="enero"; break;
                case 2: $mes="febrero"; break;
                case 3: $mes="marzo"; break;
                case 4: $mes="abril"; break;
                case 5: $mes="mayo"; break;
                case 6: $mes="junio"; break;
                case 7: $mes="julio"; break;
                case 8: $mes="agosto"; break;
                case 9: $mes="septiembre"; break;
                case 10: $mes="octubre"; break;
                case 11: $mes="noviembre"; break;
                case 12: $mes="diciembre"; break;
             }
            $anio = date('Y');
            $pdf = Pdf::loadView('pdf.actaCalificacion', compact('studentData','studentData2','prom1','prom2','dias', 'mes', 'anio', 'nombrePrefecto'));
        
       return $pdf->stream();
        }

        

      // return $studentData;

    }
}
