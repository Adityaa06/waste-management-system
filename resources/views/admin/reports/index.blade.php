@extends('layouts.dashboard')

@section('title', 'System Reports')
@section('header', 'Reports & Logs')

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
    <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-chart-line"></i>
        <span class="font-medium">Analytics</span>
    </a>
    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
        <i class="fas fa-file-alt"></i>
        <span class="font-medium">Reports</span>
    </a>
@endsection

@section('content')
    <!-- Report Filters -->
    <div class="glass p-6 rounded-3xl mb-8 border border-white/5">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Start Date -->
            <div>
                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full bg-dark-bg/50 border border-white/10 rounded-2xl px-4 py-3 text-xs focus:border-primary focus:ring-0 text-white">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full bg-dark-bg/50 border border-white/10 rounded-2xl px-4 py-3 text-xs focus:border-primary focus:ring-0 text-white">
            </div>

            <!-- Worker -->
            <div>
                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Assigned Worker</label>
                <select name="worker_id" class="w-full bg-dark-bg/50 border border-white/10 rounded-2xl px-4 py-3 text-xs focus:border-primary focus:ring-0 text-white">
                    <option value="">All Workers</option>
                    @foreach($workers as $worker)
                    <option value="{{ $worker->id }}" {{ ($filters['worker_id'] ?? '') == $worker->id ? 'selected' : '' }}>{{ $worker->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full bg-dark-bg/50 border border-white/10 rounded-2xl px-4 py-3 text-xs focus:border-primary focus:ring-0 text-white">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="assigned" {{ ($filters['status'] ?? '') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="in_progress" {{ ($filters['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Waste Type -->
            <div>
                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Waste Type</label>
                <select name="waste_type" class="w-full bg-dark-bg/50 border border-white/10 rounded-2xl px-4 py-3 text-xs focus:border-primary focus:ring-0 text-white">
                    <option value="">All Waste Types</option>
                    <option value="dry" {{ ($filters['waste_type'] ?? '') == 'dry' ? 'selected' : '' }}>Dry</option>
                    <option value="wet" {{ ($filters['waste_type'] ?? '') == 'wet' ? 'selected' : '' }}>Wet</option>
                    <option value="mixed" {{ ($filters['waste_type'] ?? '') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-span-1 md:col-span-2 lg:col-span-5 flex flex-wrap gap-4 justify-between items-center mt-2 pt-4 border-t border-white/5">
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/95 text-white rounded-2xl text-xs font-bold transition-all flex items-center gap-2">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 rounded-2xl text-xs text-gray-300 font-semibold transition-all flex items-center gap-2">
                        <i class="fas fa-undo"></i> Reset Filters
                    </a>
                </div>
                
                @if(count($requests) > 0)
                <a href="{{ route('admin.reports.export', $filters) }}" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-2xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-green-500/20">
                    <i class="fas fa-file-csv text-sm"></i> Export to CSV
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="glass rounded-3xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white/5 text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Request ID</th>
                        <th class="px-6 py-4 font-medium">User Details</th>
                        <th class="px-6 py-4 font-medium">Title & Type</th>
                        <th class="px-6 py-4 font-medium">Address</th>
                        <th class="px-6 py-4 font-medium">Worker</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Dates</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($requests as $request)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono text-gray-500">
                            #{{ $request->id }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-white">{{ $request->user->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $request->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-white">{{ $request->title }}</p>
                            <span class="px-2 py-0.5 rounded bg-white/5 text-[9px] font-bold uppercase text-gray-400">{{ $request->waste_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400 max-w-[200px] truncate" title="{{ $request->address }}">
                            {{ $request->address }}
                        </td>
                        <td class="px-6 py-4">
                            @if($request->worker)
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-xs text-gray-300">{{ $request->worker->name }}</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-600">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider
                                {{ $request->status == 'pending' ? 'bg-yellow-500/10 text-yellow-500' : 
                                   ($request->status == 'assigned' ? 'bg-blue-500/10 text-blue-500' : 
                                   ($request->status == 'in_progress' ? 'bg-purple-500/10 text-purple-500' : 'bg-green-500/10 text-green-500')) }}">
                                {{ str_replace('_', ' ', $request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[10px] text-gray-500 space-y-1">
                            <p><span class="text-gray-600">Req:</span> {{ $request->created_at->format('Y-m-d H:i') }}</p>
                            @if($request->completed_at)
                            <p><span class="text-green-600">Done:</span> {{ $request->completed_at->format('Y-m-d H:i') }}</p>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic">No request records match the chosen filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
