@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <h1>Users Request</h1>

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

        @if(!empty($users))

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
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
        @else
            <div class="w3-panel w3-info">
                <p>No New Users</p>
            </div>
        @endif
    </div>
@endsection
