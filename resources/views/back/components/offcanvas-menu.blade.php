<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
    <!-- Application (all authenticated users) -->
    <li class="nav-item text-light">Oficina Virtual</li>
    <hr class="narrow text-light">

    <li class="nav-item"><a class="nav-link text-light " href="{{ route('back.divisas.index') }}"><i class="fa-solid fa-dollar-sign fa-fw"></i> Divisas (Dolar BCV)</a></li>
    <li class="nav-item"><a class="nav-link text-light " href="{{ route('back.recibos.index') }}"><i class="fa-solid fa-file-invoice-dollar fa-fw"></i> Recibos</a></li>
    <li class="nav-item"><a class="nav-link text-light " href="{{ route('back.tablas.index') }}"><i class="fa-solid fa-table-pivot fa-fw"></i> Programas y Menciones</a></li>
    <li class="nav-item"><a class="nav-link text-light " href="{{ route('back.aranceles.index') }}"><i class="fa-light fa-money-check-dollar-pen fa-fw"></i> Aranceles </a></li>
    <li class="nav-item"><a class="nav-link text-light " href="{{ route('back.estudiantes.index') }}"><i class="fa-light fa-screen-users fa-fw"></i> Estudiantes</a></li>

    <!-- Administration (only propietario) -->
    @can('propietario')
        <hr class="narrow text-light">
        <li class="nav-item text-light">Administración</li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light" href="#" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                <i class="bi bi-person-bounding-box nav-icon"></i>{{ Auth::user()->name }}
            </a>

            <ul class="dropdown-menu" aria-labelledby="offcanvasNavbarDropdown">
                <li><a class="dropdown-item" href="{{ route('back.profile.show') }}"><i class="bi bi-person-badge nav-icon"></i>Perfil</a></li>

                <li><a class="dropdown-item" href="{{ route('back.users.index') }}"><i class="bi bi-people-fill nav-icon"></i>Usuarios</a></li>
                <li><a class="dropdown-item" href="{{ route('back.userslog.index') }}"><i class="bi bi-person-lines-fill nav-icon"></i>Control Ingresos</a></li>
                <hr class="narrow">
                <li><a class="dropdown-item" href="{{ route('back.divisas.index') }}"><i class="fa-regular fa-print fa-fw"></i> Reportes</a></li>
                <li><a class="dropdown-item" href="{{ route('back.divisas.index') }}"><i class="fa-regular fa-money-bill-1-wave fa-fw"></i> Conciliación Bancaria</a></li>
                <li><a class="dropdown-item" href="{{ route('back.petitorios.index') }}"><i class="fa-regular fa-hands-praying fa-fw"></i> Donaciones y Petitorios</a></li>

                <hr class="narrow">

                <li><a class="dropdown-item" href="{{ route('back.backups.index') }}"><i class="bi bi-archive-fill nav-icon"></i>Backups</a></li>
                <hr class="narrow">


            </ul>
        </li>
    @endcan
</ul>
