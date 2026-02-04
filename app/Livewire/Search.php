<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\PostService;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $query = '';
    public $showSearch = false;

    protected $listeners = ['openSearch' => 'toggleSearch'];

    public function mount()
    {
        $this->resetSearch();
    }

    public function toggleSearch()
    {
        $this->showSearch = !$this->showSearch;
        if (!$this->showSearch) {
            $this->resetSearch();
        }
    }

    public function closeSearch()
    {
        $this->showSearch = false;
        $this->resetSearch();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->query = '';
        $this->resetPage();
    }

    public function render()
    {
        $postService = new PostService(new \App\Repositories\PostRepository());
        
        if ($this->query) {
            $posts = $postService->searchPosts($this->query, 10);
        } else {
            // Return empty paginator when no query
            $posts = \App\Models\Post::query()->whereRaw('1 = 0')->paginate(10);
        }

        return view('livewire.search', [
            'posts' => $posts,
        ]);
    }
}
