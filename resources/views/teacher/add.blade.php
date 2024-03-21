@extends('layout.header')



@section('title','Registro de docentes')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/teachers/initTeacherAdd.js') }}"></script>
<body>   
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoRegistro'))
        <script>
            swal({
			title: "Registro agregado",
			text: "{{ session('exitoRegistro') }}",
			icon: "success",
			button: "OK",
			}).then(function(){
				window.open("/pdf/docente.pdf","blank")
			})			
        </script>		
	@endif

	@if (session('errorRegistro'))
        <script>
            swal({
			title: "Error al registrar",
			text: "{{ session('errorRegistro') }}",
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
                    <a href="{{ route('teachers.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Registro de docentes</p>
                    </div>
                </div>
            </nav>                           
            <div class="card teacherAdd-Card">
                <div class="card-body">
            	<p class="d-flex justify-content-center">Registro de docentes</p>
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
						<form method="POST" action="{{route('teachers.add')}}">
							@csrf
							@method('POST')
							<div class="row">
								<div class="col-lg-5 col-xs-12">
									<p class="d-flex justify-content-center mt-2 mb-0">Información general</p>
									<p class="d-flex justify-content-center mt-0 subtitle">Ingrese la información que se solicita</p>
									<div class="input-group mb-3 mt-3">
										<span class="input-group-text" id="teacherName"><i class="fa-solid fa-person"></i></span>
										<input type="text" class="form-control" placeholder="Ingrese nombre" aria-label="name" name="nombreDocente" value="{{old('nombreDocente')}}">
									</div>
									<div class="input-group mb-3 mt-3">
										<span class="input-group-text" id="teacherLastName"><i class="fa-solid fa-person"></i></span>
										<input type="text" class="form-control" placeholder="Ingrese apellido" aria-label="lastname" name="apellidoDocente" value="{{old('apellidoDocente')}}">
									</div>
									<div class="input-group mb-3 mt-3">
										<span class="input-group-text" id="teacherDui"><i class="fa-solid fa-id-card"></i></span>
										<input type="text" class="form-control" placeholder="Ingrese DUI" aria-label="dui" id="txtDui" name="duiDocente" value="{{old('duiDocente')}}">
									</div>
									<div class="input-group mb-3 mt-3">
										<span class="input-group-text" id="teacherPhone"><i class="fa-solid fa-phone"></i></span>
										<input type="text" class="form-control txtPhone" id="txtPhone" placeholder="Ingrese número de teléfono" aria-label="phone" name="telefonoDocente" value="{{old('telefonoDocente')}}">
									</div>
									<div class="input-group mb-3 mt-3">
										<span class="input-group-text" id="teacherEmail"><i class="fa-solid fa-envelope"></i></span>
										<input type="email" class="form-control" placeholder="Ingrese correo electrónico" aria-label="email" name="correoDocente" value="{{old('correoDocente')}}">
									</div>	
									<div class="input-group mb-3 mt-3">
										<span class="input-group-text" id="teacherTitles"><i class="fa-solid fa-graduation-cap"></i></span>
										<textarea class="form-control" placeholder="Ingrese titulos del docentes separados por coma" aria-label="teacherTitles" name="titulosDocente" rows="3">{{old('titulosDocente')}}</textarea>
									</div>									
								</div>
								<div class="col-lg-7 col-xs-12 subjects-container">
									<p class="d-flex justify-content-center mt-2 mb-0">Seleccione las asignaturas que el docente puede impartir</p>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Primero de Filosofía Cuatrimestre 1</p>
										@foreach ($materias as $materia)
											@if ($materia->nivel==1)
											<div class="col-lg-4 col-xs-12 ">
												<div class="form-check mt-3">
													<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
													<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
														{{ $materia->nombreMateria }}
													</label>
												</div>
											</div>
											@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Primero de Filosofía Cuatrimestre 2</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==2)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Bienio Filosófico Ciclo 1 Cuatrimestre 1</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==3)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Bienio Filosófico Ciclo 1 Cuatrimestre 2</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==4)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Bienio Filosófico Ciclo 2 Cuatrimestre 1</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==5)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Bienio Filosófico Ciclo 2 Cuatrimestre 2</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==6)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Primero de Teología Cuatrimestre 1</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==7)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Primero de Teología Cuatrimestre 2</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==8)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Trienio Teológico Ciclo 1 Cuatrimestre 1</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==9)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Trienio Teológico Ciclo 1 Cuatrimestre 2</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==10)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Trienio Teológico Ciclo 2 Cuatrimestre 1</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==11)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Trienio Teológico Ciclo 2 Cuatrimestre 2</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==12)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Trienio Teológico Ciclo 3 Cuatrimestre 1</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==13)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
									<br><div class="separator"></div><br>
									<div class="row">
										<p class="d-flex justify-content-center mt-0">Trienio Teológico Ciclo 3 Cuatrimestre 2</p>
										@foreach ($materias as $materia)
										@if ($materia->nivel==14)
										<div class="col-lg-4 col-xs-12 ">
											<div class="form-check mt-3">
												<input class="form-check-input" type="checkbox" value="{{ $materia->idMateria }}" id="checkSubject{{$materia->idMateria}}" name="materias[]" {{ in_array($materia->idMateria, old('materias', [])) ? 'checked' : '' }}>
												<label class="form-check-label" for="checkSubject{{$materia->idMateria}}">
													{{ $materia->nombreMateria }}
												</label>
											</div>
										</div>
										@endif	
										@endforeach																		
									</div>
								</div>
							</div>
							<div class="row mx-2 my-2">
								<div class="col d-flex justify-content-center">
									<button type="submit" class="btn btn-block btn-Add">Registrar docente</button>
								</div>								
							</div>
						</form>
                </div>
            </div>                                             
    </div>	
    
</body>

</html>
