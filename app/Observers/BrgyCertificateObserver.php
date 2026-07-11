<?php

namespace App\Observers;

use App\Models\BrgyCertificate;
use Illuminate\Support\Facades\Cache;

class BrgyCertificateObserver
{
    /**
     * Handle the BrgyCertificate "created" event.
     */
    public function created(BrgyCertificate $brgyCertificate): void
    {
       Cache::forget(BrgyCertificate::BRGY_CERTIFICATE_CACHE_KEY);
    }

    /**
     * Handle the BrgyCertificate "updated" event.
     */
    public function updated(BrgyCertificate $brgyCertificate): void
    {
       Cache::forget(BrgyCertificate::BRGY_CERTIFICATE_CACHE_KEY);
    }

    /**
     * Handle the BrgyCertificate "deleted" event.
     */
    public function deleted(BrgyCertificate $brgyCertificate): void
    {
        Cache::forget(BrgyCertificate::BRGY_CERTIFICATE_CACHE_KEY);
    }

    /**
     * Handle the BrgyCertificate "restored" event.
     */
    public function restored(BrgyCertificate $brgyCertificate): void
    {
       Cache::forget(BrgyCertificate::BRGY_CERTIFICATE_CACHE_KEY);
    }

    /**
     * Handle the BrgyCertificate "force deleted" event.
     */
    public function forceDeleted(BrgyCertificate $brgyCertificate): void
    {
       Cache::forget(BrgyCertificate::BRGY_CERTIFICATE_CACHE_KEY);
    }
}
