@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Мои растения</h1>
    <a href="{{ route('plants.create') }}" class="btn btn-primary mb-3">Добавить растение</a>
    
    <div class="row">
        @foreach($plants as $plant)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $plant->name }}</h5>
                    <p class="card-text">{{ $plant->species }}</p>
                    <p class="card-text">Посажен: {{ $plant->planting_date->format('d.m.Y') }}</p>
                    <a href="{{ route('plants.show', $plant) }}" class="btn btn-sm btn-info">Подробнее</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection