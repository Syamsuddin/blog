<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\SpamFilterService;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 'user_id', 'author_name', 'author_email', 'body', 
        'is_approved', 'spam_score', 'spam_reasons', 'status', 
        'is_flagged', 'ip_address', 'reviewed_at', 'reviewed_by'
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_flagged' => 'boolean',
            'spam_reasons' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    public function scopeHighSpamScore($query, int $threshold = 50)
    {
        return $query->where('spam_score', '>=', $threshold);
    }

    /**
     * Methods
     */
    public function isSpam(): bool
    {
        return $this->status === 'spam' || $this->spam_score >= 50;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved' && $this->is_approved;
    }

    public function approve(User $user = null): void
    {
        $this->update([
            'status' => 'approved',
            'is_approved' => true,
            'reviewed_at' => now(),
            'reviewed_by' => $user?->id,
        ]);
    }

    public function reject(User $user = null): void
    {
        $this->update([
            'status' => 'rejected',
            'is_approved' => false,
            'reviewed_at' => now(),
            'reviewed_by' => $user?->id,
        ]);
    }

    public function markAsSpam(User $user = null): void
    {
        $this->update([
            'status' => 'spam',
            'is_approved' => false,
            'is_flagged' => true,
            'reviewed_at' => now(),
            'reviewed_by' => $user?->id,
        ]);

        // Add IP to spam list if available
        if ($this->ip_address) {
            SpamFilterService::addSpamIp($this->ip_address);
        }
    }

    public function flag(User $user = null): void
    {
        $this->update([
            'is_flagged' => true,
            'reviewed_at' => now(),
            'reviewed_by' => $user?->id,
        ]);
    }

    /**
     * Check for spam when creating/updating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $spamCheck = SpamFilterService::isSpam(
                $comment->body,
                $comment->author_email,
                $comment->ip_address
            );

            $comment->spam_score = $spamCheck['score'];
            $comment->spam_reasons = $spamCheck['reasons'];

            // Auto-determine status based on spam score
            if ($spamCheck['action'] === 'block') {
                $comment->status = 'spam';
                $comment->is_approved = false;
                $comment->is_flagged = true;
            } elseif ($spamCheck['action'] === 'moderate') {
                $comment->status = 'pending';
                $comment->is_approved = false;
                $comment->is_flagged = true;
            } elseif ($spamCheck['action'] === 'flag') {
                $comment->status = 'pending';
                $comment->is_approved = false;
                $comment->is_flagged = true;
            } else {
                $comment->status = 'approved';
                $comment->is_approved = true;
            }

            // Clean content
            $comment->body = SpamFilterService::cleanContent($comment->body);

            // Update rate limiting
            SpamFilterService::updateRateLimit($comment->ip_address, $comment->author_email);
        });
    }
}
