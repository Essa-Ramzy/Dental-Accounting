@extends('layouts.app')
@section('head')
    @parent
    <meta name="search-url" content="{{ route(explode('.', Route::currentRouteName())[0] . '.search') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/daterangepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/views/layouts/success-alert.css') }}">
    <script src="{{ asset('resources/js/moment.min.js') }}"></script>
    <script src="{{ asset('resources/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('resources/js/views/layouts/table.js') }}"></script>
@endsection
@section('svg-icons')
    <symbol id="check-circle-fill" viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z">
        </path>
    </symbol>
    @if ($trash)
        <symbol id="arrow-clockwise" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"></path>
            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466">
            </path>
        </symbol>
    @else
        <symbol id="plus-circle" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
        </symbol>
    @endif
    <symbol id="funnel" viewBox="0 0 16 16">
        <path
            d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z">
        </path>
    </symbol>
    <symbol id="calendar-date-fill" viewBox="0 0 16 16">
        <path
            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zm5.402 9.746c.625 0 1.184-.484 1.184-1.18 0-.832-.527-1.23-1.16-1.23-.586 0-1.168.387-1.168 1.21 0 .817.543 1.2 1.144 1.2">
        </path>
        <path
            d="M16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-6.664-1.21c-1.11 0-1.656-.767-1.703-1.407h.683c.043.37.387.82 1.051.82.844 0 1.301-.848 1.305-2.164h-.027c-.153.414-.637.79-1.383.79-.852 0-1.676-.61-1.676-1.77 0-1.137.871-1.809 1.797-1.809 1.172 0 1.953.734 1.953 2.668 0 1.805-.742 2.871-2 2.871zm-2.89-5.435v5.332H5.77V8.079h-.012c-.29.156-.883.52-1.258.777V8.16a13 13 0 0 1 1.313-.805h.632z">
        </path>
    </symbol>
    <symbol id="search-lens" viewBox="0 0 16 16">
        <path
            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0">
        </path>
    </symbol>
    <symbol id="pencil-square" viewBox="0 0 24 24">
        <path
            d="m21.28 6.4 -9.54 9.54c-0.95 0.95 -3.77 1.39 -4.4 0.76s-0.2 -3.45 0.75 -4.4l9.55 -9.55a2.58 2.58 0 1 1 3.64 3.65">
        </path>
        <path d="M11 4H6a4 4 0 0 0 -4 4v10a4 4 0 0 0 4 4h11c2.21 0 3 -1.8 3 -4v-5">
        </path>
    </symbol>
    <symbol id="trash-fill" viewBox="0 0 20 20">
        <path
            d="M4.29 16.67c0 1.05 0.86 1.9 1.91 1.9h7.62c1.05 0 1.91-0.86 1.91-1.9V5.24H4.29v11.43zM6.43 9.93l1.34-1.34L9.9 10.71l2.02-2.02 1.34 1.34L11.25 12l2.02 2.02-1.34 1.34L9.9 13.29l-2.02 2.02-1.34-1.34L8.55 12l-2.12-2.07zM13.36 2.38l-0.95-0.95h-4.76l-0.95 0.95H3.33v1.9h13.34V2.38z">
        </path>
    </symbol>
    <symbol id="box-arrow-in-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z">
        </path>
        <path fill-rule="evenodd"
            d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z">
        </path>
    </symbol>
    <symbol id="exclamation-triangle" viewBox="0 0 16 16">
        <path
            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z">
        </path>
        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z">
        </path>
    </symbol>
    <symbol id="x-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
        <path
            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708">
        </path>
    </symbol>
    <symbol id="chevron-down" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708">
        </path>
    </symbol>
@endsection
@section('app_content')
    @include('partials.header')
    <!-- This is the layout for the table view -->
    <main class="d-flex flex-column flex-grow-1">
        @if (Session('success'))
            @include('partials.success-alert', ['message' => Session('success')])
        @endif
        <!-- Enhanced Action bar with Bootstrap styling -->
        <div class="bg-dark-subtle border-bottom py-2 shadow-sm">
            <div class="container-fluid">
                <div class="row g-3 align-items-center">
                    @if ($trash)
                        <div class="col-md-10 col-12">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6 col-12">
                                    <a class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm fw-semibold py-2"
                                        type="button" data-bs-toggle="modal" href="#restoreModal"
                                        aria-label="Restore selected entries">
                                        <svg width="18" height="18" fill="currentColor">
                                            <use href="#arrow-clockwise" fill="currentColor" />
                                        </svg>
                                        Restore Selected
                                    </a>
                                </div>
                                <div class="col-md-6 col-12">
                                    <a class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm fw-semibold py-2"
                                        type="button" data-bs-toggle="modal" href="#deleteModal"
                                        aria-label="Delete selected entries">
                                        <svg width="18" height="18" fill="currentColor">
                                            <use href="#trash-fill" fill="currentColor"></use>
                                        </svg>
                                        Delete Selected
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Enhanced Add button -->
                        <div class="col-md-10 col-12">
                            <a type="button"
                                class="btn btn-primary w-100 w-md-auto d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm fw-semibold px-4 py-2"
                                href="{{ route(explode('.', Route::currentRouteName())[0] . '.create') }}">
                                <svg width="18" height="18" fill="currentColor">
                                    <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Add New {{ explode('.', Route::currentRouteName())[0] }}
                            </a>
                        </div>
                    @endif
                    <!-- Enhanced Filter toggle button -->
                    <div class="col-md-2 col-12">
                        <button
                            class="btn btn-outline-secondary text-body w-100 d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm fw-semibold py-2"
                            type="button" data-bs-toggle="collapse" data-bs-target="#filter" aria-expanded="false"
                            aria-controls="filter">
                            <svg width="16" height="16">
                                <use href="#funnel" fill="currentColor" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Filter panel with Bootstrap styling -->
        <div class="collapse bg-secondary border-bottom shadow-sm" id="filter">
            <div class="container py-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="row g-3 mb-1">
                            <!-- Date range filter with enhanced styling -->
                            <div class="col-lg-6 col-12">
                                <label for="report_range"
                                    class="form-label fw-bold text-primary mb-2 d-flex align-items-center gap-2">
                                    <svg width="16" height="16" aria-hidden="true">
                                        <use href="#calendar-date-fill" fill="currentColor" />
                                    </svg>
                                    Date Range
                                </label>
                                <input id="report_range"
                                    class="form-control form-control-md text-start border-2 rounded-3" readonly
                                    type="button" value="Select Date Range">
                                <input hidden id="from_date" aria-label="from_date">
                                <input hidden id="to_date" aria-label="to_date">
                                <small class="text-muted mt-1 d-block">Click to select a date range for
                                    filtering</small>
                            </div>

                            <!-- Search filter with enhanced styling -->
                            <div class="col-lg-6 col-12">
                                <label for="search"
                                    class="form-label fw-bold text-primary mb-2 d-flex align-items-center gap-2">
                                    <svg width="16" height="16" aria-hidden="true">
                                        <use href="#search-lens" fill="currentColor" />
                                    </svg>
                                    Search
                                </label>
                                <div class="form-group p-0 w-100 d-flex align-items-end p-0 position-relative"
                                    style="z-index: 1050;">
                                    <input class="form-control border-2 rounded-3" type="search"
                                        style="padding-right: calc(20 * 0.375rem)" placeholder="Enter search terms..."
                                        aria-label="Search" id="search"
                                        value="{{ Session::get('customer') ?? (Session::get('item') ?? '') }}">
                                    <div class="dropdown position-absolute end-0 top-50 translate-middle-y d-flex">
                                        <button id="dropdown_btn" style="margin-right: calc(2 * var(--bs-border-width))"
                                            class="btn btn-outline-secondary text-body dropdown-toggle border-0 rounded-0 rounded-end fw-semibold"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Search by
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-2 rounded-3 mt-1"
                                            style="z-index: 1051;">
                                            @yield('dropdown')
                                        </ul>
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">Choose a search category from the
                                    dropdown</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table with improved responsive handling -->
        <div class="container-fluid table-responsive flex-grow-1 text-nowrap">
            <table class="table table-striped table-hover m-0 overflow-y-scroll" id="table">
                @yield('content')
            </table>
            <div class="mt-3" id="pagination">
                @if (explode('.', Route::currentRouteName())[0] === 'Entry')
                    {{ $entries->links() }}
                @elseif (explode('.', Route::currentRouteName())[0] === 'Customer')
                    {{ $customers->links() }}
                @else
                    {{ $items->links() }}
                @endif
            </div>
        </div>
    </main>
    @yield('modal')
@endsection
