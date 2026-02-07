<?php

namespace App\Livewire;

use App\Actions\Report\CreateReport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ReportModal extends Component
{
    use AuthorizesRequests;

    public $show = false;
    public $targetType = ''; // post, user, comment
    public $targetId = null;
    public $selectedReason = '';
    public $customReason = '';
    
    // Report reasons (similar to Facebook/Instagram)
    public $reasons = [
        'post' => [
            'spam' => 'Spam',
            'false_information' => 'False Information',
            'harassment' => 'Harassment or Bullying',
            'hate_speech' => 'Hate Speech',
            'violence' => 'Violence or Dangerous Organizations',
            'intellectual_property' => 'Intellectual Property Violation',
            'nudity' => 'Nudity or Sexual Content',
            'other' => 'Something Else',
        ],
        'user' => [
            'spam' => 'Spam',
            'fake_account' => 'Fake Account',
            'harassment' => 'Harassment or Bullying',
            'hate_speech' => 'Hate Speech',
            'violence' => 'Violence or Dangerous Organizations',
            'impersonation' => 'Impersonation',
            'other' => 'Something Else',
        ],
        'comment' => [
            'spam' => 'Spam',
            'harassment' => 'Harassment or Bullying',
            'hate_speech' => 'Hate Speech',
            'false_information' => 'False Information',
            'nudity' => 'Nudity or Sexual Content',
            'other' => 'Something Else',
        ],
    ];

    // No listeners - we'll use Alpine.js to call methods directly
    
    public function openModal(string $targetType, int $targetId): void
    {
        $this->open($targetType, $targetId);
    }

    public function open(string $targetType, int $targetId): void
    {
        if (!in_array($targetType, ['post', 'user', 'comment'], true)) {
            return;
        }

        $this->targetType = $targetType;
        $this->targetId = $targetId;
        $this->show = true;
        $this->selectedReason = '';
        $this->customReason = '';
    }

    public function close(): void
    {
        $this->show = false;
        $this->targetType = '';
        $this->targetId = null;
        $this->selectedReason = '';
        $this->customReason = '';
    }

    public function submit(): void
    {
        $this->validate([
            'selectedReason' => ['required', 'string'],
            'customReason' => ['required_if:selectedReason,other', 'string', 'max:500'],
        ], [
            'selectedReason.required' => 'Please select a reason for reporting.',
            'customReason.required_if' => 'Please provide a reason for reporting.',
            'customReason.max' => 'The reason must not exceed 500 characters.',
        ]);

        try {
            $reason = $this->selectedReason === 'other' 
                ? $this->customReason 
                : ($this->reasons[$this->targetType][$this->selectedReason] ?? $this->selectedReason);

            app(CreateReport::class)->create(
                $this->targetType,
                $this->targetId,
                $reason
            );

            session()->flash('success', 'Report submitted successfully. We will review it shortly.');
            $this->close();
            $this->dispatch('reportSubmitted');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $availableReasons = $this->reasons[$this->targetType] ?? [];
        
        return view('livewire.report-modal', [
            'availableReasons' => $availableReasons,
        ]);
    }
}
