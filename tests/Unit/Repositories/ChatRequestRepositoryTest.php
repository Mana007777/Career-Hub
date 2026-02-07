<?php

use App\Repositories\ChatRequestRepository;
use App\Models\ChatRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('chat request repository can find request by id', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    $request = ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
    ]);
    
    $repository = new ChatRequestRepository();
    $found = $repository->findById($request->id);
    
    expect($found)->toBeInstanceOf(ChatRequest::class)
        ->and($found->id)->toBe($request->id);
});

test('chat request repository can get pending requests for user', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
        'status' => 'pending',
    ]);
    
    $repository = new ChatRequestRepository();
    $requests = $repository->getPendingRequestsForUser($toUser->id);
    
    expect($requests)->toHaveCount(1);
});

test('chat request repository can get pending request between users', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    $request = ChatRequest::factory()->create([
        'from_user_id' => $fromUser->id,
        'to_user_id' => $toUser->id,
        'status' => 'pending',
    ]);
    
    $repository = new ChatRequestRepository();
    $found = $repository->getPendingRequest($fromUser->id, $toUser->id);
    
    expect($found)->toBeInstanceOf(ChatRequest::class)
        ->and($found->id)->toBe($request->id);
});

test('chat request repository can update or create request', function () {
    $fromUser = User::factory()->create();
    $toUser = User::factory()->create();
    
    $repository = new ChatRequestRepository();
    $request = $repository->updateOrCreate(
        ['from_user_id' => $fromUser->id, 'to_user_id' => $toUser->id],
        ['status' => 'pending']
    );
    
    expect($request)->toBeInstanceOf(ChatRequest::class)
        ->and($request->status)->toBe('pending');
});
