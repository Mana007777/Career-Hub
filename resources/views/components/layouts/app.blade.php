{{-- Livewire full-page components default to this layout (see SupportPageComponents). Reuse the main app shell. --}}
@include('layouts.app', ['slot' => $slot])
