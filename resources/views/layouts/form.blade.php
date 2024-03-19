@extends('layouts.app_with_header')

@section('layout')
    <main class="container">
        @yield('content')
    </main>
    @yield('js')
@endsection
