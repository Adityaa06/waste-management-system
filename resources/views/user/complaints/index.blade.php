@extends('layouts.dashboard')

@section('title', 'My Complaints')
@section('header', 'Complaints Registry')

@section('sidebar')
    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-th-large"></i>
        <span class="font-medium">Dashboard</span>
    </a>
    <a href="{{ route('user.requests.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-plus-circle"></i>
        <span class="font-medium">Create Request</span>
    </a>
    <a href="{{ route('user.requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-history"></i>
        <span class="font-medium">My Requests</span>
    </a>
    <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
        <i class="fas fa-exclamation-triangle"></i>
        <span class="font-medium">Complaints</span>
    </a>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-xl font-bold">Track Your Issues</h2>
            <p class="text-gray-400 text-sm">Submit and monitor complaints regarding waste collection services.</p>
        </div>
        <a href="{{ route('user.complaints.create') }}" class="btn-primary">
            File a Complaint
            <i class="fas fa-plus-circle ml-2"></i>
        </a>
    </div>

    @if(session('success'))
    <div class="glass p-4 rounded-2xl border-l-4 border-l-primary mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center text-primary">
                <i class="fas fa-check text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-white">Success</p>
                <p class="text-xs text-gray-400">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($complaints as $complaint)
        <div class="glass rounded-3xl overflow-hidden border border-white/5 group hover:border-primary/20 transition-all flex flex-col justify-between">
            <div>
                @if($complaint->image)
                <img src="{{ asset('storage/' . $complaint->image) }}" alt="Complaint Image" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-white/5 flex items-center justify-center text-gray-600">
                    <i class="fas fa-exclamation-circle text-4xl"></i>
                </div>
                @endif

                <div class="p-6">
                    <div class="flex justify-between items-start mb-4 gap-2">
                        <h3 class="font-bold text-lg leading-tight line-clamp-1">{{ $complaint->title }}</h3>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider whitespace-nowrap 
                            {{ $complaint->status == 'pending' ? 'bg-yellow-500/10 text-yellow-500' : ($complaint->status == 'in_progress' ? 'bg-blue-500/10 text-blue-500' : 'bg-green-500/10 text-green-500') }}">
                            {{ str_replace('_', ' ', $complaint->status) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-gray-400 mb-4">
                        <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 uppercase tracking-wider text-[10px] font-medium">{{ $complaint->category }}</span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-flag text-[10px] {{ $complaint->priority == 'high' ? 'text-red-500' : ($complaint->priority == 'medium' ? 'text-yellow-500' : 'text-green-500') }}"></i>
                            <span class="capitalize text-[10px] font-medium">{{ $complaint->priority }} Priority</span>
                        </span>
                    </div>

                    <p class="text-gray-400 text-sm mb-4 line-clamp-3">{{ $complaint->description }}</p>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="flex justify-between items-center pt-4 border-t border-white/5">
                    <span class="text-[10px] text-gray-600 uppercase">{{ $complaint->created_at->format('d M Y') }}</span>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.complaints.show', $complaint) }}" class="text-gray-400 hover:text-white transition-colors text-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('user.complaints.edit', $complaint) }}" class="text-gray-400 hover:text-primary transition-colors text-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('user.complaints.destroy', $complaint) }}" method="POST" onsubmit="return confirm('Delete this complaint?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-600 hover:text-red-500 transition-colors text-sm flex items-center justify-center">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full glass rounded-3xl p-12 text-center">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-2xl text-gray-600"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">No Complaints Filed</h3>
            <p class="text-gray-400 mb-6">If you have any issues or concerns, feel free to report them immediately.</p>
            <a href="{{ route('user.complaints.create') }}" class="btn-primary">File Your First Complaint</a>
        </div>
        @endforelse
    </div>
@endsection
