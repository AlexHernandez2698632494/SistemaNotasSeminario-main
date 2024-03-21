@extends('layout.header')


@section('title','Control de administradores')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/admin/indexInit.js') }}"></script>

<body style="overflow-x: hidden"> 
	<script src="{{ asset('js/inactividad.js') }}"></script>    
	@if (session('exitoActualizar'))
        <script>
            swal({
                title: "Administrador Modificado",
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
                title: "Error al actualizar",
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
                title: "Docente eliminado",
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
                title: "Error al eliminar",
                text: "{{ session('errorEliminar') }}",
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
						<p style="color: black; margin: 0; font-weight: bold">Control de administradores</p>
					</div>                                          
                </div>
            </nav>                         				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Administradores registrados</p>
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
												<div class="row">
													<div class="col-4 mx-0 px-0">
														<a type="button" class="btn btn-warning icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Actualizar" onclick="updateAdministradorModal({{$admin->idAdministrador}})"><i class="fa-solid fa-arrows-rotate my-1" style="color: white"></i></a>
													</div>
													<div class="col-4 mx-0 px-0">
														<button 
															type="button" 
															class="btn btn-danger icon-button"
															data-bs-toggle="tooltip" 
															data-bs-placement="bottom" 
															data-bs-title="Eliminar" 
															onclick="confirmarEliminacion({{$admin->idAdministrador}})">
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
	<!-- Modal para actualizar información de administrador-->
    <div class="modal fade" id="modificarAdministrador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Actualización de información</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{route('admin.update')}}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row justify-content-center">
							<div class="col-lg-6 col-xs-12 mt-2">                                    
								<label for="txtNombreAdministrador" class="form-label">Nombre</label>                                
								<input type="text" id="txtNombreAdministrador" name="nombreAdministrador" placeholder="Ingrese nombre" class="form-control inputTxt"  value="{{old('nombreAdministrador')}}" required>                                    
							</div>
							<div class="col-lg-6 col-xs-12 mt-2">
								<label for="txtApellidoAdministrador" class="form-label">Apellido</label>                                
								<input type="text" id="txtApellidoAdministrador" name="apellidoAdministrador" placeholder="Ingrese apellido" class="form-control inputTxt" value="{{old('apellidoAdministrador')}}" required>
							</div>
						</div>			
						<div class="row mt-2 justify-content-center">
							<div class="col-lg-6 col-xs-12 mt-2">
								<label for="txtDui" class="form-label">DUI</label>                                
								<input type="text" id="txtDui"  name="duiAdministrador" placeholder="Ingrese DUI" class="form-control inputTxt" value="{{old('duiAdministrador')}}" required>                                
							</div>
							<div class="col-lg-6 col-xs-12 mt-2">
								<label for="txtTelefono" class="form-label">Número de teléfono/celular</label>                                
								<input type="text" id="txtTelefono" name="telefono" placeholder="Ingrese número" class="form-control inputTxt txtPhone" value="{{old('telefono')}}">                                                                                                    
							</div>                                
						</div>	
						<div class="row mt-2 justify-content-center">
							<div class="col-lg-12 col-xs-12 mt-2">                                    
								<label for="txtCorreoAdministrador" class="form-label">Correo electrónico</label>                                
								<input type="email" id="txtCorreoAdministrador"  placeholder="Ingrese correo" name="correoAdministrador" class="form-control  inputTxt" value="{{old('correoAdministrador')}}" required>                                    
							</div>
						</div>
                        <div class="row mt-2">   
                            <input type="text" id="txtIdAdministradorActualizar" name="idAdministrador" hidden>                    
                        </div>
                    </div>
                    <div class="modal-footer">                        
                            @csrf
                            @method('PUT')                           
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-warning" style="color: white">Actualizar</button>                       
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal para eliminar administrador-->
    <div class="modal fade" id="eliminarAdministrador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar administrador</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.delete') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-12 col-xs-12">
                                <label id="txtPregunta" name="txtPregunta" class="form-label" style="font-weight: bold"></label>
                            </div>
                        </div>
                        <div class="row mt-2">   
                            <input type="text" id="txtIdAdministradorEliminar" name="idAdministradorEliminar" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">                        
                            @csrf
                            @method('delete')                           
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger" style="color: white">Eliminar</button>                       
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>