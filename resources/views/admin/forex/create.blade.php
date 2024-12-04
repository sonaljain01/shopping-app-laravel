@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Forex Rate</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.forex.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('admin.message')
        <form method="POST" action="{{ route('forex.store') }}">
            @csrf
            <div class="form-group">
                {{-- <label for="base_currency">Base Currency:</label>
                <input type="text" name="base_currency" id="base_currency" required> --}}
                {{-- fix the base currency to INR --}}
                <label for="base_currency">Base Currency:</label>
                <input type="text" name="base_currency" id="base_currency" value="INR" readonly>
            </div>
            <div class="form-group">
                <label for="target_currency">Target Currency:</label>
                <input type="text" name="target_currency" id="target_currency" required>
            </div>
            <div class="form-group">
                <label for="currency_symbol">Currency Symbol:</label>
                <input type="text" name="currency_symbol" id="currency_symbol" required>
            </div>
            <div class="form-group">
                <label for="rate">Conversion Rate:</label>
                <input type="text" name="rate" id="rate" step="0.1" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Rate</button>
        </form>
    </div>
</section>
@endsection