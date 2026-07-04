@extends('layouts.app')

@section('title', 'Consignaciones')
@section('page_title', 'Consignaciones')

@section('content')
    <livewire:buscar-consignacion />

    <a href="{{ route('consignaciones.create') }}" class="fab" title="Nueva consignaci&oacute;n">&#43;</a>
@endsection
