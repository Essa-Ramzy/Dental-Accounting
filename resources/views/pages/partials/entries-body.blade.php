@forelse ($entries as $entry)
    <tr class="align-middle">
        <th scope="row" class="text-center text-muted">{{ $entry->id }}</th>
        <td class="text-muted">
            {{ $entry->date->format('M d, Y') }}
        </td>
        <td class="fw-semibold">
            {{ $entry->customer->name }}
        </td>
        <td class="fw-medium">
            {{ $entry->item->name }}
        </td>
        <td class="text-center">
            <span role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                data-bs-content-target="toothTooltip{{ $entry->id }}" class="badge bg-light-subtle text-body border">
                {{ $entry->teeth }}
                <svg width="12" height="12" class="ms-1 opacity-75" aria-hidden="true">
                    <use href="#eye" fill="currentColor" />
                </svg>
            </span>
            <div id="toothTooltip{{ $entry->id }}" class="d-none">
                <div class="text-center p-2">
                    <div class="fw-semibold mb-2">Treated Teeth</div>
                    <div class="tooth-chart mx-auto">
                        @include('components.teeth-visual', ['selectedTeeth' => $entry->teeth_list])
                    </div>
                </div>
            </div>
        </td>
        <td class="text-center">
            <span class="badge bg-primary rounded-pill">{{ $entry->amount }}</span>
        </td>
        <td class="text-end fw-semibold">£
            {{ number_format($entry->unit_price, strlen(rtrim(substr(strrchr($entry->unit_price, '.'), 1), '0'))) }}
        </td>
        <td class="text-end {{ $entry->discount > 0 ? 'text-danger' : 'text-muted' }}">
            {{ $entry->discount > 0 ? '-£ ' . number_format($entry->discount, strlen(rtrim(substr(strrchr($entry->discount, '.'), 1), '0'))) : '£ 0' }}
        </td>
        <td class="text-end fw-bold text-success">£
            {{ number_format($entry->price, strlen(rtrim(substr(strrchr($entry->price, '.'), 1), '0'))) }}
        </td>
        <td class="text-end text-muted">£
            {{ number_format($entry->cost, strlen(rtrim(substr(strrchr($entry->cost, '.'), 1), '0'))) }}
        </td>
        <td class="text-end">
            <div class="btn-group" role="group" aria-label="Entry actions">
                <a href="{{ route('Entry.edit', ['id' => $entry->id]) }}"
                    class="btn btn-sm btn-outline-primary border-secondary border-end-0"
                    aria-label="Edit entry for {{ $entry->customer->name }}">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#pencil-square" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <a href="#deleteModal" data-bs-toggle="modal" id="{{ $entry->id }}"
                    class="btn btn-sm btn-outline-danger border-secondary border-start-0"
                    aria-label="Delete entry for {{ $entry->customer->name }}">
                    <svg width="24" height="24" aria-hidden="true">
                        <use href="#trash-fill" fill="currentColor" />
                    </svg>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="12" class="text-center py-5 text-muted">
            <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                <use href="#journal-medical" fill="currentColor" />
            </svg>
            <div class="h5">No entries found</div>
            <p class="mb-3">Start by adding your first treatment entry.</p>
            <a href="{{ route('Entry.create') }}" class="btn btn-primary">
                <svg width="16" height="16" class="me-1 mb-1" aria-hidden="true">
                    <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Add Entry
            </a>
        </td>
    </tr>
@endforelse
