@extends('layouts.app')
<!-- This is the PDF layout for the entries table -->
@section('app_content')
    <main class="container-fluid table-responsive overflow-y-hidden flex-grow-1">
        <table class="table table-striped m-0">
            <thead>
                <tr>
                    <!-- Set the chosen columns to be displayed -->
                    @if (isset($columns['id']))
                        <th scope="col">#</th>
                    @endif
                    @if (isset($columns['date']))
                        <th scope="col">Date</th>
                    @endif
                    @if (isset($columns['name']))
                        <th scope="col">Name</th>
                    @endif
                    @if (isset($columns['item']))
                        <th scope="col">Item</th>
                    @endif
                    @if (isset($columns['teeth']))
                        <th scope="col">Teeth</th>
                    @endif
                    @if (isset($columns['amount']))
                        <th scope="col">Amount</th>
                    @endif
                    @if (isset($columns['unit_price']))
                        <th scope="col">Unit Price</th>
                    @endif
                    @if (isset($columns['discount']))
                        <th scope="col">Discount</th>
                    @endif
                    @if (isset($columns['price']))
                        <th scope="col">Price</th>
                    @endif
                    @if (isset($columns['cost']))
                        <th scope="col">Cost</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <!-- Loop through the entries and display the data -->
                @foreach ($entries as $entry)
                    <tr>
                        <!-- Set the chosen columns to be displayed -->
                        @if (isset($columns['id']))
                            <th scope="row">{{ $entry->id }}</th>
                        @endif
                        @if (isset($columns['date']))
                            <td>{{ $entry->date->format('d-m-Y') }}</td>
                        @endif
                        @if (isset($columns['name']))
                            <td>{{ $entry->customer->name }}</td>
                        @endif
                        @if (isset($columns['item']))
                            <td>{{ $entry->item->name }}</td>
                        @endif
                        @if (isset($columns['teeth']))
                            <td>{{ $entry->teeth }}</td>
                        @endif
                        @if (isset($columns['amount']))
                            <td>{{ $entry->amount }}</td>
                        @endif
                        @if (isset($columns['unit_price']))
                            <td>{{ $entry->unit_price }}</td>
                        @endif
                        @if (isset($columns['discount']))
                            <td>{{ $entry->discount }}</td>
                        @endif
                        @if (isset($columns['price']))
                            <td>{{ $entry->price }}</td>
                        @endif
                        @if (isset($columns['cost']))
                            <td>{{ $entry->cost }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <!-- Set the rows count and the total of the price and cost columns -->
                    @if ($count)
                        <th scope="row" colspan="{{ $count }}" class="text-md-center">Number of
                            Entries: {{ $entries->count() }}</th>
                    @endif
                    @if (isset($columns['price']))
                        <td>Total: {{ $entries->sum('price') }}</td>
                    @endif
                    @if (isset($columns['cost']))
                        <td>Total: {{ $entries->sum('cost') }}</td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </main>
@endsection
