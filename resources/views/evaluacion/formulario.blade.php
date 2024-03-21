@extends('layout.header')


@section('title','Registro de Evaluación')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/evaluaciones/initEvaluacionesAdd.js') }}"></script>

<body style="overflow-x: hidden">   
    <script src="{{ asset('js/inactividad.js') }}"></script> 	
    @include('layout.horizontalMenu')    
    <div class="wrapper">
        @include('layout.verticalMenuTeacher')
        <div id="content" class="mt-0 pt-0">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3 mx-5">
                <div class="container-fluid">					  
					<div class="col d-flex justify-content-center">
						<p style="color: black; margin: 0; font-weight: bold">Información de evaluación</p>
					</div>                                          
                </div>
            </nav>
            <div class="card mx-5">
                <div class="card-body">
                    <p class="d-flex justify-content-center">Registro de evaluación</p>
                    <div class="separator mb-3"></div>
                    <p class="d-flex justify-content-center mt-0 subtitle">Ingrese la información que se solicita</p>
                    @if ($errors->any())
                        <div class="alert alert-danger my-2 pb-0">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('evaluacion.store', $id) }}">
                        @csrf							
                        <div class="row">
                            <div class="col-lg-6 col-xs-12 mt-2">                                    
                                <label for="txtNombreEvaluacion" class="form-label">Nombre</label>                                
                                <input type="text" id="txtNombreEvaluacion" name="nombre" placeholder="Ingrese nombre de evaluación" class="form-control inputTxt"  value="{{old('nombre')}}" required>                                    
                            </div>
                            <div class="col-lg-6 col-xs-12 mt-2">
                                <label for="txtPorcentaje" class="form-label">Porcentaje</label>                                
                                <input type="text" id="txtPorcentaje" name="porcentaje" placeholder="Ingrese porcentaje" class="form-control inputTxt txtPorcentaje" oninput="validateOnlyNumbersOnInput(this);" value="{{old('porcentaje')}}"required>                                                                                                    
                            </div>
                            <div class="col-lg-12 col-xs-12 mt-2">                                    
                                <label for="txtDescripcion" class="mb-1">Descripción</label>
                                <textarea class="form-control" placeholder="Ingrese descripción de evaluación" id="txtDescripcion" name="descripcion" style="height: 100px" class="inputTxt">{{old('descripcion')}}</textarea>                                    
                            </div>
                        </div>																																									
                        <div class="row mx-2 my-2 mt-3">
                            <div class="col d-flex justify-content-center">
                                <button type="submit" id="btnRegistrar" class="btn btn-block btn-Add">Registrar evaluación</button>
                            </div>								
                        </div>
                    </form>
                    
                </div>
            </div> 	                        											           
        </div>

    </div>
</body>
</html>