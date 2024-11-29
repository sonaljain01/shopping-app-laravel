@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Forex Rate</h1>
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
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('forex.update', $rate->id) }}" name="editForexForm"
                        id="editForexForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="base_currency">Base Currency:</label>
                            <input type="text" name="base_currency" id="base_currency"
                                value="{{ $rate->base_currency }}" required>
                        </div>
                        <div class="form-group">
                            <label for="target_currency">Target Currency:</label>
                            <input type="text" name="target_currency" id="target_currency"
                                value="{{ $rate->target_currency }}" required>
                        </div>
                        <div class="form-group">
                            <label for="rate">Rate:</label>
                            <input type="text" name="rate" id="rate" value="{{ $rate->rate }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
