@extends('layouts.admin.default')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-9">
                <h1>Create Asset Type</h1>

                @include('alert')

                <form method="POST" action="{{ route('create-asset-type') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
