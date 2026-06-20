@extends('layouts.app')

@section('title', 'Personas')
@section('page_title', 'Personas')

@section('content')
    <livewire:buscar-persona />

    <a href="{{ route('personas.create') }}" class="fab" title="Nueva persona">&#43;</a>
@endsection
