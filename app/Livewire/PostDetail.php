<?php

namespace App\Livewire;

use App\Actions\Comment\AddComment;
use App\Actions\Comment\AddReply;
use App\Actions\Comment\LikeComment;
use App\Actions\Post\LikePost;
use App\Actions\Post\UploadPostCv;
use App\Livewire\Validations\AddCommentValidation;
use App\Livewire\Validations\AddReplyValidation;
use App\Models\Comment;
use App\Models\Post as PostModel;
use App\Repositories\PostCvRepository;
use App\Services\PostService;
use App\Models\UserNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostDetail extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public $slug;
    public $post;
    public $content = '';
    public $replyContent = [];
    public $showLikersModal = false;
    public $showSuspendModal = false;
    public $suspendReason = '';
    public $suspendExpiresAt = null;
    public $cvFile;
    public $cvMessage = '';
    public $showCvUpload = false;
    public $hasUploadedCv = false;

    public function mount(string $slug, PostService $postService, PostCvRepository $postCvRepository): void
    {
        $this->slug = $slug;
        $this->loadPost($postService, $postCvRepository);
    }

    public function hydrate(PostCvRepository $postCvRepository): void
    {
        // Re-check CV upload status on re-hydration
        if ($this->post && Auth::check() && $this->post->job_type) {
            $this->hasUploadedCv = $postCvRepository->hasUserUploadedCv($this->post->id, Auth::id());
        }
    }

    protected function loadPost(PostService $postService, PostCvRepository $postCvRepository): void
    {
        // Extract ID from slug (format: slug-text-123)
        $parts = explode('-', $this->slug);
        $id = end($parts);

        if (!is_numeric($id)) {
            abort(404, 'Invalid post slug');
        }

        $this->post = $postService->getPostById((int) $id);
        
        if (!$this->post) {
            abort(404, 'Post not found');
        }

        // Ensure suspension relationship is loaded
        if (!$this->post->relationLoaded('suspension')) {
            $this->post->load('suspension');
        }

        // If post is suspended and current user is not admin, show 404
        if ($this->post->isSuspended() && (!Auth::check() || !Auth::user()->isAdmin())) {
            abort(404, 'Post not found');
        }

        // If post author is suspended and current user is not admin, show 404
        if ($this->post->user && $this->post->user->isSuspended() && (!Auth::check() || !Auth::user()->isAdmin())) {
            abort(404, 'Post not found');
        }

        // Check if current user has already uploaded a CV for this post
        if (Auth::check() && $this->post->job_type) {
            $this->hasUploadedCv = $postCvRepository->hasUserUploadedCv($this->post->id, Auth::id());
        }
    }

    public function getMediaUrl(PostModel $post): ?string
    {
        return app(PostService::class)->getMediaUrl($post);
    }

    public function togglePostLike(LikePost $likePostAction): void
    {
        try {
            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            $likePostAction->toggle($this->post);
            $this->post->refresh()->loadMissing(['likes.user', 'likedBy']);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to like post. Please try again.');
        }
    }
    
    public function togglePostStar(\App\Actions\Post\StarPost $starPostAction): void
    {
        try {
            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            $starPostAction->toggle($this->post);
            $this->post->refresh()->loadMissing(['stars.user', 'starredBy']);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to star post. Please try again.');
        }
    }

    public function addComment(): void
    {
        $addCommentAction = app(AddComment::class);
        
        try {
            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            // Ensure property is initialized
            $this->content = $this->content ?? '';

            // Validate using Livewire validation class
            $this->validate(AddCommentValidation::rules(), AddCommentValidation::messages());

            $addCommentAction->create($this->post, $this->content);

            $this->content = '';
            $this->post->refresh()->loadMissing([
                'comments.user',
                'comments.likes',
                'comments.replies.user',
                'comments.replies.likes'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add comment. Please try again.');
        }
    }

    public function addReply(int $parentId): void
    {
        $addReplyAction = app(AddReply::class);
        
        try {
            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            // Ensure replyContent array is initialized
            if (!is_array($this->replyContent)) {
                $this->replyContent = [];
            }

            $content = trim($this->replyContent[$parentId] ?? '');
            if (empty($content)) {
                return;
            }

            // Validate using Livewire validation class
            // For nested array properties, we need to validate the specific key
            $rules = AddReplyValidation::rules();
            $messages = AddReplyValidation::messages();
            $this->validate([
                "replyContent.{$parentId}" => $rules['content']
            ], [
                "replyContent.{$parentId}.required" => $messages['content.required'],
                "replyContent.{$parentId}.max" => $messages['content.max'],
            ]);

            $addReplyAction->create($this->post, $parentId, $content);

            $this->replyContent[$parentId] = '';
            $this->post->refresh()->loadMissing([
                'comments.user',
                'comments.likes',
                'comments.replies.user',
                'comments.replies.likes'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to add reply. Please try again.');
        }
    }

    public function toggleCommentLike(int $commentId, LikeComment $likeCommentAction): void
    {
        try {
            $comment = Comment::findOrFail($commentId);
            $likeCommentAction->toggle($comment);
            $this->post->refresh()->loadMissing(['comments.likes', 'comments.replies.likes']);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to like comment. Please try again.');
        }
    }

    public function toggleLikersModal(): void
    {
        if (!$this->showLikersModal && $this->post) {
            // Ensure likedBy relationship is loaded when opening the modal
            $this->post->loadMissing('likedBy');
        }
        $this->showLikersModal = !$this->showLikersModal;
    }

    public function toggleCvUpload(): void
    {
        $this->showCvUpload = !$this->showCvUpload;
        if (!$this->showCvUpload) {
            $this->cvFile = null;
            $this->cvMessage = '';
        }
    }

    public function uploadCv(): void
    {
        $uploadCvAction = app(UploadPostCv::class);
        
        try {
            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            // Validate CV file
            $this->validate([
                'cvFile' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
                'cvMessage' => ['nullable', 'string', 'max:1000'],
            ], [
                'cvFile.required' => 'Please select a CV file to upload.',
                'cvFile.file' => 'The CV must be a valid file.',
                'cvFile.mimes' => 'The CV must be a PDF, DOC, or DOCX file.',
                'cvFile.max' => 'The CV file size must not exceed 5MB.',
                'cvMessage.max' => 'The message may not be greater than 1000 characters.',
            ]);

            $uploadCvAction->upload($this->post, $this->cvFile, $this->cvMessage);

            session()->flash('success', 'CV uploaded successfully!');
            $this->cvFile = null;
            $this->cvMessage = '';
            $this->showCvUpload = false;
            $this->hasUploadedCv = true; // Update the flag after successful upload
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to upload CV. Please try again.');
        }
    }

    public function deletePostAsAdmin(int $postId): void
    {
        try {
            $post = \App\Models\Post::findOrFail($postId);
            
            // Use policy for authorization
            $this->authorize('delete', $post);
            
            // Delete the post
            app(\App\Actions\Post\DeletePost::class)->delete($post);
            
            session()->flash('success', 'Post deleted successfully!');
            $this->redirect(route('dashboard'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            session()->flash('error', 'You are not authorized to delete this post.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete post. Please try again.');
        }
    }

    public function deleteCommentAsAdmin(int $commentId): void
    {
        try {
            $comment = \App\Models\Comment::findOrFail($commentId);
            
            // Use policy for authorization
            $this->authorize('delete', $comment);
            
            // Delete the comment
            $comment->delete();
            
            // Refresh the post to update comments
            $this->post->refresh()->loadMissing([
                'comments.user',
                'comments.likes',
                'comments.replies.user',
                'comments.replies.likes'
            ]);
            
            session()->flash('success', 'Comment deleted successfully!');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            session()->flash('error', 'You are not authorized to delete this comment.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete comment. Please try again.');
        }
    }

    public function openSuspendModal(): void
    {
        try {
            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to suspend posts.');
                return;
            }

            $this->suspendReason = '';
            $this->suspendExpiresAt = null;
            $this->showSuspendModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load suspension form. Please try again.');
        }
    }

    public function closeSuspendModal(): void
    {
        $this->showSuspendModal = false;
        $this->suspendReason = '';
        $this->suspendExpiresAt = null;
    }

    public function suspendPost(): void
    {
        try {
            \Log::info('suspendPost method called (PostDetail)', [
                'post_id' => $this->post->id ?? null,
                'admin_id' => Auth::id(),
                'suspendReason' => $this->suspendReason ?? 'empty',
                'suspendExpiresAt' => $this->suspendExpiresAt ?? 'empty',
            ]);

            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                \Log::error('Post not found in suspendPost (PostDetail)');
                return;
            }

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to suspend posts.');
                \Log::error('Not authorized to suspend post (PostDetail)', ['admin_id' => Auth::id()]);
                return;
            }

            // Normalize empty string to null for expires_at
            if (empty($this->suspendExpiresAt) || $this->suspendExpiresAt === '') {
                $this->suspendExpiresAt = null;
            }

            $rules = [
                'suspendReason' => 'required|string|max:1000',
            ];
            
            $messages = [
                'suspendReason.required' => 'Please provide a reason for suspending this post.',
                'suspendReason.max' => 'The reason cannot exceed 1000 characters.',
            ];

            // Only validate expires_at if it's not null
            if ($this->suspendExpiresAt !== null) {
                $rules['suspendExpiresAt'] = 'required|date|after:now';
                $messages['suspendExpiresAt.required'] = 'If provided, expiration date is required.';
                $messages['suspendExpiresAt.after'] = 'The expiration date must be in the future.';
            }

            $this->validate($rules, $messages);

            $expiresAt = null;
            if (!empty($this->suspendExpiresAt)) {
                try {
                    // Handle datetime-local format (Y-m-d\TH:i)
                    $expiresAt = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $this->suspendExpiresAt);
                } catch (\Exception $e) {
                    // Try alternative format
                    try {
                        $expiresAt = \Carbon\Carbon::parse($this->suspendExpiresAt);
                    } catch (\Exception $e2) {
                        \Log::error('Failed to parse expiration date', [
                            'date' => $this->suspendExpiresAt,
                            'error' => $e2->getMessage(),
                        ]);
                        throw new \Exception('Invalid expiration date format.');
                    }
                }
            }

            $suspension = \App\Models\PostSuspension::updateOrCreate(
                ['post_id' => $this->post->id],
                [
                    'reason' => trim($this->suspendReason),
                    'expires_at' => $expiresAt,
                ]
            );

            \Log::info('Post suspension created (PostDetail)', [
                'suspension_id' => $suspension->post_id,
                'reason' => $suspension->reason,
                'expires_at' => $suspension->expires_at,
            ]);

            // Clear cached detail data for this post so it stops showing on detail page
            app(\App\Queries\PostQueries::class)->clearPostCache($this->post->id);

            // Notify the post owner about the suspension (immediately, no queue required)
            if ($this->post->user_id) {
                UserNotification::create([
                    'user_id' => $this->post->user_id,
                    'source_user_id' => Auth::id(),
                    'type' => 'post_suspended',
                    'post_id' => $this->post->id,
                    'message' => sprintf(
                        'Your post "%s" has been suspended by an administrator. Reason: %s',
                        $this->post->title ?: 'Post #' . $this->post->id,
                        trim($this->suspendReason)
                    ),
                    'is_read' => false,
                ]);
            }

            // Log admin action
            \App\Models\AdminLog::create([
                'admin_id' => Auth::id(),
                'action' => 'Suspended post: ' . ($this->post->title ?: 'Post #' . $this->post->id) . ' - Reason: ' . $this->suspendReason,
                'target_type' => \App\Models\Post::class,
                'target_id' => $this->post->id,
            ]);

            session()->flash('success', 'Post suspended successfully!');
            $this->closeSuspendModal();
            $this->post->refresh();
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in suspendPost (PostDetail)', [
                'errors' => $e->errors(),
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error in suspendPost (PostDetail): ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to suspend post: ' . $e->getMessage());
        }
    }

    public function unsuspendPost(): void
    {
        try {
            if (!$this->post) {
                session()->flash('error', 'Post not found.');
                return;
            }

            if (!Auth::check() || !Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to unsuspend posts.');
                return;
            }

            $this->post->suspension?->delete();

            // Log admin action
            \App\Models\AdminLog::create([
                'admin_id' => Auth::id(),
                'action' => 'Unsuspended post: ' . ($this->post->title ?: 'Post #' . $this->post->id),
                'target_type' => \App\Models\Post::class,
                'target_id' => $this->post->id,
            ]);

            session()->flash('success', 'Post unsuspended successfully!');
            $this->post->refresh();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to unsuspend post. Please try again.');
        }
    }

    public function render(): View
    {
        return view('livewire.post-detail');
    }
}
