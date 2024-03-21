@extends('layout.header')


@section('title','Información de ciclo')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/period/initPeriodIndex.js') }}"></script>
<script src="{{ asset('js/period/endPeriod.js') }}"></script>

<body style="overflow-x: hidden">    	
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('exitoFinalizacion'))
        <script>
            swal({
                title: "Ciclo finalizado",
                text: "{{ session('exitoFinalizacion') }}",
                icon: "success",
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
                    <a href="{{ route('period.create') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Grupos de clases</p>
                    </div>
                </div>
            </nav>                     				
			<div class="card mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Grupos de clases del {{ $period->nombreCiclo }}</p>
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
                    <table class="table data-table">
                        <thead>
                            <tr>
                                <th scope="col">Nombre del grupo</th>
                                <th scope="col">Asignatura</th>
                                <th scope="col">Docente responsable</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach ($groups as $group)
                                <tr>
                                    <td>{{ $group->nombreGrupo}}</td>
                                    <td>{{ $group->nombreMateria }}</td>
                                    <td>{{ $group->nombreDocente.' '.$group->apellidoDocente}}</td>
                                    <td>                                       
                                        <a type="button" class="btn btn-primary icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver información" href="{{ route('group.information',$group->idGrupo)}}"><i class="fa-solid fa-eye my-1" style="color: white"></i></a>                                        
                                    </td>
                                </tr>
                            @endforeach                                                                                                                                         
                        </tbody>
                    </table>                                            
				</div>
			</div>									           
        </div>
    </div>
</body>
</html>