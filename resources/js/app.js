import './bootstrap';
import './chat';
import './theme';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

// Register Alpine plugins
Alpine.plugin(focus);

// Expose Alpine globally for Livewire
window.Alpine = Alpine;

// Start Alpine only after Livewire has loaded, so @entangle works correctly
document.addEventListener('livewire:load', () => {
    Alpine.start();
});