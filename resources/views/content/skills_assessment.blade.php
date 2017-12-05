@extends('layouts.master', ['active' => 'skills'])

@section('title', 'Tutorials')

@section('content')

    @if(session('success'))
        {{session('success')}} <br><br><br>
    @endif

    @foreach($tutorials as $tutorial)
        <a href="/skills_assessment/{{$tutorial->id}}">{{$tutorial->name}}</a> <br>
    @endforeach

@endsection
