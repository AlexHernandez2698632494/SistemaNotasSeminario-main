@extends('layout.header')


@section('title','Control de grupos')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/groups/initFinalizedGroups.js') }}"></script>

<body style="overflow-x: hidden">    	
	<script src="{{ asset('js/inactividad.js') }}"></script>
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
                        <p style="color: black; margin: 0; font-weight: bold">Grupos finalizados</p>
                    </div>
                </div>
            </nav>                 				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Grupos de clase finalizados</p>
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