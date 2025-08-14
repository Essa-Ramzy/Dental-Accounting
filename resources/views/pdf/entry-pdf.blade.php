@extends('layouts.app')
<!-- This is the PDF layout for the entries table -->
@section('app_content')
    <style>
        @media print {
            .container-fluid {
                max-width: 100% !important;
            }

            .table {
                font-size: 12px;
            }

            .table th {
                background-color: #f8f9fa !important;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .page-break {
                page-break-after: always;
            }
        }

        .pdf-header {
            border-bottom: 3px solid #0d6efd;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
        }

        .pdf-table {
            border: 1px solid #dee2e6;
        }

        .pdf-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .pdf-table td {
            font-size: 0.85rem;
            border-bottom: 1px solid #e9ecef;
        }

        .pdf-footer {
            border-top: 2px solid #0d6efd;
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .teeth-display {
            max-width: 200px;
            word-wrap: break-word;
            font-size: 0.8rem;
        }

        .currency {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
    </style>

    <main class="container-fluid">
        <!-- PDF Header -->
        <div class="pdf-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-1 text-primary fw-bold">Dental Clinic Entries Report</h1>
                    <p class="text-muted mb-0">Generated on {{ date('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="border rounded p-3 bg-light">
                        <div class="small text-muted">Total Entries</div>
                        <div class="h4 mb-0 text-primary">{{ $entries->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table table-sm pdf-table mb-0">
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
                            <th scope="col">Patient Name</th>
                        @endif
                        @if (isset($columns['item']))
                            <th scope="col">Treatment</th>
                        @endif
                        @if (isset($columns['teeth']))
                            <th scope="col">Teeth</th>
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
                            <th scope="col" class="text-end">Total Price</th>
                        @endif
                        @if (isset($columns['cost']))
                            <th scope="col" class="text-end">Cost</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through the entries and display the data -->
                    @foreach ($entries as $entry)
                        <tr>
                            <!-- Set the chosen columns to be displayed -->
                            @if (isset($columns['id']))
                                <th scope="row" class="text-center">{{ $entry->id }}</th>
                            @endif
                            @if (isset($columns['date']))
                                <td>{{ $entry->date->format('d/m/Y') }}</td>
                            @endif
                            @if (isset($columns['name']))
                                <td class="fw-semibold">{{ $entry->customer->name }}</td>
                            @endif
                            @if (isset($columns['item']))
                                <td>{{ $entry->item->name }}</td>
                            @endif
                            @if (isset($columns['teeth']))
                                <td class="teeth-display">
                                    <span class="badge bg-light text-dark border">{{ $entry->teeth }}</span>
                                </td>
                            @endif
                            @if (isset($columns['amount']))
                                <td class="text-center">{{ $entry->amount }}</td>
                            @endif
                            @if (isset($columns['unit_price']))
                                <td class="currency">£
                                    {{ number_format($entry->unit_price, strlen(rtrim(substr(strrchr($entry->unit_price, '.'), 1), '0'))) }}
                                </td>
                            @endif
                            @if (isset($columns['discount']))
                                <td class="currency">
                                    £
                                    {{ number_format($entry->discount, strlen(rtrim(substr(strrchr($entry->discount, '.'), 1), '0'))) }}
                                </td>
                            @endif
                            @if (isset($columns['price']))
                                <td class="currency fw-semibold">
                                    £
                                    {{ number_format($entry->price, strlen(rtrim(substr(strrchr($entry->price, '.'), 1), '0'))) }}
                                </td>
                            @endif
                            @if (isset($columns['cost']))
                                <td class="currency">£
                                    {{ number_format($entry->cost, strlen(rtrim(substr(strrchr($entry->cost, '.'), 1), '0'))) }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="pdf-footer">
                    <tr>
                        <!-- Set the rows count and the total of the price and cost columns -->
                        @if ($count)
                            <th scope="row" colspan="{{ $count }}" class="text-center py-3">
                                <strong>Total Entries: {{ $entries->count() }}</strong>
                            </th>
                        @endif
                        @if (isset($columns['price']))
                            <td class="currency py-3">
                                <strong>£
                                    {{ number_format($entries->sum('price'), strlen(rtrim(substr(strrchr($entries->sum('price'), '.'), 1), '0'))) }}</strong>
                            </td>
                        @endif
                        @if (isset($columns['cost']))
                            <td class="currency py-3">
                                <strong>£
                                    {{ number_format($entries->sum('cost'), strlen(rtrim(substr(strrchr($entries->sum('cost'), '.'), 1), '0'))) }}</strong>
                            </td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>
@endsection
