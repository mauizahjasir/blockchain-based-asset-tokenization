@extends('layouts.app')

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    @include('admin.topbar')
    <div class="app-main">
        @include('admin.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                @include('alert')
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-header">Create Asset Type</div>

                            <form method="POST" action="{{ route('asset-type.create') }}">
                                @csrf

                                <div class="form-group ml-3 mr-4 mt-2">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="form-group ml-3 mr-4 mt-2">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary ml-3">Submit</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @include('footer')
        </div>
    </div>
</div>
</body>
