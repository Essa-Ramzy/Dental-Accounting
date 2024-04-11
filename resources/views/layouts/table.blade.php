@extends('layouts.app_with_header')
<!-- This is the layout for the table view -->
@section('layout')
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
    <script>
        $('.dropdown-item').on('click', e => {
            $('#dropdown_btn').text(e.target.textContent);
            $('#search').trigger('change');
        });

        $('#search').on('input', () => {
            $('#search').trigger('change');
        });

        let handleDeleteModalClick = () => {
            $('a[href="#deleteModal"]').on('click', e => {
                let search = $('#search').val().toLowerCase();
                let filter = $('#dropdown_btn').text().toLowerCase().replace(' ', '_');
                if (e.currentTarget.id) {
                    search = e.currentTarget.id;
                    filter = 'id';
                }
                let from_date = $('#from_date').val();
                let to_date = $('#to_date').val();

                $('input[aria-label="delete_filter"]').val(!filter.includes('search_by') ? filter : '');
                $('input[aria-label="delete_search"]').val(!filter.includes('search_by') && search ? search : '');
                $('input[aria-label="delete_from_date"]').val(from_date ? from_date + ' 00:00:00' : '');
                $('input[aria-label="delete_to_date"]').val(to_date ? to_date + ' 23:59:59' : '');
            });
        };

        handleDeleteModalClick();

        $(document).ready(function () {
            $('#search, #from_date, #to_date').on('change', () => {
                let search = $('#search').val().toLowerCase();
                let filter = $('#dropdown_btn').text().toLowerCase().replace(' ', '_');
                let from_date = $('#from_date').val();
                let to_date = $('#to_date').val();
                $.ajax({
                    url: '{{ route($view . '.search') }}',
                    type: 'GET',
                    data: {
                        search: !filter.includes('search_by') && search ? search : '',
                        filter: !filter.includes('search_by') ? filter : '',
                        from_date: from_date ? from_date + ' 00:00:00' : '',
                        to_date: to_date ? to_date + ' 23:59:59' : ''
                    },
                    success: function (data) {
                        $('tbody').html(data['body']);
                        $('tfoot').html(data['footer']);
                        handleDeleteModalClick();
                    }
                });
            });
        });
    </script>
    @yield('js')
@endsection
