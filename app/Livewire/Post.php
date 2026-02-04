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
    public $specialties = [];
    public $specialtyName = '';
    public $subSpecialtyName = '';
    public $tags = [];
    public $tagName = '';
    public $editingPostId = null;
    public $editContent = '';
    public $editMedia;
    public $editSpecialties = [];
    public $editSpecialtyName = '';
    public $editSubSpecialtyName = '';
    public $editTags = [];
    public $editTagName = '';
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
        $postService = new PostService(new \App\Repositories\PostRepository());
        $post = $postService->getPostById($postId);
        
        if (!$post || $post->user_id !== Auth::id()) {
            session()->flash('error', 'You are not authorized to edit this post.');
            return;
        }

        $this->editingPostId = $postId;
        $this->editContent = $post->content;
        $this->editMedia = null;
        
        // Load existing specialties
        $this->editSpecialties = [];
        foreach ($post->specialties as $specialty) {
            $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null;
            $subSpecialty = $subSpecialtyId ? \App\Models\SubSpecialty::find($subSpecialtyId) : null;
            if ($subSpecialty) {
                $this->editSpecialties[] = [
                    'specialty_name' => $specialty->name,
                    'sub_specialty_name' => $subSpecialty->name,
                ];
            }
        }
        
        // Load existing tags
        $this->editTags = [];
        foreach ($post->tags as $tag) {
            $this->editTags[] = ['name' => $tag->name];
        }
        
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingPostId = null;
        $this->editContent = '';
        $this->editMedia = null;
        $this->editSpecialties = [];
        $this->editSpecialtyName = '';
        $this->editSubSpecialtyName = '';
        $this->editTags = [];
        $this->editTagName = '';
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

    public function addSpecialty()
    {
        if (trim($this->specialtyName) && trim($this->subSpecialtyName)) {
            // Check if this combination already exists
            $exists = collect($this->specialties)->contains(function ($spec) {
                return strtolower(trim($spec['specialty_name'])) == strtolower(trim($this->specialtyName))
                    && strtolower(trim($spec['sub_specialty_name'])) == strtolower(trim($this->subSpecialtyName));
            });

            if (!$exists) {
                $this->specialties[] = [
                    'specialty_name' => trim($this->specialtyName),
                    'sub_specialty_name' => trim($this->subSpecialtyName),
                ];
            }

            $this->specialtyName = '';
            $this->subSpecialtyName = '';
        }
    }

    public function addEditSpecialty()
    {
        if (trim($this->editSpecialtyName) && trim($this->editSubSpecialtyName)) {
            $exists = collect($this->editSpecialties)->contains(function ($spec) {
                return strtolower(trim($spec['specialty_name'])) == strtolower(trim($this->editSpecialtyName))
                    && strtolower(trim($spec['sub_specialty_name'])) == strtolower(trim($this->editSubSpecialtyName));
            });

            if (!$exists) {
                $this->editSpecialties[] = [
                    'specialty_name' => trim($this->editSpecialtyName),
                    'sub_specialty_name' => trim($this->editSubSpecialtyName),
                ];
            }

            $this->editSpecialtyName = '';
            $this->editSubSpecialtyName = '';
        }
    }

    public function addTag()
    {
        if (trim($this->tagName)) {
            $tagName = trim($this->tagName);
            $exists = collect($this->tags)->contains(function ($tag) use ($tagName) {
                return strtolower(trim($tag['name'])) == strtolower($tagName);
            });

            if (!$exists) {
                $this->tags[] = ['name' => $tagName];
            }

            $this->tagName = '';
        }
    }

    public function addEditTag()
    {
        if (trim($this->editTagName)) {
            $tagName = trim($this->editTagName);
            $exists = collect($this->editTags)->contains(function ($tag) use ($tagName) {
                return strtolower(trim($tag['name'])) == strtolower($tagName);
            });

            if (!$exists) {
                $this->editTags[] = ['name' => $tagName];
            }

            $this->editTagName = '';
        }
    }

    public function removeSpecialty($index)
    {
        unset($this->specialties[$index]);
        $this->specialties = array_values($this->specialties);
    }

    public function removeEditSpecialty($index)
    {
        unset($this->editSpecialties[$index]);
        $this->editSpecialties = array_values($this->editSpecialties);
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function removeEditTag($index)
    {
        unset($this->editTags[$index]);
        $this->editTags = array_values($this->editTags);
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
                'specialties' => $this->specialties,
                'tags' => $this->tags,
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
                'specialties' => $this->editSpecialties,
                'tags' => $this->editTags,
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
        $postService = new PostService(new \App\Repositories\PostRepository());
        return $postService->getMediaUrl($post);
    }

    public function resetForm()
    {
        $this->content = '';
        $this->media = null;
        $this->specialties = [];
        $this->specialtyName = '';
        $this->subSpecialtyName = '';
        $this->tags = [];
        $this->tagName = '';
    }

    public function render()
    {
        $postService = new PostService(new \App\Repositories\PostRepository());
        $posts = $postService->getAllPosts(10);

        return view('livewire.post', [
            'posts' => $posts,
        ]);
    }
}
