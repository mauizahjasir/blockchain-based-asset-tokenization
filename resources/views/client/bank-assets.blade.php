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
                            <div class="card-header">Bank Assets</div>
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
                                            <td>{{ $asset['name'] }}</td>
                                            <td>{{ $asset['qty'] }}</td>
                                            <td>{{ $asset['info'] && $asset['info']['units'] ? $asset['info']['units'] : '' }}</td>
                                            <td>{{ $asset['info'] && $asset['info']['details'] ? $asset['info']['details']['type'] ?? '' : '' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('bank-assets-purchase', ['asset' => $asset['name']]) }}">
                                                    @csrf
                                                    <!-- Submit Button -->
                                                    <button type="submit" class="btn btn-primary mt-2 {{ \Illuminate\Support\Facades\Auth::user()->canSubmitPurchaseRequest($asset['name'])  ? 'disabled' : '' }}">Submit Purchase Request</button>
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
