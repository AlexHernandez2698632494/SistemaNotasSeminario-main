@extends('layout.header')


@section('title','Asignación de calificación')

<script src="{{ asset('js/sweetalert.js') }}"></script>

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
        @include('layout.verticalMenu')
        <div id="content" class="mt-0 pt-0">            
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">                    
                    <a href="{{ route('student.showFailedExtra') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Asignación de calificación de la actividad</p>
                    </div>
                </div>
            </nav> 
            @if ($information->count() > 0)
                <div class="card mx-5">
                    <div class="card-body cardBody-Teachers">
                        <p class="d-flex justify-content-center">Información</p>
                        <div class="separator mb-3"></div>	                        
                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Nombre del estudiante</p>
                                {{ $information[0]->nombreEstudiante }} {{ $information[0]->apellidoEstudiante }} 
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Materia</p>
                                {{ $information[0]->nombreMateria }}                                                        
                            </div>                                               
                        </div>		
                        <div class="row mt-3">
                            <div class="col-lg-6 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Actividad</p>
                                {{ $information[0]->actividad }} 
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Porcentaje (%)</p>
                                {{ $information[0]->porcentaje }}%                                                        
                            </div>                         
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <p style="margin-bottom: 0; font-weight: bold" class="mt-2">Descripción de la actividad</p>
                                {{ $information[0]->descripcion }} 
                            </div>                                                     
                        </div>
                        <p class="d-flex justify-content-center mt-2">Asignación de calificación</p>
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
                        <form method="POST" action="{{ route('student.storeGradeE') }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Calificación</span>
                                        <input type="text" id="txtNota" maxlength="3" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="calificacion" value="{{ old('calificacion') }}" required>
                                        <input type="number" name="detalle" value="{{ $information[0]->idDetalle }}" required hidden>
                                        <input type="number" name="porcentaje" value="{{ $information[0]->porcentaje }}" required hidden>
                                        <input type="number" name="promedioActual" value="{{ $information[0]->promedio }}" required hidden>
                                        <input type="number" name="estudiante" value="{{ $information[0]->idEstudiante }}" required hidden>
                                        <input type="number" name="materia" value="{{ $information[0]->idMateria }}" required hidden>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-success" type="submit" style="background-color: #7386d5">Registrar calificación</button>
                                </div>
                            </div>
                        </form>
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
    <script src="{{ asset('js/students/validarNota.js') }}" defer></script>
</body>
</html>