@extends('layout.header')


@section('title','Evaluaciones')

<script src="{{ asset('js/alertify.js') }}"></script>
<link href="{{ asset('css/alertify.min.css') }}" type="text/css" rel="stylesheet">
<script src="{{ asset('js/sweetalert.js') }}"></script>
<body style="overflow-x: hidden">    
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('flash.exitoAgregar'))
        <script>
            swal({
                title: "Notas agregadas",
                text: "{{ session('flash.exitoAgregar') }}",
                icon: "success",
                button: "OK",
                closeOnClickOutside: false,
                }).then((value) => {
                if (value) {
                    location.reload(); 
                }
            })            
        </script>                     
    @endif

    @if (session('flash.errorAgregar'))
        <script>
            swal({
                title: "Error al agregar notas",
                text: "{{ session('flash.errorAgregar') }}",
                icon: "error",
                button: "OK",
                closeOnClickOutside: false,
                }).then((value) => {
                if (value) {
                    location.reload(); 
                }
            })            
        </script>
    @endif
    
    @include('layout.horizontalMenu')    
    <div class="wrapper">
        @include('layout.verticalMenuTeacher')
        <div id="content" class="mt-0 pt-0">              
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('teacherSite.groupInformation',$groupInformation[0]->idGrupo) }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Actividades del grupo</p>
                    </div>
                </div>
            </nav>                
            <div class="row mx-5 my-1">
                @if (!empty($groupInformation[0]))
                    <div class="alert alert-primary">
                        Las actividades que se muestran corresponden al grupo: {{ $groupInformation[0]->nombreMateria }} ({{ $groupInformation[0]->nombreGrupo }})                 
                    </div>
                @endif                    
                @if (!empty($evaluations))                
                    @foreach ($evaluations as $evaluation)                       
                        <div class="col-12 my-2">
                            <div class="card">
                                <div class="card-header" style="background-color: {{ in_array($evaluation->idEvaluacion,$evaluationAssignedArray) ? '#008F39' : '#F7FE99' }}">                           
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $evaluation->nombreEvaluacion }}</h5>
                                    <p style="margin: 0"><b>Descripción de actividad: </b>{{ $evaluation->descripcion }}</p>                                    
                                    <p style="margin: 0"><b>Porcentaje (%): </b>{{ $evaluation->porcentaje }}%</p>                                    
                                </div>
                                <div class="card-footer text-body-secondary d-flex justify-content-center">                                    
                                    @if (!in_array($evaluation->idEvaluacion,$evaluationAssignedArray))
                                        <a href="{{ route('teacherSite.gradesAssigment',$evaluation->idEvaluacion) }}" class="btn btn-success my-1 mx-1">Asignar calificaciones</a>  
                                    @else
                                        <a href="{{ route('teacherSite.updateGradesView',$evaluation->idEvaluacion) }}" class="btn btn-secondary my-1">Visualizar notas</a>                                                                    
                                    @endif                                    
                                </div>
                            </div>
                        </div>                                                    
                    @endforeach
                @else
                    <div class="alert alert-warning" role="alert">
                        No se han encontrado actividades
                    </div>
                @endif
            </div>            							
        </div>
    </div>	
    
</body>
</html>