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
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">					  
					<div class="col d-flex justify-content-center">
						<p style="color: black; margin: 0; font-weight: bold">Mis grupos de clase</p>
					</div>                                          
                </div>
            </nav>    
            <div class="row mx-5">
                @if (!empty($studentGroups))
                    <div class="alert alert-primary" role="alert">
                        Grupos de clase del ciclo:  <b>{{ $period[0]->nombreCiclo }}</b>
                    </div>
                    @foreach ($studentGroups as $group)
                        <div class="col-lg-4 col-xl-6 col-md-6 col-xs-12">
                            <div class="card" style="height: 220px; max-height: 220px; overflow-y: auto">
                                <div class="card-header" style="background-color: #7386D5">                           
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $group->nombreMateria }}</h5>
                                    <p><b>Nombre del grupo: </b>{{ $group->nombreGrupo }}</p>                                    
                                </div>
                                <div class="card-footer text-body-secondary d-flex justify-content-center">
                                    <a href="{{ route('studentSite.showSubjectGrade',$group->idGrupo) }}" class="btn btn-primary my-1 mx-1" style="background-color: #7386D5;">Ver calificaciones</a>                                    
                                </div>
                            </div>
                        </div>
                    @endforeach 
                @else
                    <div class="alert alert-warning" role="alert">
                        No se han encontrado ciclos para este periodo/ciclo
                    </div>
                @endif                                           
                    
            </div>                     							
        </div>
    </div>	
    
</body>
</html>