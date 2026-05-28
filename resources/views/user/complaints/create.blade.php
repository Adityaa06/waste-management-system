@extends('layouts.dashboard')

@section('title', 'File a Complaint')
@section('header', 'New Complaint')

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
        <div class="mb-6">
            <h2 class="text-2xl font-bold">Report an Issue</h2>
            <p class="text-gray-400 text-sm">Please provide as much detail as possible to help us resolve your concern quickly.</p>
        </div>

        <form action="{{ route('user.complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title">Complaint Title</label>
                    <input type="text" name="title" id="title" class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 focus:border-primary focus:ring-primary/20 text-white" placeholder="e.g. Missed pickup in Sector 4" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category">Category</label>
                    <select name="category" id="category" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:border-primary focus:ring-primary/20 text-white" required>
                        <option value="Missed Collection">Missed Collection</option>
                        <option value="Damaged Bin">Damaged Bin</option>
                        <option value="Worker Behavior">Worker Behavior</option>
                        <option value="Spill/Littering">Spill/Littering</option>
                        <option value="Billing/Accounts">Billing/Accounts</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="priority">Priority Level</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="relative flex items-center justify-center p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 cursor-pointer transition-all">
                        <input type="radio" name="priority" value="low" class="absolute opacity-0 peer" required>
                        <div class="peer-checked:text-green-400 flex items-center gap-2">
                            <i class="fas fa-flag text-green-400 text-xs"></i>
                            <span class="text-sm font-semibold">Low</span>
                        </div>
                    </label>
                    <label class="relative flex items-center justify-center p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 cursor-pointer transition-all">
                        <input type="radio" name="priority" value="medium" class="absolute opacity-0 peer" checked required>
                        <div class="peer-checked:text-yellow-400 flex items-center gap-2">
                            <i class="fas fa-flag text-yellow-400 text-xs"></i>
                            <span class="text-sm font-semibold">Medium</span>
                        </div>
                    </label>
                    <label class="relative flex items-center justify-center p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 cursor-pointer transition-all">
                        <input type="radio" name="priority" value="high" class="absolute opacity-0 peer" required>
                        <div class="peer-checked:text-red-400 flex items-center gap-2">
                            <i class="fas fa-flag text-red-400 text-xs"></i>
                            <span class="text-sm font-semibold">High</span>
                        </div>
                    </label>
                </div>
                @error('priority') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description">Complaint Description</label>
                <textarea name="description" id="description" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:border-primary focus:ring-primary/20 text-white placeholder-gray-500 transition-all" placeholder="Tell us what happened. Include dates and names if relevant." required></textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="image">Attach Supporting Image (Optional)</label>
                <div class="relative group">
                    <input type="file" name="image" id="image" class="hidden" onchange="previewImage(this)">
                    <label for="image" class="w-full border-2 border-dashed border-white/10 rounded-2xl p-8 flex flex-col items-center justify-center cursor-pointer hover:border-primary/50 transition-all group">
                        <div id="uploadPlaceholder" class="flex flex-col items-center">
                            <i class="fas fa-image text-4xl text-gray-500 group-hover:text-primary transition-colors mb-2"></i>
                            <span id="fileName" class="text-sm text-gray-400 group-hover:text-white">Click to select an image</span>
                        </div>
                        <img id="imagePreview" class="hidden h-40 w-full object-contain rounded-xl mt-2 border border-white/10">
                    </label>
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('user.complaints.index') }}" class="px-6 py-3 border border-white/10 hover:bg-white/5 rounded-full text-sm font-semibold transition-all">Cancel</a>
                <button type="submit" class="btn-primary">
                    Submit Complaint
                    <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const placeholder = document.getElementById('uploadPlaceholder');
                    const fileName = document.getElementById('fileName');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
