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
    <th scope="col">Description</th>
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
    <td>{{ $entry->description }}</td>
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
    <td colspan="2">Total Profit: {{ $entries->sum('price') - $entries->sum('cost') }}</td>
</tr>
</tfoot>
@endsection

@section('dropdown')
<li class="dropdown-item">All</li>
<li class="dropdown-item">ID</li>
<li class="dropdown-item">Date</li>
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

    document.getElementById('search').addEventListener('input', (e) => {
        let search = e.target.value.toLowerCase();
        let filter = dropdown.textContent.toLowerCase();
        if (filter.includes('search by')) return;
        let headers = document.querySelectorAll('thead th');
        let rows = document.querySelectorAll('tbody tr');
        let footer = document.querySelector('tfoot tr').querySelectorAll('th, td');
        let footer_content = [0, 0, 0];

        rows.forEach(row => {
            let cells = row.querySelectorAll('td, th');
            let valid = false;

            if (filter === 'all') {
                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(search)) {
                        row.style.display = '';
                        valid = true;
                        break;
                    }
                }
                if (!valid) row.style.display = 'none';
            } else if (filter === 'id') {
                if (cells[0].textContent.toLowerCase().includes(search)) {
                    row.style.display = '';
                    valid = true;
                } else {
                    row.style.display = 'none';
                }
            } else {
                for (let i = 1; i < cells.length; i++) {
                    if (headers[i].textContent.toLowerCase() === filter) {
                        if (cells[i].textContent.toLowerCase().includes(search)) {
                            row.style.display = '';
                            valid = true;
                            break;
                        }
                    }
                }
                if (!valid) row.style.display = 'none';
            }
            footer_content[0] += valid;
            footer_content[1] += valid ? parseInt(cells[7].textContent) : 0;
            footer_content[2] += valid ? parseInt(cells[8].textContent) : 0;
        });
        footer[0].textContent = `Number of Entries: ${footer_content[0]}`;
        footer[1].textContent = `Total: ${footer_content[1]}`;
        footer[2].textContent = `Total: ${footer_content[2]}`;
        footer[3].textContent = `Total Profit: ${footer_content[1] - footer_content[2]}`;
    });

    window.onload = () => {
        document.getElementById('customer_search')?.click();
        document.getElementById('item_search')?.click();
    };
</script>
@endsection
