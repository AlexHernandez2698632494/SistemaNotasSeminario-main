@extends('layout.header')



@section('title','Información de docente')

<body>   	
	<script src="{{ asset('js/inactividad.js') }}"></script>
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
                        <p style="color: black; margin: 0; font-weight: bold">Información del docente</p>
                    </div>
                </div>
            </nav>
			<div class="row">
				<div class="col-lg-4 col-xs-12">
					<div class="card">
						<div class="card-body">
							<p class="d-flex justify-content-center">Información general del docente</p>
							<div class="separator"></div>	
							<div class="row mt-2">								
								<div class="col-12"><b>Nombre</b></div>
								<div class="col-12">{{ $teacherInfo->nombreDocente.' '.$teacherInfo->apellidoDocente}}</div>
							</div>
							<div class="row mt-2">								
								<div class="col-12"><b>DUI</b></div>
								<div class="col-12">{{ $teacherInfo->duiDocente }}</div>
							</div>
							<div class="row mt-2">								
								<div class="col-12"><b>Número de teléfono</b></div>
								<div class="col-12">{{ $teacherInfo->numeroTelefono}}</div>
							</div>
							<div class="row mt-2">								
								<div class="col-12"><b>Correo</b></div>
								<div class="col-12">{{ $teacherInfo->correoDocente}}</div>
							</div>
						</div>																					
					</div>  
				</div>
				<div class="col-lg-8 col-xs-12">
					<div class="card InfoTitlesSubjectsCard">
						<div class="card-body titlesSubject-container">	
							<p class="d-flex justify-content-center">Títulos y materias que puede impartir</p>
							<div class="separator"></div>
							<div class="row mt-2">
								<div class="col-lg-6 col-xs-12">
									<p class="d-flex justify-content-center"><b>Materias que el docente puede impartir</b></p>
									<div class="col">
										<ul>		
											@if (count($teacherSubjects) > 0)
												@foreach ($teacherSubjects as $teacherSubject)
													<li> {{ $teacherSubject->nombreMateria}}<br>{{$teacherSubject->nombreEtapa.' Año '.$teacherSubject->anio.' '.$teacherSubject->cuatrimestre}}</li><br>
												@endforeach	
											@else
												<div class="alert alert-warning">
													No se encontró información
												</div>
											@endif																																																																
										</ul>
									</div>
								</div>	
												
								<div class="col-lg-6 col-xs-12">
									<p class="d-flex justify-content-center"><b>Títulos</b></p>
									<div class="col">
										<ul>
											@if (count($teacherTitles) > 0)
												@foreach ($teacherTitles as $teacherTitle)
													<li> {{ $teacherTitle->tituloDocente }}</li>
												@endforeach		
											@else
												<div class="alert alert-warning">
													No se encontró información
												</div>
											@endif																																																				
										</ul>
									</div>	
								</div>
							</div>												
						</div>																					
					</div>  
				</div>				
			</div>    
		</div>                                                                            
    </div>
    
</body>

</html>
