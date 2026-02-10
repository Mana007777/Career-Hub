<?php

namespace App\Livewire;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\User\BlockUser;
use App\Models\Report;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Repositories\BlockRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Settings extends Component
{
    use WithFileUploads, WithPagination;

    public $blockedUsers = [];
    public $showBlocksModal = false;
    public $showProfileModal = false;
    public $showReportsModal = false;
    public $showSuspendedItemsModal = false;
    
    // Profile fields
    public $name;
    public $email;
    public $username;
    public $bio;
    public $location;
    public $website;
    public $photo;
    
    // Theme preference
    public $themePreference = 'system';

    public function mount(): void
    {
        $this->loadBlockedUsers();
        $this->loadProfileData();
        $this->loadThemePreference();
    }

    protected function loadProfileData(): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        
        if ($user->profile) {
            $this->bio = $user->profile->bio;
            $this->location = $user->profile->location;
            $this->website = $user->profile->website;
        }
    }

    protected function loadThemePreference(): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $this->themePreference = $user->theme_preference ?? 'system';
    }

    public function openBlocksModal(): void
    {
        $this->loadBlockedUsers();
        $this->showBlocksModal = true;
    }

    public function closeBlocksModal(): void
    {
        $this->showBlocksModal = false;
    }

    public function openReportsModal(): void
    {
        $this->showReportsModal = true;
        $this->resetPage();
    }

    public function closeReportsModal(): void
    {
        $this->showReportsModal = false;
    }

    public function openSuspendedItemsModal(): void
    {
        $this->showSuspendedItemsModal = true;
        $this->resetPage();
    }

    public function closeSuspendedItemsModal(): void
    {
        $this->showSuspendedItemsModal = false;
    }

    protected function loadBlockedUsers(): void
    {
        if (!Auth::check()) {
            $this->blockedUsers = [];
            return;
        }

        $blockRepository = app(BlockRepository::class);
        $this->blockedUsers = $blockRepository->getBlockedUsers(Auth::id());
    }

    public function unblockUser(int $userId, BlockUser $blockUserAction): void
    {
        try {
            $userToUnblock = \App\Models\User::findOrFail($userId);
            $blockUserAction->unblock($userToUnblock);
            $this->loadBlockedUsers();
            session()->flash('success', 'You have unblocked ' . $userToUnblock->name);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function openProfileModal(): void
    {
        $this->loadProfileData();
        $this->showProfileModal = true;
    }

    public function closeProfileModal(): void
    {
        $this->showProfileModal = false;
        $this->photo = null;
    }

    public function updateProfile(UpdateUserProfileInformation $updateProfileAction): void
    {
        try {
            $user = Auth::user();
            
            $input = [
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'bio' => $this->bio,
                'location' => $this->location,
                'website' => $this->website,
            ];

            if ($this->photo) {
                $input['photo'] = $this->photo;
            }

            $updateProfileAction->update($user, $input);
            
            $this->loadProfileData();
            $this->showProfileModal = false;
            $this->photo = null;
            
            session()->flash('success', 'Profile updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Validation failed: ' . implode(', ', $e->errors()['updateProfileInformation'] ?? ['Please check your input']));
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function deleteProfilePhoto(): void
    {
        $user = Auth::user();
        $user->deleteProfilePhoto();
        $this->loadProfileData();
        session()->flash('success', 'Profile photo removed successfully!');
    }

    public function updateThemePreference(): void
    {
        try {
            $user = Auth::user();
            $user->theme_preference = $this->themePreference;
            $user->save();
            
            session()->flash('success', 'Theme preference updated successfully!');
            
            // Dispatch browser event to update theme immediately
            $this->dispatch('theme-updated', theme: $this->themePreference);
            
            // Also dispatch a JavaScript event for immediate update
            $this->js("window.dispatchEvent(new CustomEvent('theme-updated', { detail: { theme: '{$this->themePreference}' } }))");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update theme preference: ' . $e->getMessage());
        }
    }

    public function unsuspendUser(int $userId): void
    {
        try {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to perform this action.');
                return;
            }

            $user = User::findOrFail($userId);
            $user->suspension?->delete();

            \App\Models\AdminLog::create([
                'admin_id' => Auth::id(),
                'action' => 'Unsuspended user: ' . $user->name,
                'target_type' => User::class,
                'target_id' => $user->id,
            ]);

            session()->flash('success', 'User unsuspended successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to unsuspend user. Please try again.');
        }
    }

    public function unsuspendPost(int $postId): void
    {
        try {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                session()->flash('error', 'You are not authorized to perform this action.');
                return;
            }

            $post = Post::findOrFail($postId);
            $post->suspension?->delete();

            \App\Models\AdminLog::create([
                'admin_id' => Auth::id(),
                'action' => 'Unsuspended post: ' . ($post->title ?: 'Post #' . $post->id),
                'target_type' => Post::class,
                'target_id' => $post->id,
            ]);

            session()->flash('success', 'Post unsuspended successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to unsuspend post. Please try again.');
        }
    }

    public function render(): View
    {
        $reports = collect([]);
        $suspendedUsers = collect([]);
        $suspendedPosts = collect([]);
        
        // Only load reports for non-admin users
        if (Auth::check() && !Auth::user()->isAdmin()) {
            $reports = Report::where('reporter_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        // Load suspended items for admins
        if (Auth::check() && Auth::user()->isAdmin()) {
            $suspendedUsers = User::whereHas('suspension', function($query) {
                $query->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                });
            })->with('suspension')->orderBy('created_at', 'desc')->get();

            $suspendedPosts = Post::whereHas('suspension', function($query) {
                $query->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                });
            })->with(['suspension', 'user'])->orderBy('created_at', 'desc')->get();
            
            // Load targets for each report
            foreach ($reports as $report) {
                $modelClass = $report->getTargetModelClass();
                if ($modelClass) {
                    if ($modelClass === Post::class) {
                        $target = Post::with('user')->find($report->target_id);
                    } elseif ($modelClass === Comment::class) {
                        $target = Comment::with('user')->find($report->target_id);
                    } elseif ($modelClass === User::class) {
                        $target = User::find($report->target_id);
                    } else {
                        $target = $modelClass::find($report->target_id);
                    }
                    $report->setRelation('target', $target);
                }
            }
        }

        return view('livewire.settings', [
            'reports' => $reports,
            'suspendedUsers' => $suspendedUsers,
            'suspendedPosts' => $suspendedPosts,
        ]);
    }
}
