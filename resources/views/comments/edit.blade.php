@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <edit-comment id="{{ $comment->id }}" content="{{ $comment->content }}" image="{{ $comment->image }}"></edit-comment>
        </div>
    </div>
</div>
@endsection