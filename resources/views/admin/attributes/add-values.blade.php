@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Values for Attribute</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('attributes.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row col-md-12">
                <form action="{{ route('attributes.storeValues', $attribute->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="{{ $attribute->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="value">Value</label>
                                <input type="text" name="values[]" class="form-control" placeholder="Enter value"
                                    required>
                            </div>
                        </div>
                    </div>


                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

                <script>
                    document.getElementById('add-more').addEventListener('click', function() {
                        let container = document.getElementById('values-container');
                        if (container) {
                            let input = document.createElement('div');
                            input.classList.add('form-group');
                            input.innerHTML =
                            '<label for="value">Value</label><input type="text" name="values[]" class="form-control" placeholder="Enter value" required>';
                            container.appendChild(input);
                        } else {
                            console.error('Container with id "values-container" not found.');
                        }
                    });
                </script>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
