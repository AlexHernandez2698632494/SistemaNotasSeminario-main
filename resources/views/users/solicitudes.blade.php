@extends('layout.header')


@section('title','Solicitudes de recuperación de contraseña')

<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('js/users/solicitudesInit.js') }}"></script>
<body style="overflow-x: hidden">    
	<script src="{{ asset('js/inactividad.js') }}"></script>
	@if (session('exitoRestablecer'))
        <script>
            swal({
                title: "Contraseña restablecida",
                text: "{{ session('exitoRestablecer') }}",
                icon: "success",
                button: "OK",
            }) .then(function(){
				window.open("/pdf/newCredential.pdf","blank")
			})	      
        </script>
    @endif

    @if (session('errorRestablecer'))
        <script>
            swal({
                title: "Error al restablecer",
                text: "{{ session('errorRestablecer') }}",
                icon: "error",
                button: "OK",
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
						<p style="color: black; margin: 0; font-weight: bold">Solicitudes de recuperación de contraseña</p>
					</div>                                          
                </div>
            </nav>                         				
			<div class="card card-Teachers mx-5">
				<div class="card-body cardBody-Teachers">
					<p class="d-flex justify-content-center">Solicitudes registradas</p>
					<div class="separator mb-3"></div>											
						<table class="table data-table table-striped" id="teachers-table">
							<thead class="table-head">
								<tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Nombre de Usuario</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<div class="table-body">
								<tbody>	
									@foreach ($solicitudes as $solicitud)
										<tr>
                                            <td>{{$solicitud->fecha}}</td>
											<td>{{ $solicitud->usuario }}</td>
											<td>
												<div class="row">
                                                    <div class="col-4 mx-0 px-0">
                                                        <a type="button" class="btn btn-success icon-button"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            data-bs-title="Restaurar" onclick="confirmarRestablecer('{{$solicitud->idSolicitud}}')"><i
                                                                class="fa-solid fa-trash-can-arrow-up my-1"
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
	<!-- Modal para restablecer contraseña-->
    <div class="modal fade" id="restablecerContra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Restablecer contraseña</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('users.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-lg-12 col-xs-12">
                                <label id="txtPregunta" name="txtPregunta" class="form-label" style="font-weight: bold"></label>
                            </div>
                        </div>
                        <div class="row mt-2">   
                            <input type="text" id="txtIdUsuario" name="idUsuario" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">                        
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success" style="color: white">Restablecer</button>                       
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>