<nav id="sidebar">
    <div class="sidebar-header">
        <h4>SEMINARIO MAYOR «PÍO XII»</h4>
    </div>
    <ul class="list-unstyled components">         
        <li id="opcionesDocente">
            <a class="dropdown-toggle" data-bs-toggle="collapse" href="#menuDocentes" role="button" aria-expanded="false" aria-controls="collapseExample">
                Grupos de clase
            </a>
            <ul class="collapse list-unstyled" id="menuDocentes">
                @if (session()->has('studentGroups'))
                    @foreach (session()->get('studentGroups') as $group)
                        <li>
                            <a href="{{ route('studentSite.showSubjectGrade',$group->idGrupo) }}" id="registroDocentes">{{ $group->nombreMateria }} ({{ $group->nombreGrupo }})</a>
                        </li>
                    @endforeach
                @else
                    <li>No se han encontrado grupos</li>
                @endif                          
            </ul>
        </li>     
        <li id="opcionesHistorialEstudiante">
            <a href="{{route('studentSite.record')}}">
                Historial de notas
            </a>
        </li>
        <li id="opcionesMiPerfil">
            <a href="{{route('studentSite.miPerfil')}}">
                Mi perfil
            </a>
        </li>     
        <li id="opcionesCambiarContra">
            <a href="{{route('users.formContra')}}">
                Cambiar contraseña
            </a>
        </li>
        <li id="opcionesMostrarManual">
            <a target="_blank" href="{{asset('/pdf/manualSeminarista.pdf')}}">
                Manual de usuario
            </a>
        </li>                                    
    </ul>
    <ul class="list-unstyled CTAs"> 
        <li class="my-1">
            <div class="card" style="background-color: #7386D5; color: white">
                <div class="card-body">
                    <b>Seminarista</b><br>
                    @if (session()->has('estudiante'))
                        {{ session()->get('estudiante')[0]->nombreEstudiante.' '.session()->get('estudiante')[0]->apellidoEstudiante }}
                    @endif                    
                </div>
            </div>
        </li>               
        <li>
            <a href="{{ route('logout') }}" class="article">Cerrar sesión</a>
        </li>        
    </ul>
</nav>

