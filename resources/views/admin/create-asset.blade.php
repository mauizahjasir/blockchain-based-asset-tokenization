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
                            <div class="card-header">Create Asset</div>

                            <form method="POST" action="{{ route('assets.create') }}">
                                @csrf

                                <div class="form-group ml-3 mr-4 mt-2">
                                    <label for="name">Asset Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="form-group ml-3 mr-4 mt-2">
                                    <label for="quantity">Asset Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                                </div>

                                {{--<div class="form-group ml-3 mr-4 mt-2">
                                    <label for="unit">Asset Unit</label>
                                    <input type="text" name="unit" id="unit" class="form-control" required>
                                </div>--}}

                                <div class="form-group ml-3 mr-4 mt-2">
                                    <label for="asset_type_id">Asset Type</label>
                                    <select name="asset_type_id" id="asset_type_id" class="form-control" required>
                                        <option value="">Select an Asset Type</option>
                                        @foreach($assetTypes as $assetType)
                                            <option value="{{ $assetType->id }}">{{ $assetType->name }}</option>
                                        @endforeach
                                    </select>
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
