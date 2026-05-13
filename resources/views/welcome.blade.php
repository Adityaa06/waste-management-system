<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSmart | Smart Waste Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="selection:bg-primary selection:text-white">

    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center glass rounded-2xl px-6 py-3">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white shadow-lg shadow-primary/50">
                    <i class="fas fa-recycle text-xl"></i>
                </div>
                <span class="text-xl font-bold tracking-tight">Eco<span class="text-primary">Smart</span></span>
            </div>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="#features" class="hover:text-primary transition-colors">Features</a>
                <a href="#about" class="hover:text-primary transition-colors">About</a>
                <a href="#contact" class="hover:text-primary transition-colors">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-primary transition-colors">Log
                            in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left flex flex-col items-center lg:items-start hero-content">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-sm font-medium mb-8 animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        Revolutionizing Waste Management with AI
                    </div>
                    <h1 class="text-5xl md:text-7xl font-bold tracking-tighter mb-6 leading-tight">
                        Smart Waste <br>
                        <span class="bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text text-transparent">Management System</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-400 max-w-xl mb-10 leading-relaxed">
                        Optimize collection routes, monitor bin levels in real-time, and reduce carbon footprint with our
                        state-of-the-art 3D tracking technology.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ route('register') }}" class="btn-primary text-lg">
                            Start Your Green Journey
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                        <a href="#demo"
                            class="px-8 py-3 rounded-full font-semibold border border-white/10 hover:bg-white/5 transition-all flex items-center justify-center">
                            View Demo
                        </a>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="relative flex justify-center lg:justify-end mt-10 lg:mt-0 hero-content">
                    <style>
                        @keyframes float-premium {
                            0%, 100% { transform: translateY(0) scale(1); }
                            50% { transform: translateY(-20px) scale(1.02); }
                        }
                        .premium-float {
                            animation: float-premium 6s ease-in-out infinite;
                        }
                    </style>
                    <div class="relative w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] lg:w-[450px] lg:h-[450px] rounded-full group cursor-pointer transition-transform duration-500 premium-float">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-cyan-500 rounded-full blur-3xl opacity-40 group-hover:opacity-70 transition-opacity duration-500"></div>
                        
                        <!-- Image Container (Glass Border) -->
                        <div class="absolute inset-0 rounded-full border border-white/20 bg-white/10 backdrop-blur-md p-2 overflow-hidden shadow-[0_0_60px_rgba(34,197,94,0.3)] z-10">
                            <img 
                                src="{{ asset('images/hero-ai-dashboard.png') }}" 
                                alt="AI Waste Management Dashboard" 
                                class="w-full h-full object-cover rounded-full transition-transform duration-700 group-hover:scale-110"
                            >
                            
                            <!-- Digital Overlay Elements -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent rounded-full pointer-events-none"></div>
                            
                            <!-- Floating Data Badge -->
                            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-black/70 backdrop-blur-lg border border-white/20 px-5 py-2 rounded-full flex items-center gap-3 whitespace-nowrap shadow-xl">
                                <div class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                                <span class="text-white text-sm font-semibold tracking-wide">AI Engine Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3D Floating Element Visual (Abstract) -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 opacity-20 pointer-events-none">
            <i class="fas fa-cube text-[200px] animate-bounce"></i>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 px-6 relative">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 feature-header">
                <h2 class="text-4xl font-bold mb-4">Core Features</h2>
                <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Smart Tracking -->
                <div class="tilt-card">
                    <div class="glass-card h-full tilt-content group">
                        <div
                            class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                            <i class="fas fa-map-location-dot text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Smart Tracking</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Real-time GPS tracking for all waste collection vehicles with optimized route planning.
                        </p>
                    </div>
                </div>

                <!-- Waste Requests -->
                <div class="tilt-card">
                    <div class="glass-card h-full tilt-content group">
                        <div
                            class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary mb-6 group-hover:bg-secondary group-hover:text-white transition-all duration-500">
                            <i class="fas fa-truck-pickup text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Waste Requests</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Seamless on-demand pickup requests for residential and industrial clients.
                        </p>
                    </div>
                </div>

                <!-- Analytics -->
                <div class="tilt-card">
                    <div class="glass-card h-full tilt-content group">
                        <div
                            class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center text-accent mb-6 group-hover:bg-accent group-hover:text-white transition-all duration-500">
                            <i class="fas fa-chart-pie text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Advanced Analytics</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Comprehensive data visualization for waste generation patterns and cost savings.
                        </p>
                    </div>
                </div>

                <!-- AI Detection -->
                <div class="tilt-card">
                    <div class="glass-card h-full tilt-content group">
                        <div
                            class="w-14 h-14 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 mb-6 group-hover:bg-green-500 group-hover:text-white transition-all duration-500">
                            <i class="fas fa-brain text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">AI Detection</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Automatic waste segregation using computer vision and machine learning.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="py-24 px-6 relative">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 feature-header">
                <h2 class="text-4xl font-bold mb-4">How EcoSmart Works</h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto mb-6">See how Users, Workers, and Admin interact with the system</p>
                <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
            </div>

            <!-- Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-12 relative z-10">
                <button onclick="switchTab('user', this)" class="demo-tab active px-8 py-3 rounded-full font-semibold bg-primary/20 text-primary border border-primary/50 transition-all shadow-lg shadow-primary/30">
                    <i class="fas fa-user mr-2"></i> User
                </button>
                <button onclick="switchTab('worker', this)" class="demo-tab px-8 py-3 rounded-full font-semibold bg-white/5 text-gray-400 border border-white/10 hover:bg-white/10 transition-all">
                    <i class="fas fa-hard-hat mr-2"></i> Worker
                </button>
                <button onclick="switchTab('admin', this)" class="demo-tab px-8 py-3 rounded-full font-semibold bg-white/5 text-gray-400 border border-white/10 hover:bg-white/10 transition-all">
                    <i class="fas fa-user-shield mr-2"></i> Admin
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="glass-card p-8 md:p-12 relative overflow-hidden">
                <!-- Decorative background elements inside card -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- User Flow -->
                <div id="flow-user" class="demo-flow flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 border border-blue-500/20 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-mobile-screen text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">1. Create Request</h4>
                        <p class="text-gray-400 text-sm">Initiate a waste pickup request</p>
                    </div>
                    <div class="hidden md:block flow-arrow text-gray-600">
                        <i class="fas fa-chevron-right text-2xl animate-pulse"></i>
                    </div>
                    <div class="md:hidden flow-arrow text-gray-600 my-2">
                        <i class="fas fa-chevron-down text-2xl animate-pulse"></i>
                    </div>
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 border border-blue-500/20 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-camera text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">2. Upload Image</h4>
                        <p class="text-gray-400 text-sm">Snap a photo of the waste</p>
                    </div>
                    <div class="hidden md:block flow-arrow text-gray-600">
                        <i class="fas fa-chevron-right text-2xl animate-pulse"></i>
                    </div>
                    <div class="md:hidden flow-arrow text-gray-600 my-2">
                        <i class="fas fa-chevron-down text-2xl animate-pulse"></i>
                    </div>
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 border border-blue-500/20 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-map-location-dot text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">3. Track Status</h4>
                        <p class="text-gray-400 text-sm">Monitor pickup in real-time</p>
                    </div>
                </div>

                <!-- Worker Flow -->
                <div id="flow-worker" class="demo-flow hidden flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 mb-6 group-hover:bg-orange-500 group-hover:text-white transition-all duration-500 border border-orange-500/20 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-clipboard-list text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">1. View Tasks</h4>
                        <p class="text-gray-400 text-sm">Check assigned pickups</p>
                    </div>
                    <div class="hidden md:block flow-arrow text-gray-600">
                        <i class="fas fa-chevron-right text-2xl animate-pulse"></i>
                    </div>
                    <div class="md:hidden flow-arrow text-gray-600 my-2">
                        <i class="fas fa-chevron-down text-2xl animate-pulse"></i>
                    </div>
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 mb-6 group-hover:bg-orange-500 group-hover:text-white transition-all duration-500 border border-orange-500/20 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-route text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">2. Navigate</h4>
                        <p class="text-gray-400 text-sm">Follow optimized route</p>
                    </div>
                    <div class="hidden md:block flow-arrow text-gray-600">
                        <i class="fas fa-chevron-right text-2xl animate-pulse"></i>
                    </div>
                    <div class="md:hidden flow-arrow text-gray-600 my-2">
                        <i class="fas fa-chevron-down text-2xl animate-pulse"></i>
                    </div>
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 mb-6 group-hover:bg-orange-500 group-hover:text-white transition-all duration-500 border border-orange-500/20 group-hover:shadow-[0_0_20px_rgba(249,115,22,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">3. Mark Complete</h4>
                        <p class="text-gray-400 text-sm">Confirm successful pickup</p>
                    </div>
                </div>

                <!-- Admin Flow -->
                <div id="flow-admin" class="demo-flow hidden flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500 mb-6 group-hover:bg-purple-500 group-hover:text-white transition-all duration-500 border border-purple-500/20 group-hover:shadow-[0_0_20px_rgba(168,85,247,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-list-check text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">1. View Requests</h4>
                        <p class="text-gray-400 text-sm">Monitor all incoming tasks</p>
                    </div>
                    <div class="hidden md:block flow-arrow text-gray-600">
                        <i class="fas fa-chevron-right text-2xl animate-pulse"></i>
                    </div>
                    <div class="md:hidden flow-arrow text-gray-600 my-2">
                        <i class="fas fa-chevron-down text-2xl animate-pulse"></i>
                    </div>
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500 mb-6 group-hover:bg-purple-500 group-hover:text-white transition-all duration-500 border border-purple-500/20 group-hover:shadow-[0_0_20px_rgba(168,85,247,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-users-gear text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">2. Assign Workers</h4>
                        <p class="text-gray-400 text-sm">Dispatch nearest team</p>
                    </div>
                    <div class="hidden md:block flow-arrow text-gray-600">
                        <i class="fas fa-chevron-right text-2xl animate-pulse"></i>
                    </div>
                    <div class="md:hidden flow-arrow text-gray-600 my-2">
                        <i class="fas fa-chevron-down text-2xl animate-pulse"></i>
                    </div>
                    <div class="flow-step text-center group flex-1 w-full md:w-auto">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500 mb-6 group-hover:bg-purple-500 group-hover:text-white transition-all duration-500 border border-purple-500/20 group-hover:shadow-[0_0_20px_rgba(168,85,247,0.5)] group-hover:-translate-y-2">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-2">3. Analytics</h4>
                        <p class="text-gray-400 text-sm">Review performance data</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-white/5 relative bg-black/20 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary rounded flex items-center justify-center text-white">
                    <i class="fas fa-recycle"></i>
                </div>
                <span class="text-lg font-bold">EcoSmart</span>
            </div>

            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} EcoSmart Systems. Delivering intelligent waste management solutions worldwide.
            </p>

            <div class="flex gap-6 text-gray-400">
                <a href="#" class="hover:text-primary transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-primary transition-colors"><i class="fab fa-github"></i></a>
                <a href="#" class="hover:text-primary transition-colors"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>

    <!-- Animations -->
    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Hero Section Animations
        gsap.from(".hero-content > *", {
            y: 50,
            opacity: 0,
            duration: 1,
            stagger: 0.2,
            ease: "power4.out"
        });

        // Feature Cards Animation
        gsap.from(".tilt-card", {
            scrollTrigger: {
                trigger: "#features",
                start: "top 80%",
            },
            y: 60,
            opacity: 0,
            duration: 0.8,
            stagger: 0.15,
            ease: "back.out(1.7)"
        });

        // 3D Tilt Effect Implementation
        const cards = document.querySelectorAll('.tilt-card');

        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg)`;
            });
        });

        // Demo Section Tabs & Animation
        window.switchTab = function(role, btn) {
            // Update buttons
            document.querySelectorAll('.demo-tab').forEach(t => {
                t.classList.remove('bg-primary/20', 'text-primary', 'border-primary/50', 'shadow-lg', 'shadow-primary/30');
                t.classList.add('bg-white/5', 'text-gray-400', 'border-white/10');
            });
            btn.classList.remove('bg-white/5', 'text-gray-400', 'border-white/10');
            btn.classList.add('bg-primary/20', 'text-primary', 'border-primary/50', 'shadow-lg', 'shadow-primary/30');

            // Hide all flows
            document.querySelectorAll('.demo-flow').forEach(f => {
                f.classList.add('hidden');
                f.classList.remove('flex');
            });

            // Show target flow
            const targetFlow = document.getElementById(`flow-${role}`);
            targetFlow.classList.remove('hidden');
            targetFlow.classList.add('flex');

            // Animate flow steps
            gsap.fromTo(targetFlow.querySelectorAll('.flow-step'), 
                { y: 30, opacity: 0 },
                { y: 0, opacity: 1, duration: 0.6, stagger: 0.2, ease: "back.out(1.5)" }
            );
            
            // Animate arrows
            gsap.fromTo(targetFlow.querySelectorAll('.flow-arrow'),
                { scale: 0, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.4, stagger: 0.2, delay: 0.4, ease: "back.out(2)" }
            );
        };

        // Initial animation for demo section on scroll
        gsap.from("#demo .demo-flow .flow-step", {
            scrollTrigger: {
                trigger: "#demo",
                start: "top 75%",
            },
            y: 40,
            opacity: 0,
            duration: 0.8,
            stagger: 0.2,
            ease: "back.out(1.5)"
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>