@extends('layout.header')


@section('title','Asignacion de notas')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/grades/initGradeAdd.js') }}"></script>
<body style="overflow-x: hidden">    
    
    <script src="{{ asset('js/inactividad.js') }}"></script>

    @include('layout.horizontalMenu')    
    <div class="wrapper">
        @include('layout.verticalMenuTeacher')
        <div id="content" class="mt-0 pt-0">             
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('teacherSite.showEvaluations',$evaluationInfo[0]->idGrupo) }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Asignación de calificaciones</p>
                    </div>
                </div>
            </nav>                  
            <div class="row mx-5 my-1">  
                @if (!empty($evaluationInfo[0]))
                    <div class="card">
                        <div class="card-body">
                            <b>Evaluacion seleccionada:</b> {{ $evaluationInfo[0]->nombreEvaluacion }}<br>
                            <b>Porcentaje:</b> {{ $evaluationInfo[0]->porcentaje }}%<br>
                            <b>Grupo de clase de la actividad:</b> {{ $evaluationInfo[0]->nombreMateria }} ({{ $evaluationInfo[0]->nombreGrupo }})
                        </div>
                    </div>
                    <p class="d-flex mt-3 justify-content-center"><b>Listado de estudiantes</b></p>	
                    @if ($errors->any())
                        <div class="alert alert-danger my-2 pb-0">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('teacherSite.storeGrades') }}">	
                        @csrf			
                        <input type="hidden" name="grupo" value="{{ $evaluationInfo[0]->idGrupo }}" required>
                        <input type="hidden" name="evaluacion" value="{{ $evaluationInfo[0]->idEvaluacion }}" required>
                        <input type="hidden" name="porcentaje" value="{{ $evaluationInfo[0]->porcentaje }}" required>
                        <table class="table">
                            <thead >
                                <tr>                                
                                    <th scope="col" style="background-color: #7386d5; color:white">Estudiantes</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Calificación</th>                                
                                </tr>
                            </thead>
                            <tbody>                              
                                    @if (count($students) > 0)
                                        @foreach ($students as $student)
                                            <tr>                                        
                                                <td>{{ $student->apellidoEstudiante }}, {{ $student->nombreEstudiante }}</td>                                                                           
                                                <td>
                                                    <input type="text" name="calificacion[]" maxlength="3" placeholder="Ingrese calificación" class="form-control inputTxt" id="txtNota" required>                                    
                                                    <input type="hidden" name="estudiante[]" value="{{ $student->idEstudiante }}" required>                                                                                                                            
                                                </td>                                                                           
                                            </tr>  
                                        @endforeach  
                                    @else
                                        <div class="alert alert-warning" role="alert">
                                            No se han encontrado estudiantes en este grupo
                                        </div>
                                    @endif                                                                                                                                                                                                         
                            </tbody>
                        </table> 
                        <div class="text-center">
                            <button class="btn btn-primary mx-auto" type="submit" style="background-color: #7386d5">Registrar calificaciones</button>  
                        </div>                                              
                    </form> 
                @else
                    <div class="alert alert-warning" role="alert">
                        No se han encontrado información
                    </div>
                @endif                                                     
            </div>            							
        </div>
    </div>	
    <script src="{{ asset('js/students/validarNota.js') }}" defer></script>
</body>
</html>