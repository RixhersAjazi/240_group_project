@extends('layouts.master', ['active' => 'commands'])

@section('title', $command->command)

@section('content')
    <div class="main">
        <div class="text">
            <p>
                {{$command->command}}
                {{$command->id}}
                {{$command->description}}
                {{$command->videoUrl}}
            </p>
            <p><a href="{{URL::previous()}}">Return to Command List</a></p>
        </div>
    </div>
@endsection