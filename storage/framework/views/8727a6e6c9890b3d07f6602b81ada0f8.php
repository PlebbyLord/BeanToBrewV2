<?php $__env->startSection('content'); ?>

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
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role == 2): ?>
                            <button class="btn btn-primary" id="addLocationBtn">Save New Location</button>
                        <?php endif; ?>
                    <?php endif; ?>
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
        fetch('<?php echo e(route('mapping.save')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                name: name,
                latitude: latitude,
                longitude: longitude
            })
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response here, e.g., show a success message
            alert(data.message);
            // Reload the page to update the map with the new marker
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/mappingsave.blade.php ENDPATH**/ ?>