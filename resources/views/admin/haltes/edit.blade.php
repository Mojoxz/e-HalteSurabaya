@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Halte</h2>
    <form action="{{ route('admin.haltes.update', $halte->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $halte->name }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $halte->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ $halte->latitude }}" required>
        </div>

        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ $halte->longitude }}" required>
        </div>

        <button type="submit" class="btn btn
