@extends('layouts.app')

@section('title', 'Login')

@section('body')
<div class="login-wrapper">
    <div class="login-container">
        <div class="login-card">
            @php $setting = \App\Models\Setting::current(); @endphp
            <div class="login-logo">
                @if($setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="height: 48px; border-radius: 8px; margin: 0 auto 16px;">
                @else
                    <div class="login-logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/>
                        </svg>
                    </div>
                @endif
                <h1>{{ $setting->nama_perusahaan }}</h1>
                <p>Sistem Informasi Manajemen Data Properti</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            @php $mode = $authMode ?? (request()->routeIs('register') ? 'register' : 'login'); @endphp

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 20px; background: #f1f5f9; padding: 4px; border-radius: 10px;">
                <button type="button" id="tabLogin" onclick="switchAuthMode('login')" style="border: none; border-radius: 8px; padding: 10px; font-weight: 700; cursor: pointer; background: {{ $mode === 'login' ? '#fff' : 'transparent' }}; color: {{ $mode === 'login' ? 'var(--primary)' : '#64748b' }}; box-shadow: {{ $mode === 'login' ? '0 1px 3px rgba(15,23,42,.12)' : 'none' }};">
                    Masuk
                </button>
                <button type="button" id="tabRegister" onclick="switchAuthMode('register')" style="border: none; border-radius: 8px; padding: 10px; font-weight: 700; cursor: pointer; background: {{ $mode === 'register' ? '#fff' : 'transparent' }}; color: {{ $mode === 'register' ? 'var(--primary)' : '#64748b' }}; box-shadow: {{ $mode === 'register' ? '0 1px 3px rgba(15,23,42,.12)' : 'none' }};">
                    Daftar
                </button>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm" style="{{ $mode === 'login' ? '' : 'display: none;' }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="nama@telkomproperty.com"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <div class="form-checkbox-group">
                    <label class="form-checkbox-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" id="btnLogin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                    </svg>
                    Masuk ke Sistem
                </button>
            </form>

            <form method="POST" action="{{ route('register') }}" id="registerForm" style="{{ $mode === 'register' ? '' : 'display: none;' }}">
                @csrf

                <div class="form-group">
                    <label for="register_name" class="form-label">Nama Lengkap</label>
                    <input
                        type="text"
                        id="register_name"
                        name="name"
                        class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name') }}"
                        placeholder="Nama pengguna"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="register_email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="register_email"
                        name="email"
                        class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="nama@telkomproperty.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="register_password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="register_password"
                        name="password"
                        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Minimal 8 karakter"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input"
                        placeholder="Ulangi password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3.75 19.5a7.5 7.5 0 0 1 15 0v.75H3.75v-.75Z"/>
                    </svg>
                    Buat Akun
                </button>
            </form>
        </div>

        <p style="text-align: center; color: #475569; font-size: 13px; margin-top: 24px;">
            &copy; {{ date('Y') }} {{ $setting->nama_perusahaan }}. All rights reserved.
        </p>
    </div>
</div>

<script>
    function switchAuthMode(mode) {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const tabLogin = document.getElementById('tabLogin');
        const tabRegister = document.getElementById('tabRegister');

        const loginActive = mode === 'login';
        loginForm.style.display = loginActive ? '' : 'none';
        registerForm.style.display = loginActive ? 'none' : '';

        tabLogin.style.background = loginActive ? '#fff' : 'transparent';
        tabLogin.style.color = loginActive ? 'var(--primary)' : '#64748b';
        tabLogin.style.boxShadow = loginActive ? '0 1px 3px rgba(15,23,42,.12)' : 'none';

        tabRegister.style.background = loginActive ? 'transparent' : '#fff';
        tabRegister.style.color = loginActive ? '#64748b' : 'var(--primary)';
        tabRegister.style.boxShadow = loginActive ? 'none' : '0 1px 3px rgba(15,23,42,.12)';

        window.history.replaceState(null, '', loginActive ? '{{ route('login') }}' : '{{ route('register') }}');
    }
</script>
@endsection
