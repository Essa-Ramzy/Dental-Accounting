<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clinic Accounting</title>
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/bootstrap-select.min.css') }}">
    <script src="{{ asset('resources/js/jquery.min.js') }}"></script>
    <script src="{{ asset('resources/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('resources/js/bootstrap-select.min.js') }}"></script>
</head>

<body class="antialiased min-vh-100 d-flex flex-column">
<header class="px-3 py-2 text-bg-dark border-bottom">
    <nav class="container">
        <div class="nav d-flex flex-wrap align-items-center justify-content-center">
            <a href="{{ url('/') }}"
               class="nav-link d-flex align-items-center my-2 my-lg-0 me-sm-auto me-0 text-white text-decoration-none">
                <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" class="bi"
                     width="40" height="32" role="img" aria-label="Bootstrap">
                    <defs>
                        <symbol id="bootstrap" viewBox="0 0 118 94">
                            <title>Home</title>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z"></path>
                        </symbol>
                    </defs>
                    <use xlink:href="#bootstrap" fill="#FFFFFF"></use>
                </svg>
            </a>

            <ul class="nav col-12 col-sm-auto my-2 justify-content-center my-md-0 text-small">
                <li>
                    <a href="{{ url('/') }}"
                       class="nav-link {{Illuminate\Support\Facades\Route::is('home')? 'text-secondary' : 'text-white'}}">
                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                             class="bi d-block mx-auto mb-1" width="24" height="24">
                            <defs>
                                <symbol id="home" viewBox="0 0 16 16">
                                    <path
                                        d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"></path>
                                </symbol>
                            </defs>
                            <use xlink:href="#home"
                                 fill="#{{Illuminate\Support\Facades\Route::is('home')? '6C757D' : 'FFFFFF'}}"></use>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ url('/items') }}"
                       class="nav-link {{Illuminate\Support\Facades\Route::is('items')? 'text-secondary' : 'text-white'}}">
                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                             class="bi d-block mx-auto mb-1" width="24" height="24">
                            <defs>
                                <symbol id="grid" viewBox="0 0 16 16">
                                    <path
                                        d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"></path>
                                </symbol>
                            </defs>
                            <use xlink:href="#grid"
                                 fill="#{{Illuminate\Support\Facades\Route::is('items')? '6C757D' : 'FFFFFF'}}"></use>
                        </svg>
                        Items
                    </a>
                </li>
                <li>
                    <a href="{{ url('/customers') }}"
                       class="nav-link {{Illuminate\Support\Facades\Route::is('customers')? 'text-secondary' : 'text-white'}}">
                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"
                             class="bi d-block mx-auto mb-1" width="24" height="24">
                            <defs>
                                <symbol id="people-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                                    <path fill-rule="evenodd"
                                          d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"></path>
                                </symbol>
                            </defs>
                            <use xlink:href="#people-circle"
                                 fill="#{{Illuminate\Support\Facades\Route::is('customers')? '6C757D' : 'FFFFFF'}}"></use>
                        </svg>
                        Customers
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
@yield('layout')
</body>
</html>
