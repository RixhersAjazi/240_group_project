@extends('layouts.master', ['active' => 'skills'])

@section('title', $section->name . ' - ' . $section->command)
@section('content')

    @if(session('error'))
        {{session('error')}} <br><br><br>
    @endif

    {{$section->body}}

    <form method="post" action="/skills_assessment/{{$section->tutorial_id}}/section/{{$sectionId}}">
        <input type="hidden" name="correctAnswer" value="{{$section->action}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        Answer: <input type="text" name="answer" value="{{old('answer')}}" autocomplete="off">
        <input type="submit">
    </form>
@endsection
