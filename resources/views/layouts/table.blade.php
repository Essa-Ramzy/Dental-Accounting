@extends('layouts.app-with-header')
@section('head')
    @parent
    <meta name="search-url" content="{{ route($view . '.search') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/daterangepicker.min.css') }}">
    <script src="{{ asset('resources/js/moment.min.js') }}"></script>
    <script src="{{ asset('resources/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('resources/js/views/layouts/table.js') }}"></script>
@endsection
@section('svg-icons')
    <symbol id="plus-circle" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="12" y1="8" x2="12" y2="16"></line>
        <line x1="8" y1="12" x2="16" y2="12"></line>
    </symbol>
    <symbol id="funnel" viewBox="0 0 16 16">
        <path
            d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z">
        </path>
    </symbol>
    <symbol id="pencil-square" viewBox="0 0 24 24">
        <path
            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z">
        </path>
        <path
            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13">
        </path>
    </symbol>
    <symbol id="trash-fill" viewBox="0 0 24 24">
        <path
            d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z">
        </path>
    </symbol>
@endsection
@section('layout')
    <!-- This is the layout for the table view -->
    <main class="d-flex flex-column flex-grow-1">
        <!-- Action bar with improved mobile responsiveness -->
        <div class="bg-dark-subtle border-bottom py-2">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
                    <!-- Add button - takes full width on mobile -->
                    <div class="col-md-10 col-12">
                        <a type="button" class="btn btn-primary w-100 w-md-auto" href="{{ route($view . '.create') }}">
                            <svg class="me-2 mb-1" width="16" height="16" fill="currentColor">
                                <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Add New {{ $view }}
                        </a>
                    </div>
                    <!-- Filter toggle button -->
                    <div class="col-md-2 col-12">
                        <button class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center"
                            type="button" data-bs-toggle="collapse" data-bs-target="#filter" aria-expanded="false"
                            aria-controls="filter">
                            <svg class="me-2" width="16" height="16">
                                <use href="#funnel" fill="currentColor" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter collapse menu with improved responsive layout -->
        <div class="collapse bg-body-secondary border-bottom" id="filter">
            <div class="container py-3">
                <div class="row g-3">
                    <!-- Date range filter -->
                    <div class="col-lg-6 col-12">
                        <label for="report_range" class="form-label fw-semibold">Date Range</label>
                        <input id="report_range" class="form-control text-start" readonly type="button"
                            value="Select Date Range">
                        <input hidden id="from_date" aria-label="from_date">
                        <input hidden id="to_date" aria-label="to_date">
                    </div>

                    <!-- Search filter -->
                    <div class="col-lg-6 col-12">
                        <label for="search" class="form-label fw-semibold">Search</label>
                        <div class="form-group p-0 w-100 d-flex align-items-end p-0 position-relative">
                            <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                                style="padding-right: calc(20 * 0.375rem)" id="search"
                                value="{{ Session::get('customer') ?? (Session::get('item') ?? '') }}">
                            <div class="dropdown position-absolute end-0 top-50 translate-middle-y d-flex"
                                style="z-index: 1050;">
                                <button id="dropdown_btn"
                                    class="btn btn-outline-secondary dropdown-toggle rounded-0 rounded-end border-0 border-start rounded-end-1"
                                    style="margin-right: calc(1 * var(--bs-border-width))" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Search by
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1051;">
                                    @yield('dropdown')
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table with improved responsive handling -->
        <div class="container-fluid table-responsive flex-grow-1">
            <table class="table table-striped table-hover m-0 text-nowrap" id="table">
                @yield('content')
            </table>
            <div class="mt-3" id="pagination">
                @if ($view === 'Entry')
                    {{ $entries->links() }}
                @elseif ($view === 'Customer')
                    {{ $customers->links() }}
                @else
                    {{ $items->links() }}
                @endif
            </div>
        </div>
    </main>
    @yield('modal')
@endsection
