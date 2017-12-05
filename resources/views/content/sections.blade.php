@extends('layouts.master', ['active' => 'skills'])

@section('title', 'Tutorials')

@section('content')

    @foreach($sections as $section)
        <a href="/skills_assessment/{{$tutorial->id}}/section/{{$section->id}}">{{$section->body}}</a> <br>
    @endforeach

@endsection
