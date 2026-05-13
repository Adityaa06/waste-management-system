@extends('layouts.dashboard')

@section('title', 'My Tasks')
@section('header', 'Assigned Jobs')

@section('sidebar')
        <a href="{{ route('worker.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-th-large"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="{{ route('worker.tasks.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
            <i class="fas fa-tasks"></i>
            <span class="font-medium">Assigned Tasks</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-clipboard-check"></i>
            <span class="font-medium">Completed Tasks</span>
        </a>
    @endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($tasks as $task)
        <div class="glass rounded-3xl p-6 border border-white/5 relative group overflow-hidden">
            <div class="absolute top-0 right-0 p-4">
                 <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                    {{ $task->status == 'assigned' ? 'bg-blue-500/10 text-blue-500' : 'bg-green-500/10 text-green-500' }}">
                    {{ $task->status }}
                </span>
            </div>

            <div class="flex items-start gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-white/5 flex-shrink-0 overflow-hidden">
                    @if($task->image)
                    <img src="{{ asset('storage/' . $task->image) }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-truck-loading text-gray-700"></i></div>
                    @endif
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-1">{{ $task->title }}</h3>
                    <p class="text-xs text-primary font-medium uppercase tracking-widest">{{ $task->waste_type }} Waste</p>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt mt-1 text-gray-500"></i>
                    <p class="text-sm text-gray-400 leading-relaxed">{{ $task->address }}</p>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle mt-1 text-gray-500"></i>
                    <p class="text-sm text-gray-400 italic">"{{ $task->description ?? 'No description provided.' }}"</p>
                </div>
                @if($task->latitude && $task->longitude)
                <button onclick="showMap({{ $task->latitude }}, {{ $task->longitude }}, '{{ $task->title }}')" class="flex items-center gap-2 text-secondary text-sm font-medium hover:underline">
                    <i class="fas fa-map-marker-alt"></i>
                    View on Map
                </button>
                @endif
            </div>

            @if($task->status == 'assigned')
            <form action="{{ route('worker.tasks.complete', $task) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 bg-green-500 hover:bg-green-600 text-white rounded-2xl font-bold transition-all shadow-lg shadow-green-500/20 flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Mark as Completed
                </button>
            </form>
            @else
            <div class="w-full py-4 bg-white/5 text-gray-500 rounded-2xl font-bold text-center border border-white/5">
                <i class="fas fa-check-circle mr-2"></i> Task Completed
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full glass rounded-3xl p-12 text-center">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-sleep text-2xl text-gray-600"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Rest Time!</h3>
            <p class="text-gray-400">No tasks are currently assigned to you.</p>
        </div>
        @endforelse
    </div>

    <!-- Map Modal (Same as Admin) -->
    <div id="mapModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeMap()"></div>
        <div class="glass w-full max-w-4xl h-[500px] rounded-3xl relative z-10 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold">Task Location</h3>
                <button onclick="closeMap()" class="text-gray-500 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div id="workerMap" class="flex-1 w-full"></div>
        </div>
    </div>

    <script>
        let workerMap;
        let workerMarker;

        function showMap(lat, lng, title) {
            document.getElementById('mapModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Location: ' + title;

            setTimeout(() => {
                if (!workerMap) {
                    workerMap = L.map('workerMap').setView([lat, lng], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(workerMap);
                    workerMarker = L.marker([lat, lng]).addTo(workerMap);
                } else {
                    workerMap.setView([lat, lng], 16);
                    workerMarker.setLatLng([lat, lng]);
                }
            }, 100);
        }

        function closeMap() {
            document.getElementById('mapModal').classList.add('hidden');
        }
    </script>
    </script>
@endsection
