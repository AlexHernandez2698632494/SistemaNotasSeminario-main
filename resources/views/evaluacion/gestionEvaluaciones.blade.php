@extends('layout.header')


@section('title','Gestión de Evaluaciones')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/evaluaciones/initEvaluacionesAdd.js') }}"></script>

<body style="overflow-x: hidden" onload="mostrarBotonAgregar({{$porcentajeAsignado}});">    	
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('exitoAgregar'))
        <script>
            swal({
                title: "Evaluación agregada",
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
                title: "Error al agregar evaluación",
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

    @if (session('exitoEliminar'))
        <script>
            swal({
                title: "Registro Eliminado",
                text: "{{ session('exitoEliminar') }}",
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
    
    @if (session('errorEliminar'))
        <script>
            swal({
                title: "Error al eliminar evaluación",
                text: "{{ session('errorActualizar') }}",
                icon: "error",
                button: "OK",
            });
        </script>
    @endif

    @include('layout.horizontalMenu')    
    <div class="wrapper">
        @include('layout.verticalMenuTeacher')
        <div id="content" class="mt-0 pt-0">           
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('teacherSite.groupInformation',$grupo[0]->idGrupo) }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Información de evaluaciones</p>
                    </div>
                </div>
            </nav>
            <div class="card mx-5">
                <div class="card-body">
                    <p class="d-flex justify-content-center">{{$grupo[0]->nombreGrupo}}</p>
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
                    <div class="row mx-1 mt-3 justify-content-center">                        
                        <div class="col-lg-4 col-xs-12 col-md-6">
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Cantidad de evaluaciones</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">{{$cantidadEvaluaciones}}</h3>                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-12 col-md-6">                            
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Porcentaje asignado</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">{{$porcentajeAsignado}} %</h3>                                    
                                </div>
                            </div>                          
                        </div>
                        <div class="col-lg-4 col-xs-12 col-md-6">
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Porcentaje por asignar</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">{{$porcentajePorAsignar}} %</h3>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="card mx-5 mt-3">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Evaluaciones</p>
					<div class="separator mb-3"></div>	                  
                    <div class="row mx-2">
                        <table class="table data-table">
                            <thead >
                                <tr>
                                    <th scope="col" style="background-color: #7386d5; color:white">Nombre evaluación</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Porcentaje</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($evaluaciones as $evaluacion)
                                    <tr>
                                        <td>{{ $evaluacion->nombreEvaluacion }}</td>
                                        <td>{{ $evaluacion->porcentaje.'%' }}</td>
                                        <td> 
                                            @if (!in_array($evaluacion->idEvaluacion,$evaluationAssignedArray))                                      
                                            <div class="row">
                                                <div class="col-4 mx-0 px-0">
                                                    <a type="button" class="btn btn-warning icon-button"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        data-bs-title="Actualizar información" onclick="updateEvaluacionModal({{$evaluacion->idEvaluacion}})"><i
                                                            class="fa-solid fa-arrows-rotate my-1"
                                                            style="color: white"></i></a>
                                                </div>
                                                <div class="col-4 mx-0 px-0">
                                                    <a type="button" class="btn btn-danger icon-button"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        data-bs-title="Eliminar" onclick="confirmarEliminacion({{$evaluacion->idEvaluacion}})"><i
                                                            class="fa-solid fa-trash my-1"
                                                            style="color: white"></i></a>
                                                </div>
                                            </div>
                                            @else No hay acciones por realizar
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach                       
                            </tbody>
                        </table> 
                        <div class="row mx-2 my-2">
                            <div class="col d-flex justify-content-center">
                                <button type="submit" id="btnAgregar" class="btn btn-block btn-Add"><a href="{{route('evaluacion.formulario', $grupo[0]->idGrupo)}}">Agregar Evaluación</a></button>
                            </div>								
                        </div>                                                                                                                    
                    </div>                                 																												
				</div>
			</div>	                        											           
        </div>

    </div>
    <!-- Modal para actualizar información de evaluación-->
    <div class="modal fade" id="modificarEvaluacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Actualización de información</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('evaluacion.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtNombreEvaluacion" class="form-label" style="font-weight: bold">Nombre de evaluación</label>                                
                                <input type="text" id="txtNombreEvaluacion" name="nombre" placeholder="Ingrese nombre de la evaluación" class="form-control inputTxt">
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtPorcentaje" class="form-label" style="font-weight: bold">Porcentaje</label>                                
                                <input type="text" id="txtPorcentaje" name="porcentaje" placeholder="Ingrese porcentaje" class="form-control inputTxt txtPorcentaje" oninput="validateOnlyNumbersOnInput(this);" required>                                                                                                    
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-xs-12 mt-2">                                    
                                <label for="txtDescripcion" class="mb-1">Descripción</label>
                                <textarea class="form-control" placeholder="Ingrese descripción de evaluación" id="txtDescripcion" name="descripcion" style="height: 100px" class="inputTxt">{{old('descripcion')}}</textarea>                                    
                            </div>
                        </div>
                        <div class="row mt-2">   
                            <input type="text" id="txtIdEvaluacionActualizar" name="idEvaluacion" hidden>                    
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
    <!-- Modal para eliminar evaluación-->
    <div class="modal fade" id="eliminarEvaluacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar evaluación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('evaluacion.delete') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-12 col-xs-12">
                                <label id="txtPregunta" name="txtPregunta" for="txtNombreEvaluacion" class="form-label" style="font-weight: bold"></label>
                            </div>
                        </div>
                        <div class="row mt-2">   
                            <input type="text" id="txtIdEvaluacionEliminar" name="idEvaluacionEliminar" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">                        
                            @csrf
                            @method('delete')                           
                            <input type="text" name="teacherIdTitle" id="txtIdTeacherTitle" hidden>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger" style="color: white">Eliminar</button>                       
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>