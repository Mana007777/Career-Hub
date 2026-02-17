<x-app-layout>
    <div class="bg-transparent dark:text-white text-gray-900 min-h-screen">
        @livewire('search', [
            'openSearchFromRoute' => $openSearch ?? false,
            'initialQuery' => $q ?? request()->query('q'),
            'initialType' => $type ?? request()->query('type'),
        ])
        @livewire('user-notifications')
        @livewire('chat-box')
        @livewire('chat-list')
        @livewire('report-modal')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-6">
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
                <div class="space-y-8">
                    <!-- Main feed (full width) -->
                    <main class="space-y-4">
                        <section>
                            <livewire:post />
                        </section>
                    </main>

                    <!-- Following list below feed, full width -->
                    <section>
                        <livewire:following-sidebar />
                    </section>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
