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
                            <div class="card-header">My Assets</div>
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

                                                @if (\App\Models\AssetsOnSale::where('asset', $asset['name'])->get()->isNotEmpty())
                                                    <form action="{{ route('remove-from-sale', ['asset' => $asset['name']]) }}"
                                                          method="POST">
                                                        @csrf
                                                        <button type="submit" id="PopoverCustomT-3"
                                                                class="btn btn-danger btn-sm mt-3 w-50">Remove from Sale
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                          action="{{ route('put-on-sale', ['asset' => $asset['name']]) }}">
                                                        @csrf
                                                        <button type="submit" id="PopoverCustomT-3"
                                                                class="btn btn-primary btn-sm mt-3 w-50">Put on sale
                                                        </button>
                                                    </form>
                                                @endif
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
