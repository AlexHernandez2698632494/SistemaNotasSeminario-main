    <nav id="sidebar">
        <div class="sidebar-header">
            <h4>SEMINARIO MAYOR «PÍO XII»</h4>
        </div>
        <ul class="list-unstyled components">  
            <li id="opcionesDocente">
                <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuDocentes" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Docentes
                </a>
                <ul class="collapse list-unstyled" id="menuDocentes">
                    <li>
                        <a href="{{ route('teachers.create') }}" id="registroDocentes">Registro de docentes</a>
                    </li>
                    <li>
                        <a href="{{ route('teachers.index') }}" id="controlDocentes">Control de docentes</a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.restoreView') }}" id="docentesEliminados">Docentes eliminados</a>
                    </li>
                </ul>
            </li>                     
            <li id="opcionesSeminaristas">
                <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuSeminaristas" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Seminaristas
                </a>
                <ul class="collapse list-unstyled" id="menuSeminaristas">
                    <li>
                        <a href="{{ route('student.create') }}" id="registroSeminarista">Registro de seminaristas</a>
                    </li>
                    <li>
                        <a href="{{ route('student.index') }}" id="controlSeminaristas">Control de seminaristas</a>
                    </li>
                    <li>
                        <a href="{{ route('student.rejected') }}" id="candidatosRechazados">Candidatos rechazados</a>
                    </li> 
                    <li>
                        <a href="{{ route('student.restoreView') }}" id="seminaristasEliminados">Seminaristas eliminados</a>
                    </li>  
                </ul>
            </li> 
            <li id="opcionesGrupo">
                <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuGrupos" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Grupos de clase
                </a>
                <ul class="collapse list-unstyled" id="menuGrupos">
                    <li>
                        <a href="{{route('group.create')}}" id="registroGrupos">Registro de grupos</a>
                    </li> 
                    <li>
                        <a href="{{route('group.index')}}" id="controlGrupos">Control de grupos</a>
                    </li>    
                    <li>
                        <a href="{{route('group.finalized')}}" id="gruposFinlizados">Grupos finalizados</a>
                    </li>                  
                </ul>
            </li>  
            <li id="opcionesCiclo">
                <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuCiclos" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Ciclos
                </a>
                <ul class="collapse list-unstyled" id="menuCiclos">
                    <li>
                        <a href="{{ route('period.create') }}" id="indexPeriodos">Registro y control de ciclos</a>
                    </li>                    
                </ul>
            </li>
            <li id="opcionesMateria">
                <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuMaterias" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Materias
                </a>
                <ul class="collapse list-unstyled" id="menuMaterias">
                    <li>
                        <a href="{{ route('subject.create') }}" id="registroMaterias">Registro de materias</a>
                    </li>  
                    <li>
                        <a href="{{ route('subject.index') }}" id="controlMaterias">Control de materias</a>
                    </li> 
                    <li>
                        <a href="{{ route('subject.indexEliminadas') }}" id="materiasEliminadas">Materias eliminadas</a>
                    </li>                    
                </ul>
            </li> 
            <li id="opcionesAdministradores">
                <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuAdministradores" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Administradores
                </a>
                <ul class="collapse list-unstyled" id="menuAdministradores">
                    <li>
                        <a href="{{route('admin.create')}}" id="registroAdministradores">Registro de Administradores</a>
                    </li>
                    <li>
                        <a href="{{route('admin.index')}}" id="controlAdministradores">Control de Administradores</a>
                    </li>     
                    <li>
                        <a href="{{route('admin.indexE')}}" id="controlAdministradoresE">Administradores Eliminados</a>
                    </li>                
                </ul>
            </li>
            <li id="opcionesUsuarios">
                <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuUsuarios" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Usuarios
                </a>
                <ul class="collapse list-unstyled" id="menuUsuarios">
                    <li>
                        <a href="{{ route('users.index') }}" id="controlUsuarios">Control de usuarios</a>
                    </li>
                    <li>
                        <a href="{{ route('users.solicitudes') }}" id="solicitudesUsuarios">Solicitudes de recuperación de contraseña</a>
                    </li>                   
                </ul>
            </li>
            <li id="opcionesReprobados">
                <a href="{{ route('student.showFailedExtra') }}" role="button" >
                    Seminaristas reprobados                    
                </a>                
            </li>
            <li id="opcionesCambiarContra">
                <a href="{{route('users.formContra')}}">
                    Cambiar contraseña
                </a>
            </li>
            <li id="opcionesMostrarManual">
                <a target="_blank" href="{{asset('/pdf/manualAdministrador.pdf')}}">
                    Manual de usuario
                </a>
            </li>                                                      
        </ul>
        <ul class="list-unstyled CTAs">
            {{-- <li>
                <a href="https://bootstrapious.com/tutorial/files/sidebar.zip" class="download">Cerrar sesión</a>
            </li> --}}
            <li>
                <a href="{{ route('logout') }}" class="article">Cerrar sesión</a>
            </li>
        </ul>
    </nav>
   <!-- Page Content  -->
   {{-- <div id="content" class="mt-0 pt-0">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-left"></i>
                    <span>Toggle Sidebar</span>
                </button>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-align-justify"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Page</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Page</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Page</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Page</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h2>Collapsible Sidebar Using Bootstrap 4</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

    </div>
</div> --}}
