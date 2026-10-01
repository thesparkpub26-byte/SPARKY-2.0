<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\OtpVerification;
use App\Models\PageView;
use App\Models\PasswordResetCode;
use Illuminate\Console\Command;

class PruneOldData extends Command
{
    protected $signature = 'maintenance:prune {--page-view-days=180 : How long to keep page-view rows} {--notification-days=60 : How long to keep read notifications}';

    protected $description = 'Delete data nobody needs any more: old page views, expired sign-up / reset codes and old read notifications';

    /** Deletes data nobody needs any more: old page views, expired sign-up and reset codes, old read notifications. */
    public function handle(): int
    {
        // The admin report only looks back 90 days at most
        $views = PageView::where('created_at', '<', now()->subDays(max(91, (int) $this->option('page-view-days'))))->delete();

        $otps = OtpVerification::where('expires_at', '<', now()->subDay())->delete();

        $resets = PasswordResetCode::where(function ($q) {
            $q->where('expires_at', '<', now()->subDay())
              ->orWhere('reset_expires_at', '<', now()->subDay());
        })->delete();

        $notifications = Notification::whereNotNull('read_at')
            ->where('read_at', '<', now()->subDays(max(1, (int) $this->option('notification-days'))))
            ->delete();

        $this->info("Removed {$views} page views, {$otps} sign-up codes, {$resets} reset codes and {$notifications} read notifications.");

        return self::SUCCESS;
    }
}
