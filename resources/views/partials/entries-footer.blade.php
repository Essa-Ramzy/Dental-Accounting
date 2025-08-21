<tr>
    <th scope="row" colspan="8" class="text-center fw-semibold">
        Total Entries: {{ $footer['count'] }}
    </th>
    <td class="text-end fw-bold text-success">
        £
        {{ number_format($footer['total_price'], strlen(rtrim(substr(strrchr($footer['total_price'], '.'), 1), '0'))) }}
    </td>
    <td class="text-end fw-semibold text-muted">
        £
        {{ number_format($footer['total_cost'], strlen(rtrim(substr(strrchr($footer['total_cost'], '.'), 1), '0'))) }}
    </td>
    <td class="text-end">
        <div class="btn-group" role="group" aria-label="Bulk actions">
            @if ($trash)
                <a class="btn btn-sm btn-outline-success border-secondary border-end-0 d-flex align-items-center"
                    data-bs-toggle="modal" href="#restoreModal" aria-label="Restore selected entries">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#arrow-clockwise" fill="currentColor" />
                    </svg>
                </a>
            @else
                <a href="{{ route('Entry.create') }}"
                    class="btn btn-sm btn-outline-success border-secondary border-end-0 d-flex align-items-center"
                    aria-label="Add new entry">
                    <svg width="20" height="20" aria-hidden="true">
                        <use href="#plus-circle" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            @endif
            <a class="btn btn-sm btn-outline-danger border-secondary border-start-0 d-flex align-items-center"
                data-bs-toggle="modal" href="#deleteModal" aria-label="Delete selected entries">
                <svg width="20" height="20" aria-hidden="true">
                    <use href="#trash-fill" fill="currentColor"></use>
                </svg>
            </a>
        </div>
    </td>
</tr>
