@extends('layouts.app')

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    @include('admin.topbar')
    <div class="app-main">
        @include('admin.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>Analytics Dashboard
                                <div class="page-title-subheading">This is a statistical dashboard of the Multichain data
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-midnight-bloom">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div class="widget-heading">My Wallet Balance</div>
                                    <div class="widget-subheading">Total # PKR in my wallet</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-white"><span>{{ \Illuminate\Support\Facades\Auth::user()?->walletBalance() }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-arielle-smile">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Clients</div>
                                    <div class="widget-subheading">Total # of Clients</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-white"><span>{{ \App\Models\User::where('user_type', 'client')->get()->count() }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-grow-early">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Asset Requests</div>
                                    <div class="widget-subheading">Total # of pending requests</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-white"><span>{{ \App\Models\AssetsRequest::where('status', \App\Models\AssetsRequest::OPEN)->get()->count() }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-xl-none d-lg-block col-md-6 col-xl-4">
                        <div class="card mb-3 widget-content bg-premium-dark">
                            <div class="widget-content-wrapper text-white">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Products Sold</div>
                                    <div class="widget-subheading">Revenue streams</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-warning"><span>$14M</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-header">Multichain Information
                            </div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Key</th>
                                        <th class="text-center">Value</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td class="text-center">{{$key}}</td>
                                            <td class="text-center">{{$value}}</td>
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
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
</div>

</body>
</html>
