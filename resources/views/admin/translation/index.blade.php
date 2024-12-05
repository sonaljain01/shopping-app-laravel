@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Translations</h1>
        <div class="d-flex justify-content-end">
            <a href="{{ route('translations.create') }}" class="btn btn-primary mb-3">Add New Translation</a>
        </div>

        @include('admin.message')

        @php
            // Group translations by language dynamically within the current page
            $groupedTranslations = $translations->groupBy('language');
        @endphp

        @foreach ($groupedTranslations as $language => $group)
            <h2 class="mt-4">{{ ucfirst($language) }}</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Language</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group as $translation)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $translation->key }}</td>
                            <td>{{ $translation->value }}</td>
                            <td>{{ $translation->language }}</td>
                            <td>
                                <a href="{{ route('translations.edit', $translation->id) }}" class="text-info w-4 h-4 mr-1">
                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="#" onclick="deleteTranslation({{ $translation->id }})"
                                    class="text-danger w-4 h-4 mr-1">
                                    <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path ath fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $translations->links() }}
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        function deleteTranslation(id) {
            var url = '{{ route('translations.destroy', 'ID') }}';
            var newUrl = url.replace("ID", id);
            if (confirm("Are you sure you want to delete this Translation?")) {
                $.ajax({
                    url: newUrl,
                    type: 'DELETE',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response["status"]) {

                            window.location.href = "{{ route('translations.index') }}"
                        }
                    }
                });
            }
        }
    </script>
@endsection
