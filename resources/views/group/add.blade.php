@extends('layout.header')


@section('title', 'Registro de grupos')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/groups/initGroupAdd.js') }}"></script>

<body style="overflow-x: hidden">
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if(session('exitoAgregar'))
        <script>
            swal({
                title: "Registro agregado",
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
                title: "Error al registrar",
                text: "{{ session('errorAgregar') }}",
                icon: "error",
                button: "OK",closeOnClickOutside: false,
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
                    <a href="{{ route('group.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a>                    
                    <div class="col text-center">
                        <p style="color: black; margin: 0; font-weight: bold">Registro de grupos de clase</p>
                    </div>
                </div>
            </nav>
            <div class="card mx-5">
                <div class="card-body">
                    <p class="d-flex justify-content-center">Ingreso de información</p>
                    <div class="separator mb-3"></div>
                    <div class="alert alert-primary" role="alert">
                        AVISO: Por defecto se selecciona el ciclo que se encuentra activo en este momento, si desea modificarlo puede hacerlo en la opción "Ciclo al que pertenece el grupo" y seleccionar el de su preferencia
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger my-2 pb-0">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('group.store') }}">
                        @csrf
                        <div class="row mx-1">
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">
                                <label for="txtNombreSeminarista" class="form-label"><b>Nombre del grupo</b></label>
                                <input type="text" id="txtNombreCiclo" name="groupName"
                                    placeholder="Ingrese nombre del ciclo" class="form-control inputTxt"
                                    value="{{ old('groupName') }}" required>
                            </div>
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">                                
                                <label for="txtEtapa" class="form-label"><b>Materia</b></label>
                                <select class="form-select select2 select" style="width:100%" aria-label="Default select example" id="materia" name="subjectGroup" onchange="docentesMateria(this.value)">
                                    <option value="0">Seleccione materia</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{$subject->idMateria}}" {{old('subjectGroup') == $subject->idMateria ? 'selected':''}}>{{$subject->nombreMateria.'-'.$subject->nombreEtapa.' Año '.$subject->anio.' '.$subject->cuatrimestre}}</option>      
                                    @endforeach                                    
                                </select>
                            </div>
                        </div>
                        <div class="row  mx-1 mt-2"> 
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">                                  
                                <label for="selectdocente" class="form-label"><b>Docente</b></label>
                                <select class="form-select select2 select" style="width:100%" aria-label="Default select example" id="selectdocente" name="teacherGroup">
                                    <option value="0">Seleccione docente</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{$teacher->idDocente}}" {{old('teacherGroup') == $teacher->idDocente ? 'selected':''}}>{{$teacher->nombreDocente.' '.$teacher->apellidoDocente}}</option>      
                                    @endforeach                                          
                                </select>                                                                                                         
                            </div>                                                                            
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">
                                <label for="txtEtapa" class="form-label"><b>Ciclo al que pertenece el grupo</b></label>
                                <select class="form-select select2 select" style="width:100%" aria-label="Default select example" id="ciclo" name="periodGroup">                                    
                                    @foreach ($periods as $period)
                                        <option  value="{{$period->idCiclo}}">{{$period->nombreCiclo}} {{$period->estado==1 ?'(Ciclo Activo)':'(Ciclo en espera de iniciar)'}}</option>      
                                    @endforeach                                            
                                </select>
                            </div>                                               
                        </div>                        
                        <div class="row mt-3 ">
                            <div class="col-lg-12">
                                <div class="btn-group d-flex justify-content-center">
                                    <button 
                                        type="submit"
                                        class="btn btn-success mt-2 btn-block btn-add" 
                                        value="registrarGrupo"      
                                        name="action"                                                                   
                                        >Registrar grupo
                                    </button>
                                </div>
                            </div>                            
                        </div>	
                    </form>
                </div>
            </div>        
        </div>
    </div>

</body>

</html>
