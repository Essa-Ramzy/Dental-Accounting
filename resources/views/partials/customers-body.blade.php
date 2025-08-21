@forelse ($customers as $customer)
    <tr class="align-middle">
        <th scope="row" class="text-center text-muted">{{ $customer->id }}</th>
        <td class="text-muted">
            @if ($trash)
                {{ $customer->deleted_at->format('M d, Y H:i') }}
            @else
                {{ $customer->updated_at->format('M d, Y') }}
            @endif
        </td>
        <td>
            <div class="fw-semibold">{{ $customer->name }}</div>
        </td>
        @if (!$trash)
            <td class="text-center">
                <a href="{{ route('Customer.records', ['id' => $customer->id]) }}" class="btn btn-sm btn-outline-primary">
                    <span class="badge bg-primary rounded-pill me-1">{{ $customer->entries_count }}</span>
                    View Records
                </a>
            </td>
        @endif
        <td class="text-center">
            <div class="btn-group" role="group" aria-label="Customer actions">
                @if ($trash)
                    <a href="#restoreModal" data-bs-toggle="modal" id="{{ $customer->id }}"
                        class="btn btn-sm btn-outline-success border-secondary border-end-0 d-flex align-items-center d-flex align-items-center"
                        aria-label="Restore {{ $customer->name }}">
                        <svg width="20" height="20" aria-hidden="true">
                            <use href="#arrow-clockwise" fill="currentColor" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('Customer.edit', ['id' => $customer->id]) }}"
                        class="btn btn-sm btn-outline-primary border-secondary border-end-0 d-flex align-items-center"
                        aria-label="Edit {{ $customer->name }}">
                        <svg width="20" height="20" aria-hidden="true">
                            <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                @endif
                <a href="#deleteModal" data-bs-toggle="modal" id="{{ $customer->id }}"
                    class="btn btn-sm btn-outline-danger border-secondary border-start-0 d-flex align-items-center"
                    aria-label="Delete {{ $customer->name }}">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#trash-fill" fill="currentColor" />
                    </svg>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="{{ $trash ? 4 : 5 }}" class="text-center py-5 text-muted">
            <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                <use href="#people-circle" fill="currentColor" />
            </svg>
            <div class="h5">No customers found</div>
            @if ($trash)
                <p class="mb-3">Your trash is empty.</p>
                <a href="{{ route('Customer.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" aria-hidden="true">
                        <use href="#box-arrow-in-right" fill="currentColor" />
                    </svg>
                    Go to Customers
                </a>
            @else
                <p class="mb-3">Get started by adding your first customer.</p>
                <a href="{{ route('Customer.create') }}"
                    class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" aria-hidden="true">
                        <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Add Customer
                </a>
            @endif
        </td>
    </tr>
@endforelse
