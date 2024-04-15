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
            $('#search').on('change', () => {
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
                        $('.table-responsive tbody').html(data['body']);
                        $('.table-responsive tfoot').html(data['footer']);
                        handleDeleteModalClick();
                    }
                });
            });
        });

        $(function() {

            let range = $('#report_range');
            let change_date = (start, end) => {
                range.val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#from_date').val(start.format('YYYY-MM-DD'));
                $('#to_date').val(end.format('YYYY-MM-DD'));
                $('#search').trigger('change');
            };

            range.daterangepicker({
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                parentEl: '#date_range_filter',
                cancelButtonClasses: 'btn-secondary',
                linkedCalendars: false,
                autoUpdateInput: false,
                opens: 'center',
                locale: {
                    cancelLabel: 'Clear'
                },
                alwaysShowCalendars: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, change_date);

            range.on('apply.daterangepicker', (ev, picker) => {
                change_date(picker.startDate, picker.endDate);
            });

            range.on('cancel.daterangepicker', () => {
                range.val('Date Range');
                $('#from_date').val('');
                $('#to_date').val('');
                $('#search').trigger('change');
            });
        });
    </script>
    @yield('js')
@endsection
