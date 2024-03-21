@extends('layout.header')


@section('title','Información seminarista')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/addInitTxt.js') }}"></script>
<script src="{{ asset('js/students/updateModal.js') }}"></script>
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
        @include('layout.verticalMenu')
        <div id="content" class="mt-0 pt-0">            
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('student.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
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
                            {{  $student->nombreEstudiante.' '.$student->apellidoEstudiante }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">DUI</p>
                            {{  $student->duiEstudiante }}                           
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de nacimiento</p>
                            {{date('d-m-Y',strtotime($student->fechaNacimiento)) }}
                        </div>                       
                    </div>		
                    <div class="row mt-3">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de Bautismo</p>
                            {{  date('d-m-Y',strtotime($student->fechaBautismo))  }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de Confirmación</p>
                            {{  date('d-m-Y',strtotime($student->fechaConfirmacion))  }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de ingreso al seminario</p>
                            {{  date('d-m-Y',strtotime($student->fechaIngreso)) }}    
                        </div>                        
                    </div>		
                    <div class="row mt-3">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Parroquía a la que pertenece</p>
                            {{  $student->parroquia }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Número de celular</p>
                            {{  $student->numeroMovil }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Número telefonico de casa</p>
                            {{  $student->numeroTelefonicoCasa }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Correo electrónico</p>
                            {{  $student->correoEstudiante }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre del padre de familia</p>
                            {{  $student->nombrePadre }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre de la madre de familia</p>
                            {{  $student->nombreMadre }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Dirección de residencia</p>
                            {{  $student->direccion }}
                        </div>
                        <div class="col-lg-6 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Enfermedades o padecimientos</p>
                            {{  $student->enfermedades }}
                        </div>
                    </div>	
                    <div class="row mt-3 mx-3 d-flex justify-content-center">
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-secondary" onclick="openUpdateModal({{$student->idEstudiante}})">Modificar información</button>
                        </div>
                    </div>																														
				</div>
			</div>									           
        </div>
    </div>
	<!-- Modal para modificar estudiante-->
    <div class="modal fade" id="updateStudent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Actualización de información</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('student.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <label for="nombreEstudiante" class="form-label" style="font-weight: bold">Nombre del seminarista</label>                                
                                <input type="text" id="txtNombreEstudiante" name="nombreSeminarista" placeholder="Ingrese nombre del seminarista" class="form-control inputTxt" value="{{old('nombreSeminarista')}}">
                            </div> 
                            <div class="col-lg-6 col-xs-12">
                                <label for="apellidoEstudiante" class="form-label" style="font-weight: bold">Apellido del seminarista</label>                                
                                <input type="text" id="txtApellidoEstudiante" name="apellidoSeminarista" placeholder="Ingrese apellido del seminarista" class="form-control inputTxt" value="{{old('apellidoSeminarista')}}">
                            </div>                       
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtDui" class="form-label" style="font-weight: bold">Número de DUI</label>                                
                                <input type="text" id="txtDui" name="duiSeminarista" placeholder="Ingrese DUI del seminarista" class="form-control inputTxt" value="{{old('duiSeminarista')}}">
                            </div> 
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtCorreo" class="form-label" style="font-weight: bold">Correo</label>                                
                                <input type="text" id="txtCorreo" name="correoSeminarista" placeholder="Ingrese correo del seminarista" class="form-control inputTxt" value="{{old('correoSeminarista')}}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtCelular" class="form-label" style="font-weight: bold">Número de celular</label>                                
                                <input type="text" id="txtCelular" name="numeroCelular" placeholder="Ingrese número de celular" class="form-control inputTxt" value="{{old('numeroCelular')}}">
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <label for="txtTelefonoCasa" class="form-label" style="font-weight: bold">Número de teléfono de casa</label>                                
                                <input type="text" id="txtTelefonoCasa" name="telefonoCasa" placeholder="Ingrese teléfono" class="form-control inputTxt txtPhone" value="{{old('telefonoCasa')}}">
                            </div>
                        </div>                    
                        <div class="row mt-2">
                            <div class="col-12">
                                <label for="txtDireccion" class="mb-1" style="font-weight: bold">Dirección de residencia</label>
                                <textarea class="form-control" placeholder="Ingrese la dirección de residencia" id="txtDireccion" name="direccionResidencia" style="height: 100px" class="inputTxt">{{old('direccionResidencia')}}</textarea>
                                <input id="txtIdEstudiante" name="idSeminarista" hidden>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <label for="txtEnfermedades" class="mb-1" style="font-weight: bold">Enfermedades o condiciones especiales que padece</label>
                                <textarea class="form-control" placeholder="Ingrese el o los padecimientos" id="txtEnfermedades" name="enfermedades" style="height: 100px" class="inputTxt">{{old('enfermedades')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">                                           
                        <input type="text" name="idDocenteEliminar" id="txtIdDocenteEliminar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-warning" style="color: white">Modificar</button>                   
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>