@extends('layouts.dashboard')

@section('title', 'Manage All Requests')
@section('header', 'System Requests')

@section('sidebar')
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-th-large"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-users"></i>
            <span class="font-medium">Manage Users</span>
        </a>
        <a href="{{ route('admin.requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
            <i class="fas fa-clipboard-list"></i>
            <span class="font-medium">Waste Requests</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-chart-line"></i>
            <span class="font-medium">Analytics</span>
        </a>
    @endsection

@section('content')
    <div class="glass rounded-3xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white/5 text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">User & Title</th>
                        <th class="px-6 py-4 font-medium">Type</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Assigned To</th>
                        <th class="px-6 py-4 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($requests as $request)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 bg-white/5">
                                    @if($request->image)
                                    <img src="{{ asset('storage/' . $request->image) }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-gray-700"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold">{{ $request->title }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $request->user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-white/5 text-[10px] font-bold uppercase">{{ $request->waste_type }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase 
                                {{ $request->status == 'pending' ? 'text-yellow-500' : ($request->status == 'assigned' ? 'text-blue-500' : 'text-green-500') }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($request->worker)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center text-[10px] font-bold text-primary">
                                    {{ substr($request->worker->name, 0, 1) }}
                                </div>
                                <span class="text-sm">{{ $request->worker->name }}</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-600">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if($request->latitude && $request->longitude)
                                <button onclick="showMap({{ $request->latitude }}, {{ $request->longitude }}, '{{ $request->title }}')" class="w-8 h-8 glass rounded-lg flex items-center justify-center text-secondary hover:scale-110 transition-all">
                                    <i class="fas fa-map-marked-alt text-xs"></i>
                                </button>
                                @endif
                                
                                <form action="{{ route('admin.requests.assign', $request) }}" method="POST" class="flex items-center justify-end gap-2">
                                    @csrf
                                    <select name="worker_id" class="bg-dark-bg border-white/10 rounded-lg text-xs px-2 py-1 focus:border-primary focus:ring-0 text-white w-32" required>
                                        <option value="" disabled selected>Assign...</option>
                                        @foreach($workers as $worker)
                                        <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white hover:scale-110 transition-all">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">No waste requests found in the system.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Map Modal -->
    <div id="mapModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeMap()"></div>
        <div class="glass w-full max-w-4xl h-[500px] rounded-3xl relative z-10 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold">Request Location</h3>
                <button onclick="closeMap()" class="text-gray-500 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div id="adminMap" class="flex-1 w-full"></div>
        </div>
    </div>

    <script>
        let adminMap;
        let adminMarker;

        function showMap(lat, lng, title) {
            document.getElementById('mapModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Location: ' + title;

            setTimeout(() => {
                if (!adminMap) {
                    adminMap = L.map('adminMap').setView([lat, lng], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(adminMap);
                    adminMarker = L.marker([lat, lng]).addTo(adminMap);
                } else {
                    adminMap.setView([lat, lng], 16);
                    adminMarker.setLatLng([lat, lng]);
                }
            }, 100);
        }

        function closeMap() {
            document.getElementById('mapModal').classList.add('hidden');
        }
    </script>
    </script>
@endsection
