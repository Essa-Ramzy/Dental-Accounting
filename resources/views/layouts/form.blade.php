@extends('layouts.app_with_header')
<!-- This is the layout for the form view -->
@section('layout')
    <main class="container">
        @yield('content')
    </main>
    @yield('js')
@endsection
