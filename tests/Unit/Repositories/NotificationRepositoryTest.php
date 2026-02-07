<?php

use App\Repositories\NotificationRepository;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('notification repository can get notifications for user', function () {
    $user = User::factory()->create();
    UserNotification::factory()->count(3)->create(['user_id' => $user->id]);
    
    $repository = new NotificationRepository();
    $notifications = $repository->getForUser($user->id, 10);
    
    expect($notifications)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class)
        ->and($notifications->count())->toBe(3);
});

test('notification repository can get unread count', function () {
    $user = User::factory()->create();
    UserNotification::factory()->create(['user_id' => $user->id, 'is_read' => false]);
    UserNotification::factory()->create(['user_id' => $user->id, 'is_read' => true]);
    
    $repository = new NotificationRepository();
    $count = $repository->getUnreadCount($user->id);
    
    expect($count)->toBe(1);
});
