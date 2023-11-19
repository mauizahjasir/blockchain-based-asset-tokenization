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
                            <div class="card-header">Manage Permissions</div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Permissions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->meta_id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <div class="row mt-2">
                                                    <div class="mr-2">
                                                        @if (\App\Facades\MultichainService::hasPermissions(['send'], $user->wallet_address))
                                                            <form action="{{ route('revoke-permission', $user->meta_id) }}"
                                                                  method="POST">
                                                                @csrf
                                                                <input type="hidden" name="permission" value="send">
                                                                <button type="submit" class="btn btn-danger">Revoke
                                                                    Send
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('grant-permission', $user->meta_id) }}"
                                                                  method="POST">
                                                                @csrf
                                                                <input type="hidden" name="permission" value="send">
                                                                <button type="submit" class="btn btn-primary">Grant
                                                                    Send
                                                                </button>
                                                            </form>
                                                        @endif

                                                    </div>
                                                    <div>
                                                        @if (\App\Facades\MultichainService::hasPermissions(['receive'], $user->wallet_address))
                                                            <form action="{{ route('revoke-permission', $user->meta_id) }}"
                                                                  method="POST">
                                                                @csrf
                                                                <input type="hidden" name="permission" value="receive">
                                                                <button type="submit" class="btn btn-danger">Revoke
                                                                    Receive
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('grant-permission', $user->meta_id) }}"
                                                                  method="POST">
                                                                @csrf
                                                                <input type="hidden" name="permission" value="receive">
                                                                <button type="submit" class="btn btn-primary">Grant
                                                                    Receive
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
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
