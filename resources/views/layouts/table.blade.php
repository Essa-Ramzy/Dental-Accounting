@extends('layouts.app')

@section('layout')
<main>
    <div class="px-3 py-2 border-bottom">
        <div class="container">
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="search"
                       value="{{ $customer ?? $item ?? '' }}">
                <div class="dropdown">
                    <button id="dropdown_btn" class="btn btn-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Search by
                    </button>
                    <ul class="dropdown-menu">
                        @yield('dropdown')
                    </ul>
                </div>
            </form>
        </div>
    </div>
    <div class="px-3 py-2 border-bottom w-100">
        <div class="container">
            <button type="button" class="btn btn-outline-primary w-100" onclick="window.location='{{ url($view) }}'">
                @yield('button')
            </button>
        </div>
    </div>
    <div class="container-fluid table-responsive">
        <table class="table table-striped">
            @yield('content')
        </table>
    </div>
    @yield('modal')
</main>
<script>
    let dropdown = document.getElementById('dropdown_btn');

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', (e) => {
            dropdown.textContent = e.target.textContent;
            document.getElementById('search').dispatchEvent(new Event('input'));
        });
    });


</script>
@yield('js')
@endsection
