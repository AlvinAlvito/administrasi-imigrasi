<nav>
    <div class="logo-name">
        <div class="logo-image">
            <img src="/images/logo.png" alt="">
        </div>
        <span class="logo_name">
            @if (session('role'))
                {{ ucfirst(session('role')) }}
            @else
                Dashboard
            @endif
        </span>
    </div>

    <div class="menu-items">
        <ul class="nav-links">

            {{-- ================= ADMIN ================= --}}
            @if (session('is_admin'))
                <li>
                    <a href="{{ route('admin.index') }}" class="{{ Request::is('admin') ? 'active' : '' }}">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Beranda</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.pegawai.index') }}"
                        class="{{ Request::is('admin/akun-pegawai') ? 'active' : '' }}">
                        <i class="uil uil-users-alt"></i>
                        <span class="link-name">Akun Pegawai</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.pimpinan.index') }}"
                        class="{{ Request::is('admin/akun-pimpinan') ? 'active' : '' }}">
                        <i class="uil uil-user-check"></i>
                        <span class="link-name">Akun Pimpinan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.surat.index') }}" class="{{ Request::is('admin/surat') ? 'active' : '' }}">
                        <i class="uil uil-clipboard-notes"></i>
                        <span class="link-name">Semua Surat</span>
                    </a>
                </li>
            @endif

            {{-- ================= PIMPINAN ================= --}}
            @if (session('is_pimpinan'))
                <li>
                    <a href="{{ route('pimpinan.index') }}" class="{{ Request::is('pimpinan') ? 'active' : '' }}">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Beranda</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pimpinan.surat.index') }}"
                        class="{{ Request::is('pimpinan/surat') ? 'active' : '' }}">
                        <i class="uil uil-check-circle"></i>
                        <span class="link-name">Verifikasi Surat</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pimpinan.profil') }}"
                        class="{{ Request::is('pimpinan/profil') ? 'active' : '' }}">
                        <i class="uil uil-user"></i>
                        <span class="link-name">Profil</span>
                    </a>
                </li>
            @endif


            {{-- ================= PEGAWAI ================= --}}
            @if (session('is_pegawai'))
                <li>
                    <a href="{{ route('pegawai.index') }}" class="{{ Request::is('pegawai') ? 'active' : '' }}">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Beranda</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pegawai.surat.index') }}"
                        class="{{ Request::is('pegawai/surat') ? 'active' : '' }}">
                        <i class="uil uil-file-plus-alt"></i>
                        <span class="link-name">Surat Saya</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pegawai.profil') }}"
                        class="{{ Request::is('pegawai/profil') ? 'active' : '' }}">
                        <i class="uil uil-user"></i>
                        <span class="link-name">Profil</span>
                    </a>
                </li>
            @endif


            {{-- Jika belum login (fallback) --}}
            @if (!session('is_admin') && !session('is_pimpinan') && !session('is_pegawai'))
                <li>
                    <a href="{{ route('login') }}" class="{{ Request::is('/') ? 'active' : '' }}">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Beranda</span>
                    </a>
                </li>
            @endif

        </ul>

        <ul class="logout-mode">
            <li>
                <a href="{{ route('logout') }}">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a>
            </li>

            <li class="mode">
                <a href="#">
                    <i class="uil uil-moon"></i>
                    <span class="link-name">Dark Mode</span>
                </a>
                <div class="mode-toggle">
                    <span class="switch"></span>
                </div>
            </li>
        </ul>
    </div>
</nav>
