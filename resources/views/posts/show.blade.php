@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <post title="{{ $post->title }}" user="{{ $post->user }}" created_at="{{ $post->created_at }}" image="{{ $post->image }}"
                    content="{{ $post->content }}" id="{{ $post->id }}" likes="{{ $post->likes }}" liked="{{ $post->liked }}">
                </post>
                <comments post_id="{{ $post->id }}" is_author="{{ true }}"></comments>
            </div>
        </div>
    </div>
@endsection
