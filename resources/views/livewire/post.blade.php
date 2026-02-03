<div class="text-white">


    <div class="fixed bottom-0 z-50 w-full -translate-x-1/2 bg-neutral-primary-soft border-t border-default left-1/2">
        <div class="w-full">
            <div class="grid max-w-xs grid-cols-3 gap-1 p-1 mx-auto my-2 bg-neutral-tertiary rounded-base" role="group">
                <button type="button"
                    class="px-5 py-1.5 text-xs font-medium text-body hover:bg-dark-strong hover:text-white rounded">
                    New
                </button>
                <button type="button" class="px-5 py-1.5 text-xs font-medium text-white bg-dark-strong rounded">
                    Popular
                </button>
                <button type="button"
                    class="px-5 py-1.5 text-xs font-medium text-body hover:bg-dark-strong hover:text-white rounded">
                    Following
                </button>
            </div>
        </div>
        <div class="grid h-full max-w-lg grid-cols-5 mx-auto">
            <button data-tooltip-target="tooltip-home" type="button"
                class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group">
                <svg class="w-6 h-6 mb-1 text-body group-hover:text-fg-brand" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                </svg>
                <span class="sr-only">Home</span>
            </button>
            <div id="tooltip-home" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Home
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-bookmark" type="button"
                class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group">
                <svg class="w-6 h-6 mb-1 text-body group-hover:text-fg-brand" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m17 21-5-4-5 4V3.889a.92.92 0 0 1 .244-.629.808.808 0 0 1 .59-.26h8.333a.81.81 0 0 1 .589.26.92.92 0 0 1 .244.63V21Z" />
                </svg>
                <span class="sr-only">Bookmark</span>
            </button>
            <div id="tooltip-bookmark" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Bookmark
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-post" type="button"
                class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group">
                <svg class="w-6 h-6 mb-1 text-body group-hover:text-fg-brand" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h14m-7 7V5" />
                </svg>
                <span class="sr-only">New post</span>
            </button>
            <div id="tooltip-post" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                New post
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-search" type="button"
                class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group">
                <svg class="w-6 h-6 mb-1 text-body group-hover:text-fg-brand" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <span class="sr-only">Search</span>
            </button>
            <div id="tooltip-search" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Search
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-settings" type="button"
                class="inline-flex flex-col items-center justify-center p-4 hover:bg-neutral-secondary-medium group">
                <svg class="w-6 h-6 mb-1 text-body group-hover:text-fg-brand" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2" />
                </svg>
                <span class="sr-only">Settings</span>
            </button>
            <div id="tooltip-settings" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Settings
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>

</div>