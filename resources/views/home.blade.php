@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <h1>Welcome to the Dashboard</h1>
        @if(Auth::user()->role === 'admin')
            <p>You have administrative privileges.</p>
            <!-- Admin-specific content here -->
        @elseif(Auth::user()->role === 'users')
            <p>You are a regular user.</p>
            <!-- User-specific content here -->
        @endif
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
   <link rel="stylesheet" href="{{ asset('vendor/leafLet/leaflet.css')}}">
@stop

@section('js')
<script src="{{asset('vendor/leafLet/leaflet.js')}}"></script>
@stop