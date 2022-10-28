@extends('layouts.app')

@section('content')
    @php $user_image = empty($user->image) ? 'default.png' : $user->image;
    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="mb-4 text-center">Welcome back, <span class="fw-bold">{{ $user->name }}</span>!</h1>
            <home-card user_image="{{ $user_image }}" user_id="{{ $user->id }}" is_home="{{ true }}"></home-card>
            <div class="col-sm-10">
                <posts is_home="{{ true }}" user_id="{{ $user->id }}" user_name="{{ $user->name }}" user_image="{{ $user_image }}"
                    is_author="{{ true }}"></posts>

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
