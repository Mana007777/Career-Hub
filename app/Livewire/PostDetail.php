<?php

namespace App\Livewire;

use App\Models\Post as PostModel;
use App\Services\PostService;
use Livewire\Component;

class PostDetail extends Component
{
    public $slug;
    public $post;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->loadPost();
    }

    protected function loadPost()
    {
        // Extract ID from slug (format: slug-text-123)
        $parts = explode('-', $this->slug);
        $id = end($parts);

        if (is_numeric($id)) {
            $postService = new PostService();
            $this->post = $postService->getPostById((int) $id);
            
            if (!$this->post) {
                abort(404, 'Post not found');
            }
        } else {
            abort(404, 'Invalid post slug');
        }
    }

    public function getMediaUrl($post)
    {
        $postService = new PostService();
        return $postService->getMediaUrl($post);
    }

    public function render()
    {
        return view('livewire.post-detail');
    }
}
