@extends('layouts.app')
<!-- This is the PDF layout for the entries table -->
@section('app_content')
    <main class="container-fluid">
        <!-- PDF Header -->
        <div class="pdf-header mb-2">
            <div class="row align-items-center">
                <div class="col-md-10">
                    <h1 class="h3 mb-1 text-primary fw-bold">Dental Clinic Entries Report</h1>
                    <p class="text-muted mb-0">Generated on {{ date('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="col-md-2 text-md-end">
                    <div class="border rounded px-3 py-2 my-2">
                        <div class="small text-muted">Total Entries</div>
                        <div class="h4 mb-0 text-primary">{{ $entries->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-sm mb-0 text-nowrap">
                <thead>
                    <tr>
                        <!-- Set the chosen columns to be displayed -->
                        @if (isset($columns['id']))
                            <th scope="col" class="text-center">#</th>
                        @endif
                        @if (isset($columns['date']))
                            <th scope="col">Date</th>
                        @endif
                        @if (isset($columns['name']))
                            <th scope="col" class="">Patient Name</th>
                        @endif
                        @if (isset($columns['item']))
                            <th scope="col">Treatment</th>
                        @endif
                        @if (isset($columns['teeth']))
                            <th scope="col" class="text-center">Teeth</th>
                        @endif
                        @if (isset($columns['amount']))
                            <th scope="col" class="text-center">Qty</th>
                        @endif
                        @if (isset($columns['unit_price']))
                            <th scope="col" class="text-end">Unit Price</th>
                        @endif
                        @if (isset($columns['discount']))
                            <th scope="col" class="text-end">Discount</th>
                        @endif
                        @if (isset($columns['price']))
                            <th scope="col" class="text-end">Total</th>
                        @endif
                        @if (isset($columns['cost']))
                            <th scope="col" class="text-end">Cost</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through the entries and display the data -->
                    @forelse ($entries as $entry)
                        <tr class="align-middle">
                            <!-- Set the chosen columns to be displayed -->
                            @if (isset($columns['id']))
                                <th scope="row" class="text-center text-muted">{{ $entry->id }}</th>
                            @endif
                            @if (isset($columns['date']))
                                <td class="text-muted">{{ $entry->date->format('M d, Y') }}</td>
                            @endif
                            @if (isset($columns['name']))
                                <td class="fw-semibold">{{ $entry->customer->name }}</td>
                            @endif
                            @if (isset($columns['item']))
                                <td class="fw-medium">{{ $entry->item->name }}</td>
                            @endif
                            @if (isset($columns['teeth']))
                                <td class="text-center">
                                    <span class="badge bg-light-subtle text-body border">{{ $entry->teeth }}</span>
                                </td>
                            @endif
                            @if (isset($columns['amount']))
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ $entry->amount }}</span>
                                </td>
                            @endif
                            @if (isset($columns['unit_price']))
                                <td class="text-end fw-semibold" style="font-family: 'Courier New', monospace;">
                                    £{{ number_format($entry->unit_price, strlen(rtrim(substr(strrchr($entry->unit_price, '.'), 1), '0'))) }}
                                </td>
                            @endif
                            @if (isset($columns['discount']))
                                <td class="text-end {{ $entry->discount > 0 ? 'text-danger' : 'text-muted' }}"
                                    style="font-family: 'Courier New', monospace;">
                                    {{ $entry->discount > 0 ? '-£' . number_format($entry->discount, strlen(rtrim(substr(strrchr($entry->discount, '.'), 1), '0'))) : '£0' }}
                                </td>
                            @endif
                            @if (isset($columns['price']))
                                <td class="text-end fw-bold text-success" style="font-family: 'Courier New', monospace;">
                                    £{{ number_format($entry->price, strlen(rtrim(substr(strrchr($entry->price, '.'), 1), '0'))) }}
                                </td>
                            @endif
                            @if (isset($columns['cost']))
                                <td class="text-end text-muted" style="font-family: 'Courier New', monospace;">
                                    £{{ number_format($entry->cost, strlen(rtrim(substr(strrchr($entry->cost, '.'), 1), '0'))) }}
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-5 text-muted">
                                <svg width="48" height="48" class="mb-3 text-muted" aria-hidden="true">
                                    <use href="#journal-medical" fill="currentColor" />
                                </svg>
                                <div class="h5">No entries found</div>
                                <p class="mb-3">Start by adding your first treatment entry.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <!-- Set the rows count and the total of the price and cost columns -->
                        @if ($count)
                            <th scope="row" colspan="{{ $count }}" class="text-center fw-semibold">Total Entries:
                                {{ $entries->count() }}</th>
                        @endif
                        @if (isset($columns['price']))
                            <td class="text-end fw-bold text-success" style="font-family: 'Courier New', monospace;">
                                £{{ number_format($entries->sum('price'), strlen(rtrim(substr(strrchr($entries->sum('price'), '.'), 1), '0'))) }}
                            </td>
                        @endif
                        @if (isset($columns['cost']))
                            <td class="text-end fw-semibold text-muted" style="font-family: 'Courier New', monospace;">
                                £{{ number_format($entries->sum('cost'), strlen(rtrim(substr(strrchr($entries->sum('cost'), '.'), 1), '0'))) }}
                            </td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>
@endsection
