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
    @if (session('errorEliminacion'))
        <script>
            swal({
                title: "Error al eleminar",
                text: "{{ session('errorEliminacion') }}",
                icon: "error",
                button: "OK",
            });
        </script>
    @endif
    @include('layout.horizontalMenu')    
    <div class="wrapper">
        @include('layout.verticalMenu')
        <div id="content" class="mt-0 pt-0">            
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3">
                <div class="container-fluid">
                    <div class="col">
                        <button type="button" id="sidebarCollapse" class="btn d-lg-none" style="background-color: #7386D5">
                            <i class="fa-solid fa-bars" style="color: white"></i>                        
                        </button>  
                    </div>   
                    <div class="col">
                        <p style="color: black; margin: 0; font-weight: bold">Actualización información</p>
                    </div>                                          
                </div>
            </nav>
            <div class="row mx-2">
				<div class="card">
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
                    </div>
                </div>					
			</div>            
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card updateSubjectTitles">
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
                                                            <option value={{ $subject->idMateria}}>{{ $subject->nombreMateria }}</option>                                                                                                                                                            
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
                                                        <th scope="col">Acción</th>                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($teacherSubjects as $teacherSubject)
                                                        <tr>                                                        
                                                            <td>{{ $teacherSubject->nombreMateria }}</td>
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
                    </div>
                    <div class="col-12 mt-3">
                        <div class="card updateSubjectTitles">
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
                    <form method="POST" action="{{route('subject.delete')}}">
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
    
</body>

</html>
