@extends('layout.header')


@section('title', 'Registro de ciclos')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/period/initPeriodIndex.js') }}"></script>

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
            <nav class="navbar navbar-expand-lg navbar-light bg-light mx-5 mt-3">
                <div class="container-fluid">
                    <div class="col d-flex justify-content-center">
                        <p style="color: black; margin: 0; font-weight: bold">Registro de ciclos/periodos</p>
                    </div>
                </div>
            </nav>
            <div class="card mx-5">
                <div class="card-body">
                    <p class="d-flex justify-content-center">Ingreso de información</p>
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
                    <form method="POST" action="{{ route('period.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">
                                <label for="txtNombreSeminarista" class="form-label"><b>Nombre del ciclo</b></label>
                                <input type="text" id="txtNombreCiclo" name="nombreCiclo"
                                    placeholder="Ingrese nombre del ciclo" class="form-control inputTxt"
                                    value="{{ old('nombreCiclo') }}" required>
                            </div>
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">
                                <label for="txtEtapa" class="form-label"><b>Fecha de inicio del ciclo</b></label>
                                <input type="date" id="txtFechaInicio" name="fechaInicio" placeholder="Ingrese fecha de inicio" class="form-control inputTxt" value="{{ old('fechaInicio') }}" required>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">
                                <label for="txtEtapa" class="form-label"><b>Fecha de finalización del ciclo</b></label>
                                <input type="date" id="txtFechaFinalizacion" name="fechaFinalizacion" placeholder="Ingrese fecha de finalización" class="form-control inputTxt" value="{{ old('fechaFinalizacion') }}" required>
                            </div>
                            <div class="col-lg-6 col-xs-12 col-md-6 mt-2">
                                <label for="txtEtapa" class="form-label"><b>Estado del ciclo</b></label>
                                <select class="form-select" aria-label="estadoCiclo" id="estadoCiclo"
                                    name="estadoCiclo">
                                    <option value="0">En espera de iniciar</option>
                                    <option value="1">Activo</option>
                                </select>
                            </div>
                        </div>                                                
                        <div class="row mx-2 mt-4">
                            <div class="col d-flex justify-content-center">
                                <button type="submit" class="btn btn-block btn-Add">Registrar ciclo</button>                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mx-5 mt-3">
                <div class="card-body cardBody-Teachers">
                    <p class="d-flex justify-content-center">Ciclos registrados</p>
                    <div class="separator mb-3"></div>
                    @if (!empty($availablePeriod[0]) )
                        <div class="alert alert-primary pb-0">
                            <div class="row">
                                <p><b>AVISO: </b>EL {{strtoupper($availablePeriod[0]->nombreCiclo)}} HA ALCANZADO SU FECHA DE INICIO</p>                               
                            </div>                                    
                        </div>
                    @endif
                    <table class="table data-table table-striped" id="teachers-table">
                        <thead class="table-head">
							<tr>
								<th scope="col">Nombre del ciclo</th>
								<th scope="col">Fecha de inicio</th>
								<th scope="col">Fecha de finalización</th>
								<th scope="col">Estado</th>
								<th scope="col">Acciones</th>
							</tr>
						</thead>
                        <tbody>	    
                            @foreach ($periods as $period)
								<tr>
									<td>{{ $period->nombreCiclo }}</td>
									<td>{{ $period->fechaInicio }}</td>
									<td>{{ $period->fechaFinalizacion }}</td>
									<td>                                        
                                        @if ($period->estado == 0)
                                            Pendiente
                                        @elseif ($period->estado == 1)
                                            Activo
                                        @else 
                                            Finalizado
                                        @endif                                           
                                    </td>
									<td>
										<div class="row">
											<div class="col-4 mx-0 px-0">
												<a type="button" class="btn btn-primary icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Ver información" href="{{ route('period.information',$period->idCiclo) }}"><i class="fa-solid fa-eye my-1" style="color: white"></i></a>
											</div>
											<div class="col-4 mx-0 px-0">
												<a type="button" class="btn btn-warning icon-button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Grupos de clase" href="{{ route('period.groups', $period->idCiclo) }}"><i class="fa-solid fa-people-group my-1" style="color: white"></i></a>
											</div>												
										</div>																									
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
