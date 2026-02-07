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

    protected $listeners = ['openSearch' => 'handleOpenSearch'];

    public function mount(): void
    {
        $this->resetSearch();
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
        // Force unlock body scroll
        $this->dispatch('search-closed');
        // Use JavaScript to ensure body scroll is unlocked
        $this->js('document.body.style.overflow = "";');
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
        $posts = $this->query
            ? $postService->searchPosts($this->query, 10)
            : $postRepository->getEmptyPaginated(10);
        
        $users = $this->query
            ? $userRepository->searchUsers($this->query, 10, auth()->id())
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

        return view('livewire.search', [
            'posts' => $posts,
            'users' => $users,
        ]);
    }
}
