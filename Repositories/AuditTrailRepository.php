<?php

namespace Leantime\Plugins\AuditTrail\Repositories;

use Leantime\Core\Db\Db;
use PDO;

class AuditTrailRepository
{
    private Db $db;

    public function __construct()
    {
        // Get DB Instance
        $this->db = app(Db::class);
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
                       u.firstname AS userFirstname, u.lastname AS userLastname, u.username AS userUsername,
                       uv.firstname AS valueFirstname, uv.lastname AS valueLastname, uv.username AS valueUsername
                FROM zp_tickethistory h
                LEFT JOIN zp_user u ON u.id = h.userId
                LEFT JOIN zp_user uv ON uv.id = h.changeValue
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
