@extends('layouts.dashboard')

@section('title', 'User Dashboard')
@section('header', 'My Dashboard')

@section('sidebar')
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
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
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="font-medium">Complaints</span>
        </a>
    @endsection

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-primary/30 transition-all group">
            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-4 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                <i class="fas fa-list-check text-xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">My Total Requests</p>
            <h3 class="text-3xl font-bold">12</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-secondary/30 transition-all group">
            <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary mb-4 group-hover:bg-secondary group-hover:text-white transition-all duration-500">
                <i class="fas fa-spinner text-xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">In Progress</p>
            <h3 class="text-3xl font-bold">2</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-green-500/30 transition-all group">
            <div class="w-12 h-12 bg-green-500/10 rounded-2xl flex items-center justify-center text-green-500 mb-4 group-hover:bg-green-500 group-hover:text-white transition-all duration-500">
                <i class="fas fa-circle-check text-xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Completed</p>
            <h3 class="text-3xl font-bold">10</h3>
        </div>
    </div>

    <!-- Quick Action Card -->
    <div class="glass rounded-3xl p-8 bg-gradient-to-br from-primary/5 to-secondary/5 border border-primary/10">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-20 h-20 bg-primary rounded-3xl flex items-center justify-center text-white text-3xl shadow-2xl shadow-primary/40">
                <i class="fas fa-truck-pickup"></i>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold mb-2">Need a Waste Pickup?</h3>
                <p class="text-gray-400">Request a collection in just a few clicks. Our worker will be at your location shortly.</p>
            </div>
            <button class="btn-primary whitespace-nowrap">Create New Request</button>
        </div>
    </div>
    </div>
@endsection
