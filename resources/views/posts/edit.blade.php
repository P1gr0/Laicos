@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <edit-post id="{{ $post->id }}" title="{{ $post->title }}" content="{{ $post->content }}" image="{{ $post->image }}"></edit-post>
        </div>
    </div>
</div>
@endsection