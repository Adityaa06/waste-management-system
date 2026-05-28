@extends('layouts.dashboard')

@section('title', 'Complaint Details')
@section('header', 'View Complaint')

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
    <div class="max-w-3xl glass rounded-3xl p-8 border border-white/5 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <a href="{{ route('user.complaints.index') }}" class="text-primary hover:underline text-sm flex items-center gap-2 mb-2">
                    <i class="fas fa-arrow-left"></i> Back to Complaints
                </a>
                <h2 class="text-2xl font-bold">{{ $complaint->title }}</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('user.complaints.edit', $complaint) }}" class="px-4 py-2 border border-white/10 hover:bg-white/5 rounded-xl text-sm font-semibold transition-all">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>

        <!-- Progress Tracker -->
        <div class="glass p-6 rounded-2xl border border-white/5 mb-8">
            <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-wider">Status Timeline</h3>
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative">
                <div class="flex items-center gap-3 z-10">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold 
                        {{ $complaint->status == 'pending' || $complaint->status == 'in_progress' || $complaint->status == 'resolved' ? 'bg-primary text-white' : 'bg-white/5 text-gray-400' }}">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Submitted</p>
                        <p class="text-[10px] text-gray-500">{{ $complaint->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 z-10">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold 
                        {{ $complaint->status == 'in_progress' || $complaint->status == 'resolved' ? 'bg-primary text-white' : 'bg-white/5 text-gray-400' }}">
                        <i class="fas fa-spinner {{ $complaint->status == 'in_progress' ? 'animate-spin' : '' }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold">In Progress</p>
                        <p class="text-[10px] text-gray-500">Under Review</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 z-10">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold 
                        {{ $complaint->status == 'resolved' ? 'bg-green-500 text-white' : 'bg-white/5 text-gray-400' }}">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Resolved</p>
                        <p class="text-[10px] text-gray-500">Completed</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="glass p-4 rounded-xl border border-white/5">
                    <span class="text-xs text-gray-500 uppercase font-bold block mb-1">Category</span>
                    <span class="text-sm font-medium">{{ $complaint->category }}</span>
                </div>
                <div class="glass p-4 rounded-xl border border-white/5">
                    <span class="text-xs text-gray-500 uppercase font-bold block mb-1">Priority Level</span>
                    <span class="text-sm font-semibold capitalize flex items-center gap-1.5">
                        <i class="fas fa-flag text-xs {{ $complaint->priority == 'high' ? 'text-red-500' : ($complaint->priority == 'medium' ? 'text-yellow-500' : 'text-green-500') }}"></i>
                        {{ $complaint->priority }}
                    </span>
                </div>
            </div>

            <div class="glass p-6 rounded-2xl border border-white/5">
                <span class="text-xs text-gray-500 uppercase font-bold block mb-2">Description</span>
                <p class="text-gray-300 text-sm whitespace-pre-line leading-relaxed">{{ $complaint->description }}</p>
            </div>

            @if($complaint->image)
            <div>
                <span class="text-xs text-gray-500 uppercase font-bold block mb-2">Attached Image</span>
                <div class="glass p-2 rounded-2xl border border-white/5 inline-block overflow-hidden max-w-full">
                    <img src="{{ asset('storage/' . $complaint->image) }}" alt="Supporting Evidence" class="rounded-xl max-h-96 object-contain">
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
