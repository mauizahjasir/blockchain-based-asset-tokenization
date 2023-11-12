@extends('layouts.default', ['activePage' => 'table', 'titlePage' => __('Table List')])
@section('content')
    <div class="content">

        @if (Session::has('success'))
            <div class="w3-panel w3-green">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        @if (Session::has('errors'))
            <div class="w3-panel w3-red">
                @foreach(Session::get('errors') as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="container-fluid mt-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Users Request</h4>
                        </div>
                        @if(!empty($users))
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                        </thead>
                                        <tbody>

                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->meta_id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->user_type }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('approve', ['user' => $user->meta_id]) }}">
                                                        @csrf
                                                        <button type="submit" class="w3-button w3-green">
                                                            Approve
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="w3-panel w3-info">
                                <p>No New Users</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
