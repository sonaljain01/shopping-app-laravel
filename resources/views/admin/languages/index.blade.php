@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Languages</h1>
        <div class="text-right">
            <a href="{{ route('languages.create') }}" class="btn btn-primary mb-3">Add Language</a>
        </div>
        @include('admin.message')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($languages as $language)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $language->name }}</td>
                        <td>{{ $language->code }}</td>
                        <td>
                            <a href="{{ route('languages.edit', $language->id) }}"
                                class="btn btn-sm btn-warning">Edit Translations</a>
                            <form action="{{ route('languages.destroy', $language->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this language?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $languages->links() }}
    </div>
@endsection
