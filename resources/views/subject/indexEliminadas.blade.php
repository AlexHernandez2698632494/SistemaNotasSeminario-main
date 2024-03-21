@extends('layout.header')


@section('title', 'Materias eliminadas')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/subjects/initSubjectEliminadas.js') }}"></script>

<body style="overflow-x: hidden">
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('exitoRestaurar'))
        <script>
            swal({
                title: "Registro restaurado",
                text: "{{ session('exitoRestaurar') }}",
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

    @if (session('errorRestaurar'))
        <script>
            swal({
                title: "Error al restaurar",
                text: "{{ session('errorRestaurar') }}",
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
                    <a href="{{ route('subject.index') }}" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Regresar">
                        <i class="fas fa-arrow-left"></i>
                    </a> 
                    <div class="col d-flex justify-content-center">
                        <p style="color: black; margin: 0; font-weight: bold">Materias eliminadas</p>
                    </div>
                </div>
            </nav>
            <div class="card card-Teachers mt-3 mx-5">
                <div class="card-body cardBody-Teachers">
                    <p class="d-flex justify-content-center">Materias eliminadas</p>
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
                                <th scope="col" hidden>Nivel</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Etapa</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <div class="table-body">
                            <tbody>
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td hidden>{{$subject->nivel}}</td>
                                        <td>{{ $subject->nombreMateria }}</td>
                                        <td>{{ $subject->nombreEtapa.' Año '.$subject->anio.' '.$subject->cuatrimestre }}</td>
                                        <td>
                                            <div class="row d-flex justify-content-center">													
                                                <div class="col-4 mx-0 px-0">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-success icon-button"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="bottom" 
                                                        data-bs-title="Restaurar"
                                                        onclick="openRestoreModal({{$subject->idMateria}})">
                                                        <i class="fa-solid fa-trash-can-arrow-up"></i>
                                                    </button>
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
<!-- Modal para restaurar materia-->
<div class="modal fade" id="restaurarMateria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Restaurar materia</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('subject.restore') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-lg-12 col-xs-12">
                            <label id="txtPregunta" name="txtPregunta" class="form-label" style="font-weight: bold"></label>
                        </div>
                    </div>
                    <div class="row mt-2">   
                        <input type="text" id="txtIdMateriaRestaurar" name="idMateria" hidden>
                    </div>
                </div>
                <div class="modal-footer">                        
                        @csrf
                        @method('PUT')                           
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" style="color: white">Restaurar</button>                       
                </div>
            </form>
        </div>
    </div>
</div>
</body>

</html>
