<?php

namespace Leantime\Plugins\AuditTrail\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Leantime\Core\Db\Db;
use PDO;

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

    /**
     * Retrieve audit trail (ticket history) rows for the given task / ticket id ordered by newest first.
     * Includes user firstname, lastname when available.
     *
     * @param int $taskId
     * @return array<int, array<string, mixed>>
     */
    public function getAuditTrail(int $taskId): array
    {
        $sql = 'SELECT h.id, h.userId, h.ticketId, h.changeType, h.changeValue, h.dateModified,
                       u.firstname AS userFirstname, u.lastname AS userLastname, u.username AS userUsername
                FROM zp_tickethistory h
                LEFT JOIN zp_user u ON u.id = h.userId
                WHERE h.ticketId = :ticketId
                ORDER BY h.dateModified DESC';

        $stmn = $this->db->database->prepare($sql);
        $stmn->bindValue(':ticketId', $taskId, PDO::PARAM_INT);
        $stmn->execute();
        $rows = $stmn->fetchAll(PDO::FETCH_ASSOC);
        $stmn->closeCursor();

        return $rows ?: [];
    }

}
