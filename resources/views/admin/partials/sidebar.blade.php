<!-- Admin Sidebar Component -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <h4 class="sidebar-title">
            <i class="fas fa-tachometer-alt"></i>
            <span class="sidebar-text">Admin Panel</span>
        </h4>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="sidebar-content">
        <nav class="sidebar-nav">
            <div class="nav-section">
                <h6 class="nav-section-title">MANAJEMEN</h6>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('admin.haltes.index') }}" class="nav-link">
                            <i class="fas fa-list"></i>
                            <span class="nav-text">Kelola Halte</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.haltes.create') }}" class="nav-link">
                            <i class="fas fa-plus-circle"></i>
                            <span class="nav-text">Tambah Halte</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span class="nav-text">Kelola User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.create') }}" class="nav-link">
                            <i class="fas fa-user-plus"></i>
                            <span class="nav-text">Tambah User</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <h6 class="nav-section-title">LAPORAN</h6>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('admin.rentals.index') }}" class="nav-link">
                            <i class="fas fa-history"></i>
                            <span class="nav-text">Riwayat Sewa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-line"></i>
                            <span class="nav-text">Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <h6 class="nav-section-title">LAINNYA</h6>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="fas fa-map"></i>
                            <span class="nav-text">Lihat Peta</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}" class="nav-link">
                            <i class="fas fa-user-cog"></i>
                            <span class="nav-text">Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>

<!-- Sidebar Show Button -->
<button class="sidebar-show-btn" id="sidebarShowBtn" style="display: none;">
    <i class="fas fa-bars"></i>
</button>

<style>
:root {
    --dishub-blue: #1a4b8c;
    --dishub-light-blue: #e6f0fa;
    --dishub-accent: #2a75d6;
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 70px;
    --header-height: 70px;
    --animation-timing: 0.3s;
}

/* Admin Sidebar Styles */
.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: linear-gradient(180deg, var(--dishub-blue) 0%, #153e75 100%);
    color: white;
    transition: all var(--animation-timing) ease;
    z-index: 1000;
    overflow-x: hidden;
}

.admin-sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    height: var(--header-height);
}

.sidebar-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
    transition: opacity var(--animation-timing) ease;
}

.admin-sidebar.collapsed .sidebar-text {
    opacity: 0;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all var(--animation-timing) ease;
}

.sidebar-toggle:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-content {
    padding: 1rem 0;
    height: calc(100vh - var(--header-height));
    overflow-y: auto;
}

/* Navigation Styles */
.nav-section {
    margin-bottom: 2rem;
}

.nav-section-title {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.6);
    padding: 0 1rem;
    margin-bottom: 0.5rem;
    transition: opacity var(--animation-timing) ease;
}

.admin-sidebar.collapsed .nav-section-title {
    opacity: 0;
}

.nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all var(--animation-timing) ease;
    position: relative;
}

.nav-link:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
    text-decoration: none;
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.15);
    color: white;
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background-color: white;
}

.nav-link i {
    width: 20px;
    text-align: center;
    margin-right: 0.75rem;
    font-size: 1rem;
}

.nav-text {
    white-space: nowrap;
    transition: opacity var(--animation-timing) ease;
}

.admin-sidebar.collapsed .nav-text {
    opacity: 0;
}

/* Sidebar Show Button */
.sidebar-show-btn {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    background: var(--dishub-blue);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(26, 75, 140, 0.3);
    cursor: pointer;
    transition: all var(--animation-timing) ease;
    font-size: 1.1rem;
}

.sidebar-show-btn:hover {
    background: #153e75;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(26, 75, 140, 0.4);
}

.sidebar-show-btn:active {
    transform: scale(0.95);
}

/* Animation for show button */
.sidebar-show-btn.show {
    animation: bounceIn 0.5s ease;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Content Adjustment Classes */
.with-sidebar {
    margin-left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
    transition: all var(--animation-timing) ease;
}

.with-sidebar.collapsed {
    margin-left: var(--sidebar-collapsed-width);
    width: calc(100% - var(--sidebar-collapsed-width));
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-sidebar {
        transform: translateX(-100%);
    }

    .admin-sidebar.show {
        transform: translateX(0);
    }

    .admin-sidebar.collapsed {
        transform: translateX(-100%);
    }

    .with-sidebar {
        margin-left: 0;
        width: 100%;
    }

    .sidebar-show-btn {
        display: flex !important;
    }

    /* Overlay when sidebar is open on mobile */
    .admin-sidebar.show::after {
        content: '';
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        width: calc(100vw - var(--sidebar-width));
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        z-index: -1;
    }
}

/* Desktop collapsed state */
@media (min-width: 769px) {
    .admin-sidebar.collapsed ~ .sidebar-show-btn {
        display: flex !important;
    }
}

@media (max-width: 480px) {
    .admin-sidebar {
        width: 260px;
    }

    .admin-sidebar.show::after {
        left: 260px;
        width: calc(100vw - 260px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarShowBtn = document.getElementById('sidebarShowBtn');

    if (!sidebar || !sidebarToggle || !sidebarShowBtn) {
        console.error('Admin Sidebar: Required elements not found');
        return;
    }

    // Function to update show button visibility
    function updateShowButtonVisibility() {
        if (sidebar.classList.contains('collapsed') || window.innerWidth <= 768) {
            sidebarShowBtn.style.display = 'flex';
            sidebarShowBtn.classList.add('show');
        } else {
            sidebarShowBtn.style.display = 'none';
            sidebarShowBtn.classList.remove('show');
        }
    }

    // Function to update main content classes
    function updateMainContentClasses() {
        const mainContent = document.querySelector('.main-content, main, .content');
        if (mainContent) {
            mainContent.classList.add('with-sidebar');
            if (sidebar.classList.contains('collapsed')) {
                mainContent.classList.add('collapsed');
            } else {
                mainContent.classList.remove('collapsed');
            }
        }
    }

    // Sidebar toggle functionality (collapse/expand)
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        updateShowButtonVisibility();
        updateMainContentClasses();

        // Save state to localStorage
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });

    // Sidebar show functionality (unhide)
    sidebarShowBtn.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('show');
        } else {
            sidebar.classList.remove('collapsed');
            localStorage.setItem('sidebarCollapsed', false);
        }
        updateShowButtonVisibility();
        updateMainContentClasses();
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
            sidebar.classList.remove('show');
        } else {
            sidebar.classList.remove('show');
        }
        updateShowButtonVisibility();
        updateMainContentClasses();
    });

    // Close sidebar when clicking outside (mobile only)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 &&
            sidebar.classList.contains('show') &&
            !sidebar.contains(e.target) &&
            !sidebarShowBtn.contains(e.target)) {
            sidebar.classList.remove('show');
            updateShowButtonVisibility();
        }
    });

    // Restore sidebar state from localStorage
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true' && window.innerWidth > 768) {
        sidebar.classList.add('collapsed');
    }

    // Set active nav link
    function setActiveNavLink() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    }

    // Smooth scroll for anchor links
    const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // Initial setup
    if (window.innerWidth <= 768) {
        sidebar.classList.remove('show');
    }

    updateShowButtonVisibility();
    updateMainContentClasses();
    setActiveNavLink();

    // Expose functions globally for external use
    window.AdminSidebar = {
        toggle: function() {
            sidebar.classList.toggle('collapsed');
            updateShowButtonVisibility();
            updateMainContentClasses();
        },
        show: function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('show');
            } else {
                sidebar.classList.remove('collapsed');
            }
            updateShowButtonVisibility();
            updateMainContentClasses();
        },
        hide: function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show');
            } else {
                sidebar.classList.add('collapsed');
            }
            updateShowButtonVisibility();
            updateMainContentClasses();
        },
        setActive: function(href) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === href) {
                    link.classList.add('active');
                }
            });
        },
        isCollapsed: function() {
            return sidebar.classList.contains('collapsed');
        },
        isVisible: function() {
            return !sidebar.classList.contains('collapsed') || sidebar.classList.contains('show');
        }
    };
});
</script>
