<?php

namespace App\Actions\User;

use App\Exceptions\UserBlockException;
use App\Models\User;
use App\Queries\ChatQueries;
use App\Repositories\BlockRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlockUser
{
    public function block(User $userToBlock): bool
    {
        $currentUser = Auth::user();

        
        if ($currentUser->id === $userToBlock->id) {
            throw new UserBlockException('You cannot block yourself.');
        }

        
        if ($currentUser->blockedUsers()->where('blocked_id', $userToBlock->id)->exists()) {
            throw new UserBlockException('You have already blocked this user.');
        }

        DB::transaction(function () use ($currentUser, $userToBlock) {
            
            $currentUser->blockedUsers()->attach($userToBlock->id);

            
            if ($currentUser->following()->where('following_id', $userToBlock->id)->exists()) {
                $currentUser->following()->detach($userToBlock->id);
            }
            if ($userToBlock->following()->where('following_id', $currentUser->id)->exists()) {
                $userToBlock->following()->detach($currentUser->id);
            }
        });

        // Clear cache
        $userRepository = app(UserRepository::class);
        $userRepository->clearUserCache($currentUser);
        $userRepository->clearUserCache($userToBlock);
        
        $blockRepository = app(BlockRepository::class);
        $blockRepository->clearBlockCache($currentUser->id);

        return true;
    }

    public function unblock(User $userToUnblock): bool
    {
        $currentUser = Auth::user();

        // Check if actually blocked
        if (!$currentUser->blockedUsers()->where('blocked_id', $userToUnblock->id)->exists()) {
            throw new UserBlockException('You have not blocked this user.');
        }

        DB::transaction(function () use ($currentUser, $userToUnblock) {
            // Remove block relationship
            $currentUser->blockedUsers()->detach($userToUnblock->id);

            // Unfollow each other (reset follow relationships)
            if ($currentUser->following()->where('following_id', $userToUnblock->id)->exists()) {
                $currentUser->following()->detach($userToUnblock->id);
            }
            if ($userToUnblock->following()->where('following_id', $currentUser->id)->exists()) {
                $userToUnblock->following()->detach($currentUser->id);
            }

            // Find and delete the chat between them (cascades to messages and message_reads)
            $chatQueries = app(ChatQueries::class);
            $chat = $chatQueries->findChatBetweenUsers($currentUser, $userToUnblock);
            
            if ($chat) {
                // Delete the chat (cascade will delete messages and message_reads)
                $chat->delete();
                
                // Clear chat cache for both users
                $chatQueries->clearUserChatCache($currentUser->id);
                $chatQueries->clearUserChatCache($userToUnblock->id);
            }

            // Delete any pending chat requests between them
            // Delete requests from current user to the unblocked user
            DB::table('chat_requests')
                ->where('from_user_id', $currentUser->id)
                ->where('to_user_id', $userToUnblock->id)
                ->delete();
            
            // Delete requests from unblocked user to current user
            DB::table('chat_requests')
                ->where('from_user_id', $userToUnblock->id)
                ->where('to_user_id', $currentUser->id)
                ->delete();
        });

        // Clear cache
        $userRepository = app(UserRepository::class);
        $userRepository->clearUserCache($currentUser);
        $userRepository->clearUserCache($userToUnblock);
        
        $blockRepository = app(BlockRepository::class);
        $blockRepository->clearBlockCache($currentUser->id);

        return true;
    }

    public function isBlocked(User $user): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        return $currentUser->blockedUsers()->where('blocked_id', $user->id)->exists();
    }

    public function isBlockedBy(User $user): bool
    {
        $currentUser = Auth::user();
        
        if (!$currentUser) {
            return false;
        }

        return $user->blockedUsers()->where('blocked_id', $currentUser->id)->exists();
    }
}
