@extends('layouts.app')

@section('content')
    <div class="container p-2">
        <ul class="list-group">
            @foreach ($users as $user)
                <li class="tomato list-group-item">
                    <a class="px-0 fs-5" href="/users/{{ $user->id }}">
                        <img class="rounded-circle img-fluid me-2" src="/images/profile/{{ $user->image }}"
                        data-holder-rendered="true" width="40">
                        {{ $user->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
