@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card mx-auto" style="max-width: 400px;">
            <div class="card">
                <div class="card-header">Transfer Item</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('transfer.item') }}">
                        @csrf

                        <div class="form-group">
                            <label for="purchase_id">Item to Transfer:</label>
                            <div>
                                <strong>Item Name:</strong> {{ $selectedPurchase->item_name }}<br>
                                <strong>Branch:</strong> {{ $selectedPurchase->branch }}<br>
                                <img src="{{ asset('storage/' . $selectedPurchase->item_image) }}" alt="{{ $selectedPurchase->item_name }}" style="max-width: 200px;">
                                <input type="hidden" name="purchase_id" value="{{ $selectedPurchase->id }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="branch">Select New Branch:</label>
                            <select class="form-control" id="branch" name="branch" required>
                                @foreach($branches as $name)
                                    @if($name != $selectedPurchase->branch)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="item_stock">Item Stock to Transfer:</label>
                            <input type="number" class="form-control" id="item_stock" name="item_stock" value="1" min="1" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Transfer Item</button>
                    </form>
                </div>
            </div>
        </div>
</div>

@endsection
