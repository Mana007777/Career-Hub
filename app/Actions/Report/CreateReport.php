<?php

namespace App\Actions\Report;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateReport
{
    /**
     * Create a report.
     *
     * @param string $targetType (post, user, comment)
     * @param int $targetId
     * @param string $reason
     * @return Report
     */
    public function create(string $targetType, int $targetId, string $reason): Report
    {
        // Validate target type
        if (!in_array($targetType, ['post', 'user', 'comment'], true)) {
            throw new \InvalidArgumentException('Invalid target type. Must be post, user, or comment.');
        }

        // Check if user already reported this target
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            throw new \Exception('You have already reported this ' . $targetType . '.');
        }

        // Prevent users from reporting themselves
        if ($targetType === 'user' && $targetId === Auth::id()) {
            throw new \Exception('You cannot report yourself.');
        }

        // Prevent admins from reporting
        if (Auth::user()->isAdmin()) {
            throw new \Exception('Admins cannot submit reports.');
        }

        return Report::create([
            'reporter_id' => Auth::id(),
            'target_type' => $targetType,
            'target_id' => $targetId,
            'reason' => $reason,
            'status' => 'pending',
        ]);
    }
}
