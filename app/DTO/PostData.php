<?php

namespace App\DTO;

/**
 * Data Transfer Object representing the data needed to create or update a post.
 */
class PostData
{
    /**
     * @param  string|null  $title
     * @param  string  $content
     * @param  mixed|null  $media
     * @param  array<int, array<string, mixed>>  $specialties
     * @param  array<int, array<string, mixed>>  $tags
     * @param  string|null  $jobType
     */
    public function __construct(
        public ?string $title,
        public string $content,
        public $media = null,
        public array $specialties = [],
        public array $tags = [],
        public ?string $jobType = null,
    ) {
    }
}

