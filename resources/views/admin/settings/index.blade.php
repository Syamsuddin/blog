@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-cog text-primary me-2"></i>Settings
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($settings->flatten()->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>No settings found!</strong> 
            Click "Seed Defaults" to initialize the settings with default values.
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            @foreach($settings as $group => $groupSettings)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($loop->first) active @endif" 
                            id="{{ $group }}-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#{{ $group }}-pane" 
                            type="button" 
                            role="tab">
                        @switch($group)
                            @case('general')
                                <i class="fas fa-globe me-2"></i>General
                                @break
                            @case('appearance')
                                <i class="fas fa-palette me-2"></i>Appearance
                                @break
                            @case('seo')
                                <i class="fas fa-search me-2"></i>SEO
                                @break
                            @case('system')
                                <i class="fas fa-server me-2"></i>System
                                @break
                            @default
                                <i class="fas fa-cog me-2"></i>{{ ucfirst($group) }}
                        @endswitch
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="settingsTabContent">
            @foreach($settings as $group => $groupSettings)
                <div class="tab-pane fade @if($loop->first) show active @endif" 
                     id="{{ $group }}-pane" 
                     role="tabpanel" 
                     aria-labelledby="{{ $group }}-tab">
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                @switch($group)
                                    @case('general')
                                        <i class="fas fa-globe me-2"></i>General Settings
                                        @break
                                    @case('appearance')
                                        <i class="fas fa-palette me-2"></i>Appearance Settings
                                        @break
                                    @case('seo')
                                        <i class="fas fa-search me-2"></i>SEO Settings
                                        @break
                                    @case('system')
                                        <i class="fas fa-server me-2"></i>System Settings
                                        @break
                                    @default
                                        <i class="fas fa-cog me-2"></i>{{ ucfirst($group) }} Settings
                                @endswitch
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($groupSettings->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No {{ $group }} settings found</h6>
                                    <p class="text-muted">Click "Seed Defaults" to initialize settings</p>
                                </div>
                            @else
                                <div class="row">
                                    @foreach($groupSettings as $setting)
                                        <div class="col-md-6 mb-4">
                                            <label for="{{ $setting->key }}" class="form-label fw-bold">
                                                {{ $setting->label }}
                                                @if($setting->description)
                                                    <small class="text-muted d-block fw-normal">{{ $setting->description }}</small>
                                                @endif
                                            </label>

                                        @switch($setting->type)
                                            @case('textarea')
                                                <textarea name="settings[{{ $setting->key }}]" 
                                                         id="{{ $setting->key }}" 
                                                         class="form-control" 
                                                         rows="4">{{ old('settings.'.$setting->key, $setting->value) }}</textarea>
                                                @break

                                            @case('boolean')
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                                    <input type="checkbox" 
                                                           name="settings[{{ $setting->key }}]" 
                                                           id="{{ $setting->key }}" 
                                                           class="form-check-input" 
                                                           value="1"
                                                           @checked(old('settings.'.$setting->key, $setting->value))>
                                                    <label class="form-check-label" for="{{ $setting->key }}">
                                                        {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                                    </label>
                                                </div>
                                                @break

                                            @case('select')
                                                <select name="settings[{{ $setting->key }}]" 
                                                        id="{{ $setting->key }}" 
                                                        class="form-select">
                                                    @if($setting->options && is_array($setting->options))
                                                        @foreach($setting->options as $optionValue => $optionLabel)
                                                            <option value="{{ $optionValue }}" 
                                                                    @selected(old('settings.'.$setting->key, $setting->value) == $optionValue)>
                                                                {{ $optionLabel }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @break

                                            @case('number')
                                                <input type="number" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       id="{{ $setting->key }}" 
                                                       class="form-control" 
                                                       value="{{ old('settings.'.$setting->key, $setting->value) }}">
                                                @break

                                            @case('email')
                                                <input type="email" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       id="{{ $setting->key }}" 
                                                       class="form-control" 
                                                       value="{{ old('settings.'.$setting->key, $setting->value) }}">
                                                @break

                                            @default
                                                <input type="text" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       id="{{ $setting->key }}" 
                                                       class="form-control" 
                                                       value="{{ old('settings.'.$setting->key, $setting->value) }}">
                                        @endswitch
                                    </div>
                                @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Save Button -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg ms-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Changes will take effect immediately after saving
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('head')
<style>
.nav-tabs .nav-link {
    color: #6c757d;
    border: 1px solid transparent;
}

.nav-tabs .nav-link.active {
    color: var(--primary-color);
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs .nav-link:hover {
    color: var(--secondary-color);
    border-color: #e9ecef #e9ecef #dee2e6;
}

.form-check-input:checked {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.alert {
    border: none;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}
</style>
@endpush
@endsection
