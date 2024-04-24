@extends('layouts.app')

@section('content')
<style>
        .card-body {
            background-color: rgb(252, 252, 252);
            border: 1px solid rgb(0, 0, 0);
        }
        .card-header {
            background-color: rgb(252, 252, 252);   
            border: 1px solid rgb(0, 0, 0);
        }
        .card-footer {
            background-color: rgb(252, 252, 252);   
            border: 1px solid rgb(0, 0, 0);
        }
</style>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @auth
                @if(auth()->user()->role == 2)
                    <a href="{{ route('features.mappingsave') }}" class="btn btn-primary" style="width: 850px; height: 40px;">Add Location</a> <!-- Adjusted width and height -->
                @endif
            @endauth
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        @foreach($mappings as $mapping)
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    {{ $mapping->name }} Branch
                </div>
                <div class="card-body" style="height: 350px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="map-{{ $mapping->id }}" style="height: 300px; width: 400px;"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    Available Items
                                </div>
                                <div class="card-body">
                                    <ul>
                                        @foreach($purchases as $purchase)
                                            @if($purchase->branch === $mapping->name)
                                                <li>{{ $purchase->item_name }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p><a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a></p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const key = '6Sx6psxBFlIVSf20sQ9H'; // Replace with your MapTiler access token

    // Function to place markers for each item in the mappings table
    function placeMarkers(map, mapping) {
        const { latitude, longitude } = mapping;
        L.marker([latitude, longitude]).addTo(map);
        map.setView([latitude, longitude], 12); // Set view to marker's coordinates
    }

    // Fetch mapping data from the server
    fetch('{{ route('mapping.getMappingData') }}')
        .then(response => response.json())
        .then(data => {
            // Loop through each mapping to create a separate map for each
            data.forEach(mapping => {
                // Initialize a new map for each map container
                const map = L.map('map-' + mapping.id).setView([14.7690, 120.5427], 12); // Starting position: Samal, Bataan, Philippines
                L.tileLayer(`https://api.maptiler.com/maps/bright-v2/{z}/{x}/{y}.png?key=${key}`, {
                    tileSize: 512,
                    zoomOffset: -1,
                    minZoom: 1,
                    attribution: "\u003ca href=\"https://www.maptiler.com/copyright/\" target=\"_blank\"\u003e\u0026copy; MapTiler\u003c/a\u003e \u003ca href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\"\u003e\u0026copy; OpenStreetMap contributors\u003c/a\u003e",
                    crossOrigin: true
                }).addTo(map);

                // Place markers for the current mapping
                placeMarkers(map, mapping);
            });
        })
        .catch(error => {
            console.error('Error fetching mapping data:', error);
        });
</script>

@endsection
