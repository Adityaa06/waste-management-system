@extends('layouts.dashboard')

@section('title', 'System Analytics')
@section('header', 'Analytics Dashboard')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-th-large"></i>
        <span class="font-medium">Dashboard</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-users"></i>
        <span class="font-medium">Manage Users</span>
    </a>
    <a href="{{ route('admin.requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-clipboard-list"></i>
        <span class="font-medium">Waste Requests</span>
    </a>
    <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
        <i class="fas fa-chart-line"></i>
        <span class="font-medium">Analytics</span>
    </a>
    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-file-alt"></i>
        <span class="font-medium">Reports</span>
    </a>
@endsection

@section('content')
    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass p-6 rounded-3xl border border-white/5 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-sm font-medium">Total Registered Users</p>
            <h3 id="stat-total-users" class="text-3xl font-bold text-white">...</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-sm font-medium">Total Workers</p>
            <h3 id="stat-total-workers" class="text-3xl font-bold text-white">...</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-yellow-500/10 rounded-2xl flex items-center justify-center text-yellow-500">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-sm font-medium">Pending Requests</p>
            <h3 id="stat-pending-requests" class="text-3xl font-bold text-white">...</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-green-500/10 rounded-2xl flex items-center justify-center text-green-500">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
            <p class="text-gray-500 text-sm font-medium">Completed Requests</p>
            <h3 id="stat-completed-requests" class="text-3xl font-bold text-white">...</h3>
        </div>
    </div>

    <!-- Charts Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Waste Type -->
        <div class="glass p-6 rounded-3xl border border-white/5 flex flex-col h-80">
            <h3 class="text-lg font-bold mb-4 text-white">Waste Type Distribution</h3>
            <div class="flex-1 min-h-0 relative flex items-center justify-center">
                <canvas id="wasteTypeChart"></canvas>
            </div>
        </div>

        <!-- Request Status -->
        <div class="glass p-6 rounded-3xl border border-white/5 flex flex-col h-80">
            <h3 class="text-lg font-bold mb-4 text-white">Request Status Overview</h3>
            <div class="flex-1 min-h-0 relative">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Weekly Trend -->
        <div class="lg:col-span-2 glass p-6 rounded-3xl border border-white/5 flex flex-col h-96">
            <h3 class="text-lg font-bold mb-4 text-white">Request Trends</h3>
            <div class="flex-1 min-h-0 relative">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Worker Performance -->
        <div class="glass p-6 rounded-3xl border border-white/5 flex flex-col h-96">
            <h3 class="text-lg font-bold mb-4 text-white">Worker Performance (Jobs Completed)</h3>
            <div class="flex-1 min-h-0 relative">
                <canvas id="workerPerformanceChart"></canvas>
            </div>
        </div>

        <!-- Complaints Breakdown -->
        <div class="glass p-6 rounded-3xl border border-white/5 flex flex-col h-96">
            <h3 class="text-lg font-bold mb-4 text-white">Complaints Priority & Resolution</h3>
            <div class="grid grid-cols-2 gap-4 flex-1 min-h-0">
                <div class="relative flex items-center justify-center">
                    <canvas id="complaintStatusChart"></canvas>
                </div>
                <div class="relative flex items-center justify-center">
                    <canvas id="complaintPriorityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to handle Charts and Real-Time Polling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let charts = {};

            // Initialize Charts with empty data
            function initCharts() {
                // 1. Waste Type (Pie/Doughnut)
                charts.wasteType = new Chart(document.getElementById('wasteTypeChart'), {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ec4899'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: '#9ca3af', boxWidth: 12, font: { family: 'Poppins' } }
                            }
                        }
                    }
                });

                // 2. Status Chart (Bar)
                charts.status = new Chart(document.getElementById('statusChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: ['#eab308', '#3b82f6', '#8b5cf6', '#10b981'],
                            borderRadius: 8,
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af', font: { family: 'Poppins' } } },
                            x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { family: 'Poppins' } } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });

                // 3. Request Trend Chart (Line)
                charts.trend = new Chart(document.getElementById('trendChart'), {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Daily Requests',
                            data: [],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.05)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af', stepSize: 1, font: { family: 'Poppins' } } },
                            x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { family: 'Poppins' } } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });

                // 4. Worker Performance Chart (Horizontal Bar)
                charts.worker = new Chart(document.getElementById('workerPerformanceChart'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af', stepSize: 1, font: { family: 'Poppins' } } },
                            y: { grid: { display: false }, ticks: { color: '#9ca3af', font: { family: 'Poppins' } } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });

                // 5. Complaint Status Chart (Doughnut)
                charts.complaintStatus = new Chart(document.getElementById('complaintStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: ['#ef4444', '#3b82f6', '#10b981'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: true, text: 'Complaints Status', color: '#fff', font: { family: 'Poppins', size: 12 } },
                            legend: { position: 'bottom', labels: { color: '#9ca3af', boxWidth: 10, font: { family: 'Poppins', size: 9 } } }
                        }
                    }
                });

                // 6. Complaint Priority Chart (Doughnut)
                charts.complaintPriority = new Chart(document.getElementById('complaintPriorityChart'), {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: ['#6b7280', '#f59e0b', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: true, text: 'Complaints Priority', color: '#fff', font: { family: 'Poppins', size: 12 } },
                            legend: { position: 'bottom', labels: { color: '#9ca3af', boxWidth: 10, font: { family: 'Poppins', size: 9 } } }
                        }
                    }
                });
            }

            // Fetch and update data
            function updateData() {
                fetch('{{ route('admin.api.stats') }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update metrics text
                        document.getElementById('stat-total-users').textContent = data.counts.totalUsers;
                        document.getElementById('stat-total-workers').textContent = data.counts.totalWorkers;
                        document.getElementById('stat-pending-requests').textContent = data.counts.pendingRequests;
                        document.getElementById('stat-completed-requests').textContent = data.counts.completedRequests;

                        // 1. Update Waste Types
                        charts.wasteType.data.labels = data.wasteTypes.map(x => x.waste_type.toUpperCase());
                        charts.wasteType.data.datasets[0].data = data.wasteTypes.map(x => x.total);
                        charts.wasteType.update();

                        // 2. Update Status Chart
                        charts.status.data.labels = data.status.map(x => x.status.toUpperCase());
                        charts.status.data.datasets[0].data = data.status.map(x => x.total);
                        charts.status.update();

                        // 3. Update Trend Chart (Daily)
                        charts.trend.data.labels = data.dailyTrend.map(x => x.date);
                        charts.trend.data.datasets[0].data = data.dailyTrend.map(x => x.total);
                        charts.trend.update();

                        // 4. Update Worker Chart
                        charts.worker.data.labels = data.workerPerformance.map(x => x.name);
                        charts.worker.data.datasets[0].data = data.workerPerformance.map(x => x.completed);
                        charts.worker.update();

                        // 5. Update Complaint Status Chart
                        charts.complaintStatus.data.labels = data.complaintsByStatus.map(x => x.status.toUpperCase());
                        charts.complaintStatus.data.datasets[0].data = data.complaintsByStatus.map(x => x.total);
                        charts.complaintStatus.update();

                        // 6. Update Complaint Priority Chart
                        charts.complaintPriority.data.labels = data.complaintsByPriority.map(x => x.priority.toUpperCase());
                        charts.complaintPriority.data.datasets[0].data = data.complaintsByPriority.map(x => x.total);
                        charts.complaintPriority.update();
                    })
                    .catch(err => console.error('Error fetching analytics stats:', err));
            }

            initCharts();
            updateData();

            // Setup real-time polling every 5 seconds
            setInterval(updateData, 5000);
        });
    </script>
@endsection
