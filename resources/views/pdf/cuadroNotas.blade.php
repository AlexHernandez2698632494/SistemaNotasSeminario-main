<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css\stylesReporteDocente.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Cuadro de Notas</title>
    <style>
        @page{
            margin: 1.5cm 2cm 2.5cm 2cm;
        }
    </style>
</head>

<body>
    <div class="encabezado">
        <div class="parteEncabezado"><img class="img" width="100" src="img\imagen1.png" alt=""></div>
        <div class="parteEncabezado">
            <div class="tituloEncabezado">
                <h2>SEMINARIO MAYOR «PÍO XII»<br><span id="subtitulo">DIÓCESIS DE SAN VICENTE,<br>EL SALVADOR,
                        C.A.</span></h2>
            </div>
        </div>
        <div class="parteEncabezado"><img class="img" width="100" src="img\imagen2.jpg" alt=""></div>
    </div>
    <div class="cuerpoReporte" style="line-height:0.5em">
        <div class="cuerpoReporteTablePrimary">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="text-center" scope="col" colspan="5" >Cuadro de Notas</th>
                    </tr>
                    <tr>
                        <th colspan="2">Grado: {{ $groupInformation[0]->nombreEtapa }} </th>
                        <th colspan="2"><b>Periodo:</b> {{ $groupInformation[0]->cuatrimestre }}</th>
                        <th colspan="1">Año: {{ $groupInformation[0]->anio }}</th>
                    </tr>
                    <tr>
                        <th colspan="5">Materia: {{ $groupInformation[0]->nombreMateria }}</th>
                    </tr>
                    <tr>
                        <th colspan="5">Profesor: {{ $groupInformation[0]->nombreDocente }}
                            {{ $groupInformation[0]->apellidoDocente }}</th>
                    </tr>

                </thead>
            </table>
            <table class="table table-bordered">
                <tbody>
                    <tr class="text-center">
                        <td></td>
                        @foreach ($evaluations as $evaluation)
                            <td><b>{{ $evaluation->porcentaje }}%</b></td>
                        @endforeach
                        <td><b>100%</b></td>
                    </tr>
                    <tr class="text-center">
                        <td><b>ALUMNO</b></td>
                        @foreach ($evaluations as $evaluation)
                            <td><b>NOTA {{ $loop->iteration }}</b></td>
                            {{-- <td>Actividad {{ $evaluation->nombreEvaluacion }}</td>                             --}}
                        @endforeach
                        <td><b>Promedio</b></td>
                    </tr>
                    @foreach ($notasEstudiantes as $idEstudiante => $estudiante)
                        <tr class="text-center">
                            <td>{{ $estudiante['Estudiante'] }}</td>
                            @foreach ($estudiante['Notas'] as $nota)
                                <td>{{ $nota['nota'] }}</td>
                            @endforeach
                            <td>{{ $estudiante['Promedio'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="footer" style="position: fixed; bottom:-2.5cm; left: 20%; text-align: center;">
        <br><br><br><p class="text-center" style="font-size: 11pt"><b>FIRMA DEL MAESTRO</b> ____________________________</p>
        <p class="text-center" style="font-size: 9pt">Seminario Mayor Pío XII – Final 1ª C. Oriente y 10ª Av. Norte, Bo. El Santuario, San Vicente, El Salvador
            C.A.
            <br>Tel. (+503) 2393 0184
        </p>
    </div>

</body>

</html>
