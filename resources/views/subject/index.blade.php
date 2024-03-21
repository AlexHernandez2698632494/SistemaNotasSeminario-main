@extends('layout.header')


@section('title', 'Control de materias')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/subjects/initSubjectControl.js') }}"></script>

<body style="overflow-x: hidden">
    <script src="{{ asset('js/inactividad.js') }}"></script>
    @if (session('exitoActualizar'))
        <script>
            swal({
                title: "Registro actualizado",
                text: "{{ session('exitoActualizar') }}",
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
            })
        </script>
    @endif

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
        @include('layout.verticalMenu')
        <div id="content" class="mt-0 pt-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">
                    <div class="col d-flex justify-content-center">
                        <p style="color: black; margin: 0; font-weight: bold">Control de materias</p>
                    </div>
                </div>
            </nav>
            <div class="card card-Teachers mt-3 mx-5">
                <div class="card-body cardBody-Teachers">
                    <p class="d-flex justify-content-center">Materias registradas</p>
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
                                            <div class="row">
                                                <div class="col-4 mx-0 px-0">
                                                    <a type="button" class="btn btn-warning icon-button"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        data-bs-title="Actualizar información" onclick="updateMateriaModal({{$subject->idMateria}})"><i
                                                            class="fa-solid fa-arrows-rotate my-1"
                                                            style="color: white"></i></a>
                                                </div>
                                                <div class="col-4 mx-0 px-0">
                                                    <a type="button" class="btn btn-danger icon-button"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        data-bs-title="Eliminar" onclick="confirmarEliminacion({{$subject->idMateria}})"><i
                                                            class="fa-solid fa-trash my-1"
                                                            style="color: white"></i></a>
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
<!-- Modal para actualizar información de materia-->
<div class="modal fade" id="modificarMateria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Actualización de información</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('subject.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-xs-12 mt-2">
                            <label for="txtNombreMateria" class="form-label"><b>Nombre</b></label>
                            <input type="text" id="txtNombreMateria" name="nombreMateria"
                                placeholder="Ingrese nombre" class="form-control inputTxt" required>
                        </div>
                        <div class="col-lg-6 col-xs-12 mt-2">
                            <label for="selectGrado" class="form-label"><b>Etapa</b></label>
                            <select class="form-select" aria-label="Default select example" id="selectGrado"
                                name="etapa" onchange="getPhaseDurationA(this.value)">
                                <option value="0">Seleccione una etapa</option>
                                @foreach ($phases as $phase)
                                    <option value={{ $phase->idEtapa }}>{{ $phase->nombreEtapa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12 mt-2">
                            <label for="selectAnio" class="form-label"><b>Año de carrera</b></label>
                            <select class="form-select" aria-label="Default select example" id="selectAnio"
                                name="anio">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-xs-12 mt-2">
                            <label for="selectCuatrimestre" class="form-label"><b>Cuatrimestre</b></label>
                            <select class="form-select" aria-label="Default select example" id="selectCuatrimestre"
                                name="cuatrimestre">
                                <option value="Cuatrimestre 1">Cuatrimestre 1</option>
                                <option value="Cuatrimestre 2">Cuatrimestre 2</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">   
                        <input type="text" id="txtIdMateriaActualizar" name="idMateria" hidden>                    
                    </div>
                </div>
                <div class="modal-footer">                        
                        @csrf
                        @method('PUT')                           
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-warning" style="color: white">Actualizar</button>                       
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para eliminar materia-->
<div class="modal fade" id="eliminarMateria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar materia</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('subject.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-lg-12 col-xs-12">
                            <label id="txtPregunta" name="txtPregunta" for="txtNombreEvaluacion" class="form-label" style="font-weight: bold"></label>
                        </div>
                    </div>
                    <div class="row mt-2">   
                        <input type="text" id="txtIdMateriaEliminar" name="idMateria" hidden>
                    </div>
                </div>
                <div class="modal-footer">                        
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger" style="color: white">Eliminar</button>                       
                </div>
            </form>
        </div>
    </div>
</div>
</body>

</html>
