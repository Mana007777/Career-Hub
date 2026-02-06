<?php

namespace App\Livewire;

use App\Models\PostCv;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Cvs extends Component
{
    use WithPagination;

    public function render(): View
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get all CVs for posts owned by the current user
        $cvs = PostCv::with(['post', 'user'])
            ->whereHas('post', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.cvs', [
            'cvs' => $cvs,
        ]);
    }

    public function downloadCv(PostCv $postCv): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $user = Auth::user();
        
        // Verify the CV belongs to a post owned by the current user
        if ($postCv->post->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $filePath = storage_path('app/public/' . $postCv->cv_file);
        
        if (!file_exists($filePath)) {
            abort(404, 'CV file not found');
        }

        return response()->download($filePath, $postCv->original_filename);
    }
}
