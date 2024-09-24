<ul class="sidebar-menu p-2">
    <li class="sidebar-header">
        <div id="logo-sidebar" class="shrink-0 flex items-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>
    </li>
    <li class="nav_seccion">administración</li>
    <li>
        <a href="{{ route('codigosqr') }}">
            <div class="d-flex align-items-center"><span class="icono icon-codigo-qr"></span> <span>Códigos QR</span> </div>
            {{-- <i class="fa fa-angle-left pull-right"></i> --}}
        </a>
        {{-- <ul class="sidebar-submenu">
            <li><a href="{{ route('codigosqr') }}"><i class="fa fa-circle-o"></i> Ver códigos QR</a></li>
            <li><a href="{{ route('codigosqr.nuevo') }}"><i class="fa fa-circle-o"></i> Generar código QR</a></li>
            <li><a href="{{ route('codigosqr.buscar') }}"><i class="fa fa-circle-o"></i> Buscar código QR</a></li>
        </ul> --}}
    </li>
    <li>
        <a href="#">
            <div class="d-flex align-items-center"><span class="icono icon-cupon"></span> <span>Cupones</span> </div><i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="sidebar-submenu">
            <li><a href="{{ route('cupones.show') }}"><i class="fa fa-circle-o"></i> Ver cupones QR</a></li>
            <li><a href="{{ route('cupones.buscar') }}"><i class="fa fa-circle-o"></i> Buscar cupones QR</a></li>
        </ul>
    </li>
    <li>
        <a href="{{ route('clientes') }}">
            <div class="d-flex align-items-center"><span class="icono icon-cliente"></span> <span>Clientes</span></div>
        </a>
    </li>
    <li>
        <a href="#">
            <div class="d-flex align-items-center"><span class="icono icon-empleados"></span> <span>Usuarios</span> </div><i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="sidebar-submenu">
            <li><a href="{{ route('usuarios') }}"><i class="fa fa-circle-o"></i> Ver usuarios</a></li>
            <li><a href="{{ route('register') }}"><i class="fa fa-circle-o"></i> Registrar usuario</a></li>
        </ul>
    </li>
    {{-- <li>
        <a href="{{ route('clientes') }}">
            <div class="d-flex align-items-center"><span class="icono icon-settings"></span> <span>Configuración</span></div>
        </a>
    </li> --}}
    
    {{-- <li class="nav_seccion">marketing</li> --}}
    
    

    <li class="nav_seccion">informes</li>
    <li>
        <a href="{{ route('graficas') }}">
            <div class="d-flex align-items-center"><span class="icono icon-estadisticas"></span> <span>Gráficas</span></div>
        </a>
    </li>    
</ul>