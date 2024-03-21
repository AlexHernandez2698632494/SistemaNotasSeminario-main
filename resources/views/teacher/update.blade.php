@extends('layout.header')



@section('title','Actualización de datos')
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/teachers/updateTeacher.js') }}"></script>

<body style="overflow-x: hidden">   
    <script src="{{ asset('js/inactividad.js') }}"></script>	
    @if (session('exitoAgregar'))
        <script>
            swal({
                title: "Registro agregado",
                text: "{{ session('exitoAgregar') }}",
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
    @if (session('errorAgregar'))
        <script>
            swal({
                title: "Error al agregar",
                text: "{{ session('errorAgregar') }}",
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
    @if (session('exitoEliminacion'))
        <script>
            swal({
                title: "Registro eliminado",
                text: "{{ session('exitoEliminacion') }}",
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
    @if (session('exitoModificar'))
        <script>
            swal({
                title: "Registro modificado",
                text: "{{ session('exitoModificar') }}",
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
    @if (session('errorModificar'))
    <script>
        swal({
            title: "Error al modificar información",
            text: "{{ session('errorModificar') }}",
            icon: "error",
            button: "OK",
        });
    </script>
@endif
    @include('layout.horizontalMenu')           
    <div class="wrapper">
        @include('layout.verticalMenu')
        <div id="content" class="mt-0 pt-0">               
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('teachers.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Actualización de materias y titulos del docente</p>
                    </div>
                </div>
            </nav>            
            <div class="row mx-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif  
            </div>        
            <div class="card mx-5">
                <div class="card-body">
                    <div class="row mx-2">
                        <p class="d-flex justify-content-center mb-1">Información general del docente seleccionado</p>
                        <div class="separator mt-0"></div>	
                        <div class="col-lg-3 col-xs-12 col-md-6">
                            <div class="row mt-2">
                                <div class="col-lg-12"><b>Nombre</b></div>
                                <div class="col-lg-12">{{ $teacher->nombreDocente.' '.$teacher->apellidoDocente }}</div>
                            </div>
                        </div>    
                        <div class="col-lg-3 col-xs-12 col-md-6">
                            <div class="row mt-2">
                                <div class="col-lg-12"><b>DUI</b></div>
                                <div class="col-lg-12">{{ $teacher->duiDocente }}</div>
                            </div>
                        </div>    
                        <div class="col-lg-3 col-xs-12 col-md-6">
                            <div class="row mt-2">
                                <div class="col-lg-12"><b>Teléfono</b></div>
                                <div class="col-lg-12">{{ $teacher->numeroTelefono }}</div>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-xs-12 col-md-6">
                            <div class="row mt-2">
                                <div class="col-lg-12"><b>Correo</b></div>
                                <div class="col-lg-12">{{ $teacher->correoDocente }}</div>
                            </div>
                        </div>   
                    </div>
                    <div class="row mx-5 mt-3">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" onclick="updateTeacherModal({{$teacher->idDocente}})">Actualizar información</button>
                        </div>
                    </div>
                </div>
            </div>           
            <div class="card updateSubjectTitles mx-5 my-3">
                <div class="card-body">
                    <div class="row mx-2">
                        <p class="d-flex justify-content-center mb-1">Asignaturas que el docente puede impartir</p>
                        <div class="separator mt-0"></div>	
                        <div class="container mt-2">
                            <form method="POST" action="{{ route('subject.add') }}">
                                @csrf
                                <div class="row mx-1">
                                    <input type="text" name="teacherId" value="{{ $teacher->idDocente }}" hidden>                                            
                                    <label for="materiaDocente" class="mb-2 d-flex justify-content-center">Seleccione la nueva materia que el docente puede impartir</label>
                                    <div class="col-lg-6 col-xs-12 px-0 d-flex justify-content-center">                                                
                                        <select class="form-select select2" aria-label="Default select example" id="materiaDocente" name="newSubject">
                                            @foreach ($subjectsAvailable as $subject)
                                                <option value={{ $subject->idMateria}}>{{ $subject->nombreMateria }} - {{$subject->nombreEtapa}} Año {{$subject->anio}} {{$subject->cuatrimestre}}</option>                                                                                                                                                            
                                            @endforeach                                                            
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-xs-12 d-flex justify-content-center px-0">
                                        <button type="submit" class="btn btn-primary btn-Add">Agregar materia</button>
                                    </div>                                            
                                </div>
                            </form>                                        
                            <div class="row my-3">
                                <table class="table data-table-updateSubjectsTittle table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Materia</th>
                                            <th scope="col">Etapa</th>
                                            <th scope="col">Acción</th>                                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacherSubjects as $teacherSubject)
                                            <tr>                                                        
                                                <td>{{ $teacherSubject->nombreMateria }}</td>
                                                <td>{{ $teacherSubject->nombreEtapa.' Año '.$teacherSubject->anio.' '.$teacherSubject->cuatrimestre }}</td>
                                                <td>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-danger icon-button" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="bottom" 
                                                        data-bs-title="Eliminar materia"
                                                        value="{{$teacherSubject->nombreMateria}},{{$teacherSubject->idDetalle}}, {{$teacher->idDocente}}" 
                                                        onclick="openDeleteSubjectModal(this.value)">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>    
                                                </td>                                                                                                                                                                            
                                            </tr>
                                        @endforeach                                             
                                    </tbody>
                                </table>    
                            </div>
                        </div>                                    
                    </div>
                </div>
            </div>  
            <div class="card updateSubjectTitles mx-5 my-3">
                <div class="card-body">
                    <div class="row mx-2">
                        <p class="d-flex justify-content-center mb-1">Títulos del docente</p>
                        <div class="separator mt-0"></div>	
                        <div class="container mt-2">                                        	
                            <form method="POST" action="{{ route('title.add') }}">
                                @csrf
                                <div class="row mx-1">
                                    <input type="text" name="teacherId" value="{{ $teacher->idDocente }}" hidden>                                            
                                    <label for="materiaDocente" class="mb-2 d-flex justify-content-center">Ingrese titulo del docente</label>
                                    <div class="col-lg-6 col-xs-12 px-0 d-flex justify-content-center">                                                
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-graduation-cap"></i></span>
                                            <input type="text" class="form-control" placeholder="Titulo" aria-label="Username" aria-describedby="basic-addon1" name="tituloDocente">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xs-12 d-flex justify-content-center px-0">
                                        <button type="submit" class="btn btn-primary btn-Add">Agregar título</button>
                                    </div>                                            
                                </div>
                            </form>                                        
                            <div class="row my-3">
                                <table class="table data-table-updateSubjectsTittle table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Título</th>
                                            <th scope="col">Acción</th>                                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacherTitles as $teacherTitle)
                                            <tr>
                                                <td> {{ $teacherTitle->tituloDocente }}</td>
                                                <td>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-danger icon-button"
                                                        value="{{$teacherTitle->tituloDocente}},{{$teacherTitle->idDetalleTitulo}},{{$teacher->idDocente}}" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="bottom" 
                                                        data-bs-title="Eliminar título"
                                                        onclick="openDeleteTitleModal(this.value)"
                                                        >
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>    
                                                </td>                                                        
                                            </tr>
                                        @endforeach                                                                                                     
                                    </tbody>
                                </table>    
                            </div>
                        </div>                                       
                    </div>
                </div>
            </div>                                 														           
        </div>        
    </div>
    <!-- Modal para eliminar materias del docente-->
    <div class="modal fade" id="eliminarMateria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Verificación de eliminación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="txtDeleteModal"></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{route('subjectT.delete')}}">
                        @csrf
                        @method('DELETE')
                        <input type="text" name="idDetalleEliminar" id="txtIdDetalleEliminar" hidden>
                        <input type="text" name="teacherId" id="txtIdTeacher" hidden>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar titulos del docente-->
    <div class="modal fade" id="eliminarTitulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Verificación de eliminación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="txtDeleteTitleModal"></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{route('title.delete')}}">
                        @csrf
                        @method('DELETE')
                        <input type="text" name="idDetalleTituloEliminar" id="txtIdDetalleTituloEliminar" hidden>
                        <input type="text" name="teacherIdTitle" id="txtIdTeacherTitle" hidden>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para actualizar información del docente-->
    <div class="modal fade" id="modificarDocente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Actualización de información</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('teacher.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtNombreDocente" class="form-label" style="font-weight: bold">Nombre del docente</label>                                
                                <input type="text" id="txtNombreDocente" name="nombreDocente" placeholder="Ingrese nombre del docente" class="form-control inputTxt">
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtApellidoDocente" class="form-label" style="font-weight: bold">Apellido del docente</label>                                
                                <input type="text" id="txtApellidoDocente" name="apellidoDocente" placeholder="Ingrese apelldio del docente" class="form-control inputTxt">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtDuiDocente" class="form-label" style="font-weight: bold">DUI del docente</label>                                
                                <input type="text" id="txtDuiDocente" name="duiDocente" placeholder="Ingrese DUI del docente" class="form-control inputTxt">
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtCorreoDocente" class="form-label" style="font-weight: bold">Correo del docente</label>                                
                                <input type="email" id="txtCorreoDocente" name="correoDocente" placeholder="Ingrese correo electrónico del docente" class="form-control inputTxt">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtTelefonoDocente" class="form-label" style="font-weight: bold">Teléfono del docente</label>                                
                                <input type="text" id="txtTelefonoDocente" name="telefonoDocente" placeholder="Ingrese teléfono del docente" class="form-control inputTxt">
                            </div>    
                            <input type="text" id="txtIdDocente" name="idDocenteActualizar" hidden>                    
                        </div>
                    </div>
                    <div class="modal-footer">                        
                            @csrf
                            @method('PUT')                           
                            <input type="text" name="teacherIdTitle" id="txtIdTeacherTitle" hidden>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-warning" style="color: white">Actualizar</button>                       
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>

</html>
