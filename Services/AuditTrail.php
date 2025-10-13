<?php

namespace Leantime\Plugins\AuditTrail\Services;

use Leantime\Plugins\AuditTrail\Repositories\AuditTrailRepository;

class AuditTrail
{
    private AuditTrailRepository $AuditTrailRepos;

    public function __construct()
    {
        $this->AuditTrailRepos = new AuditTrailRepository();
    }

    public function install(): void
    {
        // Repo call to create tables.
        \Illuminate\Log\log('Audit Trail Plugin Installed');

    }

    public function uninstall(): void
    {
        // Remove tables
        \Illuminate\Log\log('Audit Trail Plugin Uninstalled');
    }
}
