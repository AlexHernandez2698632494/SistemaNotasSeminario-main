@extends('layout.header')



@section('title','Registro de administradores')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/admin/addInit.js') }}"></script>
<body>   
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
            })            
        </script>
    @endif

    @if (session('errorAgregar'))
        <script>
            swal({
                title: "Error al registrar",
                text: "{{ session('errorAgregar') }}",
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
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3">
                <div class="container-fluid">                    
                    <a href="{{ route('admin.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Registro de nuevo administrador</p>
                    </div>
                </div>
            </nav>                      
            <div class="card teacherAdd-Card" id="data">
                <div class="card-body">
                <p class="d-flex justify-content-center">Registro de administrador</p>
					<div class="separator"></div>
					@if ($errors->any())
						<div class="alert alert-danger my-2 pb-0">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif							
						<form method="POST" action="{{ route('admin.add') }}">
							@csrf							
							<div class="row">
								<div class="col-12">		
									<p class="d-flex justify-content-center mt-2 mb-0">Información general del administrador</p>
									<p class="d-flex justify-content-center mt-0 subtitle">Ingrese la información que se solicita</p>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-3 col-xs-12 mt-2">                                    
                                    <label for="txtNombreAdministrador" class="form-label">Nombre</label>                                
                                    <input type="text" id="txtNombreAdministrador" name="nombreAdministrador" placeholder="Ingrese nombre" class="form-control inputTxt"  value="{{old('nombreAdministrador')}}" required>                                    
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtApellidoAdministrador" class="form-label">Apellido</label>                                
                                    <input type="text" id="txtApellidoAdministrador" name="apellidoAdministrador" placeholder="Ingrese apellido" class="form-control inputTxt" value="{{old('apellidoAdministrador')}}" required>
                                </div>
                            </div>			
                            <div class="row mt-2 justify-content-center">
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtDui" class="form-label">DUI</label>                                
                                    <input type="text" id="txtDui"  name="duiAdministrador" placeholder="Ingrese DUI" class="form-control inputTxt" value="{{old('duiAdministrador')}}" required>                                
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtTelefono" class="form-label">Número de teléfono/celular</label>                                
                                    <input type="text" id="txtTelefono" name="telefono" placeholder="Ingrese número" class="form-control inputTxt txtPhone" value="{{old('telefono')}}">                                                                                                    
                                </div>                                
                            </div>	
                            <div class="row mt-2 justify-content-center">
                                <div class="col-lg-6 col-xs-12 mt-2">                                    
                                    <label for="txtCorreoAdministrador" class="form-label">Correo electrónico</label>                                
                                    <input type="email" id="txtCorreoAdministrador"  placeholder="Ingrese correo" name="correoAdministrador" class="form-control  inputTxt" value="{{old('correoAdministrador')}}" required>                                    
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-3 col-xs-12 mt-2">                                    
                                    <label for="txtUsuarioAdministrador" class="form-label">Usuario</label>                                
                                    <input type="text" id="txtUsuarioAdministrador" name="usuarioAdministrador" placeholder="Ingrese usuario" class="form-control inputTxt"  value="{{old('usuarioAdministrador')}}" required>                                    
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtPasswordAdministrador" class="form-label">Contraseña</label>                                
                                    <input type="password" id="txtPasswordAdministrador" name="passwordAdministrador" placeholder="Ingrese contraseña" class="form-control inputTxt" value="{{old('passwordAdministrador')}}" required>
                                </div>
                            </div>																																							
							<div class="row mx-2 my-2 mt-3">
								<div class="col d-flex justify-content-center">
									<button type="submit" class="btn btn-block btn-Add">Registrar administrador</button>
								</div>								
							</div>
						</form>
                </div>
            </div>              
        </div>                                          
    </div>          
</body>

</html>
