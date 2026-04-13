@php
    $dashboardTitle = match (true) {
        !empty($showCvs ?? false) => 'CVs',
        !empty($showReports ?? false) => 'Reports',
        isset($profileUsername) => $profilePageTitle ?? ('@'.ltrim((string) $profileUsername, '@')),
        isset($postSlug) => $postPageTitle ?? 'Post',
        !empty($showSettings ?? false) => 'Settings',
        !empty($showBookmarks ?? false) => 'Bookmarks',
        !empty($showExploreUsers ?? false) => 'Explore people',
        !empty($openSearch ?? false) => $searchPageTitle ?? 'Search',
        default => 'Home',
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
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Main Feed (Left) -->
                    <main class="lg:col-span-8 xl:col-span-9 order-1">
                        <section>
                            <livewire:post />
                        </section>
                    </main>

                    <!-- Sidebar (Right) -->
                    <aside class="lg:col-span-4 xl:col-span-3 order-2">
                        <!-- Unified Social Hub (Chats + Following Mixed) -->
                        @livewire('chat-list', ['inline' => true])
                    </aside>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
