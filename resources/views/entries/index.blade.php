@extends('layouts.table')

@section('content')
<thead>
<tr>
    <th scope="col">#</th>
    <th scope="col">Date</th>
    <th scope="col">Name</th>
    <th scope="col">Item</th>
    <th scope="col">Teeth</th>
    <th scope="col">Amount</th>
    <th scope="col">Discount</th>
    <th scope="col">Price</th>
    <th scope="col">Cost</th>
    <th scope="col"></th>
</tr>
</thead>
<tbody>
@foreach($entries as $entry)
<tr>
    <th scope="row">{{ $entry->id }}</th>
    <td>{{ $entry->date->format('d-m-Y') }}</td>
    <td>{{ $entry->customer()->first()->name }}</td>
    <td>{{ $entry->item()->first()->name }}</td>
    <td>{{ $entry->teeth }}</td>
    <td>{{ strlen(preg_replace('/[^0-9]+/', '', $entry->teeth)) }}</td>
    <td>{{ $entry->discount }}</td>
    <td>{{ $entry->price }}</td>
    <td>{{ $entry->cost }}</td>
    <td>
        <div class="text-end">
            <a class="text-decoration-none" data-bs-toggle="modal" href="#deleteModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF" width="24" height="24" viewBox="0 0 24 24">
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
<li class="dropdown-item">ID</li>
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

@section('button', 'Add Entry')

@section('js')
<script>
    let anchors = document.querySelectorAll('a[href="#deleteModal"]');

    anchors.forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            let id = e.target.closest('tr').querySelector('th').textContent;
            document.querySelector('#deleteModal a').href = `{{ url('/') }}/${id}/delete`;
        });
    });

    let from_date = document.getElementById('from_date');
    let to_date = document.getElementById('to_date');
    let search_field = document.getElementById('search');

    [from_date, to_date, search_field].forEach(input => {
        input.addEventListener('change', () => {
            let search = search_field.value.toLowerCase();
            let filter = dropdown.textContent.toLowerCase();
            let headers = document.querySelectorAll('thead th');
            let footer = document.querySelector('tfoot tr');
            let [visible_rows, total_price, total_cost] = [0, 0, 0];

            rows.forEach(row => {
                let cells = row.querySelectorAll('td, th');
                let date_field = cells[1].textContent.split('-');
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

                if(valid) {
                    row.style.display = '';
                    if (filter === 'all') {
                        row.style.display = 'none';
                        for (let i = 0; i < cells.length; i++) {
                            if (cells[i].textContent.toLowerCase().includes(search)) {
                                row.style.display = '';
                                break;
                            }
                        }
                    } else if (filter === 'id') {
                        if (cells[0].textContent.toLowerCase().includes(search)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
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
                        total_price += parseFloat(cells[7].textContent);
                        total_cost += parseFloat(cells[8].textContent);
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
