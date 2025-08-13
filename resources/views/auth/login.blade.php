@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt"></i> Login Admin
                    </h4>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   autofocus
                                   placeholder="Masukkan email Anda">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Masukkan password Anda">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Hanya admin yang dapat mengakses halaman ini.
                    </small>
                    <br>
                    <small class="text-muted">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </small>
                </div>
            </div>

            <!-- Demo Login Info -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-info"></i> Informasi Login Demo
                    </h6>
                    <div class="row">
                        <div class="col-md-12">
                            <small class="text-muted">
                                <strong>Email:</strong> admin@halte.com<br>
                                <strong>Password:</strong> password
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 1rem;
}
.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}
.form-control {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
}
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}
.btn-lg {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}
.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding-top: 2rem;
    padding-bottom: 2rem;
}
.container {
    position: relative;
    z-index: 1;
}
</style>
@endsection
