@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="login-wrapper">
    <div class="login-container">
        <!-- Left Side - Login Form -->
        <div class="login-section">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-icon">
                        <img src="{{ asset('DISHUB SURABAYA.svg') }}" alt="Logo E-HalteDishub" class="login-logo">
                    </div>
                    <h2 class="login-title">Selamat Datang</h2>
                    <p class="login-subtitle">Masuk untuk melanjutkan ke dashboard</p>
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

                        <button type="submit" class="login-button">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </button>
                    </form>
                </div>

                <div class="login-footer">
                    <a href="{{ route('home') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Side - Image Slider -->
        <div class="slider-section">
            <div class="image-slider">
                <div class="slider-track">
                    <div class="slide active">
                        <img src="https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800&h=1000&fit=crop" alt="Bus Stop 1">
                        <div class="slide-overlay">
                            <div class="slide-content">
                                <h3>Sistem Manajemen Halte Modern</h3>
                                <p>Kelola data halte dengan mudah dan efisien</p>
                            </div>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800&h=1000&fit=crop" alt="Bus Stop 2">
                        <div class="slide-overlay">
                            <div class="slide-content">
                                <h3>Transportasi Publik Terintegrasi</h3>
                                <p>Informasi real-time untuk pengguna halte</p>
                            </div>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=800&h=1000&fit=crop" alt="Bus Stop 3">
                        <div class="slide-overlay">
                            <div class="slide-content">
                                <h3>Monitoring dan Pelaporan</h3>
                                <p>Dashboard analitik yang komprehensif</p>
                            </div>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=800&h=1000&fit=crop" alt="Bus Stop 4">
                        <div class="slide-overlay">
                            <div class="slide-content">
                                <h3>Layanan Terpercaya</h3>
                                <p>Meningkatkan kualitas transportasi publik</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Dots -->
                <div class="slider-dots">
                    <span class="dot active" onclick="goToSlide(0)"></span>
                    <span class="dot" onclick="goToSlide(1)"></span>
                    <span class="dot" onclick="goToSlide(2)"></span>
                    <span class="dot" onclick="goToSlide(3)"></span>
                </div>

                <!-- Navigation Arrows -->
                <button class="slider-arrow prev" onclick="prevSlide()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-arrow next" onclick="nextSlide()">
                    <i class="fas fa-chevron-right"></i>
                </button>
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
    <script>
        // Image Slider Functionality
        let currentSlide = 0;
        let slideInterval;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const totalSlides = slides.length;

        function showSlide(n) {
            // Reset if out of bounds
            if (n >= totalSlides) currentSlide = 0;
            if (n < 0) currentSlide = totalSlides - 1;

            // Remove active class from all slides and dots
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            // Add active class to current slide and dot
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            currentSlide++;
            showSlide(currentSlide);
            resetInterval();
        }

        function prevSlide() {
            currentSlide--;
            showSlide(currentSlide);
            resetInterval();
        }

        function goToSlide(n) {
            currentSlide = n;
            showSlide(currentSlide);
            resetInterval();
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Auto slide every 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            slideInterval = setInterval(nextSlide, 5000);

            // Pause on hover
            const sliderSection = document.querySelector('.slider-section');
            if (sliderSection) {
                sliderSection.addEventListener('mouseenter', () => {
                    clearInterval(slideInterval);
                });

                sliderSection.addEventListener('mouseleave', () => {
                    slideInterval = setInterval(nextSlide, 5000);
                });
            }
        });
    </script>
@endpush
