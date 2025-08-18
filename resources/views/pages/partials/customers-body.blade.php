@forelse ($customers as $customer)
    <tr class="align-middle">
        <th scope="row" class="text-center text-muted">{{ $customer->id }}</th>
        <td class="text-muted">
            {{ $customer->updated_at->format('M d, Y') }}
        </td>
        <td>
            <div class="fw-semibold">{{ $customer->name }}</div>
        </td>
        <td class="text-center">
            <a href="{{ route('Customer.records', ['id' => $customer->id]) }}" class="btn btn-sm btn-outline-primary">
                <span class="badge bg-primary rounded-pill me-1">{{ $customer->entries_count }}</span>
                View Records
            </a>
        </td>
        <td class="text-center">
            <div class="btn-group" role="group" aria-label="Customer actions">
                <a href="{{ route('Customer.edit', ['id' => $customer->id]) }}"
                    class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                    aria-label="Edit {{ $customer->name }}">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="#deleteModal" data-bs-toggle="modal" id="{{ $customer->id }}"
                    class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                    aria-label="Delete {{ $customer->name }}">
                    <svg width="24" height="24" aria-hidden="true">
                        <use href="#trash-fill" fill="currentColor" />
                    </svg>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                <use href="#people-circle" fill="currentColor" />
            </svg>
            <div class="h5">No customers found</div>
            <p class="mb-3">Get started by adding your first customer.</p>
            <a href="{{ route('Customer.create') }}" class="btn btn-primary">
                <svg width="16" height="16" class="me-1 mb-1" aria-hidden="true">
                    <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Add Customer
            </a>
        </td>
    </tr>
@endforelse
