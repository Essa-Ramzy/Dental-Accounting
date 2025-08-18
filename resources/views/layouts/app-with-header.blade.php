@extends('layouts.app')
@section('app_content')
    <!-- This is the main layout with a header for the web application -->
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="bootstrap" viewBox="0 0 118 94">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z">
            </path>
        </symbol>
        <symbol id="home" viewBox="0 0 16 16">
            <path
                d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z">
            </path>
        </symbol>
        <symbol id="grid" viewBox="0 0 16 16">
            <path
                d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z">
            </path>
        </symbol>
        <symbol id="tags" viewBox="0 0 16 16">
            <path
                d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z">
            </path>
            <path
                d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z">
            </path>
        </symbol>
        <symbol id="people-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
            <path fill-rule="evenodd"
                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z">
            </path>
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path
                d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z">
            </path>
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z">
            </path>
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z">
            </path>
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
        </symbol>
        <symbol id="check-lg" viewBox="0 0 16 16">
            <path
                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z">
            </path>
        </symbol>
        @yield('svg-icons')
    </svg>
    <!-- Header (navigation bar) for the web application -->
    <header
        class="navbar navbar-expand-lg navbar-dark bg-dark {{ Illuminate\Support\Facades\Route::is('Home') ? '' : 'border-bottom' }}">
        <div class="container-fluid">
            <!-- Brand/Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('Home') }}"
                aria-label="Dental Clinic Accounting - Home">
                <svg class="me-2 align-middle" width="30" height="24" role="img" aria-hidden="true">
                    <use href="#bootstrap" fill="currentColor" />
                </svg>
                <span class="fw-bold align-middle">Dental Clinic</span>
            </a>

            <!-- Mobile menu toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link{{ Illuminate\Support\Facades\Route::is('Home') ? ' active' : '' }}"
                            href="{{ route('Home') }}" aria-label="Dashboard">
                            <svg class="me-1 align-middle" width="16" height="16" role="img"
                                aria-hidden="true">
                                <use href="#home" fill="currentColor" />
                            </svg>
                            <span class="align-middle">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ Illuminate\Support\Facades\Route::is('Entries') ? ' active' : '' }}"
                            href="{{ route('Entries') }}" aria-label="View Entries">
                            <svg class="me-1 align-middle" width="16" height="16" role="img"
                                aria-hidden="true">
                                <use href="#grid" fill="currentColor" />
                            </svg>
                            <span class="align-middle">Entries</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ Illuminate\Support\Facades\Route::is('Items') ? ' active' : '' }}"
                            href="{{ route('Items') }}" aria-label="View Items">
                            <svg class="me-1 align-middle" width="16" height="16" role="img"
                                aria-hidden="true">
                                <use href="#tags" fill="currentColor" />
                            </svg>
                            <span class="align-middle">Items</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ Illuminate\Support\Facades\Route::is('Customers') ? ' active' : '' }}"
                            href="{{ route('Customers') }}" aria-label="View Customers">
                            <svg class="me-1 align-middle" width="16" height="16" role="img"
                                aria-hidden="true">
                                <use href="#people-circle" fill="currentColor" />
                            </svg>
                            <span class="align-middle">Customers</span>
                        </a>
                    </li>
                </ul>

                <!-- Theme toggle dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="bd-theme"
                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle theme">
                        <svg class="me-1 mb-1" width="16" height="16" role="img" aria-hidden="true">
                            <use id="bd-theme-icon" href="#circle-half" fill="currentColor" />
                        </svg>
                        <span id="bd-theme-text">Auto</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start dropdown-menu-lg-end p-1" aria-labelledby="bd-theme">
                        <li>
                            <button type="button" class="dropdown-item rounded d-flex align-items-center"
                                data-bs-theme-value="light" aria-pressed="false">
                                <svg class="me-2 mb-0" width="16" height="16" role="img" aria-hidden="true">
                                    <use href="#sun-fill" fill="currentColor" />
                                </svg>
                                Light
                                <svg class="ms-auto d-none mb-0" width="16" height="16" role="img"
                                    aria-hidden="true">
                                    <use href="#check-lg" fill="currentColor" />
                                </svg>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item rounded d-flex align-items-center"
                                data-bs-theme-value="dark" aria-pressed="false">
                                <svg class="me-2 mb-0" width="16" height="16" role="img" aria-hidden="true">
                                    <use href="#moon-stars-fill" fill="currentColor" />
                                </svg>
                                Dark
                                <svg class="ms-auto d-none mb-0" width="16" height="16" role="img"
                                    aria-hidden="true">
                                    <use href="#check-lg" fill="currentColor" />
                                </svg>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item rounded d-flex align-items-center active"
                                data-bs-theme-value="auto" aria-pressed="true">
                                <svg class="me-2 mb-0" width="16" height="16" role="img" aria-hidden="true">
                                    <use href="#circle-half" fill="currentColor" />
                                </svg>
                                Auto
                                <svg class="ms-auto d-none mb-0" width="16" height="16" role="img"
                                    aria-hidden="true">
                                    <use href="#check-lg" fill="currentColor" />
                                </svg>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    @yield('layout')
@endsection
