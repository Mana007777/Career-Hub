<?php

namespace App\Livewire;

use App\Livewire\Listeners\OpenSearchListener;
use App\Models\Post;
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

    public function render(PostService $postService): View
    {
        $posts = $this->query
            ? $postService->searchPosts($this->query, 10)
            : Post::query()->whereRaw('1 = 0')->paginate(10);

        return view('livewire.search', [
            'posts' => $posts,
        ]);
    }
}
