@extends('layouts.master', ['active' => 'skills'])

@section('title', 'Login')
@section('content')

    @if(session('error'))
        {{session('error')}} <br><br><br>
    @elseif(session('success'))
        {{Session('success')}} <br><br><br>
    @endif

    <form method="post" action="/login">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        Username: <input type="text" name="username" value="{{old('username')}}" autocomplete="off">
        Password: <input type="password" name="password" autocomplete="off">
        <input type="submit">
    </form>
@endsection
