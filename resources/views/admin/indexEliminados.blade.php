@extends('layout.header')


@section('title','Administradores Eliminados')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/admin/indexEInit.js') }}"></script>

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
                    <a href="{{ route('admin.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Control de administradores eliminados</p>
                    </div>
                </div>
            </nav>                  				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Administradores eliminados</p>
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
									<th scope="col">Contacto</th>
									<th scope="col">Correo</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<div class="table-body">
								<tbody>	
									@foreach ($administradores as $admin)
										<tr>
											<td>{{ $admin->nombreAdministrador.' '.$admin->apellidoAdministrador }}</td>
											<td>{{ $admin->duiAdministrador }}</td>
											<td>{{ $admin->telefonoAdministrador }}</td>
											<td>{{ $admin->correoAdministrador }}</td>
											<td>
												<div class="row d-flex justify-content-center">													
													<div class="col-4 mx-0 px-0">
														<button 
															type="button" 
															class="btn btn-success icon-button"
															data-bs-toggle="tooltip" 
															data-bs-placement="bottom" 
															data-bs-title="Restaurar"
															onclick="openRestoreModal({{$admin->idAdministrador}})">
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
    <!-- Modal para restaurar administrador-->
    <div class="modal fade" id="restaurarAdministrador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Restaurar administrador</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.restore') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-12 col-xs-12">
                                <label id="txtPregunta" name="txtPregunta" class="form-label" style="font-weight: bold"></label>
                            </div>
                        </div>
                        <div class="row mt-2">   
                            <input type="text" id="txtIdAdministradorRestaurar" name="idAdministradorRestaurar" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">                        
                            @csrf
                            @method('PUT')                           
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" style="color: white">Restaurar</button>                       
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>