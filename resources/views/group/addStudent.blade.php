@extends('layout.header')


@section('title', 'Registro de grupos')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/groups/initGroupControl.js') }}"></script>

<body style="overflow-x: hidden">
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if(session('registroAlumnosExito'))
        <script>
            swal({
                title: "Seminaristas agregados al grupo",
                text: "{{ session('registroAlumnosExito') }}",
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

    @if (session('registroAlumnosError'))
        <script>
            swal({
                title: "Error al agregar seminaristas",
                text: "{{ session('registroAlumnosError') }}",
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
        @include('layout.verticalMenu')
        <div id="content" class="mt-0 pt-0">            
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('group.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Asignación de estudiantes al grupo de clase</p>
                    </div>
                </div>
            </nav>

            <div class="card mx-5">
                <div class="card-body">
                    <p class="d-flex justify-content-center">Información del grupo</p>
                    <div class="separator mb-3"></div>                                      
                    @if ($errors->any())
                        <div class="alert alert-danger my-2 pb-0">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif                   
                    <div class="row mx-1">
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre del grupo:</p>
                            {{$group[0]->nombreGrupo}}                               
                        </div>
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Materia:</p>
                            {{$group[0]->nombreMateria}}                              
                        </div>
                    </div>
                    <div class="row  mx-1 mt-2"> 
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Docente responsable:</p>
                            {{$group[0]->nombreDocente.' '.$group[0]->apellidoDocente}}                               
                        </div>                                                                     
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Ciclo al que pertenece:</p>
                            {{$group[0]->nombreCiclo}}                               
                        </div>                                            
                    </div>                                                                    
                </div>
            </div> 
            <div class="card mx-5 mt-2">
                <div class="card-body">
                    <p class="d-flex justify-content-center">Seminaristas que se pueden registrar en el grupo</p>
                    <div class="separator mb-3" style="height: 2px;"></div>  
                    @if ($students->count() > 0)                 
                        <div class="row">
                            <form method="POST" action="{{ route('group.storeStudents')}}">
                                @csrf
                                <div class="row">                                  
                                        <input value="{{ $group[0]->idGrupo }}" name="idGrupo" hidden>                         
                                        @foreach ($students as $student)
                                            @if (!in_array($student->idEstudiante,$studentFinalizedArray))
                                                <div class="col-lg-4 col-xs-12">
                                                    <div class="form-check mt-3">
                                                        <input class="form-check-input" type="checkbox" value="{{ $student->idEstudiante }}" id="checkSubject{{$student->idEstudiante}}" name="estudiantes[]" checked>
                                                        <label class="form-check-label" for="checkSubject{{$student->idEstudiante}}">
                                                            {{ $student->nombreEstudiante.' '.$student->apellidoEstudiante }}
                                                        </label>
                                                    </div>
                                                </div>                                                                                            
                                            @endif                                                	
                                        @endforeach	
                                        <div class="col-12 mt-3">
                                            <div class="btn-group d-flex justify-content-center">
                                                <button 
                                                    type="submit"
                                                    class="btn btn-success mt-2 btn-block btn-add" 
                                                    value="registrarGrupo"      
                                                    name="action"                                                                   
                                                    >Añadir seminaristas al grupo
                                                </button>
                                            </div>
                                        </div>	                                   
                                    </div> 
                            </form>   
                        </div>  
                        @else
                            <div class="row mx-2">
                                <div class="alert alert-warning" role="alert">
                                    No se han encontrado seminaristas
                                </div>
                            </div>
                        @endif                                                                                                          
                </div>
            </div>        
        </div>
    </div>

</body>

</html>
