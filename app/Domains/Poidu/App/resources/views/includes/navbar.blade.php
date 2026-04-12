<nav class="navbar navbar-expand navbar-transparent navbar-dark navbar-theme-primary mb-4">
    <div class="cnt container position-relative d-flex align-items-center justify-content-start">
        <a class="navbar-brand me-lg-3" href="{{ route('general') }}">
            <img class="navbar-brand-dark" src="{{ asset('volt/assets/img/brand/light.svg') }}" alt="menuimage">
            <img class="navbar-brand-light" src="{{ asset('volt/assets/img/brand/dark.svg') }}" alt="menuimage">
        </a>
        <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
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