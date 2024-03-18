@extends('layouts.table')

@section('content')
    <thead>
    <tr>
        <th scope="col">Date</th>
        <th scope="col">Name</th>
        <th scope="col">Item</th>
        <th scope="col">Teeth</th>
        <th scope="col">Amount</th>
        <th scope="col">Unit Price</th>
        <th scope="col">Discount</th>
        <th scope="col">Price</th>
        <th scope="col">Cost</th>
        <th scope="col">
            <div class="dropdown text-end">
                <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false"
                        data-bs-auto-close="outside">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V11C19 11.5523 19.4477 12 20 12C20.5523 12 21 11.5523 21 11V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM10.3078 23.5628C10.4657 23.7575 10.6952 23.9172 10.9846 23.9762C11.2556 24.0316 11.4923 23.981 11.6563 23.9212C11.9581 23.8111 12.1956 23.6035 12.3505 23.4506C12.5941 23.2105 12.8491 22.8848 13.1029 22.5169C14.2122 22.1342 15.7711 21.782 17.287 21.5602C18.1297 21.4368 18.9165 21.3603 19.5789 21.3343C19.8413 21.6432 20.08 21.9094 20.2788 22.1105C20.4032 22.2363 20.5415 22.3671 20.6768 22.4671C20.7378 22.5122 20.8519 22.592 20.999 22.6493C21.0755 22.6791 21.5781 22.871 22.0424 22.4969C22.3156 22.2768 22.5685 22.0304 22.7444 21.7525C22.9212 21.4733 23.0879 21.0471 22.9491 20.5625C22.8131 20.0881 22.4588 19.8221 22.198 19.6848C21.9319 19.5448 21.6329 19.4668 21.3586 19.4187C21.11 19.3751 20.8288 19.3478 20.5233 19.3344C19.9042 18.5615 19.1805 17.6002 18.493 16.6198C17.89 15.76 17.3278 14.904 16.891 14.1587C16.9359 13.9664 16.9734 13.7816 17.0025 13.606C17.0523 13.3052 17.0824 13.004 17.0758 12.7211C17.0695 12.4497 17.0284 12.1229 16.88 11.8177C16.7154 11.4795 16.416 11.1716 15.9682 11.051C15.5664 10.9428 15.1833 11.0239 14.8894 11.1326C14.4359 11.3004 14.1873 11.6726 14.1014 12.0361C14.0288 12.3437 14.0681 12.6407 14.1136 12.8529C14.2076 13.2915 14.4269 13.7956 14.6795 14.2893C14.702 14.3332 14.7251 14.3777 14.7487 14.4225C14.5103 15.2072 14.1578 16.1328 13.7392 17.0899C13.1256 18.4929 12.4055 19.8836 11.7853 20.878C11.3619 21.0554 10.9712 21.2584 10.6746 21.4916C10.4726 21.6505 10.2019 21.909 10.0724 22.2868C9.9132 22.7514 10.0261 23.2154 10.3078 23.5628ZM11.8757 23.0947C11.8755 23.0946 11.8775 23.0923 11.8824 23.0877C11.8783 23.0924 11.8759 23.0947 11.8757 23.0947ZM16.9974 19.5812C16.1835 19.7003 15.3445 19.8566 14.5498 20.0392C14.9041 19.3523 15.2529 18.6201 15.5716 17.8914C15.7526 17.4775 15.9269 17.0581 16.0885 16.6431C16.336 17.0175 16.5942 17.3956 16.8555 17.7681C17.2581 18.3421 17.6734 18.911 18.0759 19.4437C17.7186 19.4822 17.3567 19.5287 16.9974 19.5812ZM16.0609 12.3842C16.0608 12.3829 16.0607 12.3823 16.0606 12.3823C16.0606 12.3822 16.0607 12.3838 16.061 12.3872C16.061 12.386 16.0609 12.385 16.0609 12.3842Z"
                              fill="#000000"/>
                    </svg>
                </button>
                <div class="dropdown-menu py-2 px-3">
                    <form action="{{ route('Entry.export') }}" method="post" class="top-0" enctype="multipart/form-data" target="_blank">
                        @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="date" value="Date"
                                   checked name="date">
                            <label class="form-check-label" for="date">Date</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="name" class="form-check-input" type="checkbox" role="switch" id="name"
                                   value="Name" checked>
                            <label class="form-check-label" for="name">Name</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="item" class="form-check-input" type="checkbox" role="switch" id="item"
                                   value="Item" checked>
                            <label class="form-check-label" for="item">Item</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="teeth" class="form-check-input" type="checkbox" role="switch" id="teeth"
                                   value="Teeth" checked>
                            <label class="form-check-label" for="teeth">Teeth</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="amount" class="form-check-input" type="checkbox" role="switch" id="amount"
                                   value="Amount" checked>
                            <label class="form-check-label" for="amount">Amount</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="unit_price" class="form-check-input" type="checkbox" role="switch"
                                   id="unit_price" value="Unit Price" checked>
                            <label class="form-check-label" for="unit_price">Unit Price</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="discount" class="form-check-input" type="checkbox" role="switch" id="discount"
                                   value="Discount" checked>
                            <label class="form-check-label" for="discount">Discount</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="price" class="form-check-input" type="checkbox" role="switch" id="price"
                                   value="Price" checked>
                            <label class="form-check-label" for="price">Price</label>
                        </div>
                        <div class="form-check form-switch">
                            <input name="cost" class="form-check-input" type="checkbox" role="switch" id="cost"
                                   value="Cost" checked>
                            <label class="form-check-label" for="cost">Cost</label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-outline-success w-100 mt-2">Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($entries as $entry)
        <tr>
            <td>{{ $entry->date->format('d-m-Y') }}</td>
            <td>{{ $entry->customer->name }}</td>
            <td>{{ $entry->item->name }}</td>
            <td>{{ $entry->teeth }}</td>
            <td>{{ $amount = strlen(preg_replace('/[^0-9]+/', '', $entry->teeth)) }}</td>
            <td>{{ $entry->price / $amount }}</td>
            <td>{{ $entry->discount }}</td>
            <td>{{ $entry->price }}</td>
            <td>{{ $entry->cost }}</td>
            <td>
                <div class="text-end">
                    <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal" id="{{ $entry->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#6C757D" width="24" height="24"
                             viewBox="0 0 24 24">
                            <path
                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th scope="row" colspan="7" class="text-md-center">Number of Entries: {{ $entries->count() }}</th>
        <td>Total: {{ $entries->sum('price') }}</td>
        <td>Total: {{ $entries->sum('cost') }}</td>
        <td></td>
    </tr>
    </tfoot>
@endsection

@section('dropdown')
    <li class="dropdown-item">All</li>
    <li class="dropdown-item" id="{{ isset($customer) ? 'customer_search' : '' }}">Name</li>
    <li class="dropdown-item" id="{{ isset($item) ? 'item_search' : '' }}">Item</li>
    <li class="dropdown-item">Amount</li>
    <li class="dropdown-item">Discount</li>
    <li class="dropdown-item">Price</li>
    <li class="dropdown-item">Cost</li>
@endsection

@section('modal')
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="Delete" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Delete This Entry?</h5>
                    <p class="mb-0">You can always change your mind. Are you Sure?</p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    <a type="button"
                       class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end">
                        <strong>Yes, Delete</strong></a>
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0"
                            data-bs-dismiss="modal">No thanks
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let anchors = document.querySelectorAll('a[href="#deleteModal"]');

        anchors.forEach(anchor => {
            anchor.addEventListener('click', () => {
                document.querySelector('#deleteModal a').href = `{{ url('/') }}/${anchor.id}/delete`;
            });
        });

        let from_date = document.getElementById('from_date');
        let to_date = document.getElementById('to_date');

        [from_date, to_date, search_field].forEach(input => {
            input.addEventListener('change', () => {
                let search = search_field.value.toLowerCase();
                let filter = dropdown.textContent.toLowerCase();
                let headers = document.querySelectorAll('thead th');
                let footer = document.querySelector('tfoot tr');
                let [visible_rows, total_price, total_cost] = [0, 0, 0];

                rows.forEach(row => {
                    let cells = row.querySelectorAll('td, th');
                    let date_field = cells[0].textContent.split('-');
                    let date = new Date(date_field[1] + '-' + date_field[0] + '-' + date_field[2]);
                    let from = new Date(from_date.value), to = new Date(to_date.value);
                    let valid = true;

                    from.setHours(0, 0, 0, 0);
                    to.setHours(0, 0, 0, 0);

                    if (from_date.value && to_date.value)
                        valid = date >= from && date <= to;
                    else if (from_date.value)
                        valid = date >= from;
                    else if (to_date.value)
                        valid = date <= to;

                    if (valid) {
                        row.style.display = '';
                        if (filter === 'all') {
                            row.style.display = 'none';
                            for (let i = 0; i < cells.length; i++) {
                                if (cells[i].textContent.toLowerCase().includes(search)) {
                                    row.style.display = '';
                                    break;
                                }
                            }
                        } else if (!filter.includes('search by')) {
                            row.style.display = 'none';
                            for (let i = 1; i < cells.length; i++) {
                                if (headers[i].textContent.toLowerCase() === filter) {
                                    if (cells[i].textContent.toLowerCase().includes(search)) {
                                        row.style.display = '';
                                        break;
                                    }
                                }
                            }
                        }
                        if (row.style.display !== 'none') {
                            visible_rows++;
                            total_price += parseFloat(cells[6].textContent);
                            total_cost += parseFloat(cells[7].textContent);
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });
                footer.querySelector('th').textContent = `Number of Entries: ${visible_rows}`;
                footer.querySelector('td:nth-child(2)').textContent = `Total: ${total_price}`;
                footer.querySelector('td:nth-child(3)').textContent = `Total: ${total_cost}`;
            });
        });

        search_field.addEventListener('input', () => {
            search_field.dispatchEvent(new Event('change'));
        });

        window.onload = () => {
            document.getElementById('customer_search')?.click();
            document.getElementById('item_search')?.click();
        };
    </script>
@endsection
