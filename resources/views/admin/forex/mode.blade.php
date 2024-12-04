@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Forex Mode</h1>
                </div>
                <div class="col-sm-6 text-right">
                    {{-- <form action="{{ route('forex.mode') }}" method="POST">
                    @csrf
                    <label for="forex_mode">Select Forex Mode:</label>
                    <select name="forex_mode" id="forex_mode">
                        <option value="auto" {{ $currentMode === 'auto' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ $currentMode === 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                    <button type="submit">Update</button>
                </form> --}}

                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <form action="{{ route('forex.mode') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="forex_mode">Select Forex Mode:</label>
                    <select name="forex_mode" id="forex_mode">
                        <option value="auto" {{ $currentMode === 'auto' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ $currentMode === 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            <div class="mt-3">
                <label for="forex_mode">Current Forex Mode:</label>
                <span id="currentMode" class="font-weight-bold text-primary">
                    {{ ucfirst($currentMode) }}
                </span>
            </div>
        </div>
    </section>
@endsection
