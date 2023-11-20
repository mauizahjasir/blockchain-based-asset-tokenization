@extends('layouts.app')

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    @include('client.topbar')
    <div class="app-main">
        @include('client.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                @include('alert')
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-header">Assets on sale</div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Units</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($assets as $asset)
                                        <tr>
                                            <td>{{ $asset->asset }}</td>
                                            <td>{{ $asset->info ? $asset->info['issueqty'] : '' }}</td>
                                            <td>{{ $asset->info ? $asset->info['units'] : '' }}</td>
                                            <td>{{ $asset->info && !empty($asset->info->details) && array_key_exists('type', $asset->info->details) ? $asset->info->details['type'] : '' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('request-purchase', ['assetOnSale' => $asset->meta_id]) }}">
                                                    @csrf
                                                    <!-- Asset ID (you may use a hidden field if needed) -->
                                                    {{--<input type="hidden" name="sale_id" value="{{ $asset->id }}">--}}

                                                    <!-- Submit Button -->
                                                    <button type="submit" class="btn btn-primary mt-2">Submit Purchase Request</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('footer')
        </div>
    </div>
</div>
</body>




{{--@extends('layouts.client.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @include('alert')

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Asset Purchase Request Form</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                Please fill out the form below to request the purchase of the <b>{{ $asset }}</b>.
                                Enter the amount you want to commit in exchange for the asset, along with any additional information about your request.
                            </p>

                            <!-- Purchase Request Form -->
                            <form method="POST" action="{{ route('request-purchase') }}">
                                @csrf

                                <!-- Asset ID (you may use a hidden field if needed) -->
                                <input type="hidden" name="asset" value="{{ $asset}}">

                                <!-- Amount to commit -->
                                <div class="form-group">
                                    <label for="commit_amount">Amount to Commit:</label>
                                    <input type="number" class="form-control" id="commit_amount" name="commit_amount" required>
                                </div>

                                <!-- Additional Information -->
                                <div class="form-group">
                                    <label for="additional_info">Additional Information:</label>
                                    <textarea class="form-control" id="additional_info" name="additional_info" rows="3"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Submit Purchase Request</button>

                                <!-- Back button to go to the client.assets route -->
                                <a href="{{ route('bank-assets') }}" class="btn btn-secondary">Back</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection--}}
