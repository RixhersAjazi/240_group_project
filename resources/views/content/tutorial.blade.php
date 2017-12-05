@extends('layouts.master', ['active' => 'tutorials'])

@section('title', $title . ' Tutorial')

@section('content')
    @foreach($tutorial_commands as $command)
        @if(!is_null($command->videoUrl))
            {{ $command->videoUrl }} <br>
        @else
            <b>No Video Available!</b> <br>
        @endif

        {{ $command->command }} <br>
        {{ $command->description }} <br>
        <br><br><br>
    @endforeach
@endsection
