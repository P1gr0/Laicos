@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="mb-4 text-center">Welcome, <span class="fw-bold">{{ $user->name }}</span>!</h1>
            <home-card user_image="{{ $user->image }}" user_id="{{ $user->id }}" is_home="{{ true }}"></home-card>
            <div class="col-sm-10">
                <posts is_home="{{ true }}" is_author="{{ true }}" userj="{{ $user }}"></posts>

                {{--      <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div> --}}
            </div>
        </div>
    </div>
@endsection
