<?php

namespace App\Enums;

enum PostStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case WaitingReview = 'waiting_review';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Failed = 'failed';

    // Optional: Agar future mein label dikhana ho (e.g. "Waiting for Review")
    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::WaitingReview => 'Waiting for Review',
            self::Scheduled => 'Scheduled',
            self::Published => 'Published',
            self::Failed => 'Failed',
        };
    }

    // Optional: Status ka color (Badge k liye)
    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Processing => 'blue',
            self::WaitingReview => 'indigo',
            self::Scheduled => 'gray',
            self::Published => 'green',
            self::Failed => 'red',
        };
    }
}
