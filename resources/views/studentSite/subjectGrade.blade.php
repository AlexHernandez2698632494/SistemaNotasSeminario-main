@extends('layout.header')


@section('title','Inicio docentes')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/indexInit.js') }}"></script>
<script src="{{ asset('js/students/deleteModal.js') }}"></script>
<body style="overflow-x: hidden">    
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoEliminar'))
        <script>
            swal({
                title: "Registro eliminado",
                text: "{{ session('exitoEliminar') }}",
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

    @if (session('errorEliminar'))
        <script>
            swal({
                title: "Error al eliminar",
                text: "{{ session('errorEliminar') }}",
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
        @include('layout.verticalMenuStudent')
        <div id="content" class="mt-0 pt-0">
            {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">					  
					<div class="col d-flex justify-content-center">
						<p style="color: black; margin: 0; font-weight: bold">Calificaciones</p>
					</div>                                          
                </div>
            </nav>     --}}
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">
                    <!-- Botón con el icono de flecha izquierda -->
                    <a href="{{ route('studentSite.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <!-- Texto centrado -->
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Calificaciones</p>
                    </div>
                </div>
            </nav>
            <div class="row mx-5"> 
                @if ($information->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <b>Materia:</b> {{ $information[0]->nombreMateria}}<br>
                            <b>Nombre del grupo:</b> {{ $information[0]->nombreGrupo}}<br>                                                                        
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        No se han encontrado información
                    </div>
                @endif  
                
                
                    <p class="d-flex justify-content-center mt-3"><b>Listado de actividades</b></p>				
                    @if ($grades->count() > 0)
                        <table class="table my-2">
                            <thead >
                                <tr>
                                    <th scope="col" style="background-color: #7386d5; color:white">Actividad</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Porcentaje (%)</th>
                                    <th scope="col" style="background-color: #7386d5; color:white">Calificación</th>                                                                                              
                                </tr>
                            </thead>
                            <tbody>                                
                                @foreach ($grades as $grade)
                                    <tr>
                                        <td>{{ $grade->nombreEvaluacion }}</td>
                                        <td>{{ $grade->porcentaje }}%</td>
                                        <td>{{ $grade->nota }}</td>                                                                                                            
                                    </tr>  
                                @endforeach                                                                                                                                                                                                     
                            </tbody>
                        </table>

                        @if (!empty($extraEvaluationInfo))
                            <div class="card mt-3">
                                <div class="card-body"> 
                                    El seminaristas desarrolló una actividad extraordinaria<br>
                                    <b>Calificación de actividad extraordinaria: </b>{{ $extraEvaluationInfo[0]->nota }}                                                                                                     
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-body"> 
                                    El promedio alcanzado con la actividad extraordinaria es de: {{ $average[0]->promedio }}<br>                                                                                                                                   
                                </div>
                            </div> 
                        @endif

                        @if ($information[0]->estadoFinalizacion == 0 && !isset($extraEvaluationInfo) && $average->count() > 0)
                            <div class="card mt-3">
                                <div class="card-body"> 
                                    Ya se han asignado las calificaciones de todas las actividades de esta asignatura<br>
                                    <b>Promedio final de esta asignatura: </b>{{ $average[0]->promedio }}                                                                                                     
                                </div>
                            </div>                                                                           
                        @endif
                    @else
                        <div class="alert alert-warning" role="alert">
                            No se han agregado calificaciones 
                        </div>
                    @endif                                                                         
            </div>                     							
        </div>
    </div>	
    
</body>
</html>