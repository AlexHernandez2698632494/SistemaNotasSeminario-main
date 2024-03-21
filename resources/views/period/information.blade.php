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

    @if (session('errorFinalizacion'))
        <script>
            swal({
                title: "Error al finalizar ciclo",
                text: "{{ session('errorFinalizacion') }}",
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

    @if (session('exitoActualizar'))
        <script>
            swal({
                title: "Ciclo actualizado",
                text: "{{ session('exitoActualizar') }}",
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

    @if (session('errorActualizar'))
        <script>
            swal({
                title: "Error al actualizar",
                text: "{{ session('errorActualizar') }}",
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

    @if (session('exitoIniciar'))
        <script>
            swal({
                title: "Ciclo iniciado",
                text: "{{ session('exitoIniciar') }}",
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

    @if (session('errorIniciar'))
        <script>
            swal({
                title: "Error al iniciar ciclo",
                text: "{{ session('errorIniciar') }}",
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
                        <p style="color: black; margin: 0; font-weight: bold">Información del ciclo</p>
                    </div>
                </div>
            </nav>                       				
			<div class="card mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Información</p>
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
                    <div class="row mx-2">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre</p>
                            {{ $period->nombreCiclo }}
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de inicio</p>
                            {{ $period->fechaInicio }}                  
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Fecha de finalización</p>
                            {{ $period->fechaFinalizacion }}
                        </div>                       
                    </div>		
                    <div class="row mx-2 mt-3">
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Estado</p>
                            @if ($period->estado == 0)
                                Pendiente
                            @elseif ($period->estado == 1)
                                Activo
                            @else 
                                Finalizado
                            @endif 
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Cantidad de materias impartidas</p>
                            {{ $subjectNumber }} materias
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Cantidad de grupos</p>
                            {{ $groupsNumber }} grupos
                        </div>                        
                    </div>		                                       
                    <div class="row mx-2 mt-3">
                        <div class="col-lg-12">
                            <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Materias impartidas</p>
                            <div class="row">  
                                @if (!$subjects->isEmpty())                                
                                    @foreach ($subjects as $subject)
                                        <div class="col-lg-4 col-md-6 col-xs-12 mt-2">                                        
                                            {{ $loop->iteration }}-{{$subject->nombreMateria}}
                                        </div>
                                    @endforeach 
                                @else
                                    <div class="col-12 mt-2">   
                                        <div class="alert alert-warning" role="alert">
                                            No se han encontrado materias
                                        </div>                                                                   
                                    </div>
                                @endif                                                                                                                                                                                            
                            </div>
                        </div>                        
                    </div>	
                    @if ($period->estado == 0)
                        <div class="row mt-3 mx-3">
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="btn-group d-flex justify-content-center">
                                    <button 
                                        type="button"
                                        class="btn btn-secondary mt-2 btn-block" 
                                        value="{{$period->idCiclo}},{{$period->nombreCiclo}},{{$period->fechaInicio}},{{$period->fechaFinalizacion}}"
                                        onclick="updatePeriod(this.value)"                                 
                                        {{$period->estado !=0 ? "hidden" : ""}}>Actualizar información
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="btn-group d-flex justify-content-center">
                                    <button 
                                        type="button"
                                        class="btn btn-success mt-2 btn-block" 
                                        value="{{$period->idCiclo}},{{$period->nombreCiclo}}"
                                        onclick="startPeriod(this.value)"                                 
                                    >Iniciar ciclo
                                    </button>
                                </div>
                            </div>                            
                        </div>
                    @endif
                    <div class="row mt-3 mx-3">
                        <div class="col-lg-6">
                            <div class="btn-group d-flex justify-content-center">
                                <button 
                                    type="button"
                                    class="btn btn-secondary mt-2 btn-block" 
                                    value="{{$period->idCiclo}},{{$period->nombreCiclo}},{{$period->fechaInicio}},{{$period->fechaFinalizacion}}"
                                    onclick="updatePeriod(this.value)"                                 
                                    {{$period->estado !=1 ? "hidden" : ""}}>Actualizar información
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="btn-group d-flex justify-content-center">
                                <button 
                                    type="button"
                                    class="btn btn-danger mt-2 btn-block" 
                                    value="{{$period->idCiclo}},{{$period->nombreCiclo}}"
                                    onclick="endPeriod(this.value)"                                 
                                    {{$period->estado !=1 ? "hidden" : ""}}>Finalizar ciclo
                                </button>
                            </div>
                        </div>
                    </div>																													
				</div>
			</div>									           
        </div>
    </div>
	<!-- Modal para finalizar ciclo-->
    <div class="modal fade" id="endPeriod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Verificación de finalización</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('period.end') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p id="txtFinalizacion"></p>
                    </div>
                    <div class="modal-footer">                                           
                        <input type="text" name="idCicloFinalizar" id="txtIdCicloFinalizar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" style="color: white">Finalizar</button>                   
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal para actualizar información de ciclo-->
    <div class="modal fade" id="updatePeriod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Actualizar información de ciclo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('period.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="txtNombreSeminarista" class="form-label"><b>Nombre del ciclo</b></label>
                                <input type="text" id="txtNombreCiclo" name="nombreCiclo"
                                    placeholder="Ingrese nombre del ciclo" class="form-control inputTxt"
                                    value="{{ old('nombreCiclo') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-2">
                                <label for="txtEtapa" class="form-label"><b>Fecha de inicio del ciclo</b></label>
                                <input type="date" id="txtFechaInicio" name="fechaInicio" placeholder="Ingrese fecha de inicio" class="form-control inputTxt" value="{{ old('fechaInicio') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-2">
                                <label for="txtEtapa" class="form-label"><b>Fecha de finalización del ciclo</b></label>
                                <input type="date" id="txtFechaFinalizacion" name="fechaFinalizacion" placeholder="Ingrese fecha de finalización" class="form-control inputTxt" value="{{ old('fechaFinalizacion') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">                                           
                        <input type="text" name="idCicloActualizar" id="txtIdCicloActualizar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-warning" style="color: white">Actualizar</button>                   
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal para iniciar ciclo-->
    <div class="modal fade" id="startPeriod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Iniciar ciclo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('period.start') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <p id="startMessage"></p>
                        </div>
                    </div>
                    <div class="modal-footer">                                           
                        <input type="text" name="idCicloIniciar" id="txtIdCicloIniciar" hidden>                        
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" style="color: white">Iniciar</button>                   
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>