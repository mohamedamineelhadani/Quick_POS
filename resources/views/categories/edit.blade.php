@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Edit Category</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @method('PUT')
                @include('categories._form', ['category' => $category])
            </form>
        </div>
    </div>
@endsection