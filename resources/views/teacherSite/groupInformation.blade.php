@extends('layout.header')


@section('title','Información del grupo')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/groups/endGroup.js') }}"></script>

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


    @if (session('errorAgregarHistorial'))
        <script>
            swal({
                title: "Error al finalizar grupo",
                text: "{{ session('errorAgregarHistorial') }}",
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

    @if (session('informacionAgregarHistorial'))
        <script>
            swal({
                title: "Información",
                text: "{{ session('informacionAgregarHistorial') }}",
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

    @if (session('exitoAgregarHistorial'))
        <script>
            swal({
                title: "Grupo finalizado",
                text: "{{ session('exitoAgregarHistorial') }}",
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
                        <p style="color: black; margin: 0; font-weight: bold">Grupo de clases</p>
                    </div>
                </div>
            </nav>
            <div class="card mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Información del grupo</p>
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
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Asignatura</p>
                            {{ $groupInformation[0]->nombreMateria }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre del grupo</p>
                            {{ $groupInformation[0]->nombreGrupo }}                            
                        </div>
                        <div class="col-lg-3 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de creación</p>
                            {{ $groupInformation[0]->anio }}                                        
                        </div>                                                                       
                    </div>  
                    <div class="separator mb-3 mt-3" style="height: 2px;"></div>                 	       
                    <div class="row mx-1 mt-3 d-flex justify-content-center">                        
                        <div class="col-lg-4 col-xs-12 col-md-6">
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Seminaristas</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">{{ $studentsQuantity }}</h3>                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-12 col-md-6">                            
                            <div class="card mb-3" style="max-width: 18rem; background-color: #7386d5">
                                <div class="card-header" style="color: white">Cantidad de actividades</div>
                                <div class="card-body">
                                    <h3 class="card-title d-flex justify-content-center" style="color: white">
                                        {{ $evaluationQuantity }}                                                                           
                                    </h3>                                    
                                </div>
                            </div>                          
                        </div>                       
                    </div>
                    <p class="d-flex justify-content-center">Acciones</p>
					<div class="separator mb-3" style="height: 2px;"></div>	
                    <div class="row mx-1">
                        <div class="row mt-3 mx-3">
                            <div class="col-lg-4">
                                <div class="btn-group d-flex justify-content-center">
                                    <a href="{{ route('teacherSite.gestionEvaluaciones',$groupInformation[0]->idGrupo) }}" class="btn btn-primary mt-2 btn-block" style="background-color: #7386D5;">Gestión de actividades</a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="btn-group d-flex justify-content-center">
                                    <a href="{{ route('teacherSite.showEvaluations',$groupInformation[0]->idGrupo) }}" class="btn btn-success mt-2 btn-block">Asignación de notas</a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="btn-group d-flex justify-content-center">
                                    @if ($groupInformation[0]->estadoFinalizacion == 1)
                                        <a 
                                            class="btn btn-secondary mt-2 btn-block"
                                            data-id="{{ $groupInformation[0]->idGrupo }}"
                                            data-nombre="{{ $groupInformation[0]->nombreGrupo }}"
                                            data-materia="{{ $groupInformation[0]->nombreMateria }}"
                                            onclick="showModalEndGroup(this)">Finalizar grupo
                                        </a>                                                                                                                   
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>            																												
				</div>
			</div>    
            <div class="card mx-5 my-2">
                <div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Seminaristas</p>
					<div class="separator mb-3" style="height: 2px;"></div>	
                    <div class="row d-flex justify-content-center">
                        @if ($groupInformation[0]->estadoFinalizacion == 0)
                            <div class="col-3">
                                <a href="{{ route('pdf.cuadroNotas',$groupInformation[0]->idGrupo) }}" class="btn btn-primary" style="background-color: #7386d5">Generar reporte de grupo</a>  
                            </div>
                        @else
                            
                        @endif                              
                    </div>
                    <table class="table data-table table-striped" id="teachers-table">
                        <thead class="table-head">
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Apellido</th>                                                               
                            </tr>
                        </thead>
                        <div class="table-body">
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->nombreEstudiante }}</td>
                                        <td>{{ $student->apellidoEstudiante }}</td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </div>
                    </table>                          																												
				</div>
            </div>                 							
        </div>
    </div>	

    <!-- Modal para finalizar grupo-->
    <div class="modal fade" id="finalizarGrupo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Verificación de finalización</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="txtEndModal"></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('teacherSite.endGroup')}}">
                        @csrf                        
                        <input type="text" name="idGrupo" id="txtIdGrupoFinalizar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-warning" style="color: white">Finalizar grupo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>