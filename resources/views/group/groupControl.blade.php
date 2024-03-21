@extends('layout.header')


@section('title','Control de grupos')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/groups/initGroupControl.js') }}"></script>

<body style="overflow-x: hidden"> 
	<script src="{{ asset('js/inactividad.js') }}"></script>   
	@if (session('registroAlumnosExito'))
        <script>
            swal({
                title: "Seminaristas agregados al grupo de clase",
                text: "{{ session('registroAlumnosExito') }}",
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
	
	@if (session('registroAlumnosError'))
        <script>
            swal({
                title: "Error al agregar estudiantes al grupo de clase",
                text: "{{ session('registroAlumnosError') }}",
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

	@if (session('eliminacionGrupo'))
        <script>
            swal({
                title: "Grupo eliminado correctamente",
                text: "{{ session('eliminacionGrupo') }}",
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

	@if (session('eliminacionGrupoError'))
        <script>
            swal({
                title: "Error al eliminar grupo",
                text: "{{ session('eliminacionGrupoError') }}",
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
					<div class="col d-flex justify-content-center">
						<p style="color: black; margin: 0; font-weight: bold">Control de grupos</p>
					</div>                                          
                </div>
            </nav>                         				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Grupos de clase</p>
					<div class="separator mb-3"></div>											
						<table class="table data-table table-striped" id="teachers-table">
							<thead class="table-head">
								<tr>
									<th scope="col">Nombre</th>
									<th scope="col">Materia</th>
									<th scope="col">Docente responsable</th>
									<th scope="col">Ciclo</th>
                                    <th scope="col">N° de seminaristas</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<div class="table-body">
								<tbody>	
									@foreach ($groups as $group)
										<tr>
											<td>{{ $group->nombreGrupo }}</td>
											<td>{{ $group->nombreMateria }}</td>
											<td>{{ $group->nombreDocente.' '.$group->apellidoDocente }}</td>
											<td>{{ $group->nombreCiclo }}</td>
                                            <td>{{ $group->cantidadAlumnos }}</td>
											<td>
												<div class="row">
													<div class="col-4 mx-1 px-0">
														<a type="button" class="btn btn-primary icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver información" href="{{ route('group.information',$group->idGrupo) }}"><i class="fa-solid fa-eye my-1"></i></a>
													</div>
													<div class="col-4 mx-1 px-0">
														<a type="button" class="btn btn-warning icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Agregar seminaristas" href="{{ route('group.addStudents',$group->idGrupo)}}"><i class="fa-solid fa-people-group my-1" style="color: white"></i></a>
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
	
    
</body>
</html>