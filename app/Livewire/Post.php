<?php

namespace App\Livewire;

use App\Actions\Post\AuthorizePostAction;
use App\Actions\Post\CreatePost;
use App\Actions\Post\DeletePost;
use App\Actions\Post\LikePost;
use App\Actions\Post\UpdatePost;
use App\Actions\User\FollowUser;
use App\Livewire\Validations\StorePostValidation;
use App\Livewire\Validations\UpdatePostValidation;
use App\Livewire\Listeners\NotificationsUpdatedListener;
use App\Livewire\Listeners\OpenCreatePostListener;
use App\Livewire\Listeners\RefreshPostsListener;
use App\Models\Post as PostModel;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use App\Services\PostService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Post extends Component
{
    use WithPagination, WithFileUploads;

    public $title = '';
    public $content = '';
    public $media;
    public $jobType = '';
    public $specialties = [];
    public $specialtyName = '';
    public $subSpecialtyName = '';
    public $tags = [];
    public $tagName = '';
    public $editingPostId = null;
    public $editTitle = '';
    public $editContent = '';
    public $editMedia;
    public $editJobType = '';
    public $editSpecialties = [];
    public $editSpecialtyName = '';
    public $editSubSpecialtyName = '';
    public $editTags = [];
    public $editTagName = '';
    public $showCreateForm = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $postToDelete = null;
    public $feedMode = 'new'; // new, popular, following
    
    // Filter properties
    public $sortOrder = 'desc'; // desc, asc
    public $selectedTags = ''; // Single tag ID
    public $selectedSpecialties = ''; // Single specialty ID
    public $selectedJobType = '';
    public $showFilters = false;

    protected $listeners = [
        'refreshPosts' => 'handleRefreshPosts',
        'notificationsUpdated' => 'handleNotificationsUpdated',
        'openCreatePost' => 'handleOpenCreatePost',
    ];

    public function mount(): void
    {
        $this->resetForm();
    }

    /**
     * CRITICAL FIX: Ensure all properties are properly initialized
     * This method is called when the component is hydrated/re-hydrated
     * Arrays can become null during re-hydration, causing validation to fail
     */
    public function hydrate(): void
    {
        // Ensure arrays are never null - they must be arrays
        if (!is_array($this->specialties)) {
            $this->specialties = [];
        }
        if (!is_array($this->tags)) {
            $this->tags = [];
        }
        // Ensure strings are never null
        $this->title = $this->title ?? '';
        $this->content = $this->content ?? '';
    }

    public function setFeedMode(string $mode): void
    {
        if (!in_array($mode, ['new', 'popular', 'following'], true)) {
            return;
        }

        $this->feedMode = $mode;
        $this->resetPage();
    }
    
    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }
    
    public function applyFilters(): void
    {
        $this->resetPage();
    }
    
    public function clearFilters(): void
    {
        $this->sortOrder = 'desc';
        $this->selectedTags = '';
        $this->selectedSpecialties = '';
        $this->selectedJobType = '';
        $this->resetPage();
    }
    
    public function updatedSelectedTags(): void
    {
        $this->resetPage();
    }
    
    public function updatedSelectedSpecialties(): void
    {
        $this->resetPage();
    }
    
    public function updatedSelectedJobType(): void
    {
        $this->resetPage();
    }
    
    public function updatedSortOrder(): void
    {
        $this->resetPage();
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

    public function openEditModal(int $postId, PostService $postService, AuthorizePostAction $authorizePostAction): void
    {
        try {
            $post = $postService->getPostById($postId);
            
            if (!$post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            if (!$authorizePostAction->canEdit($post)) {
                session()->flash('error', 'You are not authorized to edit this post.');
                return;
            }

            $this->loadPostDataForEditing($post);
            $this->showEditModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load post. Please try again.');
        }
    }

    protected function loadPostDataForEditing(PostModel $post): void
    {
        $this->editingPostId = $post->id;
        $this->editTitle = $post->title;
        $this->editContent = $post->content;
        $this->editMedia = null;
        $this->editJobType = $post->job_type ?? '';
        
        // Load existing specialties with eager loading
        $post->loadMissing(['specialties', 'tags']);
        
        $this->editSpecialties = [];
        foreach ($post->specialties as $specialty) {
            $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null;
            if ($subSpecialtyId) {
                $subSpecialty = \App\Models\SubSpecialty::find($subSpecialtyId);
                if ($subSpecialty) {
                    $this->editSpecialties[] = [
                        'specialty_name' => $specialty->name,
                        'sub_specialty_name' => $subSpecialty->name,
                    ];
                }
            }
        }
        
        // Load existing tags
        $this->editTags = $post->tags->map(fn($tag) => ['name' => $tag->name])->toArray();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingPostId = null;
        $this->editTitle = '';
        $this->editContent = '';
        $this->editMedia = null;
        $this->editSpecialties = [];
        $this->editSpecialtyName = '';
        $this->editSubSpecialtyName = '';
        $this->editTags = [];
        $this->editTagName = '';
    }

    public function openDeleteModal(int $postId, AuthorizePostAction $authorizePostAction, PostRepository $postRepository): void
    {
        try {
            $post = $postRepository->findById($postId);
            
            if (!$authorizePostAction->canDelete($post)) {
                session()->flash('error', 'You are not authorized to delete this post.');
                return;
            }

            $this->postToDelete = $postId;
            $this->showDeleteModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load post. Please try again.');
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->postToDelete = null;
    }

    public function addSpecialty(): void
    {
        $specialtyName = trim($this->specialtyName);
        $subSpecialtyName = trim($this->subSpecialtyName);

        if (empty($specialtyName) || empty($subSpecialtyName)) {
            return;
        }

        $exists = $this->specialtyExists($specialtyName, $subSpecialtyName, $this->specialties);

        if (!$exists) {
            $this->specialties[] = [
                'specialty_name' => $specialtyName,
                'sub_specialty_name' => $subSpecialtyName,
            ];
        }

        $this->specialtyName = '';
        $this->subSpecialtyName = '';
    }

    protected function specialtyExists(string $specialtyName, string $subSpecialtyName, array $specialties): bool
    {
        return collect($specialties)->contains(function ($spec) use ($specialtyName, $subSpecialtyName) {
            return strtolower(trim($spec['specialty_name'])) === strtolower($specialtyName)
                && strtolower(trim($spec['sub_specialty_name'])) === strtolower($subSpecialtyName);
        });
    }

    public function addEditSpecialty(): void
    {
        $specialtyName = trim($this->editSpecialtyName);
        $subSpecialtyName = trim($this->editSubSpecialtyName);

        if (empty($specialtyName) || empty($subSpecialtyName)) {
            return;
        }

        $exists = $this->specialtyExists($specialtyName, $subSpecialtyName, $this->editSpecialties);

        if (!$exists) {
            $this->editSpecialties[] = [
                'specialty_name' => $specialtyName,
                'sub_specialty_name' => $subSpecialtyName,
            ];
        }

        $this->editSpecialtyName = '';
        $this->editSubSpecialtyName = '';
    }

    public function addTag(): void
    {
        $tagName = trim($this->tagName);

        if (empty($tagName)) {
            return;
        }

        $exists = $this->tagExists($tagName, $this->tags);

        if (!$exists) {
            $this->tags[] = ['name' => $tagName];
        }

        $this->tagName = '';
    }

    protected function tagExists(string $tagName, array $tags): bool
    {
        return collect($tags)->contains(function ($tag) use ($tagName) {
            return strtolower(trim($tag['name'])) === strtolower($tagName);
        });
    }

    public function addEditTag(): void
    {
        $tagName = trim($this->editTagName);

        if (empty($tagName)) {
            return;
        }

        $exists = $this->tagExists($tagName, $this->editTags);

        if (!$exists) {
            $this->editTags[] = ['name' => $tagName];
        }

        $this->editTagName = '';
    }

    public function removeSpecialty(int $index): void
    {
        unset($this->specialties[$index]);
        $this->specialties = array_values($this->specialties);
    }

    public function removeEditSpecialty(int $index): void
    {
        unset($this->editSpecialties[$index]);
        $this->editSpecialties = array_values($this->editSpecialties);
    }

    public function removeTag(int $index): void
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function removeEditTag(int $index): void
    {
        unset($this->editTags[$index]);
        $this->editTags = array_values($this->editTags);
    }

    public function create(): void
    {
        $createPostAction = app(CreatePost::class);
        
        // Ensure properties are initialized
        $this->title = $this->title ?? '';
        $this->content = $this->content ?? '';
        $this->specialties = is_array($this->specialties) ? $this->specialties : [];
        $this->tags = is_array($this->tags) ? $this->tags : [];

        // Validate using Livewire validation class
        $this->validate(StorePostValidation::rules(), StorePostValidation::messages());

        // Trim values after validation passes
        $this->title = trim($this->title);
        $this->content = trim($this->content);

        // Trim specialty values
        foreach ($this->specialties as $index => $specialty) {
            if (isset($specialty['specialty_name'])) {
                $this->specialties[$index]['specialty_name'] = trim($specialty['specialty_name']);
            }
            if (isset($specialty['sub_specialty_name'])) {
                $this->specialties[$index]['sub_specialty_name'] = trim($specialty['sub_specialty_name']);
            }
        }

        // Trim tag values and remove empty ones
        foreach ($this->tags as $index => $tag) {
            if (isset($tag['name'])) {
                $this->tags[$index]['name'] = trim($tag['name']);
            }
        }
        $this->tags = array_values(array_filter($this->tags, fn($tag) => !empty($tag['name'] ?? '')));

        try {
            $createPostAction->create(
                new \App\DTO\PostData(
                    title: $this->title,
                    content: $this->content,
                    media: $this->media,
                    specialties: $this->specialties,
                    tags: $this->tags,
                    jobType: $this->jobType ?: null,
                )
            );

            session()->flash('success', 'Post created successfully!');
            $this->closeCreateForm();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create post. Please try again.');
        }
    }

    public function update(): void
    {
        $updatePostAction = app(UpdatePost::class);
        $authorizePostAction = app(AuthorizePostAction::class);
        
        // Ensure properties are initialized
        $this->editTitle = $this->editTitle ?? '';
        $this->editContent = $this->editContent ?? '';
        $this->editSpecialties = is_array($this->editSpecialties) ? $this->editSpecialties : [];
        $this->editTags = is_array($this->editTags) ? $this->editTags : [];

        // Validate using Livewire validation class
        $this->validate(UpdatePostValidation::rules(), UpdatePostValidation::messages());

        // Trim values after validation passes
        $this->editTitle = trim($this->editTitle);
        $this->editContent = trim($this->editContent);

        // Trim specialty values
        foreach ($this->editSpecialties as $index => $specialty) {
            if (isset($specialty['specialty_name'])) {
                $this->editSpecialties[$index]['specialty_name'] = trim($specialty['specialty_name']);
            }
            if (isset($specialty['sub_specialty_name'])) {
                $this->editSpecialties[$index]['sub_specialty_name'] = trim($specialty['sub_specialty_name']);
            }
        }

        // Trim tag values and remove empty ones
        foreach ($this->editTags as $index => $tag) {
            if (isset($tag['name'])) {
                $this->editTags[$index]['name'] = trim($tag['name']);
            }
        }
        $this->editTags = array_values(array_filter($this->editTags, fn($tag) => !empty($tag['name'] ?? '')));

        try {
            $post = app(PostRepository::class)->findById($this->editingPostId);
            
            if (!$authorizePostAction->canEdit($post)) {
                session()->flash('error', 'You are not authorized to update this post.');
                return;
            }

            $updatePostAction->update(
                $post,
                new \App\DTO\PostData(
                    title: $this->editTitle,
                    content: $this->editContent,
                    media: $this->editMedia,
                    specialties: $this->editSpecialties,
                    tags: $this->editTags,
                    jobType: $this->editJobType ?: null,
                )
            );

            session()->flash('success', 'Post updated successfully!');
            $this->closeEditModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update post. Please try again.');
            \Log::error('Failed to update post: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

    public function delete(DeletePost $deletePostAction, AuthorizePostAction $authorizePostAction, PostRepository $postRepository): void
    {
        try {
            $post = $postRepository->findById($this->postToDelete);
            
            if (!$authorizePostAction->canDelete($post)) {
                session()->flash('error', 'You are not authorized to delete this post.');
                return;
            }

            $deletePostAction->delete($post);

            session()->flash('success', 'Post deleted successfully!');
            $this->closeDeleteModal();
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete post. Please try again.');
        }
    }

    public function getMediaUrl(PostModel $post): ?string
    {
        return app(PostService::class)->getMediaUrl($post);
    }

    public function isFollowing(int $userId): bool
    {
        if (!Auth::check() || Auth::id() === $userId) {
            return false;
        }
        
        try {
            $userRepository = app(UserRepository::class);
            $followUserAction = app(FollowUser::class);
            $user = $userRepository->findById($userId);
            return $followUserAction->isFollowing($user);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function toggleFollow(int $userId): void
    {
        try {
            $followUserAction = app(FollowUser::class);
            $userRepository = app(UserRepository::class);
            $user = $userRepository->findById($userId);
            
            if ($followUserAction->isFollowing($user)) {
                $followUserAction->unfollow($user);
                session()->flash('success', 'You have unfollowed ' . $user->name);
            } else {
                $followUserAction->follow($user);
                session()->flash('success', 'You are now following ' . $user->name);
            }
            
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update follow status. Please try again.');
        }
    }

    public function togglePostLike(int $postId, LikePost $likePostAction, PostRepository $postRepository): void
    {
        try {
            $post = $postRepository->findById($postId);
            $likePostAction->toggle($post);
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to like post. Please try again.');
        }
    }
    
    public function togglePostStar(int $postId, \App\Actions\Post\StarPost $starPostAction, PostRepository $postRepository): void
    {
        try {
            $post = $postRepository->findById($postId);
            $starPostAction->toggle($post);
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to star post. Please try again.');
        }
    }

    public function handleRefreshPosts(): void
    {
        app(RefreshPostsListener::class)->handle($this);
    }

    public function handleNotificationsUpdated(): void
    {
        app(NotificationsUpdatedListener::class)->handle($this);
    }

    public function handleOpenCreatePost(): void
    {
        app(OpenCreatePostListener::class)->handle($this);
    }

    public function resetForm(): void
    {
        $this->title = '';
        $this->content = '';
        $this->media = null;
        $this->jobType = '';
        $this->specialties = [];
        $this->specialtyName = '';
        $this->subSpecialtyName = '';
        $this->tags = [];
        $this->tagName = '';
    }

    public function render(PostService $postService): View
    {
        $filterParams = [
            'sortOrder' => $this->sortOrder,
            'tags' => $this->selectedTags ? [$this->selectedTags] : [],
            'specialties' => $this->selectedSpecialties ? [$this->selectedSpecialties] : [],
            'jobType' => $this->selectedJobType,
        ];
        
        $posts = match ($this->feedMode) {
            'popular' => $postService->getPopularPosts(10, $filterParams),
            'following' => $postService->getFollowingPosts(10, $filterParams),
            default => $postService->getAllPosts(10, $filterParams),
        };
        
        // Get all available tags, specialties, and job types for filter dropdowns
        $allTags = \App\Models\Tag::orderBy('name')->get();
        $allSpecialties = \App\Models\Specialty::with('subSpecialties')->orderBy('name')->get();
        $jobTypes = ['full-time', 'part-time', 'contract', 'freelance', 'internship', 'remote'];

        return view('livewire.post', [
            'posts' => $posts,
            'allTags' => $allTags,
            'allSpecialties' => $allSpecialties,
            'jobTypes' => $jobTypes,
        ]);
    }
}
