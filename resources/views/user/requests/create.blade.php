@extends('layouts.dashboard')

@section('title', 'Create Waste Request')
@section('header', 'New Request')

@section('sidebar')
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-th-large"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="{{ route('user.requests.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20">
            <i class="fas fa-plus-circle"></i>
            <span class="font-medium">Create Request</span>
        </a>
        <a href="{{ route('user.requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-history"></i>
            <span class="font-medium">My Requests</span>
        </a>
        <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-gray-400 hover:text-white">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="font-medium">Complaints</span>
        </a>
    @endsection

@section('content')
    <div class="max-w-3xl glass rounded-3xl p-8 border border-white/5 mx-auto">
        <form action="{{ route('user.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title">Request Title</label>
                    <input type="text" name="title" id="title" placeholder="e.g. Household Waste" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="waste_type">Waste Type</label>
                    <select name="waste_type" id="waste_type" class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 focus:border-primary focus:ring-primary/20 text-white" required>
                        <option value="dry">Dry Waste</option>
                        <option value="wet">Wet Waste</option>
                        <option value="mixed">Mixed Waste</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="address">Pickup Address</label>
                <textarea name="address" id="address" rows="2" class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 focus:border-primary focus:ring-primary/20 text-white" placeholder="Street, Building, Flat No." required></textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label>Select Pickup Location on Map</label>
                <div id="map" class="h-64 w-full rounded-2xl border border-white/10 overflow-hidden z-0"></div>
                <p class="text-[10px] text-gray-500 italic"><i class="fas fa-info-circle mr-1"></i> Click on the map to pin the exact collection point.</p>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </div>

            <div>
                <label for="description">Additional Notes (Optional)</label>
                <textarea name="description" id="description" rows="2" class="w-full bg-white/5 border-white/10 rounded-xl px-4 py-3 focus:border-primary focus:ring-primary/20 text-white" placeholder="Any special instructions..."></textarea>
            </div>

            <div>
                <label for="image">Upload Waste Image</label>
                <div class="relative group">
                    <input type="file" name="image" id="image" class="hidden" onchange="previewAndClassify(this)">
                    <label for="image" class="w-full border-2 border-dashed border-white/10 rounded-2xl p-8 flex flex-col items-center justify-center cursor-pointer hover:border-primary/50 transition-all group">
                        <div id="uploadPlaceholder" class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-500 group-hover:text-primary transition-colors mb-2"></i>
                            <span id="fileName" class="text-sm text-gray-400 group-hover:text-white">Click to select an image</span>
                        </div>
                        <img id="imagePreview" class="hidden h-32 w-32 object-cover rounded-xl mt-2 border border-white/10">
                    </label>
                </div>
                <div id="aiStatus" class="hidden mt-2 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                    <span id="aiResult" class="text-xs font-bold text-primary italic uppercase tracking-wider">AI analyzing...</span>
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="btn-primary">
                    Submit Request
                    <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default coordinates (e.g. city center)
            const defaultLat = 28.6139;
            const defaultLng = 77.2090;

            const map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let marker;

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });

            // Try to get user location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 15);
                });
            }
        });

        // AI Classification Logic
        let classifier;
        ml5.imageClassifier('MobileNet').then(model => {
            classifier = model;
            console.log('AI Model Loaded');
        });

        function previewAndClassify(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const placeholder = document.getElementById('uploadPlaceholder');
                    const status = document.getElementById('aiStatus');
                    const resultText = document.getElementById('aiResult');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    status.classList.remove('hidden');
                    
                    // Delay slightly to ensure image is rendered
                    setTimeout(() => {
                        if (classifier) {
                            classifier.classify(preview).then(results => {
                                const topResult = results[0].label.toLowerCase();
                                let detectedType = 'mixed';
                                
                                // Simple mapping logic
                                if (topResult.includes('bottle') || topResult.includes('can') || topResult.includes('plastic') || topResult.includes('paper')) {
                                    detectedType = 'dry';
                                } else if (topResult.includes('food') || topResult.includes('fruit') || topResult.includes('vegetable')) {
                                    detectedType = 'wet';
                                }
                                
                                resultText.textContent = 'AI Detected: ' + detectedType + ' waste (' + Math.round(results[0].confidence * 100) + '%)';
                                document.getElementById('waste_type').value = detectedType;
                            }).catch(err => {
                                console.error('Classification error:', err);
                                resultText.textContent = 'AI Classification Failed';
                            });
                        }
                    }, 500);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
