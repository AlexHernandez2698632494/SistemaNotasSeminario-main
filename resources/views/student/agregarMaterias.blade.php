@extends('layout.header')



@section('title','Registro de seminaristas')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/prueba.js') }}"></script>
<script src="{{ asset('js/students/addInitTxt.js') }}"></script>
<body>   
    <script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoMateria'))
        <script>
            swal({
                title: "Registro agregado",
                text: "{{ session('exitoMateria') }}",
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

    @if (session('errorMateria'))
        <script>
            swal({
                title: "Error al registrar",
                text: "{{ session('errorMateria') }}",
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
                    <div class="col d-flex justify-content-center">
                        <p style="color: black; margin: 0; font-weight: bold">Registro de materias previamente aprobadas</p>
                    </div>                                      
                </div>
            </nav>
                <div class="col-12 mx-8 my-2">
                    <div class="card mx-5">
                        <div class="card-body">
                            <h5 class="card-title">Información de seminarista registrado:</h5>
                            <p><b>Nombre: </b>{{ $estudiante[0]->nombreEstudiante.' '.$estudiante[0]->apellidoEstudiante }}
                                <br><b>DUI: </b>{{ $estudiante[0]->duiEstudiante }}</p>
                        </div>
                    </div>
                </div> 
                                        
            <div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">	
                    
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
                                    <th scope="col" hidden>Nivel</th>
                                    <th scope="col">Etapa</th>
									<th scope="col">Materia</th>
									<th scope="col">Nota</th>
                                    <th scope="col">Año</th>
                                    <th scope="col">Convocatoria</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<div class="table-body">
								<tbody>	
									@foreach ($materias as $materia)
										<tr>
                                            <form method="POST" action="{{ route('student.registroMateria', [$estudiante[0]->idEstudiante, $materia->idMateria]) }}">
                                                @csrf
                                            <td hidden>{{$materia->nivel}}</td>
                                            <td>{{$materia->nombreEtapa}}<br>{{$materia->cuatrimestre.' Año '.$materia->anio}}</td>
											<td>{{ $materia->nombreMateria }}</td>
											<td><div class="row">
                                                    <div class="col-lg-8 col-xs-12 mt-0">                                    
                                                        <input type="text" id="txtNota" name="nota{{$materia->idMateria}}" placeholder="Ingrese nota" class="form-control inputTxt" maxlength="3" required>                                    
                                                    </div>
                                                </div>
                                            </td>
                                            <td><div class="row">
                                                    <div class="col-lg-8 col-xs-12 mt-0">                                    
                                                        <input type="text" id="txtAnio" name="anio{{$materia->idMateria}}" placeholder="Ingrese año" class="form-control inputTxt" maxlength="4" required>                                    
                                                    </div>
                                                </div>
                                            </td>
                                            <td><div class="row">
                                                <div class="col-lg-8 col-xs-12 mt-0"> 
                                                    <select name="convocatoria{{$materia->idMateria}}" id="selectConvocatoria">
                                                        <option value="Ordinaria" selected>Ordinaria</option>
                                                        <option value="Extraordinaria">Extraordinaria</option>
                                                    </select>                                                                                       
                                                </div>
                                            </div>
                                        </td>
											<td>
												<div class="row">
													<div class="col-4 mx-0 px-0">
														<button type="submit" class="btn btn-block btn-Add">Registrar</button>
													</div>
												</div>																									
											</td>
                                        </form> 
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
    <script src="{{ asset('js/students/validarNota.js') }}" defer></script>          
</body>

</html>
