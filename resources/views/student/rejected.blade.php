@extends('layout.header')


@section('title','Candidatos rechazados')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/indexRejected.js') }}"></script>

<body style="overflow-x: hidden">    
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoEliminacion'))
        <script>
            swal({
                title: "Candidato eliminado",
                text: "{{ session('exitoEliminacion') }}",
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

    @if (session('exitoAceptacion'))
        <script>
            swal({
                title: "Candidato aceptado",
                text: "{{ session('exitoAceptacion') }}",
                icon: "success",
                button: "OK",               
            }).then(function(){
			window.open("/pdf/estudianteAceptado.pdf","blank")
		})             
        </script>
    @endif

    @if (session('errorRestaurar'))
        <script>
            swal({
                title: "Error al restaurar",
                text: "{{ session('errorRestaurar') }}",
                icon: "error",
                button: "OK",
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
                        <p style="color: black; margin: 0; font-weight: bold">Candidatos rechazados</p>
                    </div>
                </div>
            </nav>                				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Candidatos que fueron rechazados</p>
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
									<th scope="col">Accion</th>									
								</tr>
							</thead>
							<div class="table-body">
								<tbody>	
									@foreach ($rejectedStudents as $rejectedStudent)
										<tr>
											<td>{{ $rejectedStudent->nombreEstudiante.' '.$rejectedStudent->apellidoEstudiante }}</td>
											<td>{{ $rejectedStudent->duiEstudiante }}</td>											
											<td>
												<div class="row">		
                                                    <div class="col-auto">
														<a type="button" class="btn btn-primary icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver información" href="{{ route('student.rejectedInfo',$rejectedStudent->idEstudiante)}}"><i class="fa-solid fa-eye my-1"></i></a>
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
    
</body>
</html>