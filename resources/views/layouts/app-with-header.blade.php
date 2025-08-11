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
        <symbol id="check2" viewBox="0 0 16 16">
            <path
                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z">
            </path>
        </symbol>
        <symbol id="file-pdf" viewBox="0 0 24 24">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V11C19 11.5523 19.4477 12 20 12C20.5523 12 21 11.5523 21 11V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM10.3078 23.5628C10.4657 23.7575 10.6952 23.9172 10.9846 23.9762C11.2556 24.0316 11.4923 23.981 11.6563 23.9212C11.9581 23.8111 12.1956 23.6035 12.3505 23.4506C12.5941 23.2105 12.8491 22.8848 13.1029 22.5169C14.2122 22.1342 15.7711 21.782 17.287 21.5602C18.1297 21.4368 18.9165 21.3603 19.5789 21.3343C19.8413 21.6432 20.08 21.9094 20.2788 22.1105C20.4032 22.2363 20.5415 22.3671 20.6768 22.4671C20.7378 22.5122 20.8519 22.592 20.999 22.6493C21.0755 22.6791 21.5781 22.871 22.0424 22.4969C22.3156 22.2768 22.5685 22.0304 22.7444 21.7525C22.9212 21.4733 23.0879 21.0471 22.9491 20.5625C22.8131 20.0881 22.4588 19.8221 22.198 19.6848C21.9319 19.5448 21.6329 19.4668 21.3586 19.4187C21.11 19.3751 20.8288 19.3478 20.5233 19.3344C19.9042 18.5615 19.1805 17.6002 18.493 16.6198C17.89 15.76 17.3278 14.904 16.891 14.1587C16.9359 13.9664 16.9734 13.7816 17.0025 13.606C17.0523 13.3052 17.0824 13.004 17.0758 12.7211C17.0695 12.4497 17.0284 12.1229 16.88 11.8177C16.7154 11.4795 16.416 11.1716 15.9682 11.051C15.5664 10.9428 15.1833 11.0239 14.8894 11.1326C14.4359 11.3004 14.1873 11.6726 14.1014 12.0361C14.0288 12.3437 14.0681 12.6407 14.1136 12.8529C14.2076 13.2915 14.4269 13.7956 14.6795 14.2893C14.702 14.3332 14.7251 14.3777 14.7487 14.4225C14.5103 15.2072 14.1578 16.1328 13.7392 17.0899C13.1256 18.4929 12.4055 19.8836 11.7853 20.878C11.3619 21.0554 10.9712 21.2584 10.6746 21.4916C10.4726 21.6505 10.2019 21.909 10.0724 22.2868C9.9132 22.7514 10.0261 23.2154 10.3078 23.5628ZM11.8757 23.0947C11.8755 23.0946 11.8775 23.0923 11.8824 23.0877C11.8783 23.0924 11.8759 23.0947 11.8757 23.0947ZM16.9974 19.5812C16.1835 19.7003 15.3445 19.8566 14.5498 20.0392C14.9041 19.3523 15.2529 18.6201 15.5716 17.8914C15.7526 17.4775 15.9269 17.0581 16.0885 16.6431C16.336 17.0175 16.5942 17.3956 16.8555 17.7681C17.2581 18.3421 17.6734 18.911 18.0759 19.4437C17.7186 19.4822 17.3567 19.5287 16.9974 19.5812ZM16.0609 12.3842C16.0608 12.3829 16.0607 12.3823 16.0606 12.3823C16.0606 12.3822 16.0607 12.3838 16.061 12.3872C16.061 12.386 16.0609 12.385 16.0609 12.3842Z">
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
    </svg>
    <!-- Header (navigation bar) for the web application -->
    <header class="px-3 py-2 text-bg-dark border-bottom">
        <nav class="container">
            <!-- Application logo and navigation links -->
            <div class="nav d-flex flex-wrap align-items-center justify-content-center">
                <!-- Application logo -->
                <a href="{{ route('Home') }}"
                    class="nav-link d-flex align-items-center my-2 my-lg-0 me-sm-auto me-0 text-white text-decoration-none">
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" class="bi"
                        width="40" height="32" role="img" aria-label="Bootstrap">
                        <title>Home</title>
                        <use href="#bootstrap" fill="#FFFFFF"></use>
                    </svg>
                </a>
                <!-- Navigation links -->
                <ul class="nav col-12 col-sm-auto my-2 justify-content-center my-md-0 text-small">
                    <li>
                        <a href="{{ route('Home') }}"
                            class="nav-link {{ Illuminate\Support\Facades\Route::is('Home') ? 'text-secondary' : 'text-white' }}">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                class="bi d-block mx-auto mb-1" width="24" height="24">
                                <use href="#home"
                                    fill="#{{ Illuminate\Support\Facades\Route::is('Home') ? '6C757D' : 'FFFFFF' }}">
                                </use>
                            </svg>
                            Home
                        </a>
                    </li>
                    <!-- Entries link -->
                    <li>
                        <a href="{{ route('Entries') }}"
                            class="nav-link {{ Illuminate\Support\Facades\Route::is('Entries') ? 'text-secondary' : 'text-white' }}">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                class="bi d-block mx-auto mb-1" width="24" height="24">
                                <use href="#grid"
                                    fill="#{{ Illuminate\Support\Facades\Route::is('Entries') ? '6C757D' : 'FFFFFF' }}">
                                </use>
                            </svg>
                            Entries
                        </a>
                    </li>
                    <!-- Items link -->
                    <li>
                        <a href="{{ route('Items') }}"
                            class="nav-link {{ Illuminate\Support\Facades\Route::is('Items') ? 'text-secondary' : 'text-white' }}">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                class="bi d-block mx-auto mb-1" width="24" height="24">
                                <use href="#tags"
                                    fill="#{{ Illuminate\Support\Facades\Route::is('Items') ? '6C757D' : 'FFFFFF' }}">
                                </use>
                            </svg>
                            Items
                        </a>
                    </li>
                    <!-- Customers link -->
                    <li>
                        <a href="{{ route('Customers') }}"
                            class="nav-link {{ Illuminate\Support\Facades\Route::is('Customers') ? 'text-secondary' : 'text-white' }}">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                                class="bi d-block mx-auto mb-1" width="24" height="24">
                                <use href="#people-circle"
                                    fill="#{{ Illuminate\Support\Facades\Route::is('Customers') ? '6C757D' : 'FFFFFF' }}">
                                </use>
                            </svg>
                            Customers
                        </a>
                    </li>
                    <!-- Theme switcher -->
                    <li class="dropdown">
                        <button class="btn btn-link nav-link px-3 py-2 d-flex flex-column align-items-center text-white"
                            id="bd-theme" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            aria-label="Toggle theme (dark)">
                            <svg class="bi mb-1 theme-icon-active" width="24" height="24" fill="currentColor"
                                aria-hidden="true">
                                <use href="#moon-stars-fill" id="bd-theme-icon"></use>
                            </svg>
                            <span class="d-inline-flex align-items-center dropdown-toggle" id="bd-theme-text">
                                Toggle theme
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-1" aria-labelledby="bd-theme-text"
                            data-bs-popper="static">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="light" aria-pressed="false">
                                    <svg class="bi me-2 opacity-50" width="16" height="16"
                                        fill="var(--bs-body-color)" aria-hidden="true">
                                        <use href="#sun-fill"></use>
                                    </svg>
                                    Light
                                    <svg class="bi ms-auto d-none" width="16" height="16"
                                        fill="var(--bs-body-color)" aria-hidden="true">
                                        <use href="#check2"></use>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active"
                                    data-bs-theme-value="dark" aria-pressed="true">
                                    <svg class="bi me-2 opacity-50" width="16" height="16"
                                        fill="var(--bs-body-color)" aria-hidden="true">
                                        <use href="#moon-stars-fill"></use>
                                    </svg>
                                    Dark
                                    <svg class="bi ms-auto d-none" width="16" height="16"
                                        fill="var(--bs-body-color)" aria-hidden="true">
                                        <use href="#check2"></use>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="auto" aria-pressed="false">
                                    <svg class="bi me-2 opacity-50" width="16" height="16"
                                        fill="var(--bs-body-color)" aria-hidden="true">
                                        <use href="#circle-half"></use>
                                    </svg>
                                    Auto
                                    <svg class="bi ms-auto d-none" width="16" height="16"
                                        fill="var(--bs-body-color)" aria-hidden="true">
                                        <use href="#check2"></use>
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    @yield('layout')
@endsection
