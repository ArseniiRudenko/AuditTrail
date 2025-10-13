<?php

namespace Leantime\Plugins\AuditTrail\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Leantime\Core\Db\Db;

class AuditTrailRepository
{
    private Db $db;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        // Get DB Instance
        $this->db = app()->make(Db::class);
    }

    // Repo methods here.
    public function createTable(): void
    {

    }

    public function dropTable(): void
    {

    }



    public function getAuditTrail($taskId): array
    {
        return $this->db->fetchAll("SELECT * FROM zp_tickethistory WHERE ticketId = ? ORDER BY dateModified DESC", [$taskId]);
    }


}

