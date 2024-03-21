@extends('layout.header')



@section('title','Registro de seminaristas')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/prueba.js') }}"></script>
<script src="{{ asset('js/students/addInitTxt.js') }}"></script>
<body>   
    <script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoAgregar'))
        <script>
            swal({
                title: "Registro agregado",
                text: "{{ session('exitoAgregar') }}",
                icon: "success",
                button: "OK",
            }).then(function(){
			window.open("/pdf/estudiante.pdf","blank")
		})          
        </script>
    @endif
    @if (session('exitoAgregarRechazado'))
        <script>
            swal({
                title: "Registro agregado",
                text: "{{ session('exitoAgregarRechazado') }}",
                icon: "success",
                button: "OK",closeOnClickOutside: false,
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
                button: "OK",closeOnClickOutside: false,
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
                    <a href="{{ route('student.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Registro de nuevos seminarista</p>
                    </div>
                </div>
            </nav>                          
            <div class="card teacherAdd-Card" id="data">
                <div class="card-body">
                <p class="d-flex justify-content-center">Registro de seminarista</p>
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
						<form method="POST" action="{{ route('student.store') }}">
							@csrf							
							<div class="row">
								<div class="col-12">		
									<p class="d-flex justify-content-center mt-2 mb-0">Información general del seminarista</p>
									<p class="d-flex justify-content-center mt-0 subtitle">Ingrese la información que se solicita</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-xs-12 mt-2">                                    
                                    <label for="txtNombreSeminarista" class="form-label">Nombre</label>                                
                                    <input type="text" id="txtNombreSeminarista" name="nombreSeminarista" placeholder="Ingrese nombre" class="form-control inputTxt"  value="{{old('nombreSeminarista')}}" required>                                    
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtApellidoSeminarista" class="form-label">Apellido</label>                                
                                    <input type="text" id="txtApellidoSeminarista" name="apellidoSeminarista" placeholder="Ingrese apellido" class="form-control inputTxt" value="{{old('apellidoSeminarista')}}" required>
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtNacimientoSeminarista" class="form-label">Fecha de nacimiento</label>                                
                                    <input type="date" id="txtNacimientoSeminarista" name="fechaNacimientoSeminarista" placeholder="Seleccione fecha" name="fechaNacimientoSeminarista" class="form-control datepicker inputTxt" value="{{old('fechaNacimientoSeminarista')}}" required>
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtDui" class="form-label">DUI</label>                                
                                    <input type="text" id="txtDui"  name="duiSeminarista" placeholder="Ingrese DUI" class="form-control inputTxt" value="{{old('duiSeminarista')}}" required>                                
                                </div>
                            </div>			
                            <div class="row mt-2">
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="fechaBautismo" class="form-label">Fecha de bautismo</label>                                
                                    <input type="date" id="fechaBautismo" name="fechaBautismo" placeholder="Seleccione fecha" class="form-control datepicker inputTxt" value="{{old('fechaBautismo')}}" required>                                                                 
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtConfirmacion" class="form-label">Fecha de confirmación</label>                                
                                    <input type="date" id="txtConfirmacion"  name="fechaConfirmacion" placeholder="Seleccione fecha" class="form-control datepicker inputTxt" value="{{old('fechaConfirmacion')}}" required>                                                                 
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtParroquia" class="form-label">Parroquia a la que pertenece</label>                                
                                    <input type="text" id="txtParroquia"  name="nombreParroquia" placeholder="Ingrese parroquia" class="form-control inputTxt" value="{{old('nombreParroquia')}}">                                                                 
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">
                                    <label for="txtTelefonoCasa" class="form-label">Número de teléfono de casa</label>                                
                                    <input type="text" id="txtTelefonoCasa" name="telefonoCasa" placeholder="Ingrese teléfono" class="form-control inputTxt txtPhone" value="{{old('telefonoCasa')}}">                                                                                                    
                                </div>                                
                            </div>	
                            <div class="row mt-2">                               
                                <div class="col-lg-3 col-xs-12 mt-2">                                    
                                    <label for="txtCelular" class="form-label">Número de celular</label>                                
                                    <input type="text" id="txtCelular" name="numeroCelular" placeholder="Ingrese número de celular" class="form-control inputTxt" value="{{old('numeroCelular')}}">
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">                                    
                                    <label for="txtPadreFamilia" class="form-label">Nombre del padre de familia</label>                                
                                    <input type="text" id="txtPadreFamilia"  placeholder="Ingrese nombre del padre" name="nombrePadre" class="form-control inputTxt" value="{{old('nombrePadre')}}">
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">                                    
                                    <label for="txtMadreFamilia" class="form-label">Nombre de la madre de familia</label>                                
                                    <input type="text" id="txtMadreFamilia"  placeholder="Ingrese nombre de la madre" name="nombreMadre" class="form-control inputTxt" value="{{old('nombreMadre')}}">
                                </div>
                                <div class="col-lg-3 col-xs-12 mt-2">                                    
                                    <label for="txtAñoIngreso" class="form-label">Fecha de ingreso</label>                                
                                    <input type="date" id="txtAñoIngreso"  placeholder="Seleccione fecha de ingreso" name="fechaIngreso" class="form-control  datePicker inputTxt" value="{{old('fechaIngreso')}}">
                                </div>
                            </div>	
                            <div class="row mt-2">                                                               
                                <div class="col-lg-6 col-xs-12 mt-2">                                    
                                    <label for="txtDireccion" class="mb-1">Dirección de residencia</label>
                                    <textarea class="form-control" placeholder="Ingrese la dirección de residencia" id="txtDireccion" name="direccionResidencia" style="height: 100px" class="inputTxt">{{old('direccionResidencia')}}</textarea>                                    
                                </div>
                                <div class="col-lg-6 col-xs-12 mt-2">                                    
                                    <label for="txtEnfermedades" class="mb-1">Enfermedades o condiciones especiales que padece</label>
                                    <textarea class="form-control" placeholder="Ingrese el o los padecimientos" id="txtEnfermedades" name="enfermedades" style="height: 100px" class="inputTxt">{{old('enfermedades')}}</textarea>                                    
                                </div>
                            </div>	
                            <div class="row mt-2">
                                <div class="col-lg-6 col-xs-12 mt-2">                                    
                                    <label for="txtAñoIngreso" class="form-label">Correo electrónico</label>                                
                                    <input type="email" id="txtCorreoSeminarista"  placeholder="Ingrese correo" name="correoSeminarista" class="form-control  inputTxt" value="{{old('correoSeminarista')}}" required>                                    
                                </div>
                                <div class="col-lg-6 col-xs-12 mt-2">
                                    <label for="txtEtapa" class="form-label">Estado de aceptación</label>
                                    <select class="form-select" aria-label="Default select example" id="txtEstadoAceptacion" name="estadoAceptacion">
                                        <option value="1">Aceptado</option>
                                        <option value="0">Rechazado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-6 col-xs-12 mt-2">
                                    <label for="txtNivel" class="form-label">Nivel de avance</label>
                                    <select class="form-select" aria-label="Default select example" id="txtNivel" name="txtNivel">
                                        <option value="0">Nuevo Ingreso</option>
                                        <option value="1">Primer Año de Filosofía Cuatrimestre 1</option>
                                        <option value="2">Primer Año de Filosofía Cuatrimestre 2</option>
                                        <option value="3">Bienio Filosófico Ciclo 1 Cuatrimestre 1</option>
                                        <option value="4">Bienio Filosófico Ciclo 1 Cuatrimestre 2</option>
                                        <option value="5">Bienio Filosófico Ciclo 2 Cuatrimestre 1</option>
                                        <option value="6">Bienio Filosófico Ciclo 2 Cuatrimestre 2</option>
                                        <option value="7">Primer Año de Teología Cuatrimestre 1</option>
                                        <option value="8">Primer Año de Teología Cuatrimestre 2</option>
                                        <option value="9">Trienio Teológico Ciclo 1 Cuatrimestre 2</option>
                                        <option value="10">Trienio Teológico Ciclo 1 Cuatrimestre 1</option>
                                        <option value="11">Trienio Teológico Ciclo 2 Cuatrimestre 1</option>
                                        <option value="12">Trienio Teológico Ciclo 2 Cuatrimestre 2</option>
                                        <option value="13">Trienio Teológico Ciclo 3 Cuatrimestre 1</option>
                                        <option value="14">Trienio Teológico Ciclo 3 Cuatrimestre 2</option>
                                    </select>
                                </div>
                            </div>	    																																							
							<div class="row mx-2 my-2 mt-3">
								<div class="col d-flex justify-content-center">
									<button type="submit" class="btn btn-block btn-Add">Registrar seminarista</button>
								</div>								
							</div>
						</form>
                </div>
            </div>              
        </div>                                          
    </div>          
</body>

</html>
