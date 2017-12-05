@extends('layouts.master', ['active' => 'commands'])

@section('title', 'Commands')

@section('content')
    <div class="main">
        <div class="text">
            <table class="table table-bordered">
                @foreach($commands as $command)
                    <tr>
                        <td><strong><li><a href="/command/{{$command->id}}">{{  $command->command }}</a></li> </strong><td>
                    </tr>
                        @endforeach
                    </tr>
            </table>
        </div>
    </div>
@endsection
