@extends('layouts.master', ['active' => 'history'])

@section('content')
    <p>Linux is an operating system that manages how your software and hardware are interconnected and communicate with each other.
        With Linux having an open source license, Linux allows you to have the freedom to run the program, the ability to study how the program works,
        while being able to change it to perform whatever task you need to do. </p>

    <p>With Linux, you are allowed the freedom to:</p>

    <ol>
        <li>redistribute copies so you can help your neighbor</li>
        <li>distribute copies of your modified versions to others</li>
    </ol>

    <p>Without the operating system, the software wouldn't function.</p>

    <p>The following is what is composed of the Linux: </p>

    <ol>
        <li>The Boot-loader: The software that manages the boot process of your computer. For most users, this will simply be a splash
            screen that pops up and eventually goes away to boot into the operating system.</li>

        <li> The Kernel: The core of the system that manages the CPU and its memory. </li>

        <li> Daemons: These are background services (printing and sound, etc.) that can start during reboot, or after you log into your desktop. </li>

        <li> The Shell: This is the shell – a command process that allows you to control the computer via commands typed into a text interface. </li>

        <li> Graphical Server: Sub-system that displays the graphics on your monitor. </li>

        <li> Desktop Environment:  Each desktop environment includes built-in applications (such as file managers, web browsers, games, etc.). </li>

        <li> Applications: Desktop environments do not offer the full array of apps. Just like Windows and Mac, Linux offers many high-quality
            software titles that can be easily found and installed. Most modern Linux distributions include App Store-like tools that centralize and
            simplify application installation. </li>

    </ol>

@endsection()