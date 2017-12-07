<?php

$tutorials = \App\Tutorials::all();

function checkAuth() {
    if (!Session::has('loggedIn') && !Session('loggedIn')) {
        return Response::redirectTo('/login')->with('error', 'Please login');
    }

    return;
}

Route::get('/', function () use($tutorials) {
    return view('content.homepage', ['tutorials' => $tutorials]);
});

Route::get('/tutorials/skill_assessment', function() use($tutorials) {
    return view('content.tutorials', ['contexts' => $tutorials, 'type' => 'skill_assessment', 'tutorials' => $tutorials]);
});

Route::get('/tutorials/skill_assessment/{tutorialId}', function($tutorialId) use($tutorials) {
    $tutorial = \App\Tutorials::query()->find($tutorialId);

    $tutorial_commands = \App\Commands::query()
        ->join('sections', 'commands.id', '=', 'sections.command_id')
        ->where('sections.tutorial_id', '=', $tutorialId)
        ->orderBy('section_id', 'asc')
        ->get(['commands.videoUrl', 'commands.description', 'commands.command', 'commands.id', 'sections.id as sectionId']);

    return view('content.tutorial', ['type' => 'skill_assessment', 'title' => $tutorial->name, 'tutorials' => $tutorials, 'tutorial_commands' => $tutorial_commands]);
});

Route::get('/tutorials/type', function() use ($tutorials) {
    $commands = \App\Commands::query()->groupBy('type')->get(['type']);

    return view('content.tutorials', ['type' => 'type', 'contexts' => $commands, 'tutorials' => $tutorials] );
});

Route::get('/tutorials/type/{type}', function($type) use ($tutorials) {
    $commands = \App\Commands::query()->where('type', '=', $type)->get(['commands.videoUrl', 'commands.description', 'commands.command', 'commands.id']);

    return view('content.tutorial', ['type' => 'type', 'tutorial_commands' => $commands, 'tutorials' => $tutorials, 'title' => $type] );
});

Route::get('/command/{commandId}', function($commandId) use($tutorials) {
    $command = \App\Commands::query()->find($commandId);
    return view('content.command', ['command' => $command, 'tutorials' => $tutorials]);
});

Route::get('/commands', function() use($tutorials) {
    $commands = \App\Commands::query()
        ->orderBy('commands.firstLetter')
        ->get();

    return view('content.commands', ['commands' => $commands, 'tutorials' => $tutorials]);
});

Route::get('/unix_history', function() use($tutorials) {
    return view('content.unix_history', ['tutorials' => $tutorials]);
});

#################### AUTHENTICATED ROUTES ####################################


Route::get('/skills_assessment', function () use ($tutorials) {
    $response = checkAuth();
    if (checkAuth() instanceof \Illuminate\Http\RedirectResponse) {
        return $response;
    }

    return view('content.skills_assessment', ['tutorials' => $tutorials]);
});

Route::get('/user/{userId}', function ($userId) use ($tutorials) {
    $response = checkAuth();
    if (checkAuth() instanceof \Illuminate\Http\RedirectResponse) {
        return $response;
    }

    $user = \App\User::query()->find($userId);
    $classes = \App\Classes::query()->where('user_id', '=', $userId)
        ->join('tutorials', 'classes.tutorial_id', '=', 'tutorials.id')
        ->get();

    return view('content.userInfo', ['user' => $user, 'classes' => $classes, 'tutorials' => $tutorials]);
});

Route::get('/skills_assessment/{tutorialId}', function ($tutorialId) use ($tutorials) {
    $response = checkAuth();
    if (checkAuth() instanceof \Illuminate\Http\RedirectResponse) {
        return $response;
    }

    $tutorial = \App\Tutorials::query()->find($tutorialId);
    $sections = \App\Sections::query()->where('tutorial_id', '=', $tutorialId)->orderBy('sections.id', 'asc')->get();

    return view('content.sections', ['tutorial' => $tutorial, 'sections' => $sections, 'tutorials' => $tutorials]);

});

Route::get('/skills_assessment/{tutorialId}/section/{sectionId}', function ($tutorialId, $sectionId) use ($tutorials) {
    $response = checkAuth();
    if (checkAuth() instanceof \Illuminate\Http\RedirectResponse) {
        return $response;
    }

    $section = \App\Sections::query()
        ->join('tutorials', 'tutorials.id', '=', 'sections.tutorial_id')
        ->join('commands', 'commands.id', '=', 'sections.command_id')
        ->where('tutorial_id', '=', $tutorialId)
        ->where('sections.id', '=', $sectionId)
        ->get();

    $tutorialView = DB::table('tutorials')->join('sections', 'tutorials.id', '=', 'sections.tutorial_id')->get();
    return view('content.section', ['section' => $section[0], 'sectionId' => $sectionId, 'tutorialView' => $tutorialView, 'tutorials' => $tutorials]);
});

Route::post('/skills_assessment/{tutorialId}/section/{sectionId}', function ($tutorialId, $sectionId) {
    $response = checkAuth();
    if (checkAuth() instanceof \Illuminate\Http\RedirectResponse) {
        return $response;
    }

    $correctAnswer = Request::post('correctAnswer');
    $answer = Request::post('answer');

    if (is_null($answer)) {
        return back()->withInput()->with('error', 'Please provide input');
    }

    if ($correctAnswer !== $answer) {
        return back()->withInput(Request::only('answer'))->with('error', 'Wrong answer');;
    }

    $section = \App\Sections::query()
        ->join('tutorials', 'tutorials.id', '=', 'sections.tutorial_id')
        ->join('commands', 'commands.id', '=', 'sections.command_id')
        ->where('tutorial_id', '=', $tutorialId)
        ->where('sections.id', '=', $sectionId)
        ->get()[0];

    if ($section->sectionCount == 1) {
        return Response::redirectTo('skills_assessment')->with('success', 'All sections for tutorial ' . $section->name . ' completed!');
    }

    $nextSection = \App\Sections::query()
        ->join('tutorials', 'tutorials.id', '=', 'sections.tutorial_id')
        ->join('commands', 'commands.id', '=', 'sections.command_id')
        ->where('tutorial_id', '=', $tutorialId)
        ->where('sections.id', '>', $sectionId)
        ->orderBy('sections.id', 'asc')
        ->get(['sections.id as sectionId']);

    if (isset($nextSection[0]->sectionId)) {
        return Response::redirectTo('/skills_assessment/' . $tutorialId . '/section/' . $nextSection[0]->sectionId);
    } else {
        return Response::redirectTo('skills_assessment')->with('success', 'All sections for tutorial ' . $section->name . ' completed!');
    }
});

Route::get('/login', function() {
    return view('content.login');
});

Route::get('/logout', function() {
    Session::flush();
    return Response::redirectTo('/');
});

Route::post('/login', function () {
    if (Session('loggedIn')) {
        return Response::redirectTo('skills_assessment');
    }

    $user = Request::get('username') ?: null;
    $password = Request::get('password') ?: null;

    if (is_null($user) || is_null($password)) {
        return Response::redirectTo('/login')->withInput()->with('error', 'Missing fields!');
    }

    $userInfo = DB::table('users')->where('username', '=', $user)->get(['id', 'password', 'email']);
    if ($userInfo->isEmpty()) {
        return Response::redirectTo('/login')->withInput()->with('error', 'Auth problem');
    }

    if (password_verify($password, $userInfo[0]->password)) {
        Session::put('loggedIn', true);
        Session::put('userId', $userInfo[0]->id);
        Session::put('email', $userInfo[0]->email);
        Session::save();
        return Response::redirectTo('/skills_assessment')->with('success', 'Welcome back');
    }

    return back()->with('error', 'Authentication Failed')->withInput();
});

Route::get('/register', function() {
    return view('content.register');
});

Route::post('/register', function() {
    if (Session('loggedIn')) {
        return Response::redirectTo('skills_assessment');
    }

    $user = Request::get('username') ?: null;
    $password = Request::get('password') ?: null;
    $email = Request::get('email') ?: null;

    if (is_null($user) || is_null($password) || is_null($email)) {
        return Response::redirectTo('/register')->withInput()->with('error', 'Missing fields');
    }

    if (DB::table('users')->where('username', '=', $user)->orWhere('email', '=', $email)->get()->isEmpty()) {
        $success = DB::table('users')->insertGetId(['username' => $user, 'password' => password_hash($password, PASSWORD_DEFAULT), 'email' => $email]);
        if (!is_null($success) && is_int($success)) {
            try {
                Mail::send('content.emails', ['content' => 'Congrats on registration to Lag6.me - your username is ' . $user], function ($message) use ($email) {
                    $message->from('no_reply@lag6.me');
                    $message->to($email);
                    $message->subject('Registration success');
                });
            } catch (Exception $e) {
                DB::table('users')->delete($success);
                return Response::redirectTo('/register')->withInput()->with('error', 'Try again');
            }

            return Response::redirectTo('/login')->with('success', 'You may now login');
        } else {
            return Response::redirectTo('/register')->withInput()->with('error', 'Try again');
        }
    } else {
        return Response::redirectTo('/register')->withInput()->with('error', 'Invalid registration attempt');
    }
});
