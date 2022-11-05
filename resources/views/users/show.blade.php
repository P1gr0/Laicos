@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1 class="my-4 text-center fw-bold">{{ $user->name }}</h1>
            <home-card userj="{{ $user }}" is_home="{{ false }}"></home-card>
            <div class="col-md-10">
                <posts is_home="{{ false }}" is_author="{{ false }}" userj="{{ $user }}"></posts>
            </div>
        </div>
    </div>
@endsection
