@extends('layout.header')


@section('title','Seminaristas reprobados')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/students/indexFailed.js') }}"></script>

<body style="overflow-x: hidden">    
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoAgregar'))    
        <script>
            swal({
                title: "Actividad asignada",
                text: "{{ session('exitoAgregar') }}",
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

    @if (session('errorAgregar'))
        <script>
            swal({
                title: "Error al agregar actividad",
                text: "{{ session('errorAgregar') }}",
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

    @if (session('exitoAgregarCalificacion'))
        <script>
            swal({
                title: "Calificación asignada",
                text: "{{ session('exitoAgregarCalificacion') }}",
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

    @if (session('errorAgregarCalificacion'))
        <script>
            swal({
                title: "Error al agregar calificación",
                text: "{{ session('errorAgregarCalificacion') }}",
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

    @if (session('estudianteReprobado'))
        <script>
            swal({
                title: "El seminarista ha reprobado la asignatura",
                text: "{{ session('estudianteReprobado') }}",
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
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Seminaristas reprobados extraordinarios</p>
                    </div>
                </div>
            </nav>                  
            <div class="card mx-5 my-2">
                <div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Lista de seminaristas reprobados</p>
					<div class="separator mb-3" style="height: 2px;"></div>	                    
                    <table class="table data-table table-striped" id="teachers-table">
                        <thead class="table-head">
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Materia</th>
                                <th scope="col">Grupo</th>
                                <th scope="col">Acciones</th>                                                               
                            </tr>
                        </thead>
                        <div class="table-body">
                            <tbody>
                                @foreach ($failedStudents as $student)
                                    <tr>
                                        <td>{{ $student->apellidoEstudiante }}, {{ $student->nombreEstudiante }}</td>
                                        <td>{{ $student->nombreMateria }}</td>                                        
                                        <td>{{ $student->nombreGrupo }}</td>                                        
                                        <td>
                                            <div class="row">
                                                <div class="col-4 mx-0 px-0">
                                                    <a type="button" class="btn btn-primary icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver información y asignar actividad" href="{{ route('student.showFailedExtraInfo',$student->idDetalle) }}"><i class="fa-solid fa-eye my-1"></i></a>
                                                </div>
                                                <div class="col-4 mx-0 px-0">
                                                    <a type="button" class="btn btn-success icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Registrar calificación" href="{{ route('student.storeGradeExtra',$student->idDetalle) }}"><i class="fa-solid fa-plus my-1" style="color: white"></i></a>
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


</body>
</html>