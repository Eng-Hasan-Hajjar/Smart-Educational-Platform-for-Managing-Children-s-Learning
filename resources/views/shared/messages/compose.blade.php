@extends('layouts.app')
@section('title', __('app.new_message'))
@section('page-title', __('app.new_message'))
@section('page-subtitle', __('app.compose_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('messages.store') }}" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="card space-y-5">
            <div>
                <label class="label">{{ __('app.recipient') }} *</label>
                <select name="recipient_id" required class="input">
                    <option value="">{{ __('app.select_option') }}</option>
                    @foreach($contacts as $contact)
                    <option value="{{ $contact->id }}" {{ ($recipient?->id ?? old('recipient_id')) == $contact->id ? 'selected' : '' }}>
                        {{ $contact->name }} — {{ __('app.'.($contact->roles->first()?->name ?? 'user')) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">{{ __('app.subject') }}</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="input"
                       placeholder="{{ __('app.subject_placeholder') }}">
            </div>
            <div>
                <label class="label">{{ __('app.message') }} *</label>
                <textarea name="body" rows="6" required class="input resize-none"
                          placeholder="{{ __('app.message_placeholder') }}">{{ old('body') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('messages.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">📤 {{ __('app.send') }}</span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    {{ __('app.sending') }}
                </span>
            </button>
        </div>
    </form>
</div>
@endsection