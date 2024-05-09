@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Add New Location
                </div>
                <div class="card-body" style="height: 400px;">
                    <div id="map" style="height: 100%;"></div>
                </div>
                <div class="card-footer">
                    <p><a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a></p>
                    @auth
                        @if(auth()->user()->role == 2)
                            <button class="btn btn-primary" id="addLocationBtn">Save New Location</button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const key = '6Sx6psxBFlIVSf20sQ9H'; // Replace with your MapTiler access token
    const map = L.map('map').setView([14.7690, 120.5427], 12); // Starting position: Samal, Bataan, Philippines
    L.tileLayer(`https://api.maptiler.com/maps/bright-v2/{z}/{x}/{y}.png?key=${key}`, {
        tileSize: 512,
        zoomOffset: -1,
        minZoom: 1,
        attribution: "\u003ca href=\"https://www.maptiler.com/copyright/\" target=\"_blank\"\u003e\u0026copy; MapTiler\u003c/a\u003e \u003ca href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\"\u003e\u0026copy; OpenStreetMap contributors\u003c/a\u003e",
        crossOrigin: true
    }).addTo(map);

    let marker = null; // Variable to store the current marker

    // Event listener for map click
    map.on('click', function(event) {
        const latitude = event.latlng.lat;
        const longitude = event.latlng.lng;

        // Remove the old marker if it exists
        if (marker) {
            map.removeLayer(marker);
        }

        // Place a marker at the clicked location
        marker = L.marker([latitude, longitude]).addTo(map);
    });

    // Add event listener to the button
    document.getElementById('addLocationBtn').addEventListener('click', function() {
        // Ask for the name of the location
        const name = prompt("Enter the name of the location:");
        if (name) {
            // Confirm the location
            const confirmLocation = confirm("Are you sure with the location?");
            if (confirmLocation && marker) {
                const latitude = marker.getLatLng().lat;
                const longitude = marker.getLatLng().lng;
                saveLocation(name, latitude, longitude);
            } else {
                alert("No marker placed on the map or location not confirmed.");
            }
        }
    });

// Function to save the location
function saveLocation(name, latitude, longitude) {
    // Send an AJAX request to save the location
    fetch('{{ route('mapping.save') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: name,
            latitude: latitude,
            longitude: longitude
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Location save failed.');
        }
        return response.json();
    })
    .then(data => {
        // Check if data contains 'error' or 'success' message
        if (data.error) {
            if (data.error === 'A location with this name already exists.') {
                alert(data.error); // Display specific error message for existing name
            } else if (data.error === 'The location is too close to an existing location.') {
                alert(data.error); // Display specific error message for close location
            } else {
                alert('Unknown error occurred.'); // Generic error handling
            }
        } else if (data.success) {
            alert(data.success); // Display success message
            // Reload the page to update the map with the new marker
            location.reload();
        } else {
            console.warn('Unexpected response:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the location.');
    });
}
</script>

@endsection
