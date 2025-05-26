@extends('layouts.dashboard')

@section('content')
    <div class="container mt-4">
        <h2>Permission Details</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>{{ __('ID') }}:</strong> {{ $permission->id }}</p>
                <p><strong>{{ __('Name') }}:</strong> {{ $permission->name }}</p>
                <p><strong>{{ __('Guard') }}:</strong> {{ $permission->guard_name }}</p>
                <p><strong>{{ __('Created At') }}:</strong> {{ optional($permission->created_at)->format('Y-m-d H:i') ?? __('N/A') }}</p>
                <p><strong>{{ __('Updated At') }}:</strong> {{ optional($permission->updated_at)->format('Y-m-d H:i') ??  __('N/A') }}</p>
            </div>
            <button>
                <a href="{{ route('admin.permissions') }}" class="btn btn-secondary mt-3">{{ __('Back') }}</a>
            </button>
        </div>
    </div>
@endsection
