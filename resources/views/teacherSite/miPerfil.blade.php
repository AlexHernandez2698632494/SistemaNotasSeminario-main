@extends('layout.header')


@section('title','Mi Perfil')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/teachers/miPerfil.js') }}"></script>

<body style="overflow-x: hidden">    
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoModificar'))
        <script>
            swal({
                title: "Registro modificado",
                text: "{{ session('exitoModificar') }}",
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

    @if (session('errorModificar'))
        <script>
            swal({
                title: "Error al modificar",
                text: "{{ session('errorModificar') }}",
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
        @include('layout.verticalMenuTeacher')
        <div id="content" class="mt-0 pt-0">            
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('teacherSite.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Mi Perfil</p>
                    </div>
                </div>
            </nav>    
            <div class="card mx-5">
				<div class="card-body cardBody-Teachers">
                    <p class="d-flex justify-content-center">Información general</p>
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
                    <div class="row mx-1">
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre</p>
                            {{ $informacionDocente[0]->nombreDocente.' '.$informacionDocente[0]->apellidoDocente}}
                        </div>
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">DUI</p>
                            {{$informacionDocente[0]->duiDocente}}
                        </div>
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Teléfono</p>
                            {{$informacionDocente[0]->numeroTelefono}}
                        </div>
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Correo electrónico</p>
                            {{$informacionDocente[0]->correoDocente}}
                        </div>                                                                
                    </div>  
                    <div class="separator mb-3 mt-3"></div>                 	       
                    <div class="row mx-1 mt-3 d-flex justify-content-center">                        
                        <div class="col-lg-4">
                            <div class="btn-group d-flex justify-content-center">
                                <a type="button" onclick="updateInformacionModal({{$informacionDocente[0]->idDocente}})" class="btn btn-primary mt-2 btn-block" style="background-color: #7386D5;">Actualizar información</a>
                            </div>
                        </div>                       
                    </div>         																												
				</div>
			</div>                 							
        </div>
    </div>	

    <!-- Modal para actualizar información-->
    <div class="modal fade" id="modificarInformacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Actualización de información</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('teacherSite.updateInfor') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtCorreoDocente" class="form-label" style="font-weight: bold">Correo del docente</label>                                
                                <input type="email" id="txtCorreoDocente" name="correoDocente" placeholder="Ingrese correo electrónico del docente" class="form-control inputTxt" value="{{$informacionDocente[0]->correoDocente}}">
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtTelefonoDocente" class="form-label" style="font-weight: bold">Teléfono del docente</label>                                
                                <input type="text" id="txtTelefonoDocente" name="telefonoDocente" placeholder="Ingrese teléfono del docente" class="form-control inputTxt" value="{{$informacionDocente[0]->numeroTelefono}}">
                            </div>    
                            <input type="text" id="txtIdDocente" name="idDocenteActualizar" value="{{$informacionDocente[0]->idDocente}}" hidden>                    
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
    
</body>
</html>