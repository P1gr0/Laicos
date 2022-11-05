@extends('layouts.app')
@section('content')
    <chat-room userj="{{ Auth::user() }}"></chat-room>
@endsection