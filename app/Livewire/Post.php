<?php

namespace App\Livewire;

use App\Actions\Post\CreatePost;
use App\Actions\Post\DeletePost;
use App\Actions\Post\UpdatePost;
use App\Livewire\Concerns\ValidatesPost;
use App\Models\Post as PostModel;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Post extends Component
{
    use WithPagination, WithFileUploads, ValidatesPost;

    public $content = '';
    public $media;
    public $editingPostId = null;
    public $editContent = '';
    public $editMedia;
    public $showCreateForm = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $postToDelete = null;

    protected $listeners = ['refreshPosts' => '$refresh'];

    public function mount()
    {
        $this->resetForm();
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->resetForm();
        }
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function openEditModal($postId)
    {
        $post = PostModel::findOrFail($postId);
        
        if ($post->user_id !== Auth::id()) {
            session()->flash('error', 'You are not authorized to edit this post.');
            return;
        }

        $this->editingPostId = $postId;
        $this->editContent = $post->content;
        $this->editMedia = null;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingPostId = null;
        $this->editContent = '';
        $this->editMedia = null;
    }

    public function openDeleteModal($postId)
    {
        $post = PostModel::findOrFail($postId);
        
        if ($post->user_id !== Auth::id()) {
            session()->flash('error', 'You are not authorized to delete this post.');
            return;
        }

        $this->postToDelete = $postId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->postToDelete = null;
    }

    public function create()
    {
        $this->validate(
            $this->getCreatePostRules(),
            $this->getPostValidationMessages()
        );

        try {
            $createPost = new CreatePost();
            $createPost->create([
                'content' => $this->content,
                'media' => $this->media,
            ]);

            session()->flash('success', 'Post created successfully!');
            $this->closeCreateForm();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create post: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate(
            $this->getUpdatePostRules(),
            $this->getPostValidationMessages()
        );

        try {
            $post = PostModel::findOrFail($this->editingPostId);
            $updatePost = new UpdatePost();
            $updatePost->update($post, [
                'content' => $this->editContent,
                'media' => $this->editMedia,
            ]);

            session()->flash('success', 'Post updated successfully!');
            $this->closeEditModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update post: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $post = PostModel::findOrFail($this->postToDelete);
            $deletePost = new DeletePost();
            $deletePost->delete($post);

            session()->flash('success', 'Post deleted successfully!');
            $this->closeDeleteModal();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete post: ' . $e->getMessage());
        }
    }

    public function getMediaUrl($post)
    {
        $postService = new PostService();
        return $postService->getMediaUrl($post);
    }

    public function resetForm()
    {
        $this->content = '';
        $this->media = null;
    }

    public function render()
    {
        $postService = new PostService();
        $posts = $postService->getAllPosts(10);

        return view('livewire.post', [
            'posts' => $posts,
        ]);
    }
}
