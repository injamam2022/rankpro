<nav class="navbar navbar-expand-lg navbar-dark fixed-top wow fadeIn" data-wow-delay="0.1s">
    <div class="cusContainer fornavonly navbar-expand-lg" style="max-width: 1420px;">
        <a href="{{ route('index') }}" class="navbar-brand ms-4 ms-lg-0">
            <h1 class="text-primary m-0"><img src="{{ asset('') }}web/images/logo.png" alt="RankPro - Educational Advisory solutions">
            </h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav header-midnav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}#pricing">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact us</a>
                </li>
            </ul>
            <div class="headerright">
                @if (Auth::check())
                    <div class="loginRegistration">
                        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? ' active' : '' }}">Dashboard</a>
                    </div>
                @else
                    <div class="loginRegistration">
                        <a href="{{ route('signup') }}" class="{{ request()->is('signup') ? ' active' : '' }}">Join for free</a>
                    </div>
                    <div class="loginRegistration" style="margin-left: 5px;">
                        <a href="{{ route('login') }}" class="{{ request()->is('login') ? ' active' : '' }}">Login</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
