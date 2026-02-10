<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FollowingSidebar extends Component
{
    public Collection $followingUsers;
    public int $followingCount = 0;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->followingUsers = collect();
            $this->followingCount = 0;
            return;
        }

        // Get excluded user IDs (both blocked and blocked by)
        $blockedIds = DB::table('blocks')
            ->where('blocker_id', $user->id)
            ->pluck('blocked_id')
            ->toArray();

        $blockedByIds = DB::table('blocks')
            ->where('blocked_id', $user->id)
            ->pluck('blocker_id')
            ->toArray();

        $excludedIds = array_unique(array_merge($blockedIds, $blockedByIds));

        // Get following user IDs
        $followingIds = $user->following()->pluck('following_id')->toArray();

        // Filter out excluded IDs
        if (! empty($excludedIds)) {
            $followingIds = array_diff($followingIds, $excludedIds);
        }

        // Get the filtered following users with profile
        if (! empty($followingIds)) {
            $this->followingUsers = User::whereIn('id', $followingIds)
                ->with('profile')
                ->get();
        } else {
            $this->followingUsers = collect();
        }

        $this->followingCount = $this->followingUsers->count();
    }

    public function render()
    {
        return view('livewire.following-sidebar');
    }
}

