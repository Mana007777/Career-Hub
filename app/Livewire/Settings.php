<?php

namespace App\Livewire;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\User\BlockUser;
use App\Repositories\BlockRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;

    public $blockedUsers = [];
    public $showBlocksModal = false;
    public $showProfileModal = false;
    
    // Profile fields
    public $name;
    public $email;
    public $username;
    public $bio;
    public $location;
    public $website;
    public $photo;

    public function mount(): void
    {
        $this->loadBlockedUsers();
        $this->loadProfileData();
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

    public function openBlocksModal(): void
    {
        $this->loadBlockedUsers();
        $this->showBlocksModal = true;
    }

    public function closeBlocksModal(): void
    {
        $this->showBlocksModal = false;
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

    public function render(): View
    {
        return view('livewire.settings');
    }
}
