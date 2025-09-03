@extends('layouts.app')

@section('title', 'Galeri Halte Bus - E-HalteDishub')

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Swiper CSS untuk carousel -->
<link href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --accent-gradient: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        --light-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        --dishub-blue: #1e3c72;
        --dishub-accent: #3b82f6;
        --gold-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --purple-gradient: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    /* Header Section - Enhanced */
    .gallery-header {
        background: var(--primary-gradient);
        color: white;
        padding: 120px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .gallery-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.08"><polygon points="0,0 0,100 1000,80 1000,0"/></svg>');
        background-size: cover;
    }

    .gallery-header-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .gallery-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        background: linear-gradient(45deg, #fff, #e0f2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .gallery-subtitle {
        font-size: 1.3rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .breadcrumb-custom {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 10px 25px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb-custom a {
        color: white;
        text-decoration: none;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .breadcrumb-custom a:hover {
        opacity: 1;
    }

    .breadcrumb-custom .separator {
        opacity: 0.6;
    }

    /* Gallery Section */
    .gallery-section {
        padding: 80px 0;
        background: var(--light-gradient);
    }

    /* Enhanced Filter Buttons */
    .filter-buttons {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 50px;
    }

    .filter-btn {
        background: white;
        color: var(--dishub-blue);
        border: 2px solid var(--dishub-blue);
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.4s ease;
        cursor: pointer;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
    }

    .filter-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: var(--primary-gradient);
        transition: left 0.4s ease;
        z-index: 0;
    }

    .filter-btn span {
        position: relative;
        z-index: 1;
    }

    .filter-btn:hover::before,
    .filter-btn.active::before {
        left: 0;
    }

    .filter-btn:hover,
    .filter-btn.active {
        color: white;
        border-color: var(--dishub-blue);
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(30, 60, 114, 0.4);
    }

    /* New Carousel Layout */
    .main-carousel-container {
        position: relative;
        height: 600px;
        margin-bottom: 60px;
    }

    .carousel-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 20px;
        height: 100%;
    }

    .main-carousel {
        grid-row: 1 / 3;
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(30, 60, 114, 0.15);
    }

    .side-carousel {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.1);
        cursor: pointer;
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .side-carousel:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(30, 60, 114, 0.2);
    }

    /* Carousel Card Styles */
    .carousel-card {
        position: relative;
        width: 100%;
        height: 100%;
        background: white;
        border-radius: inherit;
        overflow: hidden;
        transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform-style: preserve-3d;
    }

    .carousel-card.expanded {
        transform: scale(1.02);
        z-index: 10;
        box-shadow: 0 30px 80px rgba(30, 60, 114, 0.3);
    }

    .carousel-card.shrunk {
        transform: scale(0.95);
        opacity: 0.7;
    }

    .carousel-image-container {
        position: relative;
        width: 100%;
        height: 70%;
        overflow: hidden;
    }

    .carousel-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.6s ease;
        filter: brightness(0.9);
    }

    .carousel-card:hover .carousel-image {
        transform: scale(1.1);
        filter: brightness(1);
    }

    .carousel-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.3));
        color: white;
        padding: 25px;
        transform: translateY(60%);
        transition: all 0.5s ease;
    }

    .carousel-card:hover .carousel-info,
    .carousel-card.expanded .carousel-info {
        transform: translateY(0);
    }

    .carousel-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .side-carousel .carousel-title {
        font-size: 1.1rem;
    }

    .carousel-description {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .carousel-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
    }

    .carousel-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        backdrop-filter: blur(10px);
        z-index: 2;
    }

    .status-available {
        background: rgba(16, 185, 129, 0.9);
        color: white;
    }

    .status-rented {
        background: rgba(239, 68, 68, 0.9);
        color: white;
    }

    .carousel-photo-count {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 5px;
        z-index: 2;
    }

    .carousel-actions {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        gap: 10px;
        opacity: 0;
        transition: all 0.5s ease;
        z-index: 5;
    }

    .carousel-card:hover .carousel-actions,
    .carousel-card.expanded .carousel-actions {
        opacity: 1;
    }

    .carousel-btn {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        font-size: 0.9rem;
    }

    .carousel-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(59, 130, 246, 0.5);
        color: white;
    }

    /* Carousel Navigation */
    .main-carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(30, 60, 114, 0.9);
        color: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-carousel-nav:hover {
        background: var(--dishub-accent);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-prev {
        left: 20px;
    }

    .carousel-next {
        right: 20px;
    }

    .carousel-indicators {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 10;
    }

    .carousel-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-indicator.active {
        background: white;
        transform: scale(1.2);
    }

    /* Traditional Grid (Hidden by default) */
    .traditional-grid {
        display: none;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 35px;
        margin-bottom: 60px;
    }

    /* Enhanced Halte Card for Traditional Grid */
    .halte-card {
        background: white;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(30, 60, 114, 0.08);
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border: 1px solid rgba(59, 130, 246, 0.1);
        position: relative;
        transform-style: preserve-3d;
    }

    .halte-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--accent-gradient);
        opacity: 0;
        transition: opacity 0.5s ease;
        z-index: 1;
        border-radius: 25px;
    }

    .halte-card:hover::before {
        opacity: 0.03;
    }

    .halte-card:hover {
        transform: translateY(-20px) rotateX(5deg) rotateY(5deg);
        box-shadow: 0 35px 70px rgba(30, 60, 114, 0.25);
    }

    /* Enhanced Image Container for Traditional Grid */
    .halte-image-container {
        position: relative;
        height: 320px;
        overflow: hidden;
        border-radius: 25px 25px 0 0;
    }

    .halte-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        filter: brightness(0.9) contrast(1.1);
    }

    .halte-card:hover .halte-image {
        transform: scale(1.15) rotate(2deg);
        filter: brightness(1) contrast(1.2);
    }

    /* Gradient Overlay for Traditional Grid */
    .halte-image-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            45deg,
            rgba(30, 60, 114, 0.1) 0%,
            transparent 40%,
            transparent 60%,
            rgba(59, 130, 246, 0.1) 100%
        );
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .halte-card:hover .halte-image-container::after {
        opacity: 1;
    }

    /* Enhanced Status Badge for Traditional Grid */
    .halte-status {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        z-index: 2;
        letter-spacing: 1px;
        animation: statusPulse 2s infinite;
    }

    @keyframes statusPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Enhanced Photo Count for Traditional Grid */
    .photo-count {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        z-index: 2;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .photo-count:hover {
        background: rgba(59, 130, 246, 0.9);
        transform: scale(1.1);
    }

    /* Enhanced Card Info for Traditional Grid */
    .halte-info {
        padding: 30px;
        position: relative;
        z-index: 2;
    }

    .halte-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--dishub-blue);
        margin-bottom: 12px;
        line-height: 1.3;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .halte-description {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .halte-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding: 15px 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }

    .halte-location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .halte-simbada {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .simbada-badge {
        background: var(--gold-gradient);
        color: white;
        padding: 6px 14px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    /* Enhanced Actions for Traditional Grid */
    .halte-actions {
        display: flex;
        gap: 12px;
    }

    .btn-view-detail {
        flex: 1;
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 15px 25px;
        border-radius: 25px;
        font-weight: 700;
        text-decoration: none;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .btn-view-detail::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }

    .btn-view-detail:hover::before {
        left: 100%;
    }

    .btn-view-detail:hover {
        transform: translateY(-3px);
        color: white;
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.5);
    }

    .btn-view-photos {
        background: var(--secondary-gradient);
        color: white;
        border: none;
        padding: 15px 20px;
        border-radius: 25px;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 0 8px 25px rgba(29, 78, 216, 0.3);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-view-photos::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transition: all 0.5s ease;
        transform: translate(-50%, -50%);
    }

    .btn-view-photos:hover::before {
        width: 200px;
        height: 200px;
    }

    .btn-view-photos:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 15px 35px rgba(29, 78, 216, 0.5);
    }

    /* View Toggle Buttons */
    .view-toggle {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 40px;
    }

    .view-toggle-btn {
        background: white;
        color: var(--dishub-blue);
        border: 2px solid var(--dishub-blue);
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .view-toggle-btn.active,
    .view-toggle-btn:hover {
        background: var(--dishub-blue);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.3);
    }

    /* Modern Photo Modal */
    .photo-modal {
        display: none;
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        backdrop-filter: blur(10px);
    }

    .photo-modal-content {
        position: relative;
        width: 95%;
        max-width: 1200px;
        height: 90vh;
        margin: 5vh auto;
        background: white;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        animation: modalSlideIn 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-100px) scale(0.8);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .photo-modal-header {
        background: var(--primary-gradient);
        color: white;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .photo-modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    .photo-modal-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }

    .photo-modal-close {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 10px;
        border-radius: 50%;
        transition: all 0.3s ease;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg) scale(1.1);
    }

    .photo-modal-body {
        padding: 0;
        height: calc(90vh - 80px);
        overflow: hidden;
    }

    /* Modern Swiper Carousel */
    .photo-carousel-container {
        height: 100%;
        position: relative;
    }

    .photo-swiper {
        width: 100%;
        height: 100%;
    }

    .photo-swiper .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
    }

    .modal-carousel-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        max-height: 100%;
        border-radius: 15px;
        transition: all 0.5s ease;
        cursor: zoom-in;
    }

    .modal-carousel-image.zoomed {
        cursor: zoom-out;
        object-fit: cover;
        transform: scale(1.5);
    }

    /* Custom Swiper Navigation */
    .swiper-button-next,
    .swiper-button-prev {
        background: rgba(30, 60, 114, 0.9);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        color: white !important;
        border: 2px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 20px;
        font-weight: 700;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(59, 130, 246, 0.9);
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
    }

    /* Custom Swiper Pagination */
    .swiper-pagination-bullet {
        background: rgba(30, 60, 114, 0.5);
        width: 12px;
        height: 12px;
        transition: all 0.3s ease;
    }

    .swiper-pagination-bullet-active {
        background: var(--dishub-blue);
        transform: scale(1.3);
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.4);
    }

    /* Image Counter */
    .image-counter {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        z-index: 1000;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .no-photos {
        text-align: center;
        padding: 80px 20px;
        color: #64748b;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .no-photos i {
        font-size: 5rem;
        margin-bottom: 30px;
        opacity: 0.3;
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Zoom Hint */
    .zoom-hint {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .photo-swiper:hover .zoom-hint {
        opacity: 1;
    }

    /* Enhanced Back to Top Button */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: var(--accent-gradient);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        font-size: 1.4rem;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        opacity: 0;
        visibility: hidden;
        z-index: 1000;
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 15px 40px rgba(59, 130, 246, 0.5);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .gallery-title {
            font-size: 2.5rem;
        }

        .carousel-layout {
            grid-template-columns: 1fr;
            grid-template-rows: 300px 150px 150px;
            height: 600px;
        }

        .main-carousel {
            grid-row: 1;
        }

        .traditional-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .filter-buttons {
            flex-direction: column;
            align-items: center;
        }

        .halte-actions {
            flex-direction: column;
        }

        .photo-modal-content {
            width: 98%;
            height: 95vh;
            margin: 2.5vh auto;
            border-radius: 15px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: 45px;
            height: 45px;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 16px;
        }

        .main-carousel-nav {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 30px;
        height: 30px;
        border: 3px solid rgba(59, 130, 246, 0.3);
        border-radius: 50%;
        border-top-color: var(--dishub-accent);
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 100px 20px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 5rem;
        margin-bottom: 30px;
        opacity: 0.3;
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        margin-bottom: 15px;
        color: #475569;
    }

    .empty-state p {
        font-size: 1.1rem;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<!-- Gallery Header -->
<section class="gallery-header">
    <div class="container">
        <div class="gallery-header-content">
            <div class="breadcrumb-custom" data-aos="fade-up">
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <span class="separator">/</span>
                <span>Galeri Halte</span>
            </div>
            <h1 class="gallery-title" data-aos="fade-up" data-aos-delay="100">
                Galeri Halte Bus
            </h1>
            <p class="gallery-subtitle" data-aos="fade-up" data-aos-delay="200">
                Jelajahi koleksi foto halte bus di seluruh kota dengan fasilitas modern dan terintegrasi
            </p>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section">
    <div class="container">
        <!-- Filter Buttons -->
        <div class="filter-buttons" data-aos="fade-up">
            <button class="filter-btn active" data-filter="all">
                <span><i class="fas fa-th me-2"></i>Semua Halte</span>
            </button>
            <button class="filter-btn" data-filter="available">
                <span><i class="fas fa-check-circle me-2"></i>Tersedia</span>
            </button>
            <button class="filter-btn" data-filter="rented">
                <span><i class="fas fa-clock me-2"></i>Disewa</span>
            </button>
            <button class="filter-btn" data-filter="simbada">
                <span><i class="fas fa-certificate me-2"></i>SIMBADA Terdaftar</span>
            </button>
        </div>

        <!-- View Toggle -->
        <div class="view-toggle" data-aos="fade-up" data-aos-delay="100">
            <button class="view-toggle-btn active" id="carouselViewBtn">
                <i class="fas fa-film me-2"></i>Tampilan Carousel
            </button>
            <!-- <button class="view-toggle-btn" id="gridViewBtn">
                <i class="fas fa-th-large me-2"></i>Tampilan Grid
            </button> -->
        </div>

        @if($haltes->count() > 0)
            <!-- Main Carousel Container -->
            <div class="main-carousel-container" id="carouselContainer" data-aos="fade-up" data-aos-delay="200">
                <div class="carousel-layout">
                    <!-- Main Carousel -->
                    <div class="main-carousel" id="mainCarousel">
                        <!-- Main carousel content will be populated by JavaScript -->
                    </div>

                    <!-- Side Carousels -->
                    <div class="side-carousel" id="sideCarousel1">
                        <!-- Side carousel 1 content will be populated by JavaScript -->
                    </div>

                    <div class="side-carousel" id="sideCarousel2">
                        <!-- Side carousel 2 content will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Navigation -->
                <button class="main-carousel-nav carousel-prev" onclick="prevMainSlide()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="main-carousel-nav carousel-next" onclick="nextMainSlide()">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators" id="carouselIndicators">
                    <!-- Indicators will be populated by JavaScript -->
                </div>
            </div>

            <!-- Traditional Grid -->
            <div class="traditional-grid" id="traditionalGrid">
                @foreach($haltes as $halte)
                <div class="halte-card"
                     data-filter="{{ $halte->isCurrentlyRented() ? 'rented' : 'available' }} {{ $halte->simbada_registered ? 'simbada' : '' }}"
                     data-aos="fade-up"
                     data-aos-delay="{{ $loop->index * 100 }}">

                    <div class="halte-image-container">
                        @php
                            $primaryPhoto = $halte->photos->where('is_primary', true)->first() ?? $halte->photos->first();
                            $photoUrl = $primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))
                                ? asset('storage/' . $primaryPhoto->photo_path)
                                : asset('images/halte-default.png');
                        @endphp

                        <img src="{{ $photoUrl }}" alt="{{ $halte->name }}" class="halte-image" loading="lazy">

                        <div class="halte-status {{ $halte->isCurrentlyRented() ? 'status-rented' : 'status-available' }}">
                            {{ $halte->isCurrentlyRented() ? 'Disewa' : 'Tersedia' }}
                        </div>

                        @if($halte->photos->count() > 0)
                        <div class="photo-count" onclick="openPhotoModal({{ $halte->id }}, '{{ $halte->name }}', {{ $halte->photos->toJson() }})">
                            <i class="fas fa-images"></i>
                            {{ $halte->photos->count() }} Foto
                        </div>
                        @endif
                    </div>

                    <div class="halte-info">
                        <h3 class="halte-title">{{ $halte->name }}</h3>

                        @if($halte->description)
                        <p class="halte-description">{{ $halte->description }}</p>
                        @endif

                        <div class="halte-meta">
                            @if($halte->address)
                            <div class="halte-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ Str::limit($halte->address, 30) }}
                            </div>
                            @endif

                            @if($halte->simbada_registered)
                            <div class="halte-simbada">
                                <span class="simbada-badge">
                                    <i class="fas fa-certificate"></i> SIMBADA
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="halte-actions">
                            @auth
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('halte.detail', $halte->id) }}" class="btn-view-detail">
                                        <i class="fas fa-info-circle me-2"></i>Lihat Detail
                                    </a>
                                @else
                                    <button onclick="showAccessModal()" class="btn-view-detail">
                                        <i class="fas fa-info-circle me-2"></i>Lihat Detail
                                    </button>
                                @endif
                            @else
                                <button onclick="showAccessModal()" class="btn-view-detail">
                                    <i class="fas fa-info-circle me-2"></i>Lihat Detail
                                </button>
                            @endauth

                            @if($halte->photos->count() > 0)
                            <button class="btn-view-photos" onclick="openPhotoModal({{ $halte->id }}, '{{ $halte->name }}', {{ $halte->photos->toJson() }})">
                                <i class="fas fa-images"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            @if($haltes->count() >= 12)
            <div class="text-center" data-aos="fade-up">
                <button class="filter-btn" id="loadMoreBtn">
                    <span class="load-text">
                        <i class="fas fa-plus me-2"></i>Muat Lebih Banyak
                    </span>
                    <span class="loading-spinner" style="display: none;"></span>
                </button>
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state" data-aos="fade-up">
                <i class="fas fa-bus"></i>
                <h3>Belum ada halte yang terdaftar</h3>
                <p>Data halte akan muncul setelah ditambahkan oleh administrator.</p>
            </div>
        @endif
    </div>
</section>

<!-- Enhanced Photo Modal with Swiper Carousel -->
<div id="photoModal" class="photo-modal">
    <div class="photo-modal-content">
        <div class="photo-modal-header">
            <h3 class="photo-modal-title" id="photoModalTitle">Foto Halte</h3>
            <button class="photo-modal-close" onclick="closePhotoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="photo-modal-body" id="photoModalBody">
            <div class="photo-carousel-container">
                <!-- Image Counter -->
                <div class="image-counter" id="imageCounter">1 / 1</div>

                <!-- Swiper Container -->
                <div class="swiper photo-swiper" id="photoSwiper">
                    <div class="swiper-wrapper" id="swiperWrapper">
                        <!-- Slides will be populated dynamically -->
                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>

                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- Zoom hint -->
                    <div class="zoom-hint">
                        <i class="fas fa-search-plus me-1"></i> Klik untuk zoom
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Access Restricted Modal -->
<div class="modal fade" id="accessRestrictedModal" tabindex="-1" aria-labelledby="accessRestrictedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-gradient); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="accessRestrictedModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>Akses Terbatas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" style="padding: 2rem; text-align: center;">
                <div style="font-size: 4rem; color: #ffc107; margin-bottom: 1rem;">
                    <i class="fas fa-lock"></i>
                </div>
                <div style="font-size: 1.5rem; font-weight: bold; color: #495057; margin-bottom: 1rem;">
                    Maaf, Akses Dibatasi!
                </div>
                <div style="color: #6c757d; font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem;">
                    Detail lengkap halte hanya dapat diakses oleh <strong>Admin</strong>.
                    Silakan login untuk melihat informasi detail halte.
                </div>
            </div>
            <div class="modal-footer justify-content-center" style="border-top: 1px solid #dee2e6; padding: 1rem 2rem; background-color: #f8f9fa; border-radius: 0 0 15px 15px;">
                @guest
                <a href="{{ route('login') }}" class="btn" style="background: var(--secondary-gradient); border: none; padding: 12px 30px; border-radius: 30px; color: white; font-weight: 600; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(29, 78, 216, 0.3); margin-right: 10px;">
                    <i class="fas fa-sign-in-alt me-1"></i> Login sebagai User
                </a>
                @endguest
                <button type="button" class="btn" data-bs-dismiss="modal" style="background: var(--accent-gradient); border: none; padding: 12px 30px; border-radius: 30px; color: white; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-check me-1"></i> Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
// Global variables
let halteData = @json($haltes);
let currentMainSlide = 0;
let photoSwiper = null;
let isCarouselView = true;

$(document).ready(function() {
    // Initialize AOS
    AOS.init({
        duration: 1000,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Initialize carousel if there's data
    if (halteData.length > 0) {
        initializeCarousel();
    }

    // View toggle functionality
    const carouselViewBtn = document.getElementById('carouselViewBtn');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const carouselContainer = document.getElementById('carouselContainer');
    const traditionalGrid = document.getElementById('traditionalGrid');

    carouselViewBtn.addEventListener('click', function() {
        if (!isCarouselView) {
            isCarouselView = true;
            this.classList.add('active');
            gridViewBtn.classList.remove('active');
            carouselContainer.style.display = 'block';
            traditionalGrid.style.display = 'none';
        }
    });

    gridViewBtn.addEventListener('click', function() {
        if (isCarouselView) {
            isCarouselView = false;
            this.classList.add('active');
            carouselViewBtn.classList.remove('active');
            carouselContainer.style.display = 'none';
            traditionalGrid.style.display = 'grid';
        }
    });

    // Enhanced Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn[data-filter]');
    const halteCards = document.querySelectorAll('.halte-card[data-filter]');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');

            // Update active filter button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Filter halte data for carousel
            let filteredHaltes = halteData;
            if (filter !== 'all') {
                filteredHaltes = halteData.filter(halte => {
                    const isRented = halte.rentals && halte.rentals.some(rental =>
                        new Date(rental.start_date) <= new Date() && new Date(rental.end_date) >= new Date()
                    );
                    const isAvailable = !isRented;

                    switch(filter) {
                        case 'available':
                            return isAvailable;
                        case 'rented':
                            return isRented;
                        case 'simbada':
                            return halte.simbada_registered;
                        default:
                            return true;
                    }
                });
            }

            // Update carousel with filtered data
            updateCarousel(filteredHaltes);

            // Filter traditional grid cards
            halteCards.forEach((card, index) => {
                const cardFilters = card.getAttribute('data-filter').split(' ');

                if (filter === 'all' || cardFilters.includes(filter)) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                    }, index * 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px) scale(0.95)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Back to top functionality
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Load more functionality
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const loadText = this.querySelector('.load-text');
            const spinner = this.querySelector('.loading-spinner');

            loadText.style.display = 'none';
            spinner.style.display = 'inline-block';

            // Simulate loading
            setTimeout(() => {
                loadText.style.display = 'inline';
                spinner.style.display = 'none';
            }, 2000);
        });
    }
});

// Carousel initialization
function initializeCarousel() {
    updateCarousel(halteData);
}

function updateCarousel(data) {
    if (data.length === 0) {
        document.getElementById('carouselContainer').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-bus"></i>
                <h3>Tidak ada halte yang sesuai filter</h3>
                <p>Coba ubah filter untuk melihat halte lainnya.</p>
            </div>
        `;
        return;
    }

    // Reset current slide if it exceeds new data length
    if (currentMainSlide >= data.length) {
        currentMainSlide = 0;
    }

    // Update carousel content
    updateCarouselContent(data);
    updateCarouselIndicators(data);
}

function updateCarouselContent(data) {
    const mainCarousel = document.getElementById('mainCarousel');
    const sideCarousel1 = document.getElementById('sideCarousel1');
    const sideCarousel2 = document.getElementById('sideCarousel2');

    // Get current, next, and previous halte data
    const currentHalte = data[currentMainSlide];
    const nextHalte = data[(currentMainSlide + 1) % data.length];
    const prevHalte = data[(currentMainSlide - 1 + data.length) % data.length];

    // Main carousel content
    mainCarousel.innerHTML = createCarouselCard(currentHalte, 'main');

    // Side carousels content
    if (data.length > 1) {
        sideCarousel1.innerHTML = createCarouselCard(nextHalte, 'side');
        sideCarousel2.innerHTML = createCarouselCard(prevHalte, 'side');
    } else {
        sideCarousel1.innerHTML = '';
        sideCarousel2.innerHTML = '';
    }

    // Add click handlers to side carousels
    if (data.length > 1) {
        sideCarousel1.onclick = () => expandCarouselCard(sideCarousel1, () => nextMainSlide());
        sideCarousel2.onclick = () => expandCarouselCard(sideCarousel2, () => prevMainSlide());
    }
}

function createCarouselCard(halte, type = 'main') {
    const primaryPhoto = halte.photos && halte.photos.length > 0
        ? halte.photos.find(p => p.is_primary) || halte.photos[0]
        : null;

    const photoUrl = primaryPhoto
        ? `/storage/${primaryPhoto.photo_path}`
        : '/images/halte-default.png';

    const isRented = halte.rentals && halte.rentals.some(rental =>
        new Date(rental.start_date) <= new Date() && new Date(rental.end_date) >= new Date()
    );

    const statusClass = isRented ? 'status-rented' : 'status-available';
    const statusText = isRented ? 'Disewa' : 'Tersedia';

    return `
        <div class="carousel-card">
            <div class="carousel-image-container">
                <img src="${photoUrl}" alt="${halte.name}" class="carousel-image" loading="lazy">

                <div class="carousel-status ${statusClass}">
                    ${statusText}
                </div>

                ${halte.photos && halte.photos.length > 0 ? `
                <div class="carousel-photo-count">
                    <i class="fas fa-images"></i>
                    ${halte.photos.length} Foto
                </div>
                ` : ''}

                <div class="carousel-actions">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="/halte/${halte.id}" class="carousel-btn">
                                <i class="fas fa-info-circle me-1"></i>Detail
                            </a>
                        @else
                            <button onclick="showAccessModal()" class="carousel-btn">
                                <i class="fas fa-info-circle me-1"></i>Detail
                            </button>
                        @endif
                    @else
                        <button onclick="showAccessModal()" class="carousel-btn">
                            <i class="fas fa-info-circle me-1"></i>Detail
                        </button>
                    @endauth

                    ${halte.photos && halte.photos.length > 0 ? `
                    <button class="carousel-btn" onclick="openPhotoModal(${halte.id}, '${halte.name}', ${JSON.stringify(halte.photos).replace(/"/g, '&quot;')})">
                        <i class="fas fa-images me-1"></i>Foto
                    </button>
                    ` : ''}
                </div>
            </div>

            <div class="carousel-info">
                <h3 class="carousel-title">${halte.name}</h3>
                ${halte.description ? `<p class="carousel-description">${halte.description.substring(0, 100)}${halte.description.length > 100 ? '...' : ''}</p>` : ''}

                <div class="carousel-meta">
                    ${halte.address ? `
                    <div class="carousel-location">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        ${halte.address.substring(0, 30)}${halte.address.length > 30 ? '...' : ''}
                    </div>
                    ` : ''}

                    ${halte.simbada_registered ? `
                    <div class="carousel-simbada">
                        <span class="simbada-badge">
                            <i class="fas fa-certificate"></i> SIMBADA
                        </span>
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

function updateCarouselIndicators(data) {
    const indicatorsContainer = document.getElementById('carouselIndicators');
    indicatorsContainer.innerHTML = '';

    for (let i = 0; i < data.length; i++) {
        const indicator = document.createElement('div');
        indicator.className = `carousel-indicator ${i === currentMainSlide ? 'active' : ''}`;
        indicator.onclick = () => goToSlide(i);
        indicatorsContainer.appendChild(indicator);
    }
}

function expandCarouselCard(cardElement, callback) {
    // Add expanded class to clicked card
    const card = cardElement.querySelector('.carousel-card');
    card.classList.add('expanded');

    // Add shrunk class to other cards
    const allCards = document.querySelectorAll('.carousel-card');
    allCards.forEach(c => {
        if (c !== card) {
            c.classList.add('shrunk');
        }
    });

    // Reset after animation and execute callback
    setTimeout(() => {
        allCards.forEach(c => {
            c.classList.remove('expanded', 'shrunk');
        });
        callback();
    }, 800);
}

function nextMainSlide() {
    const data = getCurrentFilteredData();
    currentMainSlide = (currentMainSlide + 1) % data.length;
    updateCarouselContent(data);
    updateCarouselIndicators(data);
}

function prevMainSlide() {
    const data = getCurrentFilteredData();
    currentMainSlide = (currentMainSlide - 1 + data.length) % data.length;
    updateCarouselContent(data);
    updateCarouselIndicators(data);
}

function goToSlide(index) {
    const data = getCurrentFilteredData();
    currentMainSlide = index;
    updateCarouselContent(data);
    updateCarouselIndicators(data);
}

function getCurrentFilteredData() {
    const activeFilter = document.querySelector('.filter-btn.active');
    const filter = activeFilter ? activeFilter.getAttribute('data-filter') : 'all';

    if (filter === 'all') {
        return halteData;
    }

    return halteData.filter(halte => {
        const isRented = halte.rentals && halte.rentals.some(rental =>
            new Date(rental.start_date) <= new Date() && new Date(rental.end_date) >= new Date()
        );
        const isAvailable = !isRented;

        switch(filter) {
            case 'available':
                return isAvailable;
            case 'rented':
                return isRented;
            case 'simbada':
                return halte.simbada_registered;
            default:
                return true;
        }
    });
}

// Photo modal functions
function openPhotoModal(halteId, halteName, photos) {
    const modal = document.getElementById('photoModal');
    const title = document.getElementById('photoModalTitle');
    const counter = document.getElementById('imageCounter');
    const swiperWrapper = document.getElementById('swiperWrapper');

    title.textContent = `Galeri ${halteName}`;

    if (photos && photos.length > 0) {
        // Clear existing slides
        swiperWrapper.innerHTML = '';

        // Create slides
        photos.forEach((photo, index) => {
            const photoUrl = `/storage/${photo.photo_path}`;
            const slide = document.createElement('div');
            slide.className = 'swiper-slide';
            slide.innerHTML = `
                <img src="${photoUrl}"
                     alt="${halteName} - Foto ${index + 1}"
                     class="modal-carousel-image"
                     onclick="toggleZoom(this)"
                     loading="lazy">
            `;
            swiperWrapper.appendChild(slide);
        });

        // Update counter
        counter.textContent = `1 / ${photos.length}`;

        // Initialize/Update Swiper
        if (photoSwiper) {
            photoSwiper.destroy(true, true);
        }

        photoSwiper = new Swiper('#photoSwiper', {
            loop: photos.length > 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + (index + 1) + '</span>';
                },
            },
            keyboard: {
                enabled: true,
                onlyInViewport: false,
            },
            mousewheel: {
                invert: false,
            },
            speed: 600,
            effect: 'slide',
            allowTouchMove: true,
            grabCursor: true,
            on: {
                slideChange: function () {
                    const currentSlide = this.realIndex + 1;
                    counter.textContent = `${currentSlide} / ${photos.length}`;

                    // Reset zoom on all images
                    const images = document.querySelectorAll('.modal-carousel-image');
                    images.forEach(img => {
                        img.classList.remove('zoomed');
                    });
                }
            }
        });
    } else {
        document.getElementById('photoModalBody').innerHTML = `
            <div class="no-photos">
                <i class="fas fa-camera-retro"></i>
                <h4>Tidak ada foto tersedia</h4>
                <p>Foto halte belum tersedia untuk saat ini.</p>
            </div>
        `;
    }

    // Show modal with animation
    modal.style.display = 'block';
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    const modal = document.getElementById('photoModal');

    // Destroy swiper instance
    if (photoSwiper) {
        photoSwiper.destroy(true, true);
        photoSwiper = null;
    }

    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);

    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Toggle zoom function for images
function toggleZoom(image) {
    const isZoomed = image.classList.contains('zoomed');

    // Reset all images first
    const allImages = document.querySelectorAll('.modal-carousel-image');
    allImages.forEach(img => img.classList.remove('zoomed'));

    // Toggle current image
    if (!isZoomed) {
        image.classList.add('zoomed');
    }
}

// Close modal when clicking outside or pressing ESC
window.addEventListener('click', function(event) {
    const modal = document.getElementById('photoModal');
    if (event.target === modal) {
        closePhotoModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePhotoModal();
    }

    // Keyboard navigation for main carousel
    if (isCarouselView && halteData.length > 0) {
        if (event.key === 'ArrowLeft') {
            prevMainSlide();
        } else if (event.key === 'ArrowRight') {
            nextMainSlide();
        }
    }
});

// Access modal function
function showAccessModal() {
    const modal = new bootstrap.Modal(document.getElementById('accessRestrictedModal'));
    modal.show();
}

// Auto-play carousel (optional)
let autoPlayInterval;

function startAutoPlay() {
    if (halteData.length > 1) {
        autoPlayInterval = setInterval(() => {
            if (isCarouselView) {
                nextMainSlide();
            }
        }, 5000);
    }
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
}

// Start auto-play when page loads
setTimeout(startAutoPlay, 3000);

// Pause auto-play on hover
document.getElementById('carouselContainer')?.addEventListener('mouseenter', stopAutoPlay);
document.getElementById('carouselContainer')?.addEventListener('mouseleave', startAutoPlay);

// Touch/swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

document.getElementById('carouselContainer')?.addEventListener('touchstart', function(event) {
    touchStartX = event.changedTouches[0].screenX;
});

document.getElementById('carouselContainer')?.addEventListener('touchend', function(event) {
    touchEndX = event.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            nextMainSlide();
        } else {
            prevMainSlide();
        }
    }
}

// Parallax effect for header
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const header = document.querySelector('.gallery-header');
    if (header) {
        const rate = scrolled * -0.5;
        header.style.transform = `translateY(${rate}px)`;
    }
});

// Intersection observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
        }
    });
}, observerOptions);

// Observe all cards and carousel elements
document.querySelectorAll('.halte-card, .carousel-card').forEach(element => {
    observer.observe(element);
});

// Preload images for better performance
function preloadImages() {
    halteData.forEach(halte => {
        if (halte.photos && halte.photos.length > 0) {
            halte.photos.forEach(photo => {
                const img = new Image();
                img.src = `/storage/${photo.photo_path}`;
            });
        }
    });
}

// Start preloading after initial load
setTimeout(preloadImages, 1000);

// Resize handler for responsive carousel
window.addEventListener('resize', function() {
    if (isCarouselView && halteData.length > 0) {
        setTimeout(() => {
            updateCarouselContent(getCurrentFilteredData());
        }, 100);
    }
});

// Performance optimization: throttle scroll events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Apply throttling to scroll events
window.addEventListener('scroll', throttle(function() {
    // Back to top visibility
    const backToTop = document.getElementById('backToTop');
    if (window.pageYOffset > 300) {
        backToTop.classList.add('show');
    } else {
        backToTop.classList.remove('show');
    }
}, 100));

// Add loading states for better UX
function showLoading() {
    const carouselContainer = document.getElementById('carouselContainer');
    if (carouselContainer) {
        carouselContainer.innerHTML = `
            <div style="display: flex; justify-content: center; align-items: center; height: 600px;">
                <div class="loading-spinner"></div>
                <span style="margin-left: 15px; color: var(--dishub-blue); font-weight: 600;">Memuat galeri...</span>
            </div>
        `;
    }
}

function hideLoading() {
    // This will be called after carousel is updated
}

// Error handling for missing images
function handleImageError(img) {
    img.src = '/images/halte-default.png';
    img.alt = 'Gambar tidak tersedia';
}

// Add error handlers to dynamically created images
document.addEventListener('DOMContentLoaded', function() {
    // Set up error handlers for all images
    document.addEventListener('error', function(e) {
        if (e.target.tagName === 'IMG') {
            handleImageError(e.target);
        }
    }, true);
});

console.log('Gallery carousel initialized with', halteData.length, 'halte(s)');
</script>
@endpush
