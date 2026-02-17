<?php

namespace App\Repositories;

use App\Models\Endorsement;
use App\Models\User;
use Illuminate\Support\Collection;

class EndorsementRepository
{
    /**
     * Get endorsements received by a user, grouped by skill with count and endorser info.
     *
     * @return Collection<int, object{skill: string, count: int, endorsers: Collection}>
     */
    public function getEndorsementsBySkillForUser(User $user): Collection
    {
        return Endorsement::where('user_id', $user->id)
            ->with('endorser:id,name,username,profile_photo_path')
            ->get()
            ->groupBy('skill')
            ->map(function (Collection $endorsements, string $skill) {
                return (object) [
                    'skill' => $skill,
                    'count' => $endorsements->count(),
                    'endorsers' => $endorsements->pluck('endorser')->filter(),
                ];
            })
            ->sortByDesc('count')
            ->values();
    }

    /**
     * Get total endorsement count for a user.
     */
    public function getEndorsementCountForUser(User $user): int
    {
        return Endorsement::where('user_id', $user->id)->count();
    }
}
