@extends('layout.header')


@section('title','Control de seminaristas')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/indexInit.js') }}"></script>
<script src="{{ asset('js/students/deleteModal.js') }}"></script>
<body style="overflow-x: hidden">    
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoEliminar'))
        <script>
            swal({
                title: "Registro eliminado",
                text: "{{ session('exitoEliminar') }}",
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

    @if (session('errorEliminar'))
        <script>
            swal({
                title: "Error al eliminar",
                text: "{{ session('errorEliminar') }}",
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
					<div class="col d-flex justify-content-center">
						<p style="color: black; margin: 0; font-weight: bold">Control de seminaristas</p>
					</div>                                          
                </div>
            </nav>                         				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Seminaristas registrados</p>
					<div class="separator mb-3"></div>											
						<table class="table data-table table-striped" id="teachers-table">
							<thead class="table-head">
								<tr>
									<th scope="col">Nombre</th>
									<th scope="col">DUI</th>
									<th scope="col">Celular</th>
									<th scope="col">Correo</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<div class="table-body">
								<tbody>	
									@foreach ($students as $student)
										<tr>
											<td>{{ $student->nombreEstudiante.' '.$student->apellidoEstudiante }}</td>
											<td>{{ $student->duiEstudiante }}</td>
											<td>{{ $student->numeroMovil }}</td>
											<td>{{ $student->correoEstudiante }}</td>
											<td>
												<div class="row">
													<div class="col-4 mx-0 px-0">
														<a type="button" class="btn btn-primary icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver información" href="{{ route('student.showInfo', $student->idEstudiante) }}"><i class="fa-solid fa-eye my-1" style="color: white"></i></a>
													</div>
													<div class="col-4 mx-0 px-0">
														<a type="button" class="btn btn-warning icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Historial" href="{{ route('student.record', $student->idEstudiante)}}"><i class="fa-solid fa-clipboard-list my-1" style="color: white"></i></a>
													</div>
													<div class="col-4 mx-0 px-0">
														<button 
															type="button" 
															class="btn btn-danger icon-button"
															data-bs-toggle="tooltip" 
															data-bs-placement="bottom" 
															data-bs-title="Eliminar" 
															value="{{$student->idEstudiante}},{{$student->nombreEstudiante.' '.$student->apellidoEstudiante}}"
															onclick="openDeleteModal(this.value)">
															<i class="fa-solid fa-trash"></i>
														</button>
													</div>	
												</div>																									
											</td>
										</tr> 
									@endforeach																																									
								</tbody>
							</div>
						</table>																
					</div>
				</div>
			</div>									           
        </div>
    </div>
	<!-- Modal para eliminar estudiante-->
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
                    <form method="POST" action="{{ route('student.delete')}}">
                        @csrf
                        @method('DELETE')
                        <input type="text" name="idEstudianteEliminar" id="txtIdEstudianteEliminar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>