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
            abort(403, __('Unauthorized'));
        }

        $cvs = $user->isSeeker()
            ? $postCvRepository->getCvsUploadedByUser($user->id, 10)
            : $postCvRepository->getCvsForUserPosts($user->id, 10);

        return view('livewire.cvs', [
            'cvs' => $cvs,
            'isSeekerCvView' => $user->isSeeker(),
        ]);
    }

    public function downloadCv(int $postCvId, PostCvRepository $postCvRepository): BinaryFileResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, __('Unauthorized'));
        }

        $postCv = $postCvRepository->findById($postCvId);
        
        if (!$postCv) {
            abort(404, __('CV not found'));
        }

        $postCv->loadMissing('post');

        $ownsPost = $postCv->post && $postCv->post->user_id === $user->id;
        $isOwnUpload = $postCv->user_id === $user->id;

        if (! $ownsPost && ! $isOwnUpload) {
            abort(403, __('Unauthorized'));
        }

        $filePath = storage_path('app/public/' . $postCv->cv_file);
        
        if (!file_exists($filePath)) {
            abort(404, __('CV file not found'));
        }

        return response()->download($filePath, $postCv->original_filename);
    }
}
