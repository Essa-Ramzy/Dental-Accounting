@extends('layouts.app_with_header')
@section('head')
    <meta name="search-url" content="{{ route($view . '.search') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/daterangepicker.min.css') }}">
    <script src="{{ asset('resources/js/moment.min.js') }}"></script>
    <script src="{{ asset('resources/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('resources/js/views/table.js') }}"></script>
@endsection
@section('layout')
    <!-- This is the layout for the table view -->
    <main class="d-flex flex-column flex-grow-1">
        <div class="px-3 py-2 border-bottom w-100">
            <div class="container d-flex">
                <!-- Button to add a new row to the table -->
                <div class="container w-100">
                    <a type="button" class="btn btn-outline-primary w-100" href="{{ route($view . '.create') }}">
                        Add {{ $view }}
                    </a>
                </div>
                <!-- Filter dropdown -->
                <div class="dropdown w-auto">
                    <!-- Filter dropdown button that displays the filter options -->
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-offset="0,7"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">Filter
                    </button>
                    <!-- Filter dropdown menu -->
                    <div class="dropdown-menu py-2 px-3 vw-100">
                        <!-- Filter date range -->
                        <div class="mb-2" id="date_range_filter">
                            <input id="report_range" class="text-body bg-body btn btn-secondary border w-100" readonly type="button" value="Date Range">
                            <input hidden id="from_date" aria-label="from_date">
                            <input hidden id="to_date" aria-label="to_date">
                        </div>
                        <!-- Filter column search -->
                        <div class="form-group me-2 w-100 d-flex align-items-end">
                            <!-- Search bar for the filter column -->
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                   id="search" value="{{ Session::get('customer') ?? Session::get('item') ?? '' }}">
                            <div class="dropdown">
                                <!-- Filter dropdown button that displays the filter column search options -->
                                <button id="dropdown_btn" class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">Search by
                                </button>
                                <!-- Filter column search dropdown menu -->
                                <ul class="dropdown-menu ">
                                    @yield('dropdown')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table to display the data -->
        <div class="container-fluid table-responsive flex-grow-1">
            <table class="table table-striped m-0">
                @yield('content')
            </table>
        </div>
        @yield('modal')
    </main>
@endsection
