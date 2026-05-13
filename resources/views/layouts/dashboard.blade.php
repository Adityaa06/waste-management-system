<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | EcoSmart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ml5.js AI -->
    <script src="https://unpkg.com/ml5@latest/dist/ml5.min.js"></script>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="bg-dark-bg text-gray-100 antialiased overflow-hidden opacity-0" id="main-body">

    <!-- Animated Background Shapes -->
    <div class="fixed inset-0 overflow-hidden -z-10 opacity-30">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
    </div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="w-72 glass h-full m-4 rounded-3xl flex flex-col transition-all duration-500 z-50">
            <div class="p-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/50">
                    <i class="fas fa-recycle text-xl"></i>
                </div>
                <span class="text-xl font-bold tracking-tight sidebar-text">Eco<span class="text-primary">Smart</span></span>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                @yield('sidebar')
            </nav>

            <div class="p-4 mt-auto">
                <div class="glass rounded-2xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary to-secondary flex items-center justify-center font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0 sidebar-text">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 h-full overflow-y-auto p-4 lg:p-8">
            <!-- Top Navbar -->
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-bold mb-1">@yield('header', 'Dashboard')</h1>
                    <p class="text-gray-500 text-sm">Welcome back, {{ Auth::user()->name }}!</p>
                </div>

                <div class="flex items-center gap-4">
                    <button class="w-10 h-10 glass rounded-xl flex items-center justify-center hover:text-primary transition-all">
                        <i class="fas fa-bell"></i>
                    </button>
                    <div class="hidden md:block">
                        <p class="text-sm font-medium">{{ date('l, d M Y') }}</p>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Toast Notification -->
    @if(session('success'))
    <div id="toast" class="fixed bottom-10 right-10 glass px-6 py-4 rounded-2xl border-l-4 border-l-primary flex items-center gap-4 z-[200] translate-x-[150%]">
        <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center text-primary">
            <i class="fas fa-check text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-white">Success</p>
            <p class="text-xs text-gray-400">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Page Entrance
            gsap.to("#main-body", {
                opacity: 1,
                duration: 1,
                ease: "power2.out"
            });

            // Sidebar Entrance Animation
        gsap.from("#sidebar", {
            x: -100,
            opacity: 0,
            duration: 0.8,
            ease: "power4.out"
        });

        // Content Entrance Animation
        gsap.from(".page-content > *", {
            y: 30,
            opacity: 0,
            duration: 0.8,
            stagger: 0.1,
            ease: "power3.out"
        });

        // Toast Animation
        if (document.getElementById('toast')) {
            gsap.to("#toast", {
                x: 0,
                duration: 0.8,
                ease: "power4.out"
            });

            setTimeout(() => {
                gsap.to("#toast", {
                    x: "150%",
                    duration: 0.8,
                    ease: "power4.in"
                });
            }, 4000);
        }
        });
    </script>
</body>
</html>
