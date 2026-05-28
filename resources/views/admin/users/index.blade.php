@extends('layouts.dashboard')

@section('title', 'Manage Users & Workers')
@section('header', 'User Management')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-th-large"></i>
        <span class="font-medium">Dashboard</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
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
    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
        <i class="fas fa-file-alt"></i>
        <span class="font-medium">Reports</span>
    </a>
@endsection

@section('content')
<div x-data="{ openCreateModal: false, openEditModal: false, editWorker: {} }">
    <!-- Action Bar & Filters -->
    <div class="glass p-6 rounded-3xl mb-8 border border-white/5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto flex-1">
                <!-- Search -->
                <div class="relative flex-1 md:max-w-xs">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" class="w-full bg-dark-bg/50 border border-white/10 rounded-2xl pl-10 pr-4 py-3 text-sm focus:border-primary focus:ring-0 text-white placeholder-gray-500" placeholder="Search by name, email...">
                </div>

                <!-- Role Filter -->
                <select name="role" class="bg-dark-bg/50 border border-white/10 rounded-2xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white w-full md:w-48">
                    <option value="all" {{ $role == 'all' ? 'selected' : '' }}>All Roles</option>
                    <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="worker" {{ $role == 'worker' ? 'selected' : '' }}>Worker</option>
                    <option value="user" {{ $role == 'user' ? 'selected' : '' }}>Regular User</option>
                </select>

                <!-- Status Filter -->
                <select name="status" class="bg-dark-bg/50 border border-white/10 rounded-2xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white w-full md:w-48">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-6 py-3 bg-secondary/20 hover:bg-secondary/30 border border-secondary/30 rounded-2xl text-secondary text-sm font-semibold transition-all">
                    Apply Filter
                </button>
            </div>

            <button type="button" @click="openCreateModal = true" class="btn-primary w-full lg:w-auto flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-plus"></i> Create Worker Account
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="glass rounded-3xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white/5 text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">User Profile</th>
                        <th class="px-6 py-4 font-medium">Role</th>
                        <th class="px-6 py-4 font-medium">Contact Details</th>
                        <th class="px-6 py-4 font-medium">Assigned Area</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary/20 to-secondary/20 border border-white/5 flex items-center justify-center font-bold text-primary">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                {{ $user->role == 'admin' ? 'bg-red-500/10 text-red-500' : ($user->role == 'worker' ? 'bg-blue-500/10 text-blue-500' : 'bg-primary/10 text-primary') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->phone_number || $user->address)
                            <div class="space-y-1">
                                <p class="text-xs text-gray-300"><i class="fas fa-phone mr-1.5 text-gray-500"></i>{{ $user->phone_number ?? 'N/A' }}</p>
                                <p class="text-[10px] text-gray-500 max-w-[200px] truncate" title="{{ $user->address }}"><i class="fas fa-map-marker-alt mr-2 text-gray-500"></i>{{ $user->address ?? 'N/A' }}</p>
                            </div>
                            @else
                            <span class="text-xs text-gray-600">No extra details</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300">
                            {{ $user->assigned_area ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider 
                                {{ $user->status == 'active' ? 'bg-green-500/10 text-green-500' : 'bg-yellow-500/10 text-yellow-500' }}">
                                {{ $user->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if($user->id !== auth()->id())
                                <!-- Toggle Status Action -->
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 glass rounded-lg flex items-center justify-center text-xs transition-all hover:scale-115 
                                        {{ $user->status == 'active' ? 'text-yellow-500 hover:bg-yellow-500/10' : 'text-green-500 hover:bg-green-500/10' }}"
                                        title="{{ $user->status == 'active' ? 'Deactivate Account' : 'Activate Account' }}">
                                        @if($user->status == 'active')
                                        <i class="fas fa-user-slash"></i>
                                        @else
                                        <i class="fas fa-user-check"></i>
                                        @endif
                                    </button>
                                </form>

                                <!-- Edit Worker Action -->
                                @if($user->role === 'worker')
                                <button type="button" 
                                    @click="editWorker = {{ json_encode($user) }}; openEditModal = true" 
                                    class="w-8 h-8 glass rounded-lg flex items-center justify-center text-blue-500 hover:bg-blue-500/10 transition-all hover:scale-115"
                                    title="Edit Worker Profile">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                @endif

                                <!-- Delete Action -->
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 glass rounded-lg flex items-center justify-center text-red-500 hover:bg-red-500/10 transition-all hover:scale-115"
                                        title="Delete Account">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-500 italic">Logged In</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Worker Modal -->
    <div x-show="openCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openCreateModal = false"></div>
        
        <div class="glass w-full max-w-xl rounded-3xl relative z-10 overflow-hidden border border-white/10 flex flex-col"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 class="text-lg font-bold text-white"><i class="fas fa-user-plus text-primary mr-2"></i>Create New Worker Account</h3>
                <button type="button" @click="openCreateModal = false" class="text-gray-500 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <form action="{{ route('admin.users.workers.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="name" required class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white" placeholder="john@example.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white" placeholder="Min. 8 characters">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Phone Number</label>
                        <input type="text" name="phone_number" required class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white" placeholder="+1234567890">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Address</label>
                    <textarea name="address" required rows="2" class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white" placeholder="Worker location/address..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Worker Status</label>
                        <select name="status" class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Assigned Area (Optional)</label>
                        <input type="text" name="assigned_area" class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white" placeholder="e.g. Zone A, Sector B">
                    </div>
                </div>

                <div class="pt-4 border-t border-white/5 flex justify-end gap-3">
                    <button type="button" @click="openCreateModal = false" class="px-6 py-3 bg-white/5 hover:bg-white/10 rounded-xl text-sm font-semibold transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/95 text-white rounded-xl text-sm font-bold shadow-lg shadow-primary/20 transition-all">Create Worker</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Worker Modal -->
    <div x-show="openEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openEditModal = false"></div>
        
        <div class="glass w-full max-w-xl rounded-3xl relative z-10 overflow-hidden border border-white/10 flex flex-col"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 class="text-lg font-bold text-white"><i class="fas fa-user-edit text-blue-500 mr-2"></i>Edit Worker Account</h3>
                <button type="button" @click="openEditModal = false" class="text-gray-500 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <form :action="`/admin/users/workers/${editWorker.id}`" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="name" x-model="editWorker.name" required class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" x-model="editWorker.email" required class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password (Leave blank to keep current)</label>
                        <input type="password" name="password" class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white" placeholder="New Password">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Phone Number</label>
                        <input type="text" name="phone_number" x-model="editWorker.phone_number" required class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Address</label>
                    <textarea name="address" x-model="editWorker.address" required rows="2" class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Worker Status</label>
                        <select name="status" x-model="editWorker.status" class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Assigned Area (Optional)</label>
                        <input type="text" name="assigned_area" x-model="editWorker.assigned_area" class="w-full bg-dark-bg/50 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-primary focus:ring-0 text-white">
                    </div>
                </div>

                <div class="pt-4 border-t border-white/5 flex justify-end gap-3">
                    <button type="button" @click="openEditModal = false" class="px-6 py-3 bg-white/5 hover:bg-white/10 rounded-xl text-sm font-semibold transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
