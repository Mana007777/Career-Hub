<?php

namespace App\Observers;

use App\Models\PostSuspension;
use App\Queries\PostQueries;

class PostSuspensionObserver
{
    public function saved(PostSuspension $postSuspension): void
    {
        app(PostQueries::class)->clearPostCache($postSuspension->post_id);
    }

    public function deleted(PostSuspension $postSuspension): void
    {
        app(PostQueries::class)->clearPostCache($postSuspension->post_id);
    }
}
