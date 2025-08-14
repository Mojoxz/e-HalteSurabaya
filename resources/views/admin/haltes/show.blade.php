@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Detail Halte</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $halte->name }}</h5>
            <p class="card-text">{{ $halte->description }}</p>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> {{ $halte->address }}</p>
                    <p><strong>Koordinat:</strong> {{ $halte->latitude }}, {{ $halte->longitude }}</p>
                    <p><strong>Status:</strong> {{ $halte->status }}</p>
                </div>

                <div class="col-md-6">
                    <p><strong>Nomor Simbada:</strong> {{ $halte->simbada_number }}</p>
                    @if($halte->is_rented)
                        <p><strong>Disewa oleh:</strong> {{ $halte->rented_by }}</p>
                        <p><strong>Periode Sewa:</strong> {{ $halte->rent_start_date }} - {{ $halte->rent_end_date }}</p>
                    @endif
                </div>
            </div>
