@extends('layout.header')


@section('title','Historial seminarista')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/indexInit.js') }}"></script>

<body style="overflow-x: hidden">    	
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('noData'))
        <script>
            swal({
                title: "No se encontró información",
                text: "{{ session('noData') }}",
                icon: "info",
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
                        <p style="color: black; margin: 0; font-weight: bold">Historial del seminarista</p>
                    </div>
                </div>
            </nav>                    				
			<div class="card mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Información del seminarista</p>
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
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre</p>
                            {{  $student->nombreEstudiante.' '.$student->apellidoEstudiante }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">DUI</p>
                            {{ $student->duiEstudiante }}
                        </div>
                        <div class="col-lg-3 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de ingreso</p>
                            {{ $student->fechaIngreso }}                       
                        </div>                                                                       
                    </div>  
                    <div class="separator mb-3 mt-3" style="height: 2px;"></div>                 	       
                    <div class="row mx-1 mt-3">                        
                        <div class="col-lg-4 col-xs-12 col-md-6">
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Asignaturas cursadas</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">{{ $subjectStudied[0]->cantidadMaterias }}</h3>                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-12 col-md-6">                            
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Promedio</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">
                                        @if ($average[0]->promedio != null)
                                            {{ $average[0]->promedio }}
                                        @else
                                            0
                                        @endif                                        
                                    </h3>                                    
                                </div>
                            </div>                          
                        </div>
                        <div class="col-lg-4 col-xs-12 col-md-6">
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Porcentaje de avance</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">{{ $percent }} %</h3>                                    
                                </div>
                            </div>
                        </div>
                    </div>            																												
				</div>
			</div>	
            <div class="card mx-5 mt-3">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Asignaturas cursadas</p>
					<div class="separator mb-3"></div>	
                    <div class="row justify-content-center">
                        <div class="col-lg-3 col-xs-12 col-md-6 d-flex mt-3">
                           <button type="submit" class="btn btn-block btn-Add" onclick="generarReporteModal()">Generar Acta de Calificaciones</button>                                
                        </div>
                    </div>   
                    <br>                           
                    <div class="row mx-2">
                        @if (!empty($subjects[0]))
                        <table class="table">
                            <thead >
                                <tr>
                                    <th scope="col" style="background-color: #7386d5; color:white" hidden>Nivel</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Año</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Etapa</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Asignatura</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Promedio</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Convocatoria</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Estado</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td hidden>{{ $subject->nivel }}</td>
                                        <td>{{ $subject->anio }}</td>
                                        <td>{{$subject->nombreEtapa}}<br>{{$subject->cuatrimestre}}</td>
                                        <td>{{ $subject->nombreMateria }}</td>
                                        <td>{{ $subject->promedio }}</td>
                                        <td>{{ $subject->convocatoria }}</td>
                                        <td>
                                            @if ($subject->promedio>=7) Aprobada
                                            @else Reprobada
                                            @endif
                                        </td>
                                    </tr>  
                                @endforeach                                                                                                                                                                        
                            </tbody>
                        </table> 
                        @else
                            <div class="alert alert-warning" role="alert">
                                No se han encontrado registros
                            </div>   
                        @endif                                                                                                                    
                    </div>                                 																												
				</div>
			</div>								           
        </div>
    </div>
    <!-- Modal para generar reporte-->
    <div class="modal fade" id="generarReporte" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Generación de acta de calificaciones</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('pdf.actaCalificacion',$student->idEstudiante) }}">
                    @csrf
                <div class="modal-body">
                    <p id="txtDeleteModal">Introducir la información necesaria para generar el reporte:</p>
                    <div class="col-lg-12 col-xs-12 col-md-6 mt-2">
                        <label for="txtAnio" class="form-label"><b>Año</b></label>
                        <input type="text" id="txtAnio" name="anio"
                            placeholder="Ingrese año del Reporte" class="form-control inputTxt"
                            maxlength="4" required>
                    </div>
                    <div class="col-lg-12 col-xs-12 col-md-6 mt-2">
                        <label for="txtName" class="form-label"><b>Prefecto de estudios</b></label>
                        <input type="text" id="txtName" name="name"
                            placeholder="Ingrese nombre del prefecto de estudios" class="form-control inputTxt"
                            required>
                    </div>
                </div>
                <div class="modal-footer">                       
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Generar reporte</button>
                </div>
            </form> 
            </div>
        </div>
    </div>	
    <script src="{{ asset('js/students/validarNota.js') }}" defer></script> 
</body>
</html>