<?php

namespace App\Livewire;

use App\Repositories\PostCvRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Cvs extends Component
{
    use WithPagination;

    public function render(PostCvRepository $postCvRepository): View
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $cvs = $postCvRepository->getCvsForUserPosts($user->id, 10);

        return view('livewire.cvs', [
            'cvs' => $cvs,
        ]);
    }

    public function downloadCv(int $postCvId, PostCvRepository $postCvRepository): BinaryFileResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $postCv = $postCvRepository->findById($postCvId);
        
        if (!$postCv) {
            abort(404, 'CV not found');
        }

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
