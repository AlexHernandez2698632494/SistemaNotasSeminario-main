@extends('layout.header')


@section('title','Modificación de notas')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/grades/initUpdateGrades.js') }}"></script>
<body style="overflow-x: hidden">
    <script src="{{ asset('js/inactividad.js') }}"></script>    
    @if (session('exitoActualizar'))
        <script>
            swal({
                title: "Registro modificado",
                text: "{{ session('exitoActualizar') }}",
                icon: "success",
                button: "OK",
                closeOnClickOutside: false,
                }).then((value) => {
                if (value) {
                    location.reload(); 
                }
            });
        </script>
    @endif

    @if (session('errorActualizar'))
        <script>
            swal({
                title: "Error al modificar información",
                text: "{{ session('errorActualizar') }}",
                icon: "error",
                button: "OK",
                closeOnClickOutside: false,
                }).then((value) => {
                if (value) {
                    location.reload(); 
                }
            });
        </script>
    @endif

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
                        <p style="color: black; margin: 0; font-weight: bold">Modificación de calificaciones</p>
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
                    @if ($evaluationInfo[0]->estadoFinalizacion == 1)
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
                            <input type="hidden" name="evaluacion" value="{{ $evaluationInfo[0]->idEvaluacion }}" required>
                            <table class="table">
                                <thead >
                                    <tr>
                                        <th scope="col" style="background-color: #7386d5; color:white" hidden>idNota</th>                               
                                        <th scope="col" style="background-color: #7386d5; color:white">Estudiante</th>
                                        <th scope="col" style="background-color: #7386d5; color:white">Calificación</th>                                
                                        <th scope="col" style="background-color: #7386d5; color:white">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>                              
                                        @if (count($notasInfo) > 0)
                                            @foreach ($notasInfo as $nota)
                                                <tr>
                                                    <td hidden>{{$nota->idNota}}</td>
                                                    <td>{{ $nota->apellidoEstudiante }}, {{ $nota->nombreEstudiante }}</td>
                                                    <td><div class="row mx-4">&emsp;{{ $nota->nota }}</div></td>
                                                    <td>
                                                        <div class="row mx-3">
                                                            <div class="col-4 mx-0 px-0">
                                                                <a type="button" class="btn btn-warning icon-button"
                                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                    data-bs-title="Actualizar información" onclick="updateNotaModal({{$nota->idNota}})"><i
                                                                        class="fa-solid fa-arrows-rotate my-1"
                                                                        style="color: white"></i></a>
                                                            </div>
                                                        </div>
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
                        </form> 
                    @else
                        <div class="alert alert-warning mt-2" role="alert">
                            El grupo ha sido finalizado, ya no se pueden modificar las calificaciones
                        </div>
                    @endif                            
                @else
                    <div class="alert alert-warning" role="alert">
                        No se han encontrado información
                    </div>
                @endif                                                     
            </div>            							
        </div>
    </div>
    <!-- Modal para actualizar nota-->
    <div class="modal fade" id="modificarNota" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Actualización de nota</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('teacherSite.updateGrade') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-12 col-xs-12">
                                <label id="txtActividad" name="txtActividad" class="form-label" style="font-weight: bold"></label>
                                <label id="txtEstudiante" name="txtEstudiante" class="form-label" style="font-weight: bold"></label>
                            </div>
                            <div class="col-lg-6 col-xs-12 mt-0">                                    
                                <label for="txtNota" class="form-label" style="font-weight: bold">Nota</label>                                
                                <input type="text" id="txtNota" name="nota" placeholder="Ingrese nota" class="form-control inputTxt" maxlength="3" required>                                    
                            </div>
                        </div>
                        <div class="row mt-2">   
                            <input type="text" id="txtIdNotaActualizar" name="idNota" hidden>                    
                            <input type="text" id="txtIdEvaluacion" name="idEvaluacion" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">                        
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" style="color: white">Actualizar</button>                       
                    </div>
                </form>
            </div>
        </div>
    </div>	
    <script src="{{ asset('js/grades/validarNota.js') }}" defer></script>
</body>
</html>