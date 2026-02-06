# DEBUG: Property Mapping Analysis

## Livewire Component Properties (app/Livewire/Post.php)
- `public $title = '';` ✅
- `public $content = '';` ✅
- `public $specialties = [];` ✅
- `public $tags = [];` ✅
- `public $media;` ✅
- `public $specialtyName = '';` (temporary input, not validated)
- `public $subSpecialtyName = '';` (temporary input, not validated)
- `public $tagName = '';` (temporary input, not validated)

## Blade Template wire:model Bindings (resources/views/livewire/post.blade.php)
- `wire:model.live="title"` ✅ Matches `$title`
- `wire:model.live="content"` ✅ Matches `$content`
- `wire:model="specialtyName"` ✅ (temporary, not validated)
- `wire:model="subSpecialtyName"` ✅ (temporary, not validated)
- `wire:model="tagName"` ✅ (temporary, not validated)
- `wire:model="media"` ✅ Matches `$media`

## Validation Rules (app/Http/Requests/StorePostRequest.php)
- `'title' => ['required', 'string', 'max:255']` ✅ Matches `$title`
- `'content' => ['required', 'string', 'max:5000']` ✅ Matches `$content`
- `'specialties' => ['required', 'array', 'min:1']` ✅ Matches `$specialties`
- `'specialties.*.specialty_name' => ['required', 'string', 'max:255']` ✅
- `'specialties.*.sub_specialty_name' => ['required', 'string', 'max:255']` ✅
- `'tags' => ['nullable', 'array']` ✅ Matches `$tags`
- `'tags.*.name' => ['required', 'string', 'max:255']` ✅

## Form Submission
- `wire:submit.prevent="create"` ✅ Matches method `create()`
- Submit button: `type="submit"` ✅ Inside `<form>` ✅

## Potential Issues Identified:
1. **Alpine.js x-show**: Form is wrapped in `x-show="show"` which might hide form from DOM
2. **wire:model.live timing**: Values might not sync before validation if form is hidden
3. **Array initialization**: Arrays might be null instead of [] if component re-hydrates
