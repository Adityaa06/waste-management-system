@extends('layouts.dashboard')

@section('title', 'My Requests')
@section('header', 'Request History')

@section('sidebar')
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-th-large"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="{{ route('user.requests.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-plus-circle"></i>
            <span class="font-medium">Create Request</span>
        </a>
        <a href="{{ route('user.requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
            <i class="fas fa-history"></i>
            <span class="font-medium">My Requests</span>
        </a>
    @endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($requests as $request)
        <div class="glass rounded-3xl overflow-hidden border border-white/5 group hover:border-primary/20 transition-all">
            @if($request->image)
            <img src="{{ asset('storage/' . $request->image) }}" alt="Waste Image" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-white/5 flex items-center justify-center text-gray-600">
                <i class="fas fa-image text-4xl"></i>
            </div>
            @endif
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-bold text-lg leading-tight">{{ $request->title }}</h3>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                        {{ $request->status == 'pending' ? 'bg-yellow-500/10 text-yellow-500' : ($request->status == 'assigned' ? 'bg-blue-500/10 text-blue-500' : 'bg-green-500/10 text-green-500') }}">
                        {{ $request->status }}
                    </span>
                </div>
                
                <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $request->description }}</p>
                
                <div class="flex items-center gap-3 text-xs text-gray-500 mb-6">
                    <i class="fas fa-map-marker-alt text-primary"></i>
                    <span class="truncate">{{ $request->address }}</span>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-white/5">
                    <span class="text-[10px] text-gray-600 uppercase">{{ $request->created_at->format('d M Y') }}</span>
                    <form action="{{ route('user.requests.destroy', $request) }}" method="POST" onsubmit="return confirm('Delete this request?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-600 hover:text-red-500 transition-colors">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full glass rounded-3xl p-12 text-center">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-box-open text-2xl text-gray-600"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">No Requests Yet</h3>
            <p class="text-gray-400 mb-6">You haven't made any waste collection requests yet.</p>
            <a href="{{ route('user.requests.create') }}" class="btn-primary">Make Your First Request</a>
        </div>
        @endforelse
    </div>

    <script>
        // Simple Polling System
        function refreshStatuses() {
            // In a real production app, we would use an API endpoint.
            // For this demo, we'll just reload the page content every 15 seconds if there are pending/assigned tasks.
            const hasActiveTasks = document.querySelector('.bg-yellow-500\\/10, .bg-blue-500\\/10');
            
            if (hasActiveTasks) {
                console.log('Checking for status updates...');
                setTimeout(() => {
                    window.location.reload();
                }, 15000);
            }
        }

        document.addEventListener('DOMContentLoaded', refreshStatuses);
    </script>
    </script>
@endsection
