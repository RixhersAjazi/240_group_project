@extends('layouts.master', ['active' => 'skills'])

@section('title', 'Login')
@section('content')

    @if(session('error'))
        {{session('error')}} <br><br><br>
    @endif

    <form method="post" action="/register">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        Username: <input type="text" name="username" value="{{old('username')}}" autocomplete="off">
        Email: <input type="text" name="email" value="{{old('email')}}" autocomplete="off">
        Password: <input type="password" name="password" autocomplete="off">
        <input type="submit">
    </form>
@endsection
