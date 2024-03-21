@extends('layout.header')


@section('title','Seminarista reprobado')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/evaluaciones/initEvaluacionesAdd.js') }}"></script>

<body style="overflow-x: hidden">    	
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('exitoModificar'))
        <script>
            swal({
                title: "Registro modificado",
                text: "{{ session('exitoModificar') }}",
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
        @include('layout.verticalMenuTeacher')
        <div id="content" class="mt-0 pt-0">            
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('teacherSite.showFailed') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Infomación del seminarista</p>
                    </div>
                </div>
            </nav>  
            @if ($studentInfo->count() > 0)
                <div class="card mx-5">
                    <div class="card-body cardBody-Teachers">
                        <p class="d-flex justify-content-center">Información</p>
                        <div class="separator mb-3"></div>	                        
                        <div class="row">
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre</p>
                                {{ $studentInfo[0]->nombreEstudiante }}
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Apellido</p>
                                {{ $studentInfo[0]->apellidoEstudiante }}                                                        
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Materia</p>
                                {{ $studentInfo[0]->nombreMateria }}                                
                            </div>                       
                        </div>		
                        <div class="row mt-3">
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre del grupo</p>
                                {{ $studentInfo[0]->nombreGrupo }}                                
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Promedio final</p>
                                {{ $studentInfo[0]->promedio }}                                
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Ciclo</p>
                                {{ $studentInfo[0]->nombreCiclo }}                                
                            </div>                        
                        </div>		                                        																													
                    </div>
                </div>                
                <div class="card mx-5 my-3">
                    <div class="card-body cardBody-Teachers">
                        <p class="d-flex justify-content-center">Asignación de actividad extraordinaria</p>
                        <div class="separator mb-3" style="height: 2px"></div>	
                        @if ($errors->any())
                            <div class="alert alert-danger my-2 pb-0">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($activity == 0)
                            <form method="POST" action="{{ route('teacherSite.storeActivity') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <label for="txtNombreActividad" class="form-label"><b>Nombre de actividad</b></label>                                
                                        <input type="text" id="txtNombreActividad" name="nombreActividad" placeholder="Ingrese nombre de actividad" class="form-control inputTxt"  value="{{old('nombreActividad')}}" required>                                    
                                        <input type="text" name="detalle" class="form-control inputTxt" value="{{ $studentInfo[0]->idDetalle }}" hidden>                                    
                                        <input type="text" name="promedio" class="form-control inputTxt" value="{{ $studentInfo[0]->promedio }}" hidden>                                    
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <label for="txtPorcentajeActividad" class="form-label"><b>Porcentaje (%)</b></label>                                
                                        <input type="text" id="txtPorcentajeActividad" name="porcentaje" placeholder="Ingrese porcentaje de actividad" class="form-control inputTxt"  value="{{ (7 - $studentInfo[0]->promedio)*10 }}" oninput="validateOnlyNumbersOnInput(this);" required>                                                                                           
                                    </div>                                                 
                                </div>		
                                <div class="row mt-1">
                                    <div class="col-12">
                                        <label for="txtDescripcionActividad" class="mb-1"><b>Descripcion de la actividad</b></label>
                                        <textarea class="form-control" placeholder="Ingrese descripcion de la actividad" id="txtDescripcionActividad" name="descripcion" style="height: 100px" class="inputTxt">{{old('descripcion')}}</textarea>                                                           
                                    </div>                                               
                                </div>
                                <div class="row mx-3 my-3">
                                    <button class="btn btn-button btn-primary" style="background-color: #7386d5">Asignar actividad</button>
                                </div>
                            </form>	
                        @else                            
                            <div class="alert alert-warning" role="alert">
                                Ya se ha asignado una actividad a este estudiante
                            </div>                            
                        @endif   	                                        																													
                    </div>
                </div>                                 
            @else
                <div class="row mx-5">
                    <div class="alert alert-warning" role="alert">
                        No se ha encontrado información
                    </div>
                </div>
            @endif                     				                									           
        </div>
    </div>	
    
</body>
</html>