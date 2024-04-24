@extends('layouts.app')

@section('content')

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form method="post" action="{{ route('save.item') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" required>
                    </div>
                    <div class="form-group">
                        <label for="coffee_type">Select Coffee Type:</label>
                        <select class="form-control" id="coffee_type" name="coffee_type">
                            <option value="green">Green Coffee Beans</option>
                            <option value="roasted">Roasted Coffee Beans</option>
                            <option value="grinded">Grinded Coffee Beans</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="branch">Select Branch:</label>
                        <select class="form-control" id="branch" name="branch">
                            @foreach($branches as $name)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>                        
                    <div class="mb-3">
                        <label for="item_image" class="form-label">Item Image (jpg, jpeg, png only)</label>
                        <input type="file" name="item_image" class="form-control" accept=".jpg, .jpeg, .png" required onchange="previewImage(this)">
                        <small id="fileHelp1" class="form-text text-muted">Accepted formats: .jpg, .jpeg, .png</small>
                        <div id="fileError4" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <img id="imagePreview" src="#" alt="Image preview" style="max-width: 100px; display: none;">
                    </div>
                    <div class="mb-3">
                        <label for="item_price" class="form-label">Item Price per Kilo</label>
                        <input type="number" class="form-control" id="item_price" name="item_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="item_stock" class="form-label">Item Stock</label>
                        <input type="number" class="form-control" id="item_stock" name="item_stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="production_date" class="form-label">Production Date</label>
                        <input type="date" class="form-control" id="production_date" name="production_date" required>
                        @error('production_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Best Before</label>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" readonly>
                        @error('expiry_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>                    
                    <div class="mb-3">
                        <label for="item_description" class="form-label">Item Description</label>
                        <select class="form-control" id="item_description" name="item_description" required>
                            <option value="">Select Coffee Type First</option> <!-- Default option -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Items in Stock</h5>
            @if (auth()->user()->role == 2)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Add New Item
                </button>
            @endif
            @if (auth()->user()->role == 1)
            <a href="{{ route('features.transfer') }}" class="btn btn-primary">Transfer</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item Image</th>
                            <th>Item Name</th>
                            <th>Coffee Type</th>
                            <th>Branch</th>
                            <th>Item Price per Kilo</th>
                            <th>Item Stock</th>
                            <th>Production Date</th>
                            <th>Best Before</th>
                            <th>Item Description</th>
                            <th>Requested By</th>
                            <th>Request Date</th>
                            <th>Arrival Date</th>
                            @if (auth()->user()->role == 1)
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                        <tr>
                            <td><img src="{{ asset('storage/' . $purchase->item_image) }}" alt="{{ $purchase->item_name }}" style="max-width: 75px; border: 1px solid black;"></td>
                            <td>{{ $purchase->item_name }}</td>
                            <td>{{ $purchase->coffee_type }}</td>
                            <td>{{ $purchase->branch }}</td>
                            <td>{{ $purchase->item_price }}</td>
                            <td class="{{ $purchase->item_stock <= 50 ? 'text-danger' : ($purchase->item_stock >= 4000 ? 'text-success' : '') }}">
                                {{ $purchase->item_stock }}
                                @if($purchase->item_stock <= 50)
                                    (low)
                                @elseif($purchase->item_stock >= 4000)
                                    (high)
                                @endif
                            </td>      
                            <td>{{ $purchase->production_date }}</td>                                                       
                            <td>{{ $purchase->expiry_date }}</td>
                            <td>{{ strlen($purchase->item_description) > 50 ? substr($purchase->item_description, 0, 50) . '...' : $purchase->item_description }}</td>
                            <td>{{ $purchase->requested_by }}</td>
                            <td>{{ $purchase->transfer_date }}</td>   
                            <td>{{ $purchase->arrival_date }}</td>   
                            @if (auth()->user()->role == 1 && $purchase->transfer_status == 1)
                            <td>
                                <form action="{{ route('approveTransfer', $purchase->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                                <form action="{{ route('rejectTransfer', $purchase->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        @elseif ($purchase->transfer_status == 2 && $purchase->arrival_date <= now())
                            <td>
                                <form action="{{ route('markReceived', $purchase->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Received</button>
                                </form>
                            </td>
                        @endif                                                  
                        </tr>
                        @endforeach                                             
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    

<script>
    document.getElementById('production_date').addEventListener('change', function() {
        var productionDate = new Date(this.value);
        var expiryDate = new Date(productionDate);

        var coffeeType = document.getElementById('coffee_type').value;

        switch (coffeeType) {
            case 'green':
                expiryDate.setFullYear(expiryDate.getFullYear() + 2); // Expiry after 2 years for green coffee
                break;
            case 'roasted':
                expiryDate.setFullYear(expiryDate.getFullYear() + 1);
                expiryDate.setMonth(expiryDate.getMonth() + 6); // Expiry after 1 year 6 months for roasted coffee
                break;
            case 'grinded':
                expiryDate.setMonth(expiryDate.getMonth() + 6); // Expiry after 6 months for ground coffee
                break;
        }

        // Format expiry date as YYYY-MM-DD for input field
        var formattedExpiryDate = expiryDate.toISOString().split('T')[0];

        document.getElementById('expiry_date').value = formattedExpiryDate;
    });

    function previewImage(input) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }

    const coffeeDescriptions = {
    'green': [
        { name: 'Liberica Green Coffee', description: 'Liberica Green Coffee beans are known for their bold and robust flavor, often described as woody and smoky with a slightly floral aroma. These beans are ideal for those who prefer a strong and distinctive coffee taste. Enjoy the unique flavor profile of Liberica beans in your favorite brewing method. The beans are sourced from high-altitude regions, resulting in a unique flavor profile with a rich body and low acidity.' },
        { name: 'Arabica Green Coffee', description: 'Arabica Green Coffee beans are prized for their smooth and nuanced flavor, featuring hints of fruitiness, sweetness, and acidity. These beans are perfect for those who appreciate a well-balanced and flavorful cup of coffee. Experience the rich aroma and delicate taste of Arabica beans in your daily brew. The beans are carefully harvested and processed to preserve their natural flavors and aromas.' },
        { name: 'Robusta Green Coffee', description: 'Robusta Green Coffee beans are renowned for their bold and intense flavor, characterized by a strong, earthy taste with a hint of bitterness. These beans are popular among espresso lovers for their ability to create a thick and creamy crema. Discover the bold and powerful flavor of Robusta beans in your espresso shots. The beans are grown in tropical climates, resulting in a robust flavor profile with high caffeine content.' },
        { name: 'Excelsa Green Coffee', description: 'Excelsa Green Coffee beans offer a unique and exotic flavor profile, featuring fruity and tart notes with a hint of spiciness. These beans are perfect for adventurous coffee enthusiasts looking to explore new taste sensations. Enjoy the intriguing flavor of Excelsa beans in your next coffee adventure. The beans are sourced from Southeast Asia, where they are carefully selected and processed to preserve their exotic flavors.' }
    ],
    'roasted': [
        { name: 'Liberica Roasted Coffee', description: 'Liberica Roasted Coffee delivers a bold and full-bodied flavor, with rich, smoky undertones and a hint of sweetness. This coffee is perfect for those who prefer a strong and robust brew with a distinctive character. Indulge in the intense and satisfying taste of Liberica coffee. The beans are roasted to perfection, resulting in a deep and complex flavor profile with a smooth finish.' },
        { name: 'Arabica Roasted Coffee', description: 'Arabica Roasted Coffee offers a smooth and aromatic flavor profile, with subtle hints of fruitiness, chocolate, and nuts. This coffee is ideal for those who appreciate a well-balanced and flavorful cup with a silky texture. Experience the luxurious taste of Arabica coffee in every sip. The beans are roasted in small batches to ensure consistency and freshness, resulting in a smooth and flavorful brew.' },
        { name: 'Robusta Roasted Coffee', description: 'Robusta Roasted Coffee boasts a bold and robust flavor, with earthy, nutty undertones and a deep, chocolatey richness. This coffee is perfect for espresso lovers seeking a strong and intense brew with a thick crema. Enjoy the powerful kick of Robusta coffee in your favorite espresso drinks. The beans are roasted to a dark roast level to enhance their bold flavor profile and create a rich and creamy texture.' },
        { name: 'Excelsa Roasted Coffee', description: 'Excelsa Roasted Coffee offers a complex and exotic flavor profile, featuring fruity and spicy notes with a hint of tartness. This coffee is perfect for those seeking a unique and adventurous taste experience. Explore the bold and intriguing flavors of Excelsa coffee. The beans are roasted to a medium roast level to highlight their exotic flavors and aromas, resulting in a balanced and flavorful cup of coffee.' }
    ],
    'grinded': [
        { name: 'Liberica Grinded Coffee', description: 'Liberica Grinded Coffee delivers a bold and aromatic brew, with deep, smoky flavors and a rich, full-bodied texture. This coffee is perfect for those who prefer the convenience of pre-ground beans without sacrificing quality or taste. Enjoy the bold and satisfying flavor of Liberica coffee in every cup. The coffee is ground to a coarse grind size to preserve its bold flavors and aromas, resulting in a flavorful and aromatic brew.' },
        { name: 'Arabica Grinded Coffee', description: 'Arabica Grinded Coffee offers a smooth and mellow taste, with delicate notes of fruitiness, caramel, and chocolate. This coffee is perfect for those who appreciate a well-balanced and flavorful cup with a velvety texture. Experience the indulgent taste of Arabica coffee in every sip. The coffee is ground to a medium grind size to highlight its delicate flavors and aromas, resulting in a smooth and balanced brew.' },
        { name: 'Robusta Grinded Coffee', description: 'Robusta Grinded Coffee delivers a bold and intense flavor, with deep, earthy tones and a strong, lingering finish. This coffee is perfect for those who crave a powerful and robust brew with a thick, creamy texture. Enjoy the bold and invigorating taste of Robusta coffee. The coffee is ground to a fine grind size to extract its bold flavors and aromas, resulting in a strong and flavorful brew with a thick crema.' },
        { name: 'Excelsa Grinded Coffee', description: 'Excelsa Grinded Coffee offers a unique and exotic taste experience, with vibrant flavors of fruit, spice, and floral notes. This coffee is perfect for those seeking a bold and adventurous cup with a complex and intriguing flavor profile. Explore the exotic taste of Excelsa coffee. The coffee is ground to a medium-coarse grind size to preserve its complex flavors and aromas, resulting in an adventurous and flavorful brew.' }
    ]
};

    // Function to populate item description dropdown based on selected coffee type
    function populateDescriptions() {
        const coffeeType = document.getElementById('coffee_type').value;
        const itemDescriptionDropdown = document.getElementById('item_description');
        itemDescriptionDropdown.innerHTML = ''; // Clear previous options

        if (coffeeType in coffeeDescriptions) {
            coffeeDescriptions[coffeeType].forEach(description => {
                const option = document.createElement('option');
                option.value = description.description; // Set the value to the detailed description
                option.textContent = description.name;
                itemDescriptionDropdown.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.textContent = 'Select Coffee Type First';
            itemDescriptionDropdown.appendChild(option);
        }
    }

    // Call the function initially
    populateDescriptions();

    // Event listener for coffee type change
    document.getElementById('coffee_type').addEventListener('change', populateDescriptions);

</script>

@endsection
