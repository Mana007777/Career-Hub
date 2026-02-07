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

    public function render(): View
    {
        return view('livewire.post-detail');
    }
}
