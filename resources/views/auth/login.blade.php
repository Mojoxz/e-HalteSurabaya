@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-icon">
                            <img src="{{ asset('DISHUB SURABAYA.svg') }}" alt="Logo E-HalteDishub" class="login-logo">
                        </div>
                        <h2 class="login-title">Login</h2>
                        <p class="login-subtitle">Masuk Untuk Pengalaman Lebih Baik</p>
                    </div>

                    <div class="login-body">
                        <form method="POST" action="{{ route('login') }}" class="login-form">
                            @csrf

                            <div class="input-group-wrapper">
                                <label for="email" class="input-label">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <div class="input-wrapper">
                                    <input
                                        type="email"
                                        class="form-input @error('email') input-error @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus
                                        placeholder="Masukkan email Anda">
                                    @error('email')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="input-group-wrapper">
                                <label for="password" class="input-label">
                                    <i class="fas fa-lock"></i>
                                    Password
                                </label>
                                <div class="input-wrapper password-wrapper">
                                    <input
                                        type="password"
                                        class="form-input password-input @error('password') input-error @enderror"
                                        id="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukkan password Anda">
                                    <button type="button" class="password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!--<div class="checkbox-wrapper">
                                <label class="checkbox-label">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        class="checkbox-input"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <span class="checkbox-custom"></span>
                                    <span class="checkbox-text">Ingat saya</span>
                                </label>
                            </div> -->

                            <button type="submit" class="login-button">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login </span>
                            </button>
                        </form>
                    </div>

                    <div class="login-footer">
                       <!--<div class="footer-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Hanya administrator yang dapat mengakses halaman ini</span>
                        </div> -->
                        <a href="{{ route('home') }}" class="back-link">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    @vite('resources/css/login.css')
@endpush

@push('scripts')
    @vite('resources/js/login.js')
@endpush
