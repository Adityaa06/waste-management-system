@extends('layouts.dashboard')

@section('title', 'Worker Dashboard')
@section('header', 'Worker Tasks')

@section('sidebar')
        <a href="{{ route('worker.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
            <i class="fas fa-th-large"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="{{ route('worker.tasks.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-tasks"></i>
            <span class="font-medium">Assigned Tasks</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-clipboard-check"></i>
            <span class="font-medium">Completed Tasks</span>
        </a>
    @endsection

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-yellow-500/30 transition-all group">
            <div class="w-12 h-12 bg-yellow-500/10 rounded-2xl flex items-center justify-center text-yellow-500 mb-4 group-hover:bg-yellow-500 group-hover:text-white transition-all duration-500">
                <i class="fas fa-briefcase text-xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Assigned Tasks</p>
            <h3 class="text-3xl font-bold">{{ $assignedTasks }}</h3>
        </div>

        <div class="glass p-6 rounded-3xl border border-white/5 hover:border-green-500/30 transition-all group">
            <div class="w-12 h-12 bg-green-500/10 rounded-2xl flex items-center justify-center text-green-500 mb-4 group-hover:bg-green-500 group-hover:text-white transition-all duration-500">
                <i class="fas fa-check-double text-xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Completed Tasks</p>
            <h3 class="text-3xl font-bold">{{ $completedTasks }}</h3>
        </div>
    </div>

    <!-- Active Tasks Table Placeholder -->
    <div class="glass rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-white/5">
            <h3 class="text-xl font-bold">Today's Assigned Tasks</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white/5 text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Location</th>
                        <th class="px-6 py-4 font-medium">Type</th>
                        <th class="px-6 py-4 font-medium">Request</th>
                        <th class="px-6 py-4 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($todayTasks as $task)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 text-sm">{{ $task->address }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold capitalize">{{ $task->waste_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $task->title }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('worker.tasks.complete', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-primary hover:underline text-sm font-medium">Mark Complete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No active assigned tasks.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection
