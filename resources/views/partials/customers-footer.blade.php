<tr>
    <th scope="row" colspan="{{ $trash ? 3 : 4 }}" class="text-center fw-semibold">
        Total Customers: {{ $customers->total() }}
    </th>
    <td class="text-center">
        <div class="btn-group" role="group" aria-label="Customer actions">
            @if ($trash)
                <a class="btn btn-sm btn-outline-success border-secondary border-end-0 d-flex align-items-center"
                    data-bs-toggle="modal" href="#restoreModal" aria-label="Restore selected entries">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#arrow-clockwise" fill="currentColor" />
                    </svg>
                </a>
            @else
                <a href="{{ route('Customer.create') }}"
                    class="btn btn-sm btn-outline-success border-secondary border-end-0 d-flex align-items-center"
                    aria-label="Add new customer">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            @endif
            <a class="btn btn-sm btn-outline-danger border-secondary border-start-0 d-flex align-items-center"
                data-bs-toggle="modal" href="#deleteModal">
                <svg width="20" height="20" aria-hidden="true">
                    <use href="#trash-fill" fill="currentColor"></use>
                </svg>
            </a>
        </div>
    </td>
</tr>
