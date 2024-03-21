@extends('layout.header')


@section('title','Información candidato')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/rejectedInit.js') }}"></script>
<body style="overflow-x: hidden">  
    <script src="{{ asset('js/inactividad.js') }}"></script>  	
    @if (session('errorEliminacion'))
        <script>
            swal({
                title: "Error al eliminar candidato",
                text: "{{ session('errorEliminacion') }}",
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

    @if (session('errorAceptacion'))
        <script>
            swal({
                title: "Error al aceptar candidato",
                text: "{{ session('errorAceptacion') }}",
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
                    <a href="{{ route('student.rejected') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Información del seminarista</p>
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
                    <div class="row">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre</p>
                            {{  $rejectedCandidate->nombreEstudiante.' '.$rejectedCandidate->apellidoEstudiante }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">DUI</p>
                            {{  $rejectedCandidate->duiEstudiante }}                           
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de nacimiento</p>
                            {{date('d-m-Y',strtotime($rejectedCandidate->fechaNacimiento)) }}
                        </div>                       
                    </div>		
                    <div class="row mt-3">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de Bautismo</p>
                            {{  date('d-m-Y',strtotime($rejectedCandidate->fechaBautismo))  }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de Confirmación</p>
                            {{  date('d-m-Y',strtotime($rejectedCandidate->fechaConfirmacion))  }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de aplicación</p>
                            {{  date('d-m-Y',strtotime($rejectedCandidate->fechaIngreso)) }}    
                        </div>                        
                    </div>		
                    <div class="row mt-3">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Parroquía a la que pertenece</p>
                            {{  $rejectedCandidate->parroquia }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Número de celular</p>
                            {{  $rejectedCandidate->numeroMovil }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Número telefonico de casa</p>
                            {{  $rejectedCandidate->numeroTelefonicoCasa }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Correo electrónico</p>
                            {{  $rejectedCandidate->correoEstudiante }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre del padre de familia</p>
                            {{  $rejectedCandidate->nombrePadre }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre de la madre de familia</p>
                            {{  $rejectedCandidate->nombreMadre }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Dirección de residencia</p>
                            {{  $rejectedCandidate->direccion }}
                        </div>
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Enfermedades o padecimientos</p>
                            {{  $rejectedCandidate->enfermedades }}
                        </div>
                    </div>	
                    <div class="row mt-2 mx-2 d-flex justify-content-center">
                        <div class="col-lg-3 col-xs-12 mt-2">
                            <button 
                                type="button" 
                                class="btn btn-success icon-button"                               
                                value="{{$rejectedCandidate->idEstudiante}},{{$rejectedCandidate->nombreEstudiante.' '.$rejectedCandidate->apellidoEstudiante}}"
                                onclick="openAcceptModal(this.value)">                                
                                Aceptar candidato
                            </button>
                        </div>											
                        <div class="col-lg-3 col-xs-12 mt-2">
                            <button 
                                type="button" 
                                class="btn btn-danger icon-button"                                                                
                                value="{{$rejectedCandidate->idEstudiante}},{{$rejectedCandidate->nombreEstudiante.' '.$rejectedCandidate->apellidoEstudiante}}"
                                onclick="openDeleteModal(this.value)">
                                Eliminar candidato
                            </button>
                        </div>
                    </div>                   																													
				</div>
			</div>									           
        </div>
    </div>
    <!-- Modal para eliminar candidato-->
    <div class="modal fade" id="eliminarCandidato" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <form method="POST" action="{{ route('student.deleteCandidate')}}">
                        @csrf
                        @method('DELETE')
                        <input type="text" name="idCandidatoEliminar" id="txtCandidatoEliminar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<!-- Modal para aceptar candidato-->
    <div class="modal fade" id="aceptarCandidato" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Aceptar candidato</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="txtAcceptModal"></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('student.acceptCandidate')}}">
                        @csrf
                        @method('PUT')
                        <input type="text" name="idCandidatoAceptar" id="txtCandidatoAceptar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Aceptar candidato</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>