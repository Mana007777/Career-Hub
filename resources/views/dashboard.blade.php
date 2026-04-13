@php
    $dashboardTitle = match (true) {
        !empty($showCvs ?? false) => __('CVs'),
        !empty($showReports ?? false) => __('Reports'),
        isset($profileUsername) => $profilePageTitle ?? ('@'.ltrim((string) $profileUsername, '@')),
        isset($postSlug) => $postPageTitle ?? __('Post'),
        !empty($showSettings ?? false) => __('Settings'),
        !empty($showBookmarks ?? false) => __('Bookmarks'),
        !empty($showExploreUsers ?? false) => __('Explore people'),
        !empty($openSearch ?? false) => $searchPageTitle ?? __('Search'),
        default => __('Home'),
    };
@endphp
<x-app-layout :title="$dashboardTitle">
    <div class="text-zinc-100 min-h-screen">
        @livewire('search', [
            'openSearchFromRoute' => $openSearch ?? false,
            'initialQuery' => $q ?? request()->query('q'),
            'initialType' => $type ?? request()->query('type'),
        ])
        @livewire('user-notifications')
        @livewire('chat-box')
        @livewire('chat-list')
        @livewire('report-modal')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-10">
            @if(isset($showCvs) && $showCvs)
                <section>
                    <livewire:cvs />
                </section>
            @elseif(isset($showReports) && $showReports)
                <section>
                    <livewire:reports />
                </section>
            @elseif(isset($profileUsername))
                <section>
                    <livewire:user-profile :username="$profileUsername" />
                </section>
            @elseif(isset($postSlug))
                <section>
                    <livewire:post-detail :slug="$postSlug" />
                </section>
            @elseif(isset($showSettings) && $showSettings)
                <section>
                    <livewire:settings />
                </section>
            @elseif(isset($showBookmarks) && $showBookmarks)
                <section>
                    <livewire:saved-posts />
                </section>
            @elseif(isset($showExploreUsers) && $showExploreUsers)
                <section>
                    <livewire:explore-users />
                </section>
            @else
                <div class="max-w-5xl mx-auto">
                    <!-- Main Feed -->
                    <main>
                        <section>
                            <livewire:post />
                        </section>
                    </main>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
