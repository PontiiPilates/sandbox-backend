<!-- Desctop navbar -->
<nav class="navbar navbar-expand navbar-transparent navbar-dark navbar-theme-primary mb-4 d-none d-md-block">
    <div class="cnt container position-relative d-flex align-items-center justify-content-start">
        <a class="navbar-brand me-lg-3" href="{{ route('general') }}">
            <img class="navbar-brand-dark" src="{{ asset('volt/assets/img/brand/light.svg') }}" alt="menuimage">
            <img class="navbar-brand-light" src="{{ asset('volt/assets/img/brand/dark.svg') }}" alt="menuimage">
        </a>
        <ul class="navbar-nav navbar-nav-hover align-items-lg-center d-none-sm">
            <li class="nav-item">
                <a href="{{ route('general') }}" class="nav-link">Главная</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">О проекте</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Контакты</a>
            </li>
        </ul>
    </div>
</nav>
<!-- End desctop navbar -->

<!-- Mobile navbar -->
<nav class="navbar navbar-dark navbar-theme-primary px-3 col-12 d-md-none mb-4">
    <a class="navbar-brand me-lg-3" href="../../index.html">
        <img class="navbar-brand-dark" src="{{ asset('volt/assets/img/brand/light.svg') }}" alt="Poidu logo">
        <img class="navbar-brand-light" src="{{ asset('volt/assets/img/brand/dark.svg') }}" alt="Poidu logo">
    </a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<nav id="sidebarMenu" class="sidebar d-md-none bg-gray-800 text-white collapse" data-simplebar="init" style="">
    <div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
            <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;">
                    <div class="simplebar-content" style="padding: 0px;">
                        <div class="sidebar-inner px-4 pt-3">
                            <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-lg me-4">
                                        <img src="{{ asset('volt/assets/img/brand/dark.svg') }}" class="card-img-top border-white" alt="Poidu logo">
                                    </div>
                                </div>
                                <div class="collapse-close d-md-none">
                                    <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation" class="collapsed">
                                        <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <ul class="nav flex-column pt-3 pt-md-0">
                                <li class="nav-item  active ">
                                    <a href="{{ route('general') }}" class="nav-link">
                                        <span class="sidebar-text">Главная</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <span>
                                            <span class="sidebar-text">О проекте</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="#" class="nav-link">
                                        <span class="sidebar-text">Контакты</span>
                                    </a>
                                </li>
                                <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>
    </div>
    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
    </div>
    <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
        <div class="simplebar-scrollbar" style="height: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div>
    </div>
</nav>
<!-- End mobile navbar -->