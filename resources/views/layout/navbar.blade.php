<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ url('/') }}">
            <img src="{{ asset('images/dinas.png') }}" alt="Logo Kabupaten" height="43">
            <img src="{{ asset('images/psu.png') }}" alt="Logo PSU" height="43">
            <span>DIPANTAU</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                {{-- Beranda --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('publik.beranda') ? 'active' : '' }}"
                       href="{{ route('publik.beranda') }}">
                        Beranda
                    </a>
                </li>

                {{-- PASUM --}}
                @php
                    $pasumActive = request()->is('perumahan*') || request()->is('pasum');
                @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ $pasumActive ? 'active' : '' }}" data-bs-toggle="dropdown">
                        Pasum<span class="dropdown-arrow">▼</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->is('pasum') ? 'active' : '' }}"
                               href="{{ route('publik.pasum') }}">
                                Pasum Perumahan
                            </a>
                        </li>
                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                        <li>
                            <a class="dropdown-item {{ request()->is('perumahan*') ? 'active' : '' }}"
                               href="{{ route('perumahan.index') }}">
                               <i class="fa-solid fa-pen-to-square me-2"></i>Pasum
                            </a>
                        </li>
                    @endif
                @endauth
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">SOP</a></li>
                        <li><a class="dropdown-item" href="#">Format dan Persyaratan</a></li>
                    </ul>
                </li>

                {{-- PJU --}}
                @php
                $pjuActive = request()->routeIs('publik.pju')
                            || request()->routeIs('publik.mapAll') 
                            || request()->routeIs('pju.*')
                @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ $pjuActive ? 'active' : '' }}" data-bs-toggle="dropdown">
                        PJU<span class="dropdown-arrow">▼</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('publik.pju') ? 'active' : '' }}" 
                            href="{{ route('publik.pju') }}">
                            Lokasi PJU
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('publik.mapAll') ? 'active' : '' }}" 
                            href="{{ route('publik.mapAll') }}">
                            Peta PJU
                            </a>
                        </li>
                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')                        
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('pju.*') ? 'active' : '' }}" 
                            href="{{ route('pju.index') }}">
                            <i class="fa-solid fa-pen-to-square me-2"></i>PJU
                            </a>
                        </li>
                    @endif
                @endauth                                             
                    </ul>
                </li>

                {{-- RTH --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('rth*') ? 'active' : '' }}" href="{{ route('publik.rth') }}">
                        KRB
                    </a>
                </li>

                {{-- Regulasi --}}
                @php
                    $regulasiActive = request()->is('file/*');
                @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ $regulasiActive ? 'active' : '' }}" data-bs-toggle="dropdown">
                        Regulasi<span class="dropdown-arrow">▼</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ asset('file/UU_No_1_Tahun_2011.pdf')}}" target="_blank">UU No. 1 Tahun 2011</a></li>
                        <li><a class="dropdown-item" href="{{ asset('file/UU_No_11_Tahun_2020.pdf')}}" target="_blank">UU No. 11 Tahun 2020</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ asset('file/PP_No_14_Tahun_2016.pdf')}}" target="_blank">PP No. 14 Tahun 2016</a></li>
                        <li><a class="dropdown-item" href="{{ asset('file/PP_No_64_Tahun_2016.pdf')}}" target="_blank">PP No. 64 Tahun 2016</a></li>
                        <li><a class="dropdown-item" href="{{ asset('file/PP_No_12_Tahun_2021.pdf')}}" target="_blank">PP No. 12 Tahun 2021</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ asset('file/Permendagri_No_09_Tahun_2009.pdf')}}" target="_blank">Permendagri No. 9 Tahun 2009</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ asset('file/Perda_No_7_Tahun_2015.pdf')}}" target="_blank">Perda No. 7 Tahun 2015</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ asset('file/Perbup_No_31_Tahun_2022.pdf')}}" target="_blank">Perbup No. 31 Tahun 2022</a></li>
                    </ul>
                </li>

                {{-- Aduan --}}
                @php
                $pjuActive = request()->routeIs('publik.aduan') 
                            || request()->routeIs('aduan.*');
                @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ $pjuActive ? 'active' : '' }}" data-bs-toggle="dropdown">
                        Aduan<span class="dropdown-arrow">▼</span>
                    </a>
                    <ul class="dropdown-menu">                    
                        <li>
                            <a class="dropdown-item" href="https://api.whatsapp.com/send?phone={{ $waSetting->whatsapp_number }}" target="_blank">Nomor Aduan</a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('publik.aduan') ? 'active' : '' }}" 
                            href="{{ route('publik.aduan') }}">
                            Riwayat Aduan
                            </a>
                        </li>                   
                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')                                                
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('aduan.*') ? 'active' : '' }}" 
                            href="{{ route('aduan.index') }}">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Rekap Aduan
                            </a>
                        </li>
                    @endif
                @endauth
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Survei Kepuasan</a></li>                        
                    </ul>
                </li>

                {{-- Masuk / Login --}}
                @php
                $AdminActive = request()->routeIs('management.users') 
                            || request()->routeIs('slides.index')
                            || request()->routeIs('management.settings');
                @endphp

                @guest
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->is('login') ? 'active' : '' }}"
                    href="{{ route('login') }}">
                        <i class="fa-solid fa-right-to-bracket me-2"></i>
                        Masuk
                    </a>
                </li>
                @endguest


                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center {{ $AdminActive ? 'active' : '' }}"
                    data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user me-2"></i>
                        {{ auth()->user()->name }}<span class="dropdown-arrow">▼</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <span class="dropdown-item-text text-muted">
                                Role: {{ auth()->user()->role }}
                            </span>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                @auth
                    @if(auth()->user()->role === 'superadmin')
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('management.users') ? 'active' : '' }}" href="{{ route('management.users') }}">
                                Tambah Admin
                            </a>
                        </li>                        
                    @endif
                @endauth

                        <li>
                            <a class="dropdown-item {{ request()->routeIs('slides.index') ? 'active' : '' }}" href="{{ route('slides.index') }}">
                                Foto Beranda
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('management.settings') ? 'active' : '' }}" href="{{ route('management.settings') }}">
                                Ganti No. WA
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" class="dropdown-item text-danger" id="btnLogout">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                                    Keluar
                                </button>
                            </form>
                        </li>

                    </ul>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>