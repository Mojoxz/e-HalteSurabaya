@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Rental History</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Halte</th>
                    <th>Rented By</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Rental Cost</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                <tr>
                    <td>{{ $history->halte->name }}</td>
                    <td>{{ $history->rented_by }}</td>
                    <td>{{ $history->rent_start_date }}</td>
                    <td>{{ $history->rent_end_date }}</td>
                    <td>{{ $history->rental_cost }}</td>
                    <td>{{ $history->creator->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $histories->links() }}
</div>
