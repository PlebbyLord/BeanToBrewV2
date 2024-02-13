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
    @if (auth()->check() && auth()->user()->email == 'beantobrew24@gmail.com')
        {{-- Adjust margins as needed --}}
        <div class="d-flex justify-content-between" style="margin-top: 10px; margin-right: 10px; margin-left: 10px;">
            {{-- <a href="{{ route('outofstock') }}" class="btn btn-primary">Out of Stock Items</a> --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Add New Item
            </button>
        </div>
    @endif

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
            <div class="card-header">
                <h5>Items in Stock</h5>
            </div> 
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item Image</th>
                                <th>Item Name</th>
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
                                <td>{{ $purchase->item_price }}</td>
                                <td>{{ $purchase->item_stock }}</td>
                                <td>{{ $purchase->expiry_date }}</td>
                                <td>{{ $purchase->item_description }}</td>
                                <td>
                                    <form method="post" action="{{ route('purchase.delete', ['id' => $purchase->id]) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
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
