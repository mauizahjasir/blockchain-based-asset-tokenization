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
