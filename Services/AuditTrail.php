<?php

namespace Leantime\Plugins\AuditTrail\Services;

use Illuminate\Support\Facades\Log;

class AuditTrail
{
    public function __construct() {}

    public function install(): void
    {
        // Repo call to create tables.
        Log::info('Audit Trail Plugin Installed');
    }

    public function uninstall(): void
    {
        // Remove tables
        Log::info('Audit Trail Plugin Uninstalled');
    }
}
