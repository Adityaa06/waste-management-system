@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('header', 'Admin Overview')

@section('sidebar')
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
            <i class="fas fa-th-large"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-users"></i>
            <span class="font-medium">Manage Users</span>
        </a>
        <a href="{{ route('admin.requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-clipboard-list"></i>
            <span class="font-medium">Waste Requests</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-chart-line"></i>
            <span class="font-medium">Analytics</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-file-alt"></i>
            <span class="font-medium">Reports</span>
        </a>
    @endsection

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-primary/30 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <span class="text-green-500 text-xs font-bold">+12%</span>
            </div>
            <p class="text-gray-500 text-sm font-medium">Total Users</p>
            <h3 class="text-3xl font-bold">1,284</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-secondary/30 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-all duration-500">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <span class="text-green-500 text-xs font-bold">+5%</span>
            </div>
            <p class="text-gray-500 text-sm font-medium">Total Requests</p>
            <h3 class="text-3xl font-bold">452</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-yellow-500/30 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-yellow-500/10 rounded-2xl flex items-center justify-center text-yellow-500 group-hover:bg-yellow-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <span class="text-red-500 text-xs font-bold">Pending</span>
            </div>
            <p class="text-gray-500 text-sm font-medium">Pending Requests</p>
            <h3 class="text-3xl font-bold">28</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-accent/30 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-accent/10 rounded-2xl flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white transition-all duration-500">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <span class="text-green-500 text-xs font-bold">Completed</span>
            </div>
            <p class="text-gray-500 text-sm font-medium">Completed Tasks</p>
            <h3 class="text-3xl font-bold">394</h3>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="glass p-8 rounded-3xl border border-white/5">
            <h3 class="text-xl font-bold mb-6">Waste Type Distribution</h3>
            <div class="h-64 flex items-center justify-center">
                <canvas id="wasteTypeChart"></canvas>
            </div>
        </div>

        <div class="glass p-8 rounded-3xl border border-white/5">
            <h3 class="text-xl font-bold mb-6">Request Status Overview</h3>
            <div class="h-64 flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 glass p-8 rounded-3xl border border-white/5">
            <h3 class="text-xl font-bold mb-6">Daily Request Trends</h3>
            <div class="h-80 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route('admin.api.stats') }}')
                .then(response => response.json())
                .then(data => {
                    renderCharts(data);
                });

            function renderCharts(data) {
                // 1. Waste Type (Pie)
                new Chart(document.getElementById('wasteTypeChart'), {
                    type: 'doughnut',
                    data: {
                        labels: data.wasteTypes.map(x => x.waste_type.toUpperCase()),
                        datasets: [{
                            data: data.wasteTypes.map(x => x.total),
                            backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                            borderWidth: 0,
                        }]
                    },
                    options: { plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af' } } } }
                });

                // 2. Status (Bar)
                new Chart(document.getElementById('statusChart'), {
                    type: 'bar',
                    data: {
                        labels: data.status.map(x => x.status.toUpperCase()),
                        datasets: [{
                            label: 'Requests',
                            data: data.status.map(x => x.total),
                            backgroundColor: '#8b5cf6',
                            borderRadius: 10,
                        }]
                    },
                    options: { 
                        scales: { 
                            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } },
                            x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });

                // 3. Trend (Line)
                new Chart(document.getElementById('trendChart'), {
                    type: 'line',
                    data: {
                        labels: data.trend.map(x => x.date),
                        datasets: [{
                            label: 'Daily Requests',
                            data: data.trend.map(x => x.total),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { 
                            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af', stepSize: 1 } },
                            x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>

    <!-- Recent Activity Placeholder -->
    <div class="glass rounded-3xl p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Recent System Activity</h3>
            <button class="text-primary text-sm font-medium hover:underline">View All</button>
        </div>
        <div class="space-y-6">
            @foreach(['John Doe registered', 'New waste request from Section B', 'Worker Mike completed Task #42', 'System update scheduled'] as $activity)
            <div class="flex items-center gap-4">
                <div class="w-2 h-2 rounded-full bg-primary shadow-lg shadow-primary"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium">{{ $activity }}</p>
                    <p class="text-xs text-gray-500">2 hours ago</p>
                </div>
                <i class="fas fa-chevron-right text-gray-700 text-xs"></i>
            </div>
            @endforeach
        </div>
    </div>
    </div>
@endsection
