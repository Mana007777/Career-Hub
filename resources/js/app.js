import './bootstrap';
import './chat';
import './theme';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Start Alpine only after Livewire has loaded, so @entangle works correctly
document.addEventListener('livewire:load', () => {
    Alpine.start();
});