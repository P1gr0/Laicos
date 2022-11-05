@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="my-4 text-center">Welcome, <span class="fw-bold">{{ $user->name }}</span>!</h1>
            <home-card userj="{{ $user }}" is_home="{{ true }}"></home-card>
            <div class="col-sm-10">
                <posts is_home="{{ true }}" is_author="{{ true }}" userj="{{ $user }}"></posts>
            </div>
        </div>
    </div>
@endsection
