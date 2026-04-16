<?php

namespace App\Livewire;

use App\Models\Report;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Actions\Post\DeletePost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination, AuthorizesRequests;

    public $selectedReport = null;
    public $showActionModal = false;
    public $actionType = ''; // delete, dismiss

    public function mount(): void
    {
        // Only admins can access this page
        if (!Auth::check() || !Auth::user()?->isAdmin()) {
            abort(403, __('Unauthorized'));
        }
    }

    public function openActionModal(int $reportId, string $actionType): void
    {
        $report = Report::with(['reporter'])->findOrFail($reportId);
        
        // Load target properly
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
        
        $this->selectedReport = $report;
        $this->actionType = $actionType;
        $this->showActionModal = true;
    }

    public function closeActionModal(): void
    {
        $this->selectedReport = null;
        $this->actionType = '';
        $this->showActionModal = false;
    }

    public function executeAction(): void
    {
        if (!$this->selectedReport) {
            return;
        }

        try {
            if ($this->actionType === 'delete') {
                $target = $this->selectedReport->target;
                
                if (!$target) {
                    session()->flash('error', __('Target not found. It may have been already deleted.'));
                    $this->selectedReport->update(['status' => 'dismissed']);
                    $this->closeActionModal();
                    $this->resetPage();
                    return;
                }

                if ($target instanceof Post) {
                    $this->authorize('delete', $target);
                    app(DeletePost::class)->delete($target);
                } elseif ($target instanceof Comment) {
                    $this->authorize('delete', $target);
                    $target->delete();
                } elseif ($target instanceof User) {
                    $this->authorize('delete', $target);
                    if (Auth::id() === $target->id) {
                        session()->flash('error', __('You cannot delete your own admin account.'));
                        $this->closeActionModal();
                        return;
                    }
                    app(\Laravel\Jetstream\Contracts\DeletesUsers::class)->delete($target);
                }

                // Mark report as resolved
                $this->selectedReport->update(['status' => 'resolved']);
                session()->flash('success', __(':type deleted successfully.', ['type' => ucfirst($this->selectedReport->target_type)]));
            } elseif ($this->actionType === 'dismiss') {
                // Mark report as dismissed
                $this->selectedReport->update(['status' => 'dismissed']);
                session()->flash('success', __('Report dismissed.'));
            }

            $this->closeActionModal();
            $this->resetPage();
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            session()->flash('error', __('You are not authorized to perform this action.'));
            $this->closeActionModal();
        } catch (\Exception $e) {
            session()->flash('error', __('Failed to execute action: :error', ['error' => $e->getMessage()]));
            Log::error('Report action failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    public function render()
    {
        $reports = Report::with(['reporter'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Load targets separately for each report type with proper relationships
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

        return view('livewire.reports', [
            'reports' => $reports,
        ]);
    }
}
