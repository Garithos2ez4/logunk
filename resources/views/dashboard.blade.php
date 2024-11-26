@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div id="dashboard-content" >
        <x-dashboard_content :registros="$registros" :inventario="$inventario" :stock="$stock" :colors="$colors" :productos="$productos" :stockMin="$stockMin" :publicaciones="$publicaciones"/>
    </div>
    <input type="hidden" id="dashboardurl" value="{{route('dashboard')}}">
    <script src="{{asset('js/dashboard.js')}}"></script>
@endsection