@extends('layouts.app')

@section('layout')
<main class="d-flex flex-column flex-grow-1">
    <div class="px-3 py-2 border-bottom w-100">
        <div class="container d-flex">
            <div class="container w-100">
                <a type="button" class="btn btn-outline-primary w-100" href="{{ url($view) }}">
                    @yield('button')
                </a>
            </div>
            <div class="dropdown w-auto">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-offset="0,7"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">Filter
                </button>
                <div class="dropdown-menu py-2 px-3 vw-100">
                    <div class="row mb-2">
                        <div class="form-group col-md-6">
                            <label for="from_date">From Date</label>
                            <input class="form-control" type="date" id="from_date">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="to_date">To Date</label>
                            <input class="form-control" type="date" id="to_date">
                        </div>
                    </div>
                    <div class="form-group me-2 w-100 d-flex align-items-end">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                               id="search" value="{{ $customer ?? $item ?? '' }}">
                        <div class="dropdown">
                            <button id="dropdown_btn" class="btn btn-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">Search by
                            </button>
                            <ul class="dropdown-menu ">
                                @yield('dropdown')
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid table-responsive overflow-y-hidden flex-grow-1">
        <table class="table table-striped m-0">
            @yield('content')
        </table>
    </div>
    @yield('modal')
</main>
<script>
    let dropdown = document.getElementById('dropdown_btn');
    let search_field = document.getElementById('search');
    let rows = document.querySelectorAll('tbody tr');


    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            dropdown.textContent = e.target.textContent;
            document.getElementById('search').dispatchEvent(new Event('input'));
        });
    });

    document.getElementById('search').addEventListener('input', () => {
        search_field.dispatchEvent(new Event('change'));
    });
</script>
@yield('js')
@endsection
