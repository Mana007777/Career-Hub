<?php

namespace App\Livewire;

use App\Livewire\Listeners\OpenSearchListener;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $query = '';
    public $showSearch = false;

    /** Filter to users, posts, or both. */
    public $resultType = 'all'; // all, users, posts

    protected $listeners = ['openSearch' => 'handleOpenSearch'];

    /**
     * Sync search state with URL for shareable links.
     * ?search=1&q=john&type=users
     */
    protected $queryString = [
        'query' => ['except' => '', 'as' => 'q'],
        'showSearch' => ['except' => false, 'as' => 'search'],
        'resultType' => ['except' => 'all', 'as' => 'type'],
    ];

    public function mount(
        bool $openSearchFromRoute = false,
        ?string $initialQuery = null,
        ?string $initialType = null,
    ): void {
        $q = $initialQuery ?? request()->query('q');
        $openSearch = $openSearchFromRoute || filter_var(request()->query('search'), FILTER_VALIDATE_BOOLEAN);

        if ($q !== null) {
            $this->query = trim((string) $q);
        }
        if ($openSearch || !empty($this->query)) {
            $this->showSearch = true;
        }

        $type = $initialType ?? request()->query('type');
        if (in_array($type, ['users', 'posts', 'all'], true)) {
            $this->resultType = $type;
        }

        $this->resetPage();
    }

    public function toggleSearch(): void
    {
        $this->showSearch = !$this->showSearch;
        if (!$this->showSearch) {
            $this->resetSearch();
        }
    }

    public function closeSearch(): void
    {
        $this->showSearch = false;
        $this->resetSearch();
        $this->resultType = 'all';
        // Force unlock body scroll
        $this->dispatch('search-closed');
        // Use JavaScript to ensure body scroll is unlocked
        $this->js('document.body.style.overflow = "";');
    }

    public function setResultType(string $type): void
    {
        if (in_array($type, ['all', 'users', 'posts'], true)) {
            $this->resultType = $type;
            $this->resetPage();
        }
    }

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function resetSearch(): void
    {
        $this->query = '';
        $this->resetPage();
    }

    public function handleOpenSearch(): void
    {
        app(OpenSearchListener::class)->handle($this);
    }

    public function render(PostService $postService, PostRepository $postRepository, \App\Repositories\UserRepository $userRepository): View
    {
        $searchQuery = trim($this->query ?? '');
        $showPosts = in_array($this->resultType, ['all', 'posts'], true);
        $showUsers = in_array($this->resultType, ['all', 'users'], true);

        $posts = ($searchQuery && $showPosts)
            ? $postService->searchPosts($searchQuery, 10)
            : $postRepository->getEmptyPaginated(10);

        $users = ($searchQuery && $showUsers)
            ? $userRepository->searchUsers($searchQuery, 10, auth()->id())
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

        return view('livewire.search', [
            'posts' => $posts,
            'users' => $users,
            'resultType' => $this->resultType,
        ]);
    }
}
