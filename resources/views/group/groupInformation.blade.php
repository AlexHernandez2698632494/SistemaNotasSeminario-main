@extends('layout.header')


@section('title','Información de grupo')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/groups/deleteGroup.js') }}"></script>

<body style="overflow-x: hidden">    	    
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('exitoEliminarEstudiante'))
        <script>
            swal({
                title: "Seminarista eliminado del grupo de clase",
                text: "{{ session('exitoEliminarEstudiante') }}",
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

    @if (session('errorEliminarEstudiante'))
        <script>
            swal({
                title: "Error al eliminar seminarista del grupo",
                text: "{{ session('errorEliminarEstudiante') }}",
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
        @include('layout.verticalMenu')
        <div id="content" class="mt-0 pt-0">              
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('group.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Información del grupo</p>
                    </div>
                </div>
            </nav>                       				
			<div class="card mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Información</p>
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
                    @if (!empty($group[0]))
                        <div class="row mx-2">
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre</p>
                                {{ $group[0]->nombreGrupo }}
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Materia</p>
                                {{ $group[0]->nombreMateria }}
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Docente responsable</p>
                                {{ $group[0]->nombreDocente.' '.$group[0]->apellidoDocente }}
                            </div>                       
                        </div>		
                        <div class="row mx-2 mt-3">
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de creación</p>
                                {{ $group[0]->anio }}
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Cantidad de estudiantes</p>
                                {{ $studentQuantity }}
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Estado de finalización</p>                            
                                @if ($group[0]->estadoFinalizacion == 1)
                                    Activo
                                @else 
                                    Finalizado
                                @endif
                                
                            </div>                        
                        </div>	
                        @if ($group[0]->estadoFinalizacion == 1)
                            @if ($evaluationQuantity == 0)
                                <div class="col mx-5 my-3 d-flex justify-content-center">
                                    <a 
                                        class="btn btn-danger"                                         
                                        onclick="openDeleteModal({{$group[0]->idGrupo}})">
                                        Eliminar grupo
                                    </a>
                                </div> 
                            @else   
                                
                            @endif                                                          
                        @else 
                            
                        @endif
                        @if ($group[0]->estadoFinalizacion == 0)
                            <div class="row my-3 mx-5">
                                <a href="{{ route('pdf.cuadroNotas',$group[0]->idGrupo) }}" class="btn btn-success" style="background-color: #7386d5">Generar cuadro de notas</a>
                            </div>
                        @else 
                            
                        @endif                        
                    @else
                        <div class="alert alert-warning" role="alert">
                            No se han encontrado registros
                        </div>
                    @endif                        	                                                          						
				</div>
			</div>            
            <div class="card mx-5 mt-3">
                <div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Estudiantes del grupo</p>
					<div class="separator mb-3" style="height: 2px"></div>
                    <div class="row mx-2">
                        @if (!empty($group[0]))
                            @if ($group[0]->estadoFinalizacion === 1)
                                <table class="table data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th th scope="col">Apellido</th>                                    
                                            <th th scope="col">Acciones</th>                                    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->nombreEstudiante }}</td>
                                                <td>{{ $student->apellidoEstudiante }}</td>   
                                                <td>
                                                    <div class="row">
                                                        <div class="col-4 px-0">
                                                            <button 
                                                                class="btn btn-danger icon-button" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-placement="bottom" 
                                                                data-bs-title="Eliminar seminarista del grupo" 
                                                                value="{{$student->nombreEstudiante.' '.$student->apellidoEstudiante}},{{$student->idDetalle}},{{$student->idGrupo}}"
                                                                onclick="openDeleteStudentModal(this.value)">
                                                                <i class="fa-solid fa-trash my-1"></i>
                                                            </button>
                                                        </div>																								
                                                    </div>
                                                </td>                                    
                                            </tr>  
                                        @endforeach                                                                  
                                    </tbody>
                                </table>
                            @elseif ($group[0]->estadoFinalizacion === 0)
                                <table class="table data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th th scope="col">Apellido</th>                                    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->nombreEstudiante }}</td>
                                                <td>{{ $student->apellidoEstudiante }}</td>                                    
                                            </tr>  
                                        @endforeach                                                                  
                                    </tbody>
                                </table>
                            @endif
                        @else
                            <div class="alert alert-warning" role="alert">
                                No se han encontrado registros
                            </div>
                        @endif
                    </div>		                    		                                                          						
				</div>
            </div>							           
        </div>
    </div>
    <!-- Modal para eliminar estudiante de un grupo-->
    <div class="modal fade" id="eliminarEstudiante" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <form method="POST" action="{{ route('group.deleteStudent') }}">
                        @csrf             
                        @method('POST')           
                        <input type="text" name="idDetalleEliminar" id="txtIdDetalleEliminar" hidden>                       
                        <input type="text" name="idGrupo" id="txtIdGrupo" hidden>                       
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para eliminar estudiante de un grupo-->
    <div class="modal fade" id="eliminarGrupo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Verificación de eliminación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="txtDeleteModalGroup"></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('group.deleteGroup') }}">
                        @csrf             
                        @method('DELETE')           
                        <input type="text" name="idDetalleEliminar" id="txtIdDetalleEliminar" hidden>                       
                        <input type="text" name="idGrupoEliminar" id="txtIdGrupoEliminar" hidden>                       
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>