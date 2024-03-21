<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css\stylesReporteDocente.css">
    <link rel="stylesheet" href="css/styleReporteIndividual.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Cuadro de Notas</title>
    <style>
        @page{
            margin: 3cm 2cm 0cm 2cm;
        }
        .encabezado{
            position: fixed; top: -2cm; left: 12%;
        }
    </style>
</head>

<body>
    <div class="encabezado">
        <div class="parteEncabezado"><img class="img" width="75" src="img\imagen1.png" alt=""></div>
        <div class="parteEncabezado">
            <div class="tituloEncabezado">
                <h2 style="font-size: 16pt">SEMINARIO MAYOR «PÍO XII»<br><span id="subtitulo" style="font-size: 8pt">DIÓCESIS DE SAN VICENTE,<br>EL SALVADOR,
                        C.A.</span></h2>
            </div>
        </div>
        <div class="parteEncabezado"><img class="img" width="75" src="img\imagen2.jpg" alt=""></div>
    </div>

    <div class="cuerpoReporte texto-pequeno" style="font-size: 10pt">
        <div class="tituloCuerpo">
            <p style="font-size: 11pt"><b>ACTA DE CALIFICACIONES</b></p>
        </div>
        <div class="detalle text-center">El seminario Mayor «Pío XII» de la Diócesis de San Vicente, hace constar en la
            presente
            ACTA DE CALIFICACIONES los siguientes promedios de:</div>
        <div class="cuerpoReporteTablePrimary" style="line-height:0.5em">
            @if (!empty($studentData[0]))
                <table class="table table-bordered">
                    <thead style="font-size: 9pt; line-height:0.2em">
                        <tr>
                            <th scope="col" colspan="2"><b>Seminarista:</b> {{ $studentData[0]->Estudiante }}</th>
                            <th scope="col" colspan="1"><b>Grado: </b> {{ $studentData[0]->nombreEtapa }}</th>
                        </tr>
                        <tr>
                            <th><b>Año: </b> {{ $studentData[0]->anio }}</th>
                            <th><b>Ciclo Anual: </b></th>
                            <th><b>Etapa: </b>Discipular</th>
                        </tr>
                    </thead>
                </table>
            
                <table class="table table-bordered" style="font-size: 8pt;line-height:0em">
                    <thead>
                        <tr>
                            <th scope="col">{{ $studentData[0]->cuatrimestre }}</th>
                        </tr>
                        <tr>
                            <th><b>ASIGNATURA</b></th>
                            <th style="text-align: center"><b>CONVOCATORIA</b></th>
                            <th style="text-align: center"><b>PROMEDIO</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentData as $studentInformation)
                            <tr>
                                <td>{{ $studentInformation->nombreMateria }}</td>
                                <td style="text-align: center">{{ $studentInformation->convocatoria }}</td>
                                <td style="text-align: center; font-weight:bold">{{ $studentInformation->promedio }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="text-align: center; font-weight:bold">
                        <td colspan="2">MEDIA DEL CUATRIMESTRE</td>
                        <td>{{$prom1}}</td>
                    </tfoot>
                </table>
            @else
                
            @endif                
            @if (!empty($studentData2[0]))
                <table class="table table-bordered" style="font-size: 8pt;line-height:0em">
                    <thead>
                        <tr>
                            <th scope="col">{{ $studentData2[0]->cuatrimestre }}</th>
                        </tr>
                        <tr>
                            <th><b>ASIGNATURA</b></th>
                            <th style="text-align: center"><b>CONVOCATORIA</b></th>
                            <th style="text-align: center"><b>PROMEDIO</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentData2 as $studentInformation)
                            <tr>
                                <td>{{ $studentInformation->nombreMateria }}</td>
                                <td style="text-align: center">{{ $studentInformation->convocatoria }}</td>
                                <td style="text-align: center; font-weight:bold">{{ $studentInformation->promedio }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="text-align: center; font-weight:bold">
                        <td colspan="2">MEDIA DEL CUATRIMESTRE</td>
                        <td>{{$prom2}}</td>
                    </tfoot>
                </table>
            @else
                
            @endif                
            
        </div>
    </div>
    <div class="footer texto-pequeno" style="font-size: 10pt; text-align:justify">
        <p>Da fe que las anteriores calificaciones son fieles a los Cuadros de Notas originales entregadas por los
            docentes de cada asignatura, en la ciudad de San Vicente, a los {{$dias}} días del mes de {{$mes}} del año
            {{$anio}}: </p> <br><br>       
            <p class="text-right"><span style="font-style: italic">{{$nombrePrefecto}}</span> <br>Prefecto de estudios</p>

    </div>

</body>

</html>
