<?php

namespace App\Actions\Report;

use App\Exceptions\ReportException;
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
        
        if (!in_array($targetType, ['post', 'user', 'comment'], true)) {
            throw new \InvalidArgumentException('Invalid target type. Must be post, user, or comment.');
        }

        
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->where('status', 'pending')
            ->first();

        if ($existingReport) {
            throw new ReportException('You have already reported this ' . $targetType . '.');
        }

    
        if ($targetType === 'user' && $targetId === Auth::id()) {
            throw new ReportException('You cannot report yourself.');
        }

        
        if (Auth::user()->isAdmin()) {
            throw new ReportException('Admins cannot submit reports.');
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
