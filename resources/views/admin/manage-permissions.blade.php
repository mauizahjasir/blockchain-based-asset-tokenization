@extends('layouts.admin.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @include('alert')

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Manage User's Permissions</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-primary">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Permissions</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->meta_id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <div class="row">
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
        </div>
    </div>
@endsection
