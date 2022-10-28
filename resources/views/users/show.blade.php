@extends('layouts.app')

@section('content')
    @php $user_image = empty($user->image) ? 'default.png' : $user->image;
    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="text-center fw-bold">{{ $user->name }}</h1>
            <home-card user_image="{{ $user_image }}" user_id="{{ $user->id }}" is_home="{{ false }}"></home-card>
            <div class="col-md-10">
               <posts is_home="{{ false }}" user_id="{{ $user->id }}" user_name="{{ $user->name }}" user_image="{{ $user_image }}"
                    is_author="{{ false }}"></posts>

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