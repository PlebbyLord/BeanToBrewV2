@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bean to Brew</title>
</head>

<body>
    {{-- Header --}}

    {{-- Button For Modal --}}
    <!-- Modal -->
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
                            <label for="item_image">Item Image (jpg, jpeg, png only)</label>
                            <input type="file" name="item_image" class="form-control" accept=".jpg, .jpeg, .png" required>
                            <small id="fileHelp1" class="form-text text-muted">Accepted formats: .jpg, .jpeg, .png</small>
                            <div id="fileError4" class="text-danger"></div>
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
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                            @error('expiry_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item_description" class="form-label">Item Description</label>
                            <textarea class="form-control" id="item_description" name="item_description" rows="3" required></textarea>
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
                @if (auth()->check() && auth()->user()->email == 'beantobrew24@gmail.com')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Add New Item
                    </button>
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
                                <th>Expiry Date</th>
                                <th>Item Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                            <tr>
                                <td><img src="{{ asset('storage/' . $purchase->item_image) }}" alt="{{ $purchase->item_name }}" style="max-width: 75px;"></td>
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
                                <td>{{ $purchase->expiry_date }}</td>
                                <td>{{ $purchase->item_description }}</td>
                                <td>
                                    <a href="{{ route('features.transfer', ['purchase_id' => $purchase->id]) }}" class="btn btn-primary">Transfer</a>
                                </td>
                            </tr>
                            @endforeach                                             
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
</body>
</html>
@endsection
