@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Translations for {{ $languageCode }}</h1>
        <div class="text-right">
            <a href="{{ route('languages.index') }}" class="btn btn-primary">Back</a>
        </div>
        <br>
        <form action="{{ route('languages.update', $languageCode) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Key</th>
                        <th>Base (English)</th>
                        <th>{{ strtoupper($languageCode) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($baseTranslations as $key => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $key }}</td>
                            <td>{{ $value }}</td>
                            <td>
                                <input type="text" name="translations[{{ $key }}]"
                                    value="{{ $translations[$key] ?? '' }}" class="form-control">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- {{ $baseTranslations->links() }} --}}
            <button type="submit" class="btn btn-primary">Save Translations</button>
            <button href="{{ route('languages.index') }}" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
@endsection