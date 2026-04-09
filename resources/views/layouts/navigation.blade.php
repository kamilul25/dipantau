{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('publik.beranda') }}">MyApp</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                {{-- Link untuk publik / semua --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('publik.beranda') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('publik.pasum') }}">Perumahan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('publik.pju') }}">PJU</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('publik.aduan') }}">Aduan</a>
                </li>

                @auth
                    {{-- Link Dashboard sesuai role --}}
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            'superadmin' => 'perumahan.index',
                            'admin' => 'aduan.index',
                            default => 'publik.beranda',
                        };
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route($dashboardRoute) }}">Dashboard</a>
                    </li>

                    {{-- Logout --}}
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="display:inline; padding:0; border:none; background:none;">
                                Logout
                            </button>
                        </form>
                    </li>
                @else
                    {{-- Link Login untuk guest --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>