@extends('layout.header')


@section('title','Docentes eliminados')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/teachers/removedInit.js') }}"></script>
<script src="{{ asset('js/teachers/restoreModal.js') }}"></script>
<body style="overflow-x: hidden">    
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoRestaurar'))
        <script>
            swal({
                title: "Registro restaurado",
                text: "{{ session('exitoRestaurar') }}",
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

    @if (session('errorRestaurar'))
        <script>
            swal({
                title: "Error al restaurar",
                text: "{{ session('errorRestaurar') }}",
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
                    <a href="{{ route('teachers.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Restauración de docentes</p>
                    </div>
                </div>
            </nav>                    				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Docentes eliminados</p>
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
						<table class="table data-table table-striped" id="teachers-table">
							<thead class="table-head">
								<tr>
									<th scope="col">Nombre</th>
									<th scope="col">DUI</th>									
									<th scope="col">Acción</th>									
								</tr>
							</thead>
							<div class="table-body">
								<tbody>	
									@foreach ($removedTeachers as $removedTeacher)
										<tr>
											<td>{{ $removedTeacher->nombreDocente.' '.$removedTeacher->apellidoDocente }}</td>
											<td>{{ $removedTeacher->duiDocente }}</td>											
											<td>
												<div class="row d-flex justify-content-center">													
													<div class="col-4 mx-0 px-0">
														<button 
															type="button" 
															class="btn btn-success icon-button"
															data-bs-toggle="tooltip" 
															data-bs-placement="bottom" 
															data-bs-title="Restaurar" 
															value="{{$removedTeacher->idDocente}},{{$removedTeacher->nombreDocente.' '.$removedTeacher->apellidoDocente}}"
															onclick="openRestoreModal(this.value)">
															<i class="fa-solid fa-trash-can-arrow-up"></i>
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
	<!-- Modal para restaurar docentes-->
    <div class="modal fade" id="restaurarDocente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Verificación de restauración</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="txtRestoreModal"></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('teacher.restore')}}">
                        @csrf
                        @method('PUT')
                        <input type="text" name="idDocenteRestaurar" id="txtIdDocenteRestaurar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Restaurar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>