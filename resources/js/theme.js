/**
 * Theme Management
 * Handles dark mode, light mode, and system default theme switching
 */

// Get the effective theme based on preference
function getEffectiveTheme(preference) {
    if (preference === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    return preference;
}

// Apply theme to document
function applyTheme(theme) {
    const html = document.documentElement;
    
    if (theme === 'dark') {
        html.classList.add('dark');
        html.classList.remove('light');
    } else {
        html.classList.add('light');
        html.classList.remove('dark');
    }
}

// Initialize theme on page load
function initTheme() {
    // Get theme preference from user data or localStorage
    let themePreference = 'system';
    
    // Try to get from meta tag (set by Laravel)
    const themeMeta = document.querySelector('meta[name="theme-preference"]');
    if (themeMeta) {
        themePreference = themeMeta.getAttribute('content') || 'system';
    } else {
        // Fallback to localStorage
        themePreference = localStorage.getItem('theme-preference') || 'system';
    }
    
    const effectiveTheme = getEffectiveTheme(themePreference);
    applyTheme(effectiveTheme);
    
    // Listen for system theme changes if preference is 'system'
    if (themePreference === 'system') {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', (e) => {
            applyTheme(e.matches ? 'dark' : 'light');
        });
    }
}

// Update theme when preference changes
function updateTheme(preference) {
    localStorage.setItem('theme-preference', preference);
    const effectiveTheme = getEffectiveTheme(preference);
    applyTheme(effectiveTheme);
    
    // Update or remove system preference listener
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Remove old listener if exists
    if (window.themeMediaQueryListener) {
        mediaQuery.removeEventListener('change', window.themeMediaQueryListener);
    }
    
    // Add new listener if preference is system
    if (preference === 'system') {
        window.themeMediaQueryListener = (e) => {
            applyTheme(e.matches ? 'dark' : 'light');
        };
        mediaQuery.addEventListener('change', window.themeMediaQueryListener);
    }
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTheme);
} else {
    initTheme();
}

// Listen for Livewire theme updates
document.addEventListener('livewire:init', () => {
    Livewire.on('theme-updated', (event) => {
        const theme = typeof event === 'string' ? event : (event.theme || event[0]?.theme || 'system');
        updateTheme(theme);
    });
});

// Also listen for Livewire 3 style events
window.addEventListener('theme-updated', (event) => {
    const theme = event.detail?.theme || 'system';
    updateTheme(theme);
});

// Also listen for Livewire navigation (for SPA-like behavior)
document.addEventListener('livewire:navigated', () => {
    initTheme();
});

// Export for use in other scripts
window.themeManager = {
    updateTheme,
    getEffectiveTheme,
    applyTheme
};
