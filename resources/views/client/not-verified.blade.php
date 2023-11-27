@extends('layouts.app')
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    @include('client.topbar')


    @include('alert')
    <div class="card mt-3">
        <div class="card-header">{{ __('Dashboard') }}</div>

        <div class="card-body">
            <div>Your account is not verified yet. Please contact network administrator.</div>
        </div>
    </div>


</div>
</body>

