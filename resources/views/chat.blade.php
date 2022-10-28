@extends('layouts.app')
@section('content')
    <chat-room user="{{ Auth::user() }}"></chat-room>
@endsection
