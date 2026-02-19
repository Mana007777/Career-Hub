<?php

namespace App\Actions\User;

use App\Exceptions\EndorsementException;
use App\Jobs\SendUserNotification;
use App\Models\Endorsement;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EndorseUser
{
    
    public function endorse(User $userToEndorse, string $skill): Endorsement
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            throw new EndorsementException('You must be logged in to endorse someone.');
        }

        if ($currentUser->id === $userToEndorse->id) {
            throw new EndorsementException('You cannot endorse yourself.');
        }

        $skill = trim($skill);
        if (empty($skill)) {
            throw new EndorsementException('Please specify a skill to endorse.');
        }

        if (strlen($skill) > 255) {
            throw new EndorsementException('The skill name is too long.');
        }

        $existing = Endorsement::where('user_id', $userToEndorse->id)
            ->where('endorsed_by', $currentUser->id)
            ->where('skill', $skill)
            ->first();

        if ($existing) {
            throw new EndorsementException('You have already endorsed this user for this skill.');
        }

        $endorsement = Endorsement::create([
            'user_id' => $userToEndorse->id,
            'endorsed_by' => $currentUser->id,
            'skill' => $skill,
        ]);

        SendUserNotification::dispatchSync([
            'user_id' => $userToEndorse->id,
            'source_user_id' => $currentUser->id,
            'type' => 'endorsement',
            'post_id' => null,
            'message' => "{$currentUser->name} endorsed you for {$skill}.",
        ]);

        return $endorsement;
    }

    
    public function removeEndorsement(User $userToUnendorse, string $skill): bool
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            throw new EndorsementException('You must be logged in to remove an endorsement.');
        }

        $deleted = Endorsement::where('user_id', $userToUnendorse->id)
            ->where('endorsed_by', $currentUser->id)
            ->where('skill', $skill)
            ->delete();

        return $deleted > 0;
    }

    
    public function hasEndorsed(User $user, string $skill): bool
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return false;
        }

        return Endorsement::where('user_id', $user->id)
            ->where('endorsed_by', $currentUser->id)
            ->where('skill', $skill)
            ->exists();
    }

   
    public function getEndorsableSkills(User $user): Collection
    {
        $skills = collect();

        $user->loadMissing(['specialties.subSpecialties']);

        foreach ($user->specialties as $specialty) {
            $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null;
            $subSpecialty = $subSpecialtyId
                ? $specialty->subSpecialties->firstWhere('id', $subSpecialtyId)
                : null;
            if ($subSpecialty) {
                $skills->push("{$specialty->name} – {$subSpecialty->name}");
            } else {
                $skills->push($specialty->name);
            }
        }

        
        $endorsedSkills = Endorsement::where('user_id', $user->id)
            ->distinct()
            ->pluck('skill');
        $skills = $skills->merge($endorsedSkills)->unique()->values();

        return $skills->sort()->values();
    }
}
