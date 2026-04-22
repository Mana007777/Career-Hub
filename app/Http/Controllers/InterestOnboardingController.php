<?php

namespace App\Http\Controllers;

use App\Services\AiRecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InterestOnboardingController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if (! $user || ! $user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $tags = \App\Models\Tag::query()
            ->orderBy('name')
            ->pluck('name')
            ->take(120)
            ->values();

        return view('interests.onboarding', [
            'suggestedTags' => $tags,
            'selectedTags' => $user->ai_interest_tags ?? [],
        ]);
    }

    public function store(Request $request, AiRecommendationService $aiService): RedirectResponse
    {
        $validated = $request->validate([
            'interests' => ['required', 'string', 'max:1000'],
        ]);

        $tags = collect(explode(',', (string) $validated['interests']))
            ->map(fn (string $tag) => Str::lower(trim($tag)))
            ->filter(fn (string $tag) => $tag !== '')
            ->unique()
            ->take(25)
            ->values()
            ->all();

        if ($tags === []) {
            return back()->withErrors([
                'interests' => 'Please add at least one interest tag.',
            ])->withInput();
        }

        $user = $request->user();
        $user->forceFill([
            'ai_interest_tags' => $tags,
        ])->save();

        $aiService->registerUser($user, $tags);
        $aiService->updateUserInterests($user, $tags);

        return redirect()->route('dashboard')->with('success', 'Your interests were saved. AI recommendations are now personalized for you.');
    }
}
